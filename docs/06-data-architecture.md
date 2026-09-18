# Data Architecture

## Database

The application will use PostgreSQL as the primary relational database.

The modular monolith uses one primary database. Module ownership is enforced through code organization, naming, application operations, authorization, and documented boundaries rather than separate databases.

## Design Principles

- Use normalized relational tables for transactional data.
- Use foreign keys for meaningful relationships.
- Use unique constraints for business identifiers where required.
- Use database constraints when a rule can be enforced reliably at the database level.
- Use indexes based on actual lookup, filtering, sorting, join, and reporting patterns.
- Avoid storing structured business data in JSON when normal relational columns are more appropriate.
- Avoid excessive nullable fields caused by combining unrelated concepts in one table.
- Preserve historical records where business traceability requires them.
- Prefer status values with explicit application rules over uncontrolled free text.

## Module Ownership

A table belongs conceptually to one module.

Examples:

```text
projects                       → Projects
contracts                      → Contracts
documents                      → Document Control
document_revisions             → Document Control
purchase_requests              → Procurement
purchase_orders                → Procurement
suppliers                      → Suppliers
employees                      → Human Resources
daily_site_reports             → Site Operations
equipment                      → Equipment
quality_inspections            → Quality
safety_incidents               → Health and Safety
project_budgets                → Project Finance
variations                     → Variations
```

## Shared Master Data

Master data must be centralized rather than copied as free text into many transaction tables.

Examples:

- companies
- business units
- departments
- sections
- positions
- locations
- cost centers
- currencies
- units
- disciplines
- document types
- supplier categories
- expense categories
- cost codes
- payment terms

## Identifiers

Use database primary keys for relational integrity.

Human-readable business numbers should be separate fields.

Examples:

```text
id: internal database key
project_number: NAS-PROJ-000123
purchase_request_number: PR-2026-000123
purchase_order_number: PO-2026-000456
```

Business numbering rules should be configurable where the process requires it.

## Money

Financial values must use fixed-precision decimal types, never floating-point storage.

Store currency explicitly where multiple currencies may occur.

## Dates and Time

Store timestamps consistently.

Business dates such as contract start date, application date, certification date, delivery date, and document submission date should have explicit columns rather than being inferred from created timestamps.

## Soft Deletes

Do not enable soft deletes automatically on every table.

Use them only where the business requires recoverability or history and where they will not undermine uniqueness, reporting, or legal traceability.

For important transactions, cancellation or status transitions are often clearer than deletion.

## Audit History

Critical business actions require immutable audit history containing at least:

- actor;
- action;
- entity type;
- entity identifier;
- previous values where relevant;
- new values where relevant;
- timestamp;
- request context where appropriate.

Audit logging is not a substitute for domain history tables such as document revisions, approval actions, purchase order amendments, or variation revisions.

## File Metadata

Large files are not stored in PostgreSQL.

The database stores metadata such as:

- storage provider;
- external file identifier;
- path or URL reference;
- original filename;
- MIME type;
- size;
- checksum when required;
- uploaded by;
- uploaded at.

Official corporate documents may be stored in SharePoint. Application-generated files and attachments may use Azure Blob Storage when appropriate.

## Reporting

Reporting requirements must influence schema design from the start.

Before implementing a transactional workflow, identify expected reporting dimensions such as:

- project;
- client;
- department;
- supplier;
- employee;
- cost center;
- date;
- status;
- category.

Do not rely on future reporting logic to reconstruct data the system never captured.
