# Contracts Module

## Purpose

Contracts own the client commercial agreement that one or more projects may execute against.

## Owns

- Contract master
- Amendments
- Guarantees
- Insurance records
- Commercial terms

## Relationship

```text
Client
└── Contract
    └── Projects
```

Projects reference a contract optionally so projects can exist during pre-contract planning.

## Permissions

- `contracts.view`
- `contracts.create`
- `contracts.update`
- `contracts.manage-amendments`
