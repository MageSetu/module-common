---
type: Concept
title: "Dependency Injection Preferences"
description: "Maps Magento 2 global object manager preferences for MageSetu_Common interfaces to their concrete implementations."
resource: "app/code/MageSetu/Common/etc/di.xml"
tags: [magento2, dependency-injection, preferences]
---

# Dependency Injection Configuration

This module registers global preferences in `etc/di.xml` to bind its API contracts (interfaces) to their concrete business logic and transport implementations.

## Registered Preferences

To ensure decoupled architectural design, other modules should always inject the abstract interface in their constructors instead of the concrete classes. The Magento Object Manager resolves them as follows:

| Interface (Inject This) | Concrete Class (Resolved Implementation) | Description |
| :--- | :--- | :--- |
| `MageSetu\Common\Api\ConfigScopeResolverInterface` | `MageSetu\Common\Model\ConfigScopeResolver` | Calculates configurations cascades and overrides across website and store views. |
| `MageSetu\Common\Api\HttpClientInterface` | `MageSetu\Common\Http\HttpClient` | A robust, retry-capable client wrapping cURL for unified JSON API requests. |