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

namespace MageSetu\Common\Http;

use MageSetu\Common\Api\HttpClientInterface;
use MageSetu\Common\Http\Client\CurlFactory;
use MageSetu\Common\Http\Client\Curl;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use MageSetu\Common\Exception\HttpClientException;

/**
 * Thin HTTP wrapper used by all HTTP client implementations.
 *
 * Responsibilities:
 * - JSON encode/decode
 * - Retry with exponential back-off on transient errors (429, 5xx)
 * - Consistent error messages
 *
 * Extracted here so neither the abstract client base nor any concrete
 * client carries HTTP concerns.
 *
 * @api
 * @since 1.0.0
 */
class HttpClient implements HttpClientInterface
{
    /** cURL Connection timeout - seconds to connect */
    protected const CURL_CONNECTION_TIMEOUT = 10;

    /** cURL timeout */
    protected const CURL_TIMEOUT = 300;
 
    /**
     * Class constructor
     *
     * @param CurlFactory $curlFactory
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected readonly CurlFactory $curlFactory,
        protected readonly SerializerInterface $serializer,
        protected readonly LoggerInterface $logger
    ) {
    }
 
    /**
     * POST a JSON payload, return decoded response array.
     *
     * @param  string $providerCode
     * @param  string $url
     * @param  array  $payload
     * @param  array  $headers  Merged on top of Content-Type/Accept defaults
     * @param  int    $maxRetries
     * @param  int    $retryDelayInMilliSeconds
     * @return array<string, mixed>
     * @throws HttpClientException
     */
    public function postJson(
        string $providerCode,
        string $url,
        array $payload,
        ?array $headers = [],
        ?int $maxRetries = 1,
        ?int $retryDelayInMilliSeconds = 300
    ): array {
        return $this->executeWithRetry(
            $providerCode,
            $maxRetries,
            $retryDelayInMilliSeconds,
            function () use ($providerCode, $url, $payload, $headers) {
                return $this->doRequest('POST', $providerCode, $url, $headers, $payload);
            }
        );
    }

    /**
     * PUT a JSON payload, return decoded response array.
     *
     * @param  string $providerCode
     * @param  string $url
     * @param  array  $payload
     * @param  array  $headers  Merged on top of Content-Type/Accept defaults
     * @param  int    $maxRetries
     * @param  int    $retryDelayInMilliSeconds
     * @return array<string, mixed>
     * @throws HttpClientException
     */
    public function putJson(
        string $providerCode,
        string $url,
        array $payload,
        ?array $headers = [],
        ?int $maxRetries = 1,
        ?int $retryDelayInMilliSeconds = 300
    ): array {
        return $this->executeWithRetry(
            $providerCode,
            $maxRetries,
            $retryDelayInMilliSeconds,
            function () use ($providerCode, $url, $payload, $headers) {
                return $this->doRequest('PUT', $providerCode, $url, $headers, $payload);
            }
        );
    }

    /**
     * GET request, return decoded response array.
     *
     * @param  string $providerCode
     * @param  string $url
     * @param  array  $headers  Merged on top of Content-Type/Accept defaults
     * @param  int    $maxRetries
     * @param  int    $retryDelayInMilliSeconds
     * @return array<string, mixed>
     * @throws HttpClientException
     */
    public function getJson(
        string $providerCode,
        string $url,
        ?array $headers = [],
        ?int $maxRetries = 1,
        ?int $retryDelayInMilliSeconds = 300
    ): array {
        return $this->executeWithRetry(
            $providerCode,
            $maxRetries,
            $retryDelayInMilliSeconds,
            function () use ($providerCode, $url, $headers) {
                return $this->doRequest('GET', $providerCode, $url, $headers);
            }
        );
    }

    /**
     * DELETE request to the given URL and return the decoded response array.
     *
     * @param  string     $providerCode
     * @param  string     $url
     * @param  array|null $payload        Optional request body — will be JSON-encoded if provided.
     * @param  array      $headers        Merged on top of Content-Type/Accept defaults
     * @param  int        $maxRetries
     * @param  int        $retryDelayInMilliSeconds
     * @return array<string, mixed>   Decoded JSON response body
     * @throws HttpClientException
     */
    public function deleteJson(
        string $providerCode,
        string $url,
        ?array $payload = null,
        ?array $headers = [],
        ?int $maxRetries = 1,
        ?int $retryDelayInMilliSeconds = 300
    ): array {
        return $this->executeWithRetry(
            $providerCode,
            $maxRetries,
            $retryDelayInMilliSeconds,
            function () use ($providerCode, $url, $payload, $headers) {
                return $this->doRequest('DELETE', $providerCode, $url, $headers, $payload);
            }
        );
    }

