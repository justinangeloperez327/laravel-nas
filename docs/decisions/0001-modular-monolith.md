# Architecture Decision 0001: Use a Modular Monolith

## Status

Accepted

## Context

The Noor Al Sahara system will cover many connected business capabilities including projects, contracts, document control, procurement, employees, site operations, quality, health and safety, equipment, subcontractors, project finance, approvals, and reporting.

These capabilities have distinct business responsibilities but also share significant transactional relationships.

A traditional unstructured Laravel application would risk mixing business logic across global controllers, models, and services as the application grows.

Microservices would introduce distributed transactions, multiple deployments, network failure modes, service discovery, observability complexity, duplicated infrastructure, and significantly higher operational overhead before those costs are justified.

## Decision

Build the application as a Laravel modular monolith.

The system will use:

- one repository;
- one Laravel application;
- one primary deployment unit;
- one primary PostgreSQL database;
- explicit business modules under `app/Modules`;
- explicit external integrations under `app/Integrations`;
- a small `app/Shared` area for genuinely shared code.

Each business module owns its application logic and conceptual database tables.

## Consequences

### Positive

- simpler development and deployment;
- strong transactional consistency;
- clear business boundaries;
- easier local reasoning than distributed services;
- lower infrastructure cost;
- straightforward testing;
- ability to refactor module boundaries while the product is still evolving.

### Negative

- module discipline must be enforced by the codebase and reviews;
- all modules share one runtime and primary database;
- poor boundaries could still produce coupling if direct cross-module mutations become common;
- scaling is primarily application-wide unless specific workloads are separated later.

## Rules

The modular monolith remains the baseline architecture.

The project will not casually introduce:

- microservices;
- separate databases per module;
- separate repositories per module;
- full Domain-Driven Design layers;
- Clean Architecture layers.

A future architecture change requires a separate documented decision based on an observed limitation rather than preference or fashion.
