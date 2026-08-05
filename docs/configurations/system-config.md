---
type: Concept
title: "Common System Configuration Tab"
description: "Defines the shared configuration tab wrapper in system.xml for unified vendor setting placement."
resource: "app/code/MageSetu/Common/etc/adminhtml/system.xml"
tags: [magento2, configuration, backend]
---

# Common System Configuration Tab

This module injects a custom, high-level structural tab into the Magento `Stores > Configuration` sidebar panel.

## Tab Configuration Meta

* **Tab ID:** `magesetu_common`

### Implementation Guideline for Child Modules
Any module requiring merchant configurations should use this tab ID to anchor its custom configuration sections. This guarantees all `MageSetu` products share a clean, grouped interface.

```xml
<?xml version="1.0"?>
<config xmlns:xsi="[http://www.w3.org/2001/XMLSchema-instance](http://www.w3.org/2001/XMLSchema-instance)" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
    <system>
        <section id="magesetu_blog" translate="label" type="text" sortOrder="10" showInDefault="1" showInWebsite="1" showInStore="1">
            <class>separator-top</class>
            <label>Blog Configuration</label>
            <tab>magesetu_common</tab>
            <resource>MageSetu_Blog::config</resource>
        </section>
    </system>
</config>