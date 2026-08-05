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

namespace MageSetu\Common\Http\Client;

/**
 * Curl HTTP client
 *
 * @api
 * @since 1.0.0
 */
class Curl extends \Magento\Framework\HTTP\Client\Curl
{
    /**
     * Make PUT request
     *
     * @param string $uri
     * @param array|string $params
     * @return void
     */
    public function put($uri, $params)
    {
        $this->setOption(CURLOPT_POSTFIELDS, is_array($params) ? json_encode($params) : $params);
        $this->makeRequest("PUT", $uri);
    }

    /**
     * Make DELETE request
     *
     * @param string $uri
     * @param array|string $params
     * @return void
     */
    public function delete($uri, $params)
    {
        $this->setOption(CURLOPT_POSTFIELDS, is_array($params) ? json_encode($params) : $params);
        $this->makeRequest("DELETE", $uri);
    }
}
