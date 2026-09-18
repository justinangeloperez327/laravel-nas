# Business Workflows

This document captures the major end-to-end workflows the system must support. Detailed workflow rules will be expanded before each module is implemented.

## Project Lifecycle

```text
Client
  ↓
Contract
  ↓
Project Creation
  ↓
Project Team Assignment
  ↓
Planning
  ↓
Execution
  ↓
Progress Tracking
  ↓
Commercial and Financial Tracking
  ↓
Completion
  ↓
Closeout
```

A project acts as the main operational reference for documents, procurement, employees, equipment, site activity, quality, health and safety, variations, and project finance.

## Document Control

```text
Create Document Record
  ↓
Upload Revision
  ↓
Submit
  ↓
Review
  ├── Approve
  ├── Reject
  └── Return for Revision
  ↓
Distribution / Transmittal
  ↓
Revision History
```

Requirements:

- approved revisions remain immutable;
- each revision remains traceable;
- current revision is identifiable;
- previous revisions are retained;
- document status changes are auditable;
- external file storage references remain synchronized with application metadata.

## Procurement

```text
Requirement
  ↓
Purchase Request
  ↓
Approval
  ↓
Request for Quotation
  ↓
Supplier Quotations
  ↓
Technical Evaluation
  ↓
Commercial Evaluation
  ↓
Comparison
  ↓
Supplier Selection
  ↓
Approval
  ↓
Purchase Order
  ↓
Delivery
  ↓
Goods Receipt
  ↓
Supplier Invoice
  ↓
Payment Tracking
```

The workflow must preserve links between the original requirement, approvals, supplier selection, purchase order, delivery, and financial impact.

## Inventory

```text
Purchase Order
  ↓
Delivery
  ↓
Goods Receipt
  ↓
Warehouse / Project Store
  ↓
Reservation
  ↓
Issue to Project
  ↓
Return / Transfer / Adjustment
```

All stock movements require a traceable source transaction.

## Human Resources

```text
Employee
  ↓
Employment Record
  ↓
Project Assignment
  ↓
Attendance / Timesheet
  ↓
Approval
  ↓
Project Cost and Utilization Reporting
```

Employee master data must remain distinct from application user accounts.

## Site Operations

```text
Project
  ↓
Daily Site Report
  ├── Manpower
  ├── Equipment
  ├── Activities
  ├── Targets
  ├── Actual Progress
  ├── Delays
  ├── Issues
  └── Photos
  ↓
Project Progress Reporting
```

## Quality

```text
Inspection Request
  ↓
Inspection
  ├── Approved
  ├── Rejected
  └── Corrective Action Required
  ↓
Corrective Action
  ↓
Closure
```

Non-conformance records must retain issue, response, evidence, responsible party, and closure history.

## Health and Safety

```text
Observation / Incident / Near Miss
  ↓
Assessment
  ↓
Corrective Action
  ↓
Responsible Person
  ↓
Due Date
  ↓
Verification
  ↓
Closure
```

## Variation

```text
Potential Change
  ↓
Variation Request
  ↓
Cost Impact
  ↓
Time Impact
  ↓
Supporting Documents
  ↓
Internal Approval
  ↓
Client Submission
  ├── Approved
  ├── Rejected
  └── Revised
  ↓
Contract / Budget Adjustment
```

## Project Finance

```text
Contract Value
  +
Approved Variations
  ↓
Revised Contract Value

Project Budget
  ↓
Commitments
  ↓
Actual Costs
  ↓
Forecast
  ↓
Margin
```

Payment applications, payment certificates, retention, advance recovery, invoices, and payments must remain traceable to the project and relevant contract.

## Approval Workflow

A reusable approval engine will support multiple modules.

Common states:

```text
Draft
  ↓
Submitted
  ↓
Pending Approval
  ├── Approved
  ├── Rejected
  └── Returned for Revision
```

Approval conditions may depend on amount, project, department, document type, or another configured business condition.

## Workflow Design Rule

Before implementing a module, document:

- who starts the process;
- required data;
- approval path;
- possible states;
- allowed transitions;
- responsible roles;
- generated records;
- downstream effects;
- notifications;
- audit requirements;
- reporting requirements.
