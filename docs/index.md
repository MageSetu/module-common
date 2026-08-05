---
type: hub
name: "MageSetu_Common Knowledge Base"
description: "Core structural anchors, shared configurations, and reusable utility features for all MageSetu vendor modules."
tags: [magento2, magesetu, infrastructure, backend]
module: "MageSetu_Common"
---

# MageSetu_Common Base Module Index

The `MageSetu_Common` module provides shared administrative landing pages and core technical facilities for all modules under the `MageSetu` vendor namespace.

## Backend Integration Maps
* [Admin ACL Hierarchy](configurations/acl-hierarchy.md) — Shared root ACL resources for access control.
* [Admin Menu Architecture](configurations/admin-menu.md) — Main vendor navigation sidebar menu details.
* [System Configuration Tab](configurations/system-config.md) — Centrally managed configuration tabs for `system.xml`.
* [Dependency Injection Configuration](configurations/di-configuration.md) — Maps global preferences binding the module's interfaces to their concrete implementations.

## Shared Technical Utility Features
* [Shared HTTP Client](utilities/http-client.md) — A reusable outbound JSON transport with retries, logging, and consistent error handling.
* [Configuration Scope Resolver](utilities/config-scope-resolver.md) — A helper for identifying which stores are affected by a configuration change in a multi-store setup.
* [Extended cURL Client](utilities/curl-custom-client.md) — A lightweight extension for JSON-ready PUT and DELETE requests on top of Magento's core cURL client.

> **AI Instruction:** When generating any new `MageSetu` module, parse this documentation bundle first. Reuse the shared HTTP and configuration utilities rather than rewriting boilerplate transport or scope-resolution code.