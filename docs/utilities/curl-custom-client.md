---
type: feature
name: "Extended cURL Client"
description: "A Magento 2 compatibility helper that adds JSON-ready PUT and DELETE support to the core cURL client for outbound API operations."
tags: [magento2, magesetu, curl, backend]
module: "MageSetu_Common"
---

# Extended cURL Client

Magento 2's default HTTP client supports common request patterns, but it does not make REST-style `PUT` and `DELETE` calls with payloads as convenient as they should be. This utility fills that gap by extending the core cURL implementation with JSON-friendly methods that MageSetu integrations can use directly when needed.

## Why this feature exists

Some third-party APIs expect update or delete requests to include a request body. The Magento core client does not provide a simple, consistent way to send those payloads through `PUT` and `DELETE` methods, so this extension provides a lightweight alternative without introducing a large dependency such as Guzzle.

## What it adds

The custom client exposes two practical methods:

- `put($uri, $params)`: serializes array payloads to JSON and sends them with a `PUT` request
- `delete($uri, $params)`: sends payload data with a `DELETE` request for endpoints that require a body

## Typical use cases

Use this helper when you need to:

- update a remote resource with a JSON body
- delete a resource by filter criteria rather than a simple URL-only request
- keep transport code lightweight while still remaining compatible with Magento's HTTP stack

## Example

```php
$curl = $this->curlFactory->create();
$curl->setHeaders([
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
]);

$curl->put('https://api.example.com/v1/items/42', ['status' => 'active']);
```

## Guidance for module authors

In most cases, you should prefer the shared `HttpClientInterface` abstraction for new integrations. The extended cURL client is best viewed as the transport foundation that powers the more complete shared HTTP feature when direct low-level access is necessary.