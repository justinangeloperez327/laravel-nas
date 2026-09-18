# Integrations

External integrations are isolated from business modules under `app/Integrations`.

## Microsoft

Expected Microsoft integrations may include:

- Microsoft Entra ID
- Microsoft Graph
- SharePoint
- Outlook
- Microsoft Teams
- Power BI

These integrations will only be enabled when tenant access, permissions, and business requirements are confirmed.

## SharePoint

SharePoint is intended for official corporate document storage where appropriate.

The application remains responsible for:

- document metadata;
- business status;
- revision logic;
- approval workflow;
- authorization;
- audit history.

SharePoint remains responsible for the stored file.

Typical flow:

```text
User Upload
  ↓
Laravel Validation
  ↓
Document Record
  ↓
Microsoft Graph
  ↓
SharePoint
  ↓
External File Identifier Saved in PostgreSQL
```

## Azure Blob Storage

Azure Blob Storage may be used for:

- generated reports;
- exports;
- temporary processing files;
- application attachments;
- files that do not belong in the corporate SharePoint repository.

The storage choice must be explicit by file category.

## Accounting

If Noor Al Sahara already uses an accounting platform, Project Finance should integrate with it rather than attempting to duplicate the entire accounting ledger.

Potential integration data includes:

- suppliers;
- purchase orders;
- supplier invoices;
- client invoices;
- payments;
- cost postings;
- project financial references.

The exact direction of synchronization will be defined after the accounting platform is confirmed.

## Power BI

Power BI may provide deeper management analytics.

Operational screens and transactional dashboards remain in Laravel.

Power BI is intended for:

- management analytics;
- historical trends;
- cross-project analysis;
- financial analysis;
- executive reporting.

## Integration Design Rules

- No third-party API calls directly from controllers.
- External API details remain inside integration classes.
- Business modules depend on stable application-facing contracts when integration complexity justifies them.
- Long-running synchronization should use queued jobs.
- Integration failures must be observable.
- Retrying must not create duplicate transactions.
- External identifiers must be stored explicitly.
- Synchronization must define the source of truth for each field.
- Credentials must remain outside source control.

## Integration Questions

Before implementing an integration, define:

- system of record;
- authentication method;
- required permissions;
- data direction;
- synchronization frequency;
- failure behavior;
- retry behavior;
- duplicate protection;
- audit requirements;
- rate limits;
- data ownership;
- data retention.
