# Module Boundaries

This document defines which module owns each major business concept.

## Ownership

| Module | Owns |
| --- | --- |
| Users | users, roles, permissions, user access |
| Organization | companies, business units, departments, sections, positions, locations, cost centers |
| Clients | client organizations and client contacts |
| Projects | projects, project teams, milestones, project status, project risks and issues |
| Contracts | client contracts and contract-level commercial terms |
| Approvals | reusable approval workflows and approval history |
| Document Control | document metadata, revisions, transmittals, document workflow |
| Procurement | purchase requests, quotation processes, evaluations, purchase orders, receipts |
| Suppliers | supplier master records, qualification, supplier documents and performance |
| Inventory | item master, stock, warehouses, project stores and stock movements |
| Human Resources | employees, assignments, attendance, leave, timesheets and employee records |
| Site Operations | daily reports, manpower records, site activity and progress |
| Equipment | equipment, vehicles, tools, maintenance and assignments |
| Quality | inspections, non-conformance and corrective quality actions |
| Health and Safety | incidents, observations, inspections and safety actions |
| Subcontractors | subcontractor master records, packages, performance and subcontract-related records |
| Project Finance | budgets, commitments, project costs, invoices, payments, retention and forecasts |
| Variations | project and contract variation workflow |
| Reporting | read-oriented dashboards, aggregated reports and exports |

## Primary Dependencies

```text
Users
  ↓
Organization
  ↓
Projects
  ├── Contracts
  ├── Document Control
  ├── Procurement
  ├── Human Resources
  ├── Site Operations
  ├── Equipment
  ├── Quality
  ├── Health and Safety
  ├── Subcontractors
  ├── Project Finance
  └── Variations
```

Approvals is a supporting module used by multiple business modules.

Reporting reads from multiple modules but should avoid becoming the place where business transactions are performed.

## Boundary Rules

### Projects

Projects may reference organization, clients, users, and contracts.

Other modules may reference projects, but must not duplicate project master data.

### Procurement and Suppliers

Procurement owns procurement transactions.

Suppliers owns supplier master data.

A purchase order references a supplier but does not own the supplier profile.

### Human Resources and Users

A system user account and an employee record are different concepts.

Not every employee must necessarily have an application user account, and not every privileged system account must be modeled as an employee.

### Project Finance

Project Finance owns project-level financial control.

It does not automatically become a complete accounting ledger.

Accounting-system integration should remain separate where the organization already has an accounting platform.

### Document Control and Storage

Document Control owns document metadata, revision state, numbering, and workflow.

SharePoint or Azure Blob Storage owns file storage.

The database stores references and metadata rather than large file binaries.

### Reporting

Reporting may aggregate data from other modules.

It should not own source transactions and should not update source records merely to make reports easier.

## Cross-Module Communication

Prefer explicit calls to module actions or stable interfaces.

Avoid widespread direct mutation of another module's models.

Reading related models is acceptable where it is simple and clear. When cross-module behavior becomes substantial, introduce a defined application-level operation instead of hiding the dependency.