    /**
     * Executes the provided request closure with exponential back-off logic.
     *
     * @param  string   $providerCode
     * @param  int      $maxRetries
     * @param  int      $retryDelayInMilliSeconds
     * @param  callable $requestAction
     * @return array<string, mixed>
     * @throws HttpClientException
     */
    private function executeWithRetry(
        string $providerCode,
        int $maxRetries,
        int $retryDelayInMilliSeconds,
        callable $requestAction
    ): array {
        $attempt = 0;
        $maxRetries = $maxRetries > 0 ? $maxRetries : 1;
 
        while (true) {
            try {
                $response = $requestAction();
 
                if (isset($response['error'])) {
                    throw new HttpClientException(sprintf(
                        '[%s] API error: %s',
                        $providerCode,
                        $response['error']['message'] ?? $this->serializer->unserialize($response['error'])
                    ));
                }
 
                return $response;
 
            } catch (HttpClientException $e) {
                throw $e; // Non-retryable – surface immediately
 
            } catch (\Exception $e) {
                $attempt++;
                $this->logger->warning(sprintf(
                    '[%s] HTTP attempt %d/%d failed: %s',
                    $providerCode,
                    $attempt,
                    $maxRetries,
                    $e->getMessage()
                ));
 
                if ($attempt >= $maxRetries) {
                    throw new HttpClientException(sprintf(
                        '[%s] Request failed after %d attempts: %s',
                        $providerCode,
                        $maxRetries,
                        $e->getMessage()
                    ), 0, $e);
                }
 
                usleep($retryDelayInMilliSeconds * 1000);
                $retryDelayInMilliSeconds *= 2;
            }
        }
    }
 
    /**
     * Core cURL execution logic for both GET and POST requests.
     *
     * @throws \RuntimeException on transient HTTP errors (retryable)
     * @throws HttpClientException on permanent HTTP errors
     */

    /**
     * Core cURL execution logic for both GET and POST requests.
     *
     * @param  string     $method
     * @param  string     $providerCode
     * @param  string     $url
     * @param  array      $headers
     * @param  array|null $payload
     * @return array
     * @throws \RuntimeException on transient HTTP errors (retryable)
     * @throws HttpClientException on permanent HTTP errors
     */
    private function doRequest(
        string $method,
        string $providerCode,
        string $url,
        array $headers,
        ?array $payload = null
    ): array {
        /** @var Curl $curl */
        $curl = $this->curlFactory->create();

        $curl->setOption(CURLOPT_CONNECTTIMEOUT, self::CURL_CONNECTION_TIMEOUT);
        $curl->setOption(CURLOPT_TIMEOUT, self::CURL_TIMEOUT);

        $curl->setHeaders(array_merge(
            ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            $headers
        ));

        if ($method === 'POST') {
            $curl->post($url, $this->serializer->serialize($payload));
        } elseif ($method === 'PUT') {
            $curl->put($url, $this->serializer->serialize($payload));
        } elseif ($method === 'DELETE') {
            $curl->delete($url, $this->serializer->serialize($payload));
        } else {
            $curl->get($url);
        }
 
        $status = (int) $curl->getStatus();
 
        if ($status === 429 || $status >= 500) {
            throw new \RuntimeException("HTTP {$status} - transient, will retry");
        }
 
        if ($status < 200 || $status >= 300) {
            throw new HttpClientException(
                sprintf('[%s] Unexpected HTTP %d from %s', $providerCode, $status, $url),
                $status
            );
        }
 
        $body = json_decode($curl->getBody(), true, 512, JSON_THROW_ON_ERROR);
 
        if (!is_array($body)) {
            throw new HttpClientException(sprintf('[%s] Non-object JSON response', $providerCode));
        }
 
        return $body;
    }
}
