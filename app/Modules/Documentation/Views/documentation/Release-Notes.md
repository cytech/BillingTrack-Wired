Release Notes  
---
---

### BillingTrack 6.1.1 (2023-04-01)
- fix duplicate item creation after module save

### BillingTrack 6.1.0 (2023-04-01)
- Upgrade to Laravel 10
- update Laravel deprecated $dates to $casts
- update all dependencies
- fix php8.1 null deprecations
- adminlte to v4-dev-bs530
- revamp skinning with bootstrap 5.3 color-modes
- add condensed option to timesheet report
- fix product cost to price in add product
- fix custom fields error in add modules
- modify client activity widget


### BillingTrack 6.0.6 (2023-01-12)
- update Fullcalendar to v6.x
- update chart.js to v4.x
- update axios to v1.x
- update vanilla-datetimerange-picker fork
- update all dependencies
- migrate webpack to vite
- allow_html in markdown config for documentation images
- fix client balance lookup for soft deletes

### BillingTrack 6.0.5 (2022-12-09)
- fix regression in Base Currency setting and currency conversion

### BillingTrack 6.0.4 (2022-10-14)
- fix regression in datatable search returnurl
- add red/bold to overdue invoice due_at in datatable
- move company profile and status filters to datatables for Quotes, Workorders and Invoices
- add saveTab to client view
- replace deprecated javascript substr() with slice() 
### BillingTrack 6.0.3 (2022-09-26)
- add employee type and termination date
- update employees available query to include null term_date or term_date > $date
- fix datatable status on paginate
- fix error with employee resource id in scheduler
- update laravel-livewire-tables to V2
- modified create-seeded-workorder-modal checkbox selection
### BillingTrack 6.0.2 (2022-06-14)
- fix workorderToInvoice date setting
### BillingTrack 6.0.1 (2022-05-01)
- minor ui fixes
### BillingTrack 6.0.0 (2022-05-01)
- Combine and optimize javascript in Scheduler Module
- complete rework of client unique_name, all client lookups now based on client name.    
  possible breaking change for add-on developers accessing the firstOrCreateByUniquename method.  
  **Migration changes and update all unique names as:**  
  if unique_name == name , unique name = name truncated to 10 characters with an underscore and a random 5 character suffix.  
  if unique_name contains name, unique_name = name truncated to 10 characters with an underscore and existing unique_name with name removed suffix.  
  if unique_name does not match above assume custom unique is entered and, unique_name = name truncated to 10 characters with an underscore and existing unique_name suffix.
- complete rework of schedule reminders. The ability to create multiple reminders for the same event never made much sense. The rework more closely follows "standard" reminder pratices...
    * Reminders have been moved as a select item in create event/recurring event  
      Only one reminder can be created per occurrence.  
      Existing reminders will be migrated to:  
      schedule_reminders->reminder_location to schedule-> location_str.  
      schedule_reminders->reminder_date to schedule->occurrence->reminder_date.  
      In the cases where many reminders were created on the same event, **ONLY THE LAST CREATED REMINDER WILL BE TRANSFERRED**.  
      The old schedule_reminders table is then deleted.
- Livewired module create modals
- Livewired all lookups (clients, employees, vendors, products, itemlookups)
- Livewired all datatables and removed yajra/datatables
- remove/replace Jquery and all dependencies
- update to Bootstrap 5 and adminlte v4
- Added ACL via Spatie/permissions
- add Laravolt Avatar
- cleanup online payment systems, replace deprecated PayPal/rest-api-php with srmklive/laravel-paypal
- Upgrade to Laravel 9 and all dependencies


### BillingTrack 5.3.3 (2021-11-05)
- fix error in purchaseorder observer
- add modified event to invoice, quote, workorder apicontrollers

