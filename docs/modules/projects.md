# Projects Module

## Purpose

Projects are the operational spine of the Noor Al Sahara system. Downstream modules reference a project instead of duplicating project identity.

## Owns

- Project master
- Milestones
- Risks
- Issues
- Progress state

## Relationships

A project references:

- Client
- Company
- optional Business Unit
- optional Location

Project staffing is intentionally deferred to Human Resources because an employee is not the same thing as a system user.

## Permissions

- `projects.view`
- `projects.create`
- `projects.update`
- `projects.manage-progress`

## Project Statuses

- planned
- active
- on-hold
- completed
- cancelled

Completed projects cannot be silently reopened; a dedicated reopen workflow is required.
