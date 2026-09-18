# Development Workflow

## Branch Model

`main` represents the stable integration branch.

Development work is performed on focused branches.

Examples:

```text
project-foundation
feature/users
feature/organization
feature/projects
feature/approvals
feature/document-control
feature/procurement
feature/human-resources
feature/project-finance
```

Do not create every future branch in advance.

Create a branch when that work begins.

## Workflow

```text
main
  ↓
Create focused branch
  ↓
Implement complete change
  ↓
Review files
  ↓
Run automated checks
  ↓
Push branch
  ↓
Pull Request
  ↓
CI
  ↓
Review
  ↓
Merge to main
```

## Development Order

The planned high-level order is:

1. Project foundation
2. Users
3. Organization
4. Clients
5. Projects
6. Contracts
7. Approvals
8. Document Control
9. Suppliers
10. Procurement
11. Inventory
12. Human Resources
13. Site Operations
14. Equipment
15. Quality
16. Health and Safety
17. Subcontractors
18. Project Finance
19. Variations
20. Reporting
21. Advanced integrations and analytics

Dependencies may cause small adjustments, but changes to the ordering should be deliberate.

## Module Implementation Sequence

Before coding a module:

```text
Understand Workflow
  ↓
Confirm Requirements
  ↓
Define Data Ownership
  ↓
Design Database
  ↓
Define Permissions
  ↓
Implement Backend
  ↓
Implement Frontend
  ↓
Write Tests
  ↓
Review Security
  ↓
Run CI
  ↓
Pull Request
```

## Commit Guidance

Commits should represent coherent changes.

Avoid arbitrary rules such as one commit per line or one commit per tiny edit.

A good commit should:

- have one understandable purpose;
- leave the branch in a reasonable state;
- use a clear imperative message;
- avoid mixing unrelated refactors with business changes.

Examples:

```text
Add project foundation documentation
Add organization module migrations
Add project creation workflow
Add purchase request approval policy
Fix document revision authorization
```

## Pull Requests

A Pull Request should explain:

- what changed;
- why it changed;
- important business rules;
- migrations or configuration changes;
- tests added;
- known follow-up work.

Large modules may require multiple Pull Requests when that reduces risk, but each Pull Request should deliver a coherent increment.

## Coding Rules

- Follow Laravel conventions unless the modular boundary provides a clear reason not to.
- Keep module code inside its owning module.
- Keep controllers thin.
- Put request validation in request classes where appropriate.
- Use actions for meaningful business operations.
- Use policies for resource authorization.
- Keep business rules on the server.
- Do not introduce abstraction without a real problem.
- Do not add dependencies casually.
- Prefer readable code over clever code.
- Preserve auditability of important state changes.

## Architecture Changes

Changing from the modular-monolith architecture requires an explicit decision.

Do not introduce:

- microservices;
- separate module repositories;
- separate module databases;
- full Domain-Driven Design layers;
- Clean Architecture layers;

as incidental refactors.

If architecture must change, document the reason, alternatives, consequences, and migration plan before implementation.
