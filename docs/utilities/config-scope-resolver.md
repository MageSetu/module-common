---
type: feature
name: "Configuration Scope Resolver"
description: "A Magento 2 helper that determines which stores are affected by a configuration change in a multi-store environment."
tags: [magento2, magesetu, config-scope, backend]
module: "MageSetu_Common"
---

# Configuration Scope Resolver

Magento configuration values can cascade from default scope to websites and store views. When a value is changed at a parent scope, child stores may inherit it unless they already have their own override. This utility helps modules identify exactly which stores are still inheriting a value and which ones are masked by a closer override.

## What this feature solves

When a module needs to react to a configuration change, it is important to know the real impact area. This helper answers the practical question:

- Which store views are actually affected by a save operation at a given scope?

That is especially important for background updates, bulk propagation logic, and any process that should only run for stores that truly inherit the value.

## How it works

The resolver inspects the store tree and the saved configuration override data to determine the result:

- if the save happened at store-view scope, only that store is affected
- if the save happened at website or default scope, the resolver excludes any store or website that already has its own override

## Example usage

```php
$storeIds = $this->configScopeResolver->getInheritedStoreIds(
    'mysection/general/enabled',
    'base',
    null
);
```

In this example, the resolver evaluates the inheritance state of the configuration path for the `base` website and returns the store IDs that should be updated because they still inherit the value.

## Integration direction

Inject the interface into a service that reacts to configuration saves. This is a good fit for modules that need to propagate or invalidate settings across multiple store views without touching stores that already override the value.