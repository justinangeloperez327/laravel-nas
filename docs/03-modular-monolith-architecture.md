# Modular Monolith Architecture

## Architecture Choice

The Noor Al Sahara system will be built as a **modular monolith**.

This means the system is deployed as one Laravel application but organized internally around clear business modules.

## Application Shape

```text
Laravel Application
├── Modules
│   ├── Users
│   ├── Organization
│   ├── Clients
│   ├── Projects
│   ├── Contracts
│   ├── Approvals
│   ├── DocumentControl
│   ├── Procurement
│   ├── Suppliers
│   ├── Inventory
│   ├── HumanResources
│   ├── SiteOperations
│   ├── Equipment
│   ├── Quality
│   ├── HealthAndSafety
│   ├── Subcontractors
│   ├── ProjectFinance
│   ├── Variations
│   └── Reporting
├── Integrations
├── Shared
└── Providers
```

## Module Structure

A module should begin with only the directories it needs.

A mature module may look like:

```text
Procurement/
├── Actions/
├── Models/
├── Http/
│   ├── Controllers/
│   └── Requests/
├── Policies/
├── Events/
├── Listeners/
├── Jobs/
├── Notifications/
└── routes.php
```

Do not create empty architecture layers simply for symmetry.

## Responsibilities

### Controllers

Controllers handle HTTP concerns:

- receive requests;
- call validated application operations;
- return responses.

Controllers must not contain substantial business rules.

### Requests

Requests handle input validation and request-level authorization where appropriate.

### Actions

Actions represent meaningful business operations such as:

- CreateProject
- SubmitPurchaseRequest
- ApprovePurchaseRequest
- IssuePurchaseOrder
- SubmitDocumentRevision
- ApproveVariation

Actions are preferred over large generic service classes when the operation is specific and business-oriented.

### Models

Models represent persistent application data and relationships.

Models may contain behavior that naturally belongs to the model, but should not become unbounded containers for every workflow rule.

### Policies

Policies control access to resources and operations.

Authorization must not rely on frontend visibility alone.

## Shared Code

`app/Shared` is reserved for code that genuinely applies across multiple modules.

It must not become a dumping ground.

If a class belongs clearly to a business module, it stays in that module.

## Integrations

External systems live under `app/Integrations`.

Examples:

```text
Integrations/
├── Microsoft/
│   ├── Graph/
│   └── SharePoint/
├── Azure/
│   └── BlobStorage/
└── Accounting/
```

Business modules may call integration abstractions, but external API implementation details should not be scattered across controllers.

## Database

The application uses one primary PostgreSQL database.

A module owns its tables conceptually even though all tables live in the same database.

Foreign keys between modules are allowed where the relationship is part of the business model. Cross-module writes should remain controlled by application operations rather than arbitrary manipulation throughout the codebase.

## Rules

- No microservices initially.
- No full Domain-Driven Design folder hierarchy.
- No Clean Architecture folder hierarchy.
- No repository abstraction around Eloquent unless a concrete requirement justifies it.
- No generic service class for every model.
- No business logic in React.
- No direct external API logic in controllers.
- No module may silently become a global dependency for every other module.
