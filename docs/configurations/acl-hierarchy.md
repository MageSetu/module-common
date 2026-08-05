---
type: Concept
title: "Shared Admin ACL Hierarchy"
description: "Details the common MageSetu parent ACL resource used to group backend module permissions."
resource: "app/code/MageSetu/Common/etc/acl.xml"
tags: [magento2, security, acl]
---

# Shared Admin ACL Hierarchy

To provide unified access management for merchants, all extensions developed under the `MageSetu` vendor namespace must nest their permissions under the parent resource defined in this module.

## Core Resource Definition

* **Parent Resource:** `Magento_Backend::admin`
* **MageSetu Root Resource ID:** `MageSetu_Common::manage`

### Implementation Guideline for Child Modules
When building a new module (e.g., `MageSetu_Blog`), your `etc/acl.xml` should anchor its permissions directly beneath this module's resource ID:

```xml
<?xml version="1.0"?>
<config xmlns:xsi="[http://www.w3.org/2001/XMLSchema-instance](http://www.w3.org/2001/XMLSchema-instance)" xsi:noNamespaceSchemaLocation="urn:magento:framework:Acl/etc/acl.xsd">
    <acl>
        <resources>
            <resource id="Magento_Backend::admin">
                <resource id="MageSetu_Common::manage">
                    <resource id="MageSetu_Blog::blog_management" title="Blog Management" sortOrder="10" />
                </resource>
            </resource>
        </resources>
    </acl>
</config>