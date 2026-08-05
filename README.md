# MageSetu Common Module for Magento 2

[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4)](https://www.php.net)
[![Magento 2](https://img.shields.io/badge/Magento-2.4.8%2B-EE6723)](https://business.adobe.com/products/magento/magento-commerce.html)

MageSetu Common is a base Magento 2 module that provides shared backend structure and reusable utilities for other MageSetu extensions. It is not a standalone storefront or commerce feature module; instead, it helps sibling modules follow a consistent admin experience and implementation pattern.

## What this module provides

This package currently focuses on shared infrastructure for MageSetu modules:

- a common admin ACL resource for permissions
- a top-level admin menu entry for MageSetu modules
- a shared configuration tab under Stores > Configuration
- dependency injection preferences for reusable interfaces
- reusable backend utilities for outbound HTTP calls, configuration scope resolution, and cURL-based transport helpers

## Requirements

| Requirement | Version |
| --- | --- |
| PHP | >= 8.3 |
| Magento Open Source / Commerce | >= 2.4.8 |

## Installation

If you are installing this module in a Magento 2 project with Composer, run:

```bash
composer require magesetu/module-common
php bin/magento module:enable MageSetu_Common
php bin/magento setup:upgrade
php bin/magento cache:flush
```

## Configuration and usage

This module does not expose merchant-facing settings or a separate end-user feature by itself. Its main value is as a foundation for other MageSetu modules.

If you are building a child module, use the shared conventions defined here:

- use the ACL resource `MageSetu_Common::manage` for your module permissions
- use `MageSetu_Common::main` as the parent admin menu node
- place your configuration sections under the shared `magesetu_common` tab
- inject the provided interfaces instead of wiring concrete classes directly

Example:

```php
use MageSetu\Common\Api\HttpClientInterface;

class MyService
{
    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }
}
```

## Reusable utilities

The repository documentation describes the shared utilities in more detail:

- [Shared HTTP client](docs/utilities/http-client.md)
- [Configuration scope resolver](docs/utilities/config-scope-resolver.md)
- [Extended cURL client](docs/utilities/curl-custom-client.md)

## Documentation

For implementation details and extension guidelines, see the documentation hub:

- [Documentation index](docs/index.md)
- [Admin ACL hierarchy](docs/configurations/acl-hierarchy.md)
- [Admin menu architecture](docs/configurations/admin-menu.md)
- [System configuration tab](docs/configurations/system-config.md)
- [Dependency injection configuration](docs/configurations/di-configuration.md)

## Disclaimer

> [!WARNING]
> **Use this module at your own risk.** 
> This module is provided "as is" without warranty of any kind, either express or implied. The developers are not responsible for any data loss, API rate limit overages, financial charges, server downtime, or other issues resulting from the use or installation of this software.

## License

This project is licensed under the Apache 2.0 License. See [LICENSE](LICENSE) for details.