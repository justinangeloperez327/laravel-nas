# Suppliers Module

## Purpose

Suppliers owns the approved supplier master used by Procurement.

## Owns

- Supplier profiles
- Categories
- Contacts
- Supplier documents and expiries
- Bank-account metadata
- Performance evaluations

## Security

IBAN and SWIFT values use Laravel encrypted casts. Access to sensitive supplier banking data requires `suppliers.view-sensitive`.

## Supplier Status

- pending
- approved
- suspended
- inactive

Procurement should select approved suppliers rather than copying supplier names into transactions.

## Permissions

- `suppliers.view`
- `suppliers.create`
- `suppliers.update`
- `suppliers.approve`
- `suppliers.view-sensitive`
