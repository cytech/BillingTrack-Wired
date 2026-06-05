Upgrade
---

---

-   [How to Upgrade BillingTrack](#how-to-upgrade-billingtrack)
-   [How to Upgrade an Add-on](#how-to-upgrade-an-add-on)

---

<a id="how-to-upgrade-billingtrack"></a>
# How to Upgrade BillingTrack
**Upgrade Existing v7.x.x installation to v8.X.X**  
**Do not attempt to upgrade a Billingtrack version older than v7.x.x to V8 [SEE UPGRADING FROM BILLINGTRACK 6.X.X](#upgrade-from-billingtrack-6xx)**
- NOTE: BillingTrack-wired v8.0.0 and later require PHP &gt;= 8.3
- BACKUP YOUR EXISTING DATABASE !!!!
- Git pull (if originally cloned V7.X.X) or download zip (https://github.com/cytech/BillingTrack-Wired/archive/refs/tags/v8.0.0.zip).
- if downloading and extracting zip, delete the
  contents of:
  - "YOUR\_BILLINGTRACK\_WEBSITE/public"
  - "YOUR_BILLINGTRACK_WEBSITE/database/seeds"
  - "YOUR\_BILLINGTRACK\_WEBSITE/app"  
    directories **PRIOR** to extracting.
- **BillingTrack V7 .env file needs to be updated**. It is recommended to backup (or rename) your .env file and then Copy ".env.example" to ".env" .
  - edit .env and change:
    - APP_KEY= (your old APP_KEY)
    - DB_HOST= (your old DB_HOST)
    - DB_DATABASE= (your old DB_DATABASE)
    - DB_USERNAME= (your old DB_USERNAME)
    - DB_PASSWORD= (your old DB_PASSWORD)

  NOTE: Do not change APP_ENV=local. Changing this will cause migrations to fail without feedback. Changing this value to "production" has no beneficial effect on BillingTrack operation.  
  **Also, do not change DB_STRICT=true**. Mysql strict mode is now required for BillingTrack V8.

- save .env file.
- Run composer update
- Start-> YOUR\_BILLINGTRACK\_WEBSITE/setup
- After migration completes, signin.

# UPGRADE FROM BILLINGTRACK 6.X.X
**Do not attempt to upgrade a Billingtrack version older than v6.x.x to V8 [SEE UPGRADING FROM BILLINGTRACK 5.X.X](#upgrade-from-billingtrack-5xx)**
- **The upgrade migration is very complex and can take a long time (a test database containing 24,000 documents takes 4 hours to complete)**
- **The migration function removes PHP memory_limit and max_execution_time limits during processing (restores them to original when complete)**
- **This could severely affect your server performance**

- NOTE: BillingTrack-wired v8.0.0 and later require PHP &gt;= 8.3
- **BREAKING CHANGES FROM V6 to v7.x.x/V8.X.X**
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
- Git pull (if originally cloned V6, remote repository has changed to https://github.com/cytech/BillingTrack-Wired/tree/main) or download zip (https://github.com/cytech/BillingTrack-Wired/archive/refs/tags/v8.0.0.zip).
- if downloading and extracting zip, delete the
  contents of:
  - "YOUR\_BILLINGTRACK\_WEBSITE/public"
  - "YOUR_BILLINGTRACK_WEBSITE/database/seeds"
  - "YOUR\_BILLINGTRACK\_WEBSITE/app"  
    directories **PRIOR** to extracting.
  - **BillingTrack V6 .env file needs to be updated**. It is recommended to backup (or rename) your .env file and then Copy ".env.example" to ".env" .
    - edit .env and change:
      - APP_KEY= (your old APP_KEY)
      - DB_HOST= (your old DB_HOST)
      - DB_DATABASE= (your old DB_DATABASE)
      - DB_USERNAME= (your old DB_USERNAME)
      - DB_PASSWORD= (your old DB_PASSWORD)

  NOTE: Do not change APP_ENV=local. Changing this will cause migrations to fail without feedback. Changing this value to "production" has no beneficial effect on BillingTrack operation.  
  **Also, do not change DB_STRICT=true**. Mysql strict mode is now required for BillingTrack V8.

- Run composer update
- Start-> YOUR\_BILLINGTRACK\_WEBSITE/setup
- After migration completes, signin.

# UPGRADE FROM BILLINGTRACK 5.X.X
**If attempting to upgrade from BillingTrack V5.X.X to V7 or V8, you will first need to upgrade to V6**  
**V6.1.2 release is available in the releases section of this repository**  
**Minimum PHP requirement for BillingTrack-Wired (v6.X.X) is PHP >= 8.1**
- Create a new V6.1.2 installation/site. (see [How to Install BillingTrack](#how-to-install-billingtrack))
- Although it is theoretically possible to do so, do not attempt to upgrade an existing BillingTrack 5.x.x Site.
- This is a new repository and existing cloners will not be able to pull the update.
- This will upgrade the existing BillingTrack database, but it is **HIGHLY** recommended to backup the existing database and copy to a new one.
- After copying old database to new and modifying the .env file per the installation instructions to reflect the NEW copied database:
- Start-> YOUR\_BILLINGTRACK\_WEBSITE/setup
- After migration completes (this may take a while. 10 minutes is not unusual), signin.
- With Billingtrack V6.1.2 site installed and functional, you can now proceed to upgrade to V8.X.X (see [How to Upgrade BillingTrack](#how-to-upgrade-billingtrack))


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
