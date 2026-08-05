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

namespace MageSetu\Common\Exception;

/**
 * Root exception for all HTTP client errors.
 * Catch this to handle any client failure generically.
 */
class HttpClientException extends \Exception
{
}
