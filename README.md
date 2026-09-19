# Noor Al Sahara Integrated Business System

Internal business platform for Noor Al Sahara, built as a Laravel modular monolith.

## Technology

- Laravel 13
- PHP 8.4.1+
- React 19
- Inertia 3
- TypeScript
- Tailwind CSS 4
- PostgreSQL
- GitHub Actions
- Microsoft Azure target deployment

## Architecture

The application uses a **modular monolith**:

- one repository;
- one Laravel application;
- one primary PostgreSQL database;
- one deployment unit;
- explicit business modules under `app/Modules`;
- external systems isolated under `app/Integrations`;
- shared code kept intentionally small.

The architecture and module boundaries are documented in [docs](docs/README.md).

## Current Status

The application foundation is complete and the first modular-monolith business module, **Users**, is implemented.

The Users module provides authentication, email verification, password reset, roles, permissions, account status management, and administrator-controlled user provisioning.

## Development

Install PHP dependencies:

```bash
composer install
```

Copy the environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

Configure PostgreSQL in `.env`, then run migrations:

```bash
php artisan migrate
```

Install frontend dependencies:

```bash
npm install
```

Start the application:

```bash
composer dev
```

## Quality Checks

Backend tests:

```bash
php artisan test
```

PHP formatting check:

```bash
vendor/bin/pint --test
```

Frontend type check:

```bash
npm run typecheck
```

Frontend production build:

```bash
npm run build
```
