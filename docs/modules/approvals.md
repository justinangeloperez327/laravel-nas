# Approvals Module

## Purpose

The Approvals module provides one reusable approval engine for business modules.

## Owns

- Workflow definitions
- Ordered workflow steps
- Approval requests
- Approval action history
- User delegation records

## Approver Types

A step can target:

- a permission slug;
- a role slug;
- a specific user.

Amount thresholds can determine whether a step applies.

## Request States

- pending
- approved
- rejected
- returned

Business modules submit records using `SubmitForApproval` and record decisions through `RecordApprovalAction`.

The approval engine stores references to source records using `entity_type` and `entity_id`; it does not own source business data.
