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

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'MageSetu_Common',
    __DIR__
);