###  BillingTrack 5.3.2 (2021-10-22)
- fix autocomplete lookup and stock counting
- add product and employee selection modals to quotes, invoice, recurring and workorders
- fix badge name and translation in datatables
- (above fix issues #24, #28 and #36)
- update dependencies
- correct "deleteing" typo in model observers
- add restoring to model observers
- track workorder_id and invoice_id as deleted/restored in quotes and workorders
- update various badges to show delete status
- removed group renumbering from deleting observers
- migration add unsigned to invoice_id in workorders table
- fix saving items  in quotes, workorders, invoices, recurring invoices and purchase orders. (remove 'saved' in
  corresponding observers and place in edit controllers. Was causing unnecessary parent model recalc on every item which
  led to long save times on records with many items. )
- added "saving" alert on record save.
- fix purchaseorder receive product numstock updating

### BillingTrack 5.3.1 (2021-02-10)
- added scheduler setting for today background color
- remove background from login page logo

### BillingTrack 5.3.0 (2021-02-08)
- upgrade to Laravel 8 (PHP 8 compatability)
- upgrade dependencies
- fixed an issue with Expense Creation

### BillingTrack 5.2.0 (2020-08-01)
- upgrade to Laravel 7
- upgrade dependencies
- upgrade FullCalendar to v5
- added tippy.js for tooltips in calendar
- replaced bootstrap-switch with bootstrap-switch-button
- added Schedule page for scheduled employees and resources
- fix Vendor delete
- added category, itemlookup and mail queue datatables
- updated all datatables
- add swalConfirm target (for sweetalert over iframe)
- added bulk client inactive to database management

### BillingTrack 5.1.0 (2019-10-01)
- Added client shipping address fields (address_2,city_2,etc) accessible in templates
- Added client industry, size, id, and VAT fields
- Added client contact firstname, lastname, title, phone, fax, mobile, primary , optin and note fields
- Added individual Client payment terms and accessed by due_at (if not set defaults to invoice config value)
- Converted many events/listeners to model observers
- Expand vendor info
- General categories and vendors for expenses and products
- App name/namespace/language to BT
- remove FusionInvoice 2018-8 conversion from setup
- Added Purchase Order Module which connects to vendors and products
- Added config for collapsed side menu
- corrected invoice update when payment amount is changed.
- corrected payment emailing
- added product inventory tracking ability to sent invoices.
  note:previously unused "type" column of products table has been changed to "inventorytype_id"
  Any existing entries in the type column will be migrated to the inventory_types table and updated
  in the product. This table is currently not editable within the application.
  This is a breaking change if you have any custom code that is accessing the type column in the products table.
- Added Database clean/purge tool
- Revamped Documentation
- Update to Laravel 6.0
- Update deprecated Fixer.io API for currency exchange rates. To use currency exchange rates in BillingTrack,
  you have to signup for a (free) API key at https://fixer.io. Once you get the key,
  Enter it in System Settings - General - FixerIO API Key
- updated compatibility with latest Paypal, Mollie and Stripe API's
- Remove zero balance invoices from Overdue status filter

### BillingTrack 5.0.0 (Mar 19, 2019)

-   \- PHP requirement &gt;=7.2
-   \- update to Laravel 5.8.\*
-   \- update to sweetalert2 v8
-   \- fixed errors when saving online payments
-   \- fix filter in OrphanCheck
-   \- fix error in recurr Show Proposed recurrence
-   \- "fixed" top navbar
-   \- added global "back to top" of page
-   \- corrected numerous export errors
-   \- add client\_id to payment importer
-   \- update resources
-   \- update to mix 4

---

### BillingTrack 4.1.2 (Dec 17, 2018)

-   \- disable scroll in datetimepicker inputs

---

### BillingTrack 4.1.1 (Dec 16, 2018)

-   \- add formatted\_summary to truncate display
-   \- correct some datatables sorting
-   \- correct duplicate class btn-enter-payment
-   \- @{{ trans() }} to @@lang
-   \- save manage trash tab state
-   \- add workorder\_to\_invoice default date setting (job\_date or
    current date)

---

### BillingTrack 4.1.0 (Nov 29, 2018)

-   \- upgrade to laravel 5.7.\*
-   \- migrate to Bootstrap 4.1.\*
-   \- start resource move to laravel mix where applicable
-   \- fix email cc and bcc
-   \- documentation
-   \- "enabled module" in sidebar (system setting)
-   \- enabled update checker
-   \- added JQuery-UI theme config
-   \- clean and optimize scheduler
-   \- enable live demo

---

### BillingTrack 4.0.2 (Oct 14, 2018)

-   \- added resource quantity selection to createworkoorder modal
-   \- corrected some error response dialogs
-   \- validation for end\_time greater than start\_time
-   \- calendar create workorder-redirect to workorder if no client
    address (new client)

---

### BillingTrack 4.0.1 (Sept 20, 2018)

-   \- added "todays workorder" widget
-   \- added "recent payments" widget
-   \- modified setup for upgrade
-   \- added resources/lang/en-cust where client = customer
-   \- added system info (.env settings) tab to system settings
-   \- added modal Enter Payment function, with client lookup and
    payable invoices
-   \- server side datatables for scheduler categories, events,
    recurring events
-   \- correct employee lookup in calendar to approved workorders
-   \- workorder datatable sort by job date instead of expires\_at
-   \- added orphan check utility for Scheduler (checks workorders for
    Unschedulable employees)

---

### BillingTrack 4.0.0 (Sept 6, 2018)

-   \- change server to public directory (requires apache change)
-   \- configure from .env (copy .env.example to .env and change
    required variables)
-   \- clean, consolidate and restructure mysql database
-   \- transfer existing 2018-8 database to new structure upon setup
-   \- move high profile sortables to server side datatables
-   \- implement softdeletes, with trash management
-   \- update to laravel 5.6.\*
-   \- update all resources
-   \- add toolbox
-   \- add products
-   \- add employees
-   \- item lookup modal in quotes and invoices
-   \- extend skin configuration
-   \- integrate timetracking (projects/tasks/timers)
-   \- integrate workorders
-   \- integrate Scheduler

---
