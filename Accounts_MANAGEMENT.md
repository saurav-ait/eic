Accounts Operation System (Full Documentation)

Overview

The Accounts Operation module manages financial transactions, including
income, expenses, vendor ledgers, reporting, and data import/export. It
ensures accurate tracking of balances and vendor-wise accounting.

------------------------------------------------------------------------

Core Concepts

Entry Types

Income Types - Received - Receivable

Expense Types - Payment - Payable - Purchase - Salary - Office Costs

------------------------------------------------------------------------

Modules

1. Dashboard

Displays: - Total Income - Total Expense - Current Balance

------------------------------------------------------------------------

2. Accounts List

Features: - Search: vendor, purpose, details, country - Filters: vendor,
country, entry type - Pagination

Fields: - Date - Vendor Name - Purpose - Entry Type - Amount - Balance

------------------------------------------------------------------------

3. Vendor Management

-   Vendor list
-   Vendor-wise totals
-   Ledger access

------------------------------------------------------------------------

4. Vendor Ledger

Features: - Date filtering - Opening balance - Running balance

Columns: - Date - Type - Purpose - Details - Debit - Credit - Balance

------------------------------------------------------------------------

Calculations

Opening Balance

Opening Balance = Total Income (before date) - Total Expense (before
date)

Running Balance

If Income: Balance += Amount
If Expense: Balance -= Amount

------------------------------------------------------------------------

Reports

Monthly Report

-   Filter by month or custom range
-   Displays all transactions

------------------------------------------------------------------------

Import / Export

Excel Import

-   Upload XLSX/CSV
-   Bulk insert records

Required Columns: - Date - Entry Type - Vendor Name - Purpose - Amount

Export

-   PDF (Ledger, Accounts)
-   Excel (Full dataset)

------------------------------------------------------------------------

File Storage

Path: storage/app/public/accounts

------------------------------------------------------------------------

System Logic

-   Sort by date, then ID
-   Apply income/expense logic sequentially
-   Save balance per record

------------------------------------------------------------------------

Data Validation

-   Date required
-   Amount numeric
-   Entry type valid
-   File max 5MB

------------------------------------------------------------------------

Future Enhancements

-   Profit & Loss
-   Balance Sheet
-   Multi-currency
-   Role permissions
-   Vue dashboard
-   Payment tracking

------------------------------------------------------------------------

Summary

✔ Financial tracking
✔ Vendor ledger
✔ Accurate balances
✔ Import/export ready
✔ Scalable architecture