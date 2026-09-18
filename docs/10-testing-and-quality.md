# Testing and Quality

Automated testing protects business workflows, permissions, financial calculations, document state, and cross-module behavior.

## Testing Priorities

Testing effort should focus on behavior that can damage operations, finances, security, or traceability if it fails.

Priority areas:

- authentication;
- permissions;
- approval rules;
- document revision rules;
- procurement workflow;
- project financial calculations;
- employee and project assignments;
- site reporting;
- quality and safety workflows;
- integration behavior;
- import validation.

## Feature Tests

Feature tests are the default for business workflows.

Examples:

```text
user_without_permission_cannot_view_project
employee_cannot_approve_own_purchase_request
approved_document_revision_cannot_be_modified
purchase_order_requires_approved_purchase_request
purchase_order_cannot_exceed_authorized_value_without_reapproval
variation_updates_revised_contract_value_after_approval
cancelled_transaction_is_not_treated_as_active
```

## Unit Tests

Use unit tests for isolated calculations or components where they provide clearer and faster feedback.

Examples:

- monetary calculations;
- retention calculations;
- numbering rules;
- status-transition rules;
- date calculations;
- reusable parsers.

Do not create unit tests merely to mirror implementation details.

## Database Testing

Tests involving persistence should verify:

- foreign keys;
- unique constraints;
- required fields;
- transaction behavior;
- status transitions;
- reporting-relevant values.

Use factories to create explicit, readable test data.

## Authorization Testing

Every protected business capability must include negative tests.

Do not test only that authorized users succeed. Also test that unauthorized users are denied.

## Integration Testing

External services should be isolated in automated tests using fakes, mocks, or test adapters where appropriate.

Test:

- successful synchronization;
- authentication failure;
- timeout;
- rejected request;
- duplicate retry;
- partial failure;
- idempotency where required.

Automated CI tests should not depend on live production integrations.

## Frontend Testing

Frontend testing should focus on important user interactions and state rather than duplicating backend validation.

The backend remains authoritative for business rules and permissions.

## Static Analysis and Formatting

The project should use automated formatting and static analysis.

Expected checks may include:

- Laravel Pint;
- PHP static analysis;
- JavaScript or TypeScript linting;
- frontend build;
- automated tests;
- dependency security checks.

Exact tools will be added deliberately during the project foundation implementation.

## Pull Request Quality Gate

A Pull Request should not merge when required CI checks fail.

At minimum, the pipeline should eventually verify:

```text
Composer Validation
  ↓
PHP Setup
  ↓
Dependency Install
  ↓
Formatting Check
  ↓
Static Analysis
  ↓
Backend Tests
  ↓
Frontend Dependency Install
  ↓
Frontend Lint / Type Check
  ↓
Frontend Build
```

## Definition of Done

A business change is not complete until:

- requirements are understood;
- implementation is reviewed;
- authorization is enforced;
- relevant automated tests pass;
- database changes are safe;
- audit behavior is covered when required;
- documentation is updated when architecture or workflow changed;
- CI passes.
