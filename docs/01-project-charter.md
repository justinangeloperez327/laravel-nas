# Project Charter

## Project Name

Noor Al Sahara Integrated Business System

## Purpose

Build a unified internal business platform for Noor Al Sahara that connects project delivery, document control, procurement, employees, site operations, quality, health and safety, equipment, subcontractors, project finance, approvals, and management reporting.

The system is intended to reduce fragmented spreadsheet, email, paper, and disconnected application workflows while preserving clear ownership, approval history, traceability, and reporting.

## Primary Objectives

- Centralize project-related operational data.
- Standardize business workflows across departments.
- Provide reusable approval workflows.
- Improve document and revision control.
- Connect procurement activity to projects, budgets, suppliers, deliveries, and payments.
- Connect employees, timesheets, attendance, and project assignments.
- Capture site activity and project progress.
- Provide reliable project financial visibility.
- Provide management dashboards and operational reports.
- Maintain complete audit trails for material business actions.
- Integrate with Microsoft and Azure services where appropriate.

## Architecture

The application will use a **modular monolith** architecture.

Core principles:

- One Laravel application.
- One GitHub repository.
- One primary deployment unit.
- One primary PostgreSQL database.
- Business modules own their own application logic.
- Cross-module dependencies must be explicit.
- Shared code must remain small and genuinely shared.
- Business logic does not belong in controllers or React components.

## Technology Baseline

- Laravel 13
- PHP 8.4.1 or newer
- PostgreSQL
- React with Inertia
- Tailwind CSS
- Redis where caching or queues justify it
- Azure for production hosting and supporting services
- GitHub Actions for automated checks and deployment workflows

## Success Criteria

The system is successful when:

- users can complete key workflows without relying on parallel spreadsheet tracking;
- approvals are traceable;
- project data can be reported consistently;
- module ownership is clear in the codebase;
- permissions prevent unauthorized access;
- business-critical workflows are covered by automated tests;
- production deployments are repeatable through CI/CD;
- the platform can grow without requiring a rewrite into microservices.

## Initial Delivery Strategy

The complete system will be built incrementally.

Foundation comes first, followed by shared organizational capabilities, then operational modules in dependency order. Each module should be usable and tested before moving substantial development effort into the next dependent module.
