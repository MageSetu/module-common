---
type: Concept
title: "Global Admin Menu Node"
description: "Defines the main sidebar menu entry point for MageSetu modules."
resource: "app/code/MageSetu/Common/etc/adminhtml/menu.xml"
tags: [magento2, backend, UI]
---

# Global Admin Menu Node

`MageSetu_Common` declares a main navigation entry point on the Magento Admin sidebar. This prevents vendor menu bloat by giving all sub-modules a predictable home.

## Anchor Specifications

* **Main Menu ID:** `MageSetu_Common::main`
* **Resource Requirement:** `MageSetu_Common::manage`
* **Title:** `MageSetu`

### Implementation Guideline for Child Modules
When adding backend dashboards or grids in other vendor extensions, target `MageSetu_Common::main` as the `parent` attribute within your `etc/adminhtml/menu.xml`.

```xml
<?xml version="1.0"?>
<config xmlns:xsi="[http://www.w3.org/2001/XMLSchema-instance](http://www.w3.org/2001/XMLSchema-instance)" xsi:noNamespaceSchemaLocation="urn:magento:framework:Menu/etc/menu.xsd">
    <menu>
        <add id="MageSetu_Blog::post"
             title="Manage Posts"
             module="MageSetu_Blog"
             sortOrder="10"
             parent="MageSetu_Common::main"
             action="magesetu_blog/post/"
             resource="MageSetu_Blog::blog_management"/>
    </menu>
</config>