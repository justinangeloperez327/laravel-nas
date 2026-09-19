# Document Control Module

## Purpose

Document Control owns document metadata, revisions, review state, transmittals, and correspondence.

## File Storage

The database stores file metadata and external identifiers. Large file binaries are not stored in PostgreSQL.

Supported metadata providers are prepared for:

- SharePoint
- Azure Blob Storage
- local development storage
- externally managed files

Actual SharePoint and Azure upload adapters are added in their integration branches.

## Revision Approval

Draft revision → Submit → Approval Engine → Approved / Rejected / Returned.

When a revision becomes approved, the previous approved revision is marked superseded. Revision records are retained for traceability.

## Permissions

- `documents.view`
- `documents.create`
- `documents.submit`
- `documents.approve`
- `documents.manage-transmittals`
