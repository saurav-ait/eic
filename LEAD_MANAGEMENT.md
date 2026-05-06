# Lead Management System

## Overview
Complete lead management system for tracking business leads with country and activity type associations.

## Features

### 1. Lead CRUD Operations
- Create, Read, Update, Delete leads
- Track company information, contacts, and location
- Associate leads with countries and activity types
- Status tracking (New, Contacted, Converted, Lost)

### 2. Search & Filtering
- Search by company name, phone, email, or city
- Filter by country
- Filter by activity type
- Filter by status

### 3. Import/Export
- Import leads from Excel/CSV files
- Export leads to Excel format
- Sample template: `/public/examples/leads-import-example.csv`

### 4. Statistics Dashboard
- Total leads count
- Today's leads
- Monthly leads

### 5. Communication (Planned)
- Email sending with templates
- SMS/WhatsApp messaging with templates

## Database Structure

### Leads Table
- `id` - Primary key
- `country_id` - Foreign key to countries
- `activity_type_id` - Foreign key to activity_types
- `company_name` - Company name (required)
- `director` - Director/contact person name
- `phone` - Phone number (required, indexed)
- `email` - Email address (indexed)
- `address` - Full address
- `city` - City name
- `status` - Enum: New, Contacted, Converted, Lost
- `timestamps` - Created/updated timestamps

## Routes

### Admin Routes (Requires Admin Role)
```
GET    /admin/leads                    - List all leads
POST   /admin/leads                    - Create new lead
PUT    /admin/leads/{id}               - Update lead
DELETE /admin/leads/{id}               - Delete lead
POST   /admin/leads/import             - Import leads from file
GET    /admin/leads/export             - Export leads to Excel
POST   /admin/leads/{id}/send-email    - Send email to lead
POST   /admin/leads/{id}/send-text     - Send SMS/WhatsApp to lead
```

## Usage

### Adding a Lead
1. Click "+ Add Lead" button
2. Fill in required fields:
   - Company Name
   - Phone
   - Country
   - Activity Type
3. Optional fields: Director, Email, City, Address, Status
4. Click "Save"

### Importing Leads
1. Click "Import" button
2. Select Excel/CSV file
3. File should have columns: company_name, director, phone, email, city, address, country_id, activity_type_id, status
4. Click "Import"

### Exporting Leads
1. Click "Export" button
2. Excel file will be downloaded with all leads

### Filtering Leads
1. Use search box to search by company, phone, email, or city
2. Select country from dropdown
3. Select activity type from dropdown
4. Select status from dropdown
5. Click "Filter"
6. Click "Reset" to clear filters

## Models

### Lead Model
- Location: `app/Models/Lead.php`
- Relationships:
  - belongsTo Country
  - belongsTo ActivityType
  - hasMany LeadLog

### Related Models
- Country (`app/Models/Country.php`)
- ActivityType (`app/Models/ActivityType.php`)
- LeadLog (`app/Models/LeadLog.php`)
- EmailTemplate (`app/Models/EmailTemplate.php`)
- TextTemplate (`app/Models/TextTemplate.php`)

## Controller

### LeadController
- Location: `app/Http/Controllers/LeadController.php`
- Methods:
  - `index()` - Display leads with filters
  - `store()` - Create new lead
  - `update()` - Update existing lead
  - `destroy()` - Delete lead
  - `import()` - Import leads from file
  - `export()` - Export leads to Excel
  - `sendEmail()` - Send email (to be implemented)
  - `sendText()` - Send SMS/WhatsApp (to be implemented)

## Views

### Main View
- Location: `resources/views/client/leads/index.blade.php`
- Features:
  - Statistics cards
  - Search and filter form
  - Data table with pagination
  - Add/Edit modal
  - Import modal

## Import/Export Classes

### LeadsImport
- Location: `app/Imports/LeadsImport.php`
- Handles Excel/CSV import with header row support

### LeadsExport
- Location: `app/Exports/LeadsExport.php`
- Exports leads with all related data

## Future Enhancements

1. Email Integration
   - Configure mail settings in `.env`
   - Implement email sending in `sendEmail()` method
   - Use EmailTemplate for personalized emails

2. SMS/WhatsApp Integration
   - Integrate with SMS gateway (Twilio, etc.)
   - Implement in `sendText()` method
   - Use TextTemplate for messages

3. Lead Scoring
   - Add scoring system based on interactions
   - Track lead quality

4. Activity Timeline
   - Track all interactions with leads
   - View history in LeadLog

5. Bulk Operations
   - Bulk status updates
   - Bulk email sending
   - Bulk delete

## Dependencies

- Laravel Framework
- Maatwebsite Excel (for import/export)
- Carbon (for date handling)

## Security

- All routes protected by authentication middleware
- Admin role required for lead management
- CSRF protection on all forms
- Input validation on all operations
- SQL injection prevention through Eloquent ORM
