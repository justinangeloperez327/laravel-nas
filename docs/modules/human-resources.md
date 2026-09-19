# Human Resources Module

## Core Rule

Employee and User are different concepts.

- Employee = workforce/employment record.
- User = application login and permissions.

An employee may optionally link to a User account.

## Owns

- Employees
- Employee documents
- Project assignments
- Attendance
- Timesheets and overtime
- Leave requests
- Certifications
- Shifts

## Project Staffing

Employee Project Assignments are the source of project staffing. Projects do not directly assign system users as workers.

## Leave Approval

Leave requests use the shared Approval Engine through the `LEAVE-APPROVAL` workflow.

Payroll is intentionally not implemented until the existing payroll/accounting environment is confirmed.
