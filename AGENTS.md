# AGENTS.md

This file focuses on how AI agents should work in this repository. For module context, architecture, and repository structure see [CONTEXT.md](CONTEXT.md). For installation and usage see [README.md](README.md).

## First read order

Before inspecting implementation, read the documentation in this order:

1. [README.md](README.md)
2. [CONTEXT.md](CONTEXT.md)
3. [docs/index.md](docs/index.md)

## Important concepts and terminology

Use the existing terminology consistently across code and docs.

- MageSetu Common: the shared base module
- MageSetu child modules: modules that build on this base module
- Shared admin resources: ACL, menu, and system configuration anchors used by child modules
- HTTP client utilities: reusable JSON-over-HTTP helpers with retry and error handling
- Configuration scope resolver: logic for determining which stores are affected by a configuration change in a multi-store setup

## Before editing code

Read the relevant documentation first. Do not assume a feature is standalone just because the module is small.

Before changing ACL behavior, admin menu integration, system configuration placement, DI preferences, or HTTP transport behavior, read the related docs under [docs/index.md](docs/index.md) and the relevant implementation files before making edits.

## Development workflow

Keep changes focused and compatible with Magento 2 module conventions.

1. Read the relevant docs and implementation files.
2. Make the smallest change that addresses the issue or feature.
3. Keep the public interfaces and module contracts stable unless the task explicitly requires a breaking change.
4. Update documentation when behavior or intended usage changes.
5. Verify the change against Magento module expectations and the surrounding code structure.

## Design principles to preserve

- Respect the existing architecture and documented design decisions.
- Prefer integration with existing patterns over introducing a new abstraction unless it is clearly justified.
- Keep the module reusable for other MageSetu modules.
- Favor compatibility and consistency over opportunistic refactoring.
- Avoid large refactors unless explicitly requested.
- Keep new features aligned with the existing Magento 2 and MageSetu conventions.

## Common mistakes to avoid

- Treating this as a user-facing feature module instead of a shared infrastructure module
- Duplicating implementation details that already exist in the docs or shared classes
- Introducing inconsistent naming or terminology for admin resources, utilities, or interfaces
- Changing DI bindings or admin XML structure without checking the related documentation
- Making broad architectural changes when a localized fix is sufficient

## Magento-specific conventions

This module follows standard Magento 2 module patterns:

- configuration is expressed through XML under the etc directory
- module identity is declared in etc/module.xml
- interfaces are exposed in Api and implemented in concrete classes under Http or Model
- admin integration uses Magento ACL, menu, and system configuration XML files

Be careful with Magento XML and module registration changes because they can affect downstream modules.

## Documentation expectations

Documentation is part of the implementation. When changing code, update the relevant documentation in the docs directory when:

- public behavior changes
- interface usage changes
- extension guidance changes
- module configuration expectations change

Do not leave code and docs out of sync.

## Testing and validation

No dedicated test suite or CI workflow is documented in this repository. Do not assume a test harness exists. When making changes, validate them carefully against the Magento 2 module structure and the repository's documented expectations.

If you add new functionality, consider how it would be verified in a Magento 2 environment, but do not invent test infrastructure that is not present here.
