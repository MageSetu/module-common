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

use MageSetu\Common\Exception\HttpClientException;

/**
 * Typed contract for the thin JSON-over-HTTP transport used by all HTTP client implementations.
 *
 * Abstracting this allows:
 *   - Easy mocking in unit tests.
 *   - Alternative transports (e.g. async Guzzle) without touching client code.
 *   - Type-safe injection across the MageSetu module family.
 *
 * @api
 * @since 1.0.0
 */
interface HttpClientInterface
{
    /**
     * POST a JSON payload to the given URL and return the decoded response array.
     *
     * Implementations are expected to handle:
     *   - JSON encode/decode
     *   - Retry with exponential back-off on transient errors (429, 5xx)
     *   - Consistent, provider-labelled error messages
     *
     * @param  string $providerCode  Machine-readable label used in log/error messages (e.g. "ollama")
     * @param  string $url           Full endpoint URL
     * @param  array  $payload       Request body — will be JSON-encoded
     * @param  array  $headers       Additional headers merged on top of Content-Type/Accept defaults
     * @param  int    $maxRetries    Retry HTTP request unit the count matches
     * @param  int    $retryDelayInMilliSeconds
     * @return array<string, mixed>   Decoded JSON response body
     * @throws HttpClientException    On non-retryable HTTP errors or malformed responses
     */
    public function postJson(
        string $providerCode,
        string $url,
        array $payload,
        ?array $headers = [],
        ?int $maxRetries = 3,
        ?int $retryDelayInMilliSeconds = 300
    ): array;

    /**
     * PUT a JSON payload to the given URL and return the decoded response array.
     *
     * Implementations are expected to handle:
     *   - JSON encode/decode
     *   - Retry with exponential back-off on transient errors (429, 5xx)
     *   - Consistent, provider-labelled error messages
     *
     * @param  string $providerCode  Machine-readable label used in log/error messages (e.g. "ollama")
     * @param  string $url           Full endpoint URL
     * @param  array  $payload       Request body — will be JSON-encoded
     * @param  array  $headers       Additional headers merged on top of Content-Type/Accept defaults
     * @param  int    $maxRetries    Retry HTTP request unit the count matches
     * @param  int    $retryDelayInMilliSeconds
     * @return array<string, mixed>   Decoded JSON response body
     * @throws HttpClientException    On non-retryable HTTP errors or malformed responses
     */
    public function putJson(
        string $providerCode,
        string $url,
        array $payload,
        ?array $headers = [],
        ?int $maxRetries = 3,
        ?int $retryDelayInMilliSeconds = 300
    ): array;

    /**
     * GET request for a given URL and return the decoded response array.
     *
     * Implementations are expected to handle:
     *   - JSON encode/decode
     *   - Retry with exponential back-off on transient errors (429, 5xx)
     *   - Consistent, provider-labelled error messages
     *
     * @param  string $providerCode  Machine-readable label used in log/error messages (e.g. "ollama")
     * @param  string $url           Full endpoint URL
     * @param  array  $headers       Additional headers merged on top of Content-Type/Accept defaults
     * @param  int    $maxRetries    Retry HTTP request unit the count matches
     * @param  int    $retryDelayInMilliSeconds
     * @return array<string, mixed>   Decoded JSON response body
     * @throws HttpClientException    On non-retryable HTTP errors or malformed responses
     */
    public function getJson(
        string $providerCode,
        string $url,
        ?array $headers = [],
        ?int $maxRetries = 3,
        ?int $retryDelayInMilliSeconds = 300
    ): array;

    /**
     * Send a DELETE request to the given URL and return the decoded response array.
     *
     * Implementations are expected to handle:
     *   - JSON encode/decode of the payload (if provided) and response
     *   - Retry with exponential back-off on transient errors (429, 5xx)
     *   - Consistent, provider-labelled error messages
     *
     * @param  string     $providerCode  Machine-readable label used in log/error messages (e.g. "qdrant")
     * @param  string     $url           Full endpoint URL
     * @param  array|null $payload       Optional request body — will be JSON-encoded if provided.
     *                                   Most DELETE endpoints need no body;
     *                                   pass null for those. Delete-by-filter style endpoints
     *                                   that require a body can still use this.
     * @param  array      $headers       Additional headers merged on top of Content-Type/Accept defaults
     * @param  int        $maxRetries    Retry HTTP request unit the count matches
     * @param  int        $retryDelayInMilliSeconds
     * @return array<string, mixed>   Decoded JSON response body
     * @throws HttpClientException    On non-retryable HTTP errors or malformed responses
     */
    public function deleteJson(
        string $providerCode,
        string $url,
        ?array $payload = null,
        ?array $headers = [],
        ?int $maxRetries = 3,
        ?int $retryDelayInMilliSeconds = 300
    ): array;
}
