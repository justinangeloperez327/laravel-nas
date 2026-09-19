# Noor Al Sahara System Documentation

This directory contains the technical and business documentation for the Noor Al Sahara internal business system.

The application is built as a **Laravel modular monolith**. It remains one application, one repository, one deployment, and one primary PostgreSQL database while keeping business capabilities separated into explicit modules.

## Documents

1. [Project Charter](01-project-charter.md)
2. [System Scope](02-system-scope.md)
3. [Modular Monolith Architecture](03-modular-monolith-architecture.md)
4. [Module Boundaries](04-module-boundaries.md)
5. [Business Workflows](05-business-workflows.md)
6. [Data Architecture](06-data-architecture.md)
7. [Security](07-security.md)
8. [Integrations](08-integrations.md)
9. [Deployment](09-deployment.md)
10. [Testing and Quality](10-testing-and-quality.md)
11. [Development Workflow](11-development-workflow.md)

## Architecture Rule

The baseline architecture is a modular monolith.

We do not introduce microservices, full Domain-Driven Design layering, Clean Architecture layering, or a separate database per module unless a future requirement provides a concrete reason to change the architecture.

## Documentation Rule

A meaningful change to a business workflow, module boundary, data ownership rule, integration, security requirement, or deployment strategy must update the relevant document in this directory.

## Module Documentation

- [Users](modules/users.md)

- [Organization](modules/organization.md)

- [Clients](modules/clients.md)

- [Projects](modules/projects.md)

- [Contracts](modules/contracts.md)

- [Approvals](modules/approvals.md)

- [Document Control](modules/document-control.md)

- [Suppliers](modules/suppliers.md)
