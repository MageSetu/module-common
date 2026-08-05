<?php
/**
 * Copyright (c) 2025 MageSetu. All rights reserved.
 *
 * @package    MageSetu_Common
 * @author     MageSetu
 * @copyright  Copyright (c) 2025 MageSetu
 * @license    https://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link       https://github.com/MageSetu/Common
 */

declare(strict_types=1);

namespace MageSetu\Common\Model;

use MageSetu\Common\Api\ConfigScopeResolverInterface;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Default implementation of ConfigScopeResolverInterface.
 *
 * Determines inheriting stores by loading all explicit scope overrides
 * for a given config path in a single collection query, then walking
 * the in-memory store tree to exclude any store or website that has
 * its own override masking the inherited value.
 */
class ConfigScopeResolver implements ConfigScopeResolverInterface
{
    /**
     * Class constructor
     *
     * @param CollectionFactory $configDataCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly CollectionFactory $configDataCollectionFactory,
        private readonly StoreManagerInterface $storeManager
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws NoSuchEntityException If the
     *         saved website or store code cannot be resolved to an entity
     */
    public function getInheritedStoreIds(
        string $path,
        ?string $savedWebsiteCode,
        ?string $savedStoreCode
    ): array {
        if ($savedStoreCode) {
            // Store-scope save only ever affects that one store
            return [(int) $this->storeManager->getStore($savedStoreCode)->getId()];
        }

        [$overriddenWebsiteIds, $overriddenStoreIds] = $this->getOverrides($path);

        $savedWebsiteId = $savedWebsiteCode
            ? (int) $this->storeManager->getWebsite($savedWebsiteCode)->getId()
            : null;

        $inherited = [];
        foreach ($this->storeManager->getStores() as $store) {
            if ($this->isInherited($store, $savedWebsiteId, $overriddenWebsiteIds, $overriddenStoreIds)) {
                $inherited[] = (int) $store->getId();
            }
        }

        return $inherited;
    }

    /**
     * Load explicit website- and store-scope overrides for the given path.
     *
     * @param string $path
     * @return array{0: array<int, bool>, 1: array<int, bool>} [$overriddenWebsiteIds, $overriddenStoreIds]
     */
    private function getOverrides(string $path): array
    {
        $overrides = $this->configDataCollectionFactory->create()
            ->addFieldToFilter('path', $path)
            ->addFieldToFilter('scope', ['in' => ['websites', 'stores']])
            ->load();

        $overriddenWebsiteIds = [];
        $overriddenStoreIds = [];
        foreach ($overrides as $row) {
            if ($row->getScope() === 'websites') {
                $overriddenWebsiteIds[(int) $row->getScopeId()] = true;
            } else {
                $overriddenStoreIds[(int) $row->getScopeId()] = true;
            }
        }

        return [$overriddenWebsiteIds, $overriddenStoreIds];
    }

    /**
     * Determine whether a store still inherits the value
     *
     * I.e. has no closer override (its own store, or its website) masking it.
     *
     * @param StoreInterface $store
     * @param int|null $savedWebsiteId
     * @param array $overriddenWebsiteIds
     * @param array $overriddenStoreIds
     * @return bool
     */
    private function isInherited(
        StoreInterface $store,
        ?int $savedWebsiteId,
        array $overriddenWebsiteIds,
        array $overriddenStoreIds
    ): bool {
        $storeId = (int) $store->getId();

        if (isset($overriddenStoreIds[$storeId])) {
            // The store overrides its own value, so it can't be inheriting
            return false;
        }

        $websiteId = (int) $store->getWebsiteId();

        if ($savedWebsiteId !== null) {
            // Only stores under the saved website are affected
            return $websiteId === $savedWebsiteId;
        }

        // Default-scope save: excluded only if its website overrides the value
        return !isset($overriddenWebsiteIds[$websiteId]);
    }
}
