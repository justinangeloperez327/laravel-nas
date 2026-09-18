# Deployment

## Environments

The application will use separate environments:

```text
Development
  ↓
Staging / UAT
  ↓
Production
```

Production data must not be used casually in development.

## Target Platform

The target production platform is Microsoft Azure.

The final Azure service selection will be confirmed during infrastructure implementation, but the initial architecture is expected to include:

```text
Internet
  ↓
Azure Edge / Front Door
  ↓
Laravel Application
  ├── PostgreSQL
  ├── Redis
  ├── Blob Storage
  ├── Microsoft / SharePoint Integrations
  └── Application Monitoring
```

## Application Deployment

The system remains one deployable Laravel application.

Business modules do not receive separate deployments.

## Database

Use managed PostgreSQL in production where practical.

Requirements:

- automated backups;
- encrypted connections;
- controlled network access;
- restricted credentials;
- monitored storage and performance;
- tested restore procedure.

## Redis

Redis may be used for:

- cache;
- queues;
- rate limiting;
- other ephemeral application state where justified.

Do not treat Redis as the system of record.

## Background Workers

Queue workers are deployed with the application environment and monitored separately from web request handling.

Likely queued work:

- email;
- notifications;
- imports;
- exports;
- SharePoint synchronization;
- file processing;
- report generation;
- external synchronization.

## Scheduled Jobs

Laravel scheduling may support:

- approval reminders;
- document expiry alerts;
- employee certification expiry alerts;
- insurance expiry alerts;
- overdue purchase deliveries;
- overdue tasks;
- daily summaries;
- integration synchronization.

## CI/CD

GitHub Actions will be used for automated validation and deployment workflows.

Pull Requests should run quality checks before merge.

Production deployment should originate from the protected production branch and use reproducible automation.

## Configuration

Environment-specific values remain outside source code.

Examples:

- application URL;
- database connection;
- Redis connection;
- mail configuration;
- Azure storage configuration;
- Microsoft credentials;
- external integration endpoints.

## Observability

Production must provide visibility into:

- application exceptions;
- failed jobs;
- integration failures;
- slow requests;
- database performance;
- authentication failures;
- resource utilization;
- availability.

## Backups and Recovery

Define and document:

- backup frequency;
- retention;
- recovery point objective;
- recovery time objective;
- database restore procedure;
- storage restore procedure where required;
- disaster recovery responsibilities.

A backup strategy is incomplete until restore procedures have been tested.
