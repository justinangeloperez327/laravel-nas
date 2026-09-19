# Organization Module

## Purpose

The Organization module owns Noor Al Sahara organizational master data.

## Hierarchy

```text
Company
└── Business Unit
    └── Department
        └── Section
```

Company-level master data also includes:

- Positions
- Locations
- Cost Centers

Employee assignments are intentionally excluded. Employees belong to the Human Resources module and will reference Organization records.

## Permissions

- `organization.view`
- `organization.create`
- `organization.update`
- `organization.change-status`

## Integrity Rules

- company codes are globally unique;
- business-unit codes are unique within a company;
- department codes are unique within a business unit;
- section codes are unique within a department;
- position, location, and cost-center codes are unique within a company;
- organization records are deactivated instead of deleted;
- foreign keys restrict deletion of parent records that are already in use.

## Tables

- `companies`
- `business_units`
- `departments`
- `sections`
- `positions`
- `locations`
- `cost_centers`
