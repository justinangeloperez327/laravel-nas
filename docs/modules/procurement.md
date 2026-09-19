# Procurement Module

## End-to-End Flow

Requirement → Purchase Request → Approval → Request for Quotation → Supplier Quotations → Evaluation → Supplier Selection → Purchase Order → Delivery → Goods Receipt → Supplier Invoice.

## Controls

- Purchase Requests are approval-engine transactions.
- Purchase Orders require an approved Purchase Request.
- Purchase Orders require an approved supplier.
- Purchase Order value cannot exceed the approved Purchase Request value without a future re-approval/change workflow.
- Deliveries and goods receipts retain their source Purchase Order.

## Permissions

- `procurement.view`
- `purchase-requests.create`
- `purchase-requests.approve`
- `rfq.manage`
- `purchase-orders.create`
- `purchase-orders.approve`
- `goods-receipts.manage`
