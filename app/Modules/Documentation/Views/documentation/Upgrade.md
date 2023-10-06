Upgrade
---

---

-   [How to Upgrade BillingTrack](#how-to-upgrade-billingtrack)
-   [How to Upgrade an Add-on](#how-to-upgrade-an-add-on)

---

<a id="how-to-upgrade-billingtrack"></a>
# How to Upgrade BillingTrack
**Upgrade Existing v6.x.x installation to v7.0.0**
- NOTE: BillingTrack-wired v7.0.0 and later require PHP &gt;= 8.2
- **BREAKING CHANGES v7.x.x**
- This update combines the core modules (Quote, Workorder, Invoice, Recurringinvoice, and Purchaseorder) database tables into a single documents table.
- User defined custom templates (in the custom/templates directory) will be affected by this change.
- Note that the existing "custom.blade.php" file in each module directory will be overwritten by the upgrade. If you have modified it directly (without copying it to a new name first) you will need to BACK IT UP prior to upgrade.
- This "custom.blade.php" file is meant to be a starting point for creating your own custom template.
- During Migration, existing custom templates will be copied to a new directory named "V6Backup" in the custom/templates directory.
- The migration will then modify the original custom templates and change all occurrences of ($quote, $workorder, $invoice, and $purchaseorder) to $document.
- It will also modify any references to ${module}->formatted_due_at or ${module}->formatted_expires_at to ${module}->formatted_action_date.
- ANY TEMPLATE CUSTOMIZATIONS OUTSIDE OF THESE PARAMETERS WILL HAVE TO BE CHANGED BY THE USER.
- Users will also need to manually modify any customizations to email templates under Admin - System Settings - Email - Templates.
- The API has also changed so if you are using it you will need to upgrade the API and any code you have referencing it.
- The new API and examples are located in this repository, resources/misc/billingtrack-api-v7.zip
- BACKUP YOUR EXISTING DATABASE !!!!
- Git pull (if originally cloned) or download and overwrite existing
  installation.
- if downloading and extracting zip, delete the
  contents of:
  - "YOUR\_BILLINGTRACK\_WEBSITE/public"
  - "YOUR_BILLINGTRACK_WEBSITE/database/seeds"
  - "YOUR\_BILLINGTRACK\_WEBSITE/app"  
    directories prior to extracting.
- Run composer update
- Start-> YOUR\_BILLINGTRACK\_WEBSITE/setup
- After migration completes, signin.

# UPGRADE FROM BILLINGTRACK 5.X.X
**Minimum PHP requirement for BillingTrack-Wired (v6.X.X) is PHP >= 8.1**
- Create a new installation/site. (see [How to Install BillingTrack](Installation.md))
- Although it is theoretically possible to do so, do not attempt to upgrade an existing BillingTrack 5.x.x Site.
- This is a new repository and existing cloners will not be able to pull the update.
- This will upgrade the existing BillingTrack database, but it is **HIGHLY** recommended to backup the existing database and copy to a new one.
- After copying old database to new and modifying the .env file per the installation instructions to reflect the NEW copied database:
- Start-> YOUR\_BILLINGTRACK\_WEBSITE/setup
- After migration completes (this may take a while. 10 minutes is not unusual), signin.

---

<a id="how-to-upgrade-an-add-on"></a>
### How to Upgrade an Add-on

#### Step 1: Download the add-on package

Download the add-on package to upgrade. Save it locally to your
computer.

#### Step 2: Unzip the add-on package

Navigate to the downloaded Add-on package and unzip the contents.

#### Step 3: Upload the add-on folder to your server

Upload the unzipped add-on folder from your computer to the
custom/addons folder on your server and let it merge/overwrite the
existing folder. It is recommended that you use a standard FTP program
such as
[FileZilla](https://filezilla-project.org/download.php?type=client) to
upload the folder to your server.

#### Step 4: Upgrade the add-on

Log into your BillingTrack install and go to System -&gt; Add-ons and
press the Upgrade button for the add-on if it appears. If the Upgrade
button doesn't appear, then no further action is required and the add-on
should be upgraded and ready to use.
