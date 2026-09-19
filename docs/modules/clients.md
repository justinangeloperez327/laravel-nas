# Clients Module

## Purpose

The Clients module owns client-company master data used by contracts and projects.

## Owns

- Clients
- Client contacts
- Client addresses

## Relationships

```text
Client
├── Contacts
├── Addresses
├── Contracts (later module)
└── Projects (later module)
```

## Permissions

- `clients.view`
- `clients.create`
- `clients.update`
- `clients.change-status`

## Rules

- client code is unique;
- inactive clients remain historically available;
- the primary contact is unique by workflow rather than deletion;
- contracts and projects reference a client instead of duplicating client data.
