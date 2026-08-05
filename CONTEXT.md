# CONTEXT.md

## Module overview

MageSetu Common is a Magento 2 base module that provides shared backend infrastructure for other MageSetu modules. It is designed to be reused by child modules rather than used as a standalone feature module.

## What this module is for

The module exists to give MageSetu extensions a common foundation for backend integration. Its responsibilities are structural and reusable rather than user-facing:

- a common ACL resource for backend permissions
- a shared admin menu entry for MageSetu modules
- a shared configuration tab under Stores > Configuration
- DI bindings for reusable interfaces used by downstream modules
- reusable utilities for outbound HTTP calls and configuration-scope analysis

## What this module is not

This repository does not implement a storefront feature, a customer-facing product, or a standalone merchant workflow by itself. Its value comes from supporting other MageSetu modules in a consistent way.

## High-level architecture

The implementation is intentionally thin and reusable. The main pieces are:

- public interfaces in the Api directory
- concrete implementations in the Http and Model directories
- Magento XML configuration in the etc directory
- documentation in the docs directory

Most of the module's value comes from shared contracts, conventions, and integration points rather than from large domain-specific business logic.

## Repository structure

- Api: public interfaces and contracts
- Http: transport implementations and related HTTP helpers
- Model: concrete business logic implementations
- etc: Magento module wiring and configuration XML
- docs: implementation and extension guidance
- registration.php: Magento module registration entry point

## Documentation role

The documentation in this repository is part of the implementation. It should be treated as the authoritative guide for how the module is intended to be used and extended.

## Important constraints

When making changes, keep these points in mind:

- preserve the existing architecture unless a refactor is explicitly requested
- keep changes compatible with Magento 2 module conventions
- avoid introducing inconsistent patterns for child modules
- update documentation when behavior, interfaces, or extension guidance changes
