# Users Module

## Purpose

The Users module owns application user accounts, authentication, roles, permissions, and account access status.

It does not own employee records. Employee information belongs to the Human Resources module.

## Location

```text
app/Modules/Users/
├── Actions/
├── Console/
├── Database/Seeders/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Policies/
├── UsersServiceProvider.php
└── routes.php
```

## Authentication

Authentication is provided through Laravel Fortify.

Enabled capabilities:

- login;
- logout;
- password reset;
- email verification;
- login rate limiting.

Public self-registration is intentionally disabled. Noor Al Sahara is an internal business system, so authorized administrators create user accounts.

Inactive accounts cannot authenticate. If an already-authenticated account is deactivated, the account is logged out on its next request.

## Authorization

Authorization is permission-based.

Initial permissions:

```text
users.view
users.create
users.update
users.change-status
roles.view
roles.create
roles.update
```

Roles group permissions. Application authorization checks permissions rather than checking business logic against role names.

The seeded `system-administrator` role receives all permissions and is protected from editing.

## User Lifecycle

```text
Administrator Creates User
  ↓
Verification Email
  ↓
User Verifies Email
  ↓
Active User Can Sign In
  ↓
Roles Determine Permissions
  ↓
Administrator May Deactivate Account
```

Users are deactivated instead of deleted.

## Integrity Rules

- a user cannot change their own role assignments;
- a user cannot deactivate their own account;
- changing an email address clears email verification and sends a new verification notification;
- deactivated accounts cannot authenticate;
- the system administrator role cannot be modified;
- password validation requires at least 12 characters with mixed case, numbers, and symbols.

## Initial Administrator

After migrations are complete, create the first administrator with:

```bash
php artisan users:create-administrator admin@example.com --name="System Administrator"
```

The command prompts securely for the password and ensures the access-control seed data exists.

## Database

The Users module uses:

- `users`
- `roles`
- `permissions`
- `role_user`
- `permission_role`
- `password_reset_tokens`
- `sessions`

## Frontend

Administration routes:

```text
/administration/users
/administration/roles
```

Authentication routes are provided by Fortify.

## Testing

Feature tests cover:

- successful authentication;
- inactive account rejection;
- email verification enforcement;
- password reset requests;
- unauthorized administration access;
- user creation;
- self-role protection;
- self-deactivation protection;
- role creation;
- protected system administrator role.
