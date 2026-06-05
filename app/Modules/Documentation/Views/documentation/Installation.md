Installation
---

---

-   [How to Install BillingTrack](#how-to-install-billingtrack)
-   [How to Install an Add-on](#how-to-install-an-add-on)

---

<a id="how-to-install-billingtrack"></a>
### How to Install BillingTrack
**_Repository Cloning_**  
Version 8.X.X  
git clone https://github.com/cytech/BillingTrack-Wired.git, git checkout main

Version 7.0.6  
git clone https://github.com/cytech/BillingTrack-Wired.git, git checkout v7.0.6

Version 6.1.2  
git clone https://github.com/cytech/BillingTrack-Wired.git, git checkout v6.1.2.FINAL

**_ZIP Download_**  
Version 8.0.0  
https://github.com/cytech/BillingTrack-Wired/archive/refs/tags/v8.0.0.zip

Version 7.0.6  
https://github.com/cytech/BillingTrack-Wired/archive/refs/tags/v7.0.6.zip

Version 6.1.2  
https://github.com/cytech/BillingTrack-Wired/archive/refs/tags/v6.1.2.zip

1. Clone or download the repository to a new web directory.

2. Run "composer install" in web directory

3. create a NEW BillingTrack database.

4. Copy .env.example to .env

5. edit .env and change:
   -   DB\_HOST=
   -   DB\_DATABASE=
   -   DB\_USERNAME=
   -   DB\_PASSWORD=  
   To your \*\*NEW\*\* database settings.  
   NOTE: Do not change APP_ENV=local. Changing this will cause migrations to fail without feedback. Changing this value to "production" has no beneficial effect on BillingTrack operation.  
   Also, do not change DB_STRICT=true. Mysql strict mode is now required for BillingTrack V8.  
6. save .env file.
   -   Run "php artisan key:generate"
   -   This copies the app key into the .env file, attached to the APP_KEY= line.  
<br/><br/>
7. Set permissions for your site.

8. Start YOUR\_BILLINGTRACK\_WEBSITE/setup

9. After database configuration finishes (this may take awhile. 10 minutes is not unusual):

   * Note: In some instances a fresh install will throw an "unknown error" alert box. If this happens, dismiss the alert box and continue. In all reported cases the migration completed properly but some timeout was thrown that causes the error.
   * Create new account -&gt; creates fresh installation with account
<br/><br/>
10. Sign in

---

<a id="how-to-install-an-add-on"></a>
### How to Install an Add-on

#### Step 1: Download the add-on package

Download the add-on package to install. Save it locally to your
computer.

#### Step 2: Unzip the add-on package

Navigate to the downloaded Add-on package and unzip the contents.

#### Step 3: Upload the add-on folder to your server

Upload the unzipped add-on folder from your computer to the
custom/addons folder on your server. It is recommended that you use a
standard FTP program such as
[FileZilla](https://filezilla-project.org/download.php?type=client) to
upload the folder to your server.

#### Step 4: Enable the add-on

Log into your BillingTrack install and go to System -&gt; Add-ons and
click the Install button for the add-on. Once the add-on is installed,
the applicable menu items will appear and the add-on will be usable.
