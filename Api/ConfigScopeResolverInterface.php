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

namespace MageSetu\Common\Api;

/**
 * Resolves which store scopes inherit a configuration value from a
 * parent scope, versus which have an explicit override that masks
 * changes made at that parent scope.
 *
 * @api
 * @since 1.0.0
 */
interface ConfigScopeResolverInterface
{
    /**
     * Get IDs of stores that inherit the given config path's value from
     * the scope a save was performed at, rather than overriding it
     * themselves.
     *
     * A store is considered inheriting only if neither it nor its parent
     * website has an explicit override for this path between the saved
     * scope and the store itself.
     *
     * @param string $path Fully qualified config path, e.g. 'mysection/general/enabled'
     * @param string|null $savedWebsiteCode Website code the save was performed against,
     *        or null if saved at default (all store views) scope
     * @param string|null $savedStoreCode Store view code the save was performed against,
     *        or null if saved at default or website scope
     * @return int[] Store IDs inheriting the value (i.e. actually affected by the save)
     */
    public function getInheritedStoreIds(
        string $path,
        ?string $savedWebsiteCode,
        ?string $savedStoreCode
    ): array;
}
