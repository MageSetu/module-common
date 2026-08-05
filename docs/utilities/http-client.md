---
type: feature
name: "Shared HTTP Client"
description: "A reusable Magento 2 utility for sending JSON requests to third-party APIs with retries, consistent errors, and provider-aware logging."
tags: [magento2, magesetu, http-client, backend]
module: "MageSetu_Common"
---

# Shared HTTP Client

The shared HTTP client is the standard outbound integration layer for MageSetu modules. It gives extension code a single way to call remote JSON APIs without repeatedly re-implementing request setup, retry logic, or error handling.

## What this feature provides

Use this utility when a Magento module needs to call an external service over HTTP and you want behavior that is:

- predictable for POST, PUT, GET, and DELETE requests
- resilient to transient failures such as HTTP 429 or 5xx responses
- easy to test through dependency injection
- consistent across all MageSetu modules

## Core capabilities

The implementation behind this feature handles the following concerns for you:

- JSON encoding and decoding of request and response bodies
- retry with exponential back-off for transient network or server-side failures
- provider-labelled logging and uniform exception wrapping via `HttpClientException`
- default JSON headers so callers can focus on business data rather than transport details

## Typical integration pattern

Inject the interface into a service or repository class and call it from the domain logic.

```php
namespace Vendor\Module\Model;

use MageSetu\Common\Api\HttpClientInterface;

class ExternalSyncService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function syncDocument(array $payload): array
    {
        return $this->httpClient->postJson(
            'ollama',
            'https://api.example.com/v1/documents',
            $payload,
            ['X-Api-Key' => 'secret-token'],
            3,
            500
        );
    }
}
```

## Example usage

The same utility can be used for update and delete operations as well:

```php
$response = $this->httpClient->putJson(
    'provider-code',
    'https://api.example.com/v1/items/42',
    ['status' => 'active']
);

$deleteResult = $this->httpClient->deleteJson(
    'provider-code',
    'https://api.example.com/v1/items/42',
    ['force' => true]
);
```

## Implementation direction

Most MageSetu modules should use this utility instead of writing their own cURL wrappers. If a new feature needs outbound API communication, start here and let the shared implementation handle the transport details.