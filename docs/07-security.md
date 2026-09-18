# Security

Security is a foundation requirement and applies to every module.

## Authentication

The application will support secure authentication.

Potential Microsoft Entra ID single sign-on may be introduced when organizational requirements and tenant access are confirmed.

Authentication controls must support:

- secure session handling;
- account activation and deactivation;
- password security when local passwords are used;
- multi-factor authentication where required;
- session invalidation;
- login throttling.

## Authorization

Authorization is permission-based.

Examples:

```text
projects.view
projects.create
projects.update
documents.upload
documents.review
documents.approve
purchase-requests.create
purchase-requests.approve
purchase-orders.create
purchase-orders.approve
```

Roles group permissions, but business code should avoid depending directly on hard-coded role names where permission checks are sufficient.

Authorization must be enforced server-side.

Frontend visibility is not an access-control mechanism.

## Least Privilege

Users receive only the permissions required for their work.

Sensitive modules and fields require additional protection, especially:

- employee personal information;
- salaries if introduced;
- banking information;
- supplier banking information;
- project financial information;
- commercial documents;
- contracts;
- client-confidential documents.

## Input Handling

All external input must be treated as untrusted.

Use:

- request validation;
- type-aware validation;
- length limits;
- allowlists where practical;
- safe file validation;
- framework-supported query binding;
- output escaping.

Avoid constructing SQL from untrusted input.

## File Uploads

Uploads require controls for:

- allowed file types;
- maximum file size;
- filename handling;
- storage isolation;
- authorization;
- malware scanning when required by production policy;
- download authorization.

Never trust a browser-provided MIME type as the only validation mechanism.

## Web Security

Maintain Laravel protections for:

- CSRF;
- session cookies;
- output escaping;
- signed URLs where useful;
- rate limiting;
- password hashing.

Production must use HTTPS.

Appropriate security headers should be added at the application or edge layer.

## Secrets

Secrets must not be committed to Git.

Use environment variables or Azure-managed secret storage.

Examples:

- database passwords;
- Microsoft client secrets;
- SharePoint credentials;
- storage credentials;
- API keys;
- mail credentials.

`.env` remains excluded from source control.

## Auditability

Material actions require audit records.

At minimum, sensitive workflows should trace:

- create;
- update;
- submit;
- approve;
- reject;
- return for revision;
- cancel;
- assign;
- upload;
- download where the business requires it;
- permission changes.

## Dependency Security

CI/CD should include dependency checks as the project matures.

Keep Laravel, PHP packages, JavaScript packages, and GitHub Actions maintained and pinned or versioned appropriately.

## Security Review Rule

Every new module must answer:

- What data is sensitive?
- Who can view it?
- Who can create it?
- Who can modify it?
- Who can approve it?
- Can the creator approve their own transaction?
- What must be audited?
- What can be deleted?
- What must remain immutable?
- What external systems receive the data?
