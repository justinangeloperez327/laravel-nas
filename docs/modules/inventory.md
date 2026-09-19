# Inventory Module

Inventory uses a stock ledger. Users do not directly edit the on-hand quantity.

## Owns

- Item master
- Warehouses and project stores
- Stock balances
- Stock movements
- Reservations

## Movement Types

- receipt
- issue
- return
- transfer-in
- transfer-out
- positive-adjustment
- negative-adjustment

Every movement stores its user, timestamp, optional project, and optional source reference. Issues and negative adjustments cannot drive stock below zero.

Procurement goods receipts can later post `receipt` movements using the source reference fields.
