# BillingTrack (Wired)
The next generation of the BillingTrack v5.x.x software.  
BillingTrack is a self-hosted billing system for freelancers, contractors and small businesses.

Includes:
- Client Management
- Quotes
- Workorders
- Invoices
- Recurring Invoices
- Payments
- Expenses
- Time tracking
- Purchase Orders
- Scheduling
- Reports
- Employees, Products, Vendors and more !!

Host on your own server.  
Your clients can view and pay their invoices online using the built-in PayPal, Stripe and Mollie integrations.  

Built with [Laravel](https://laravel.com),
Wired with [Laravel Livewire](https://laravel-livewire.com/)  
:+1: If you find this software useful, feel free to make a donation: [https://paypal.me/cytecheng](https://paypal.me/cytecheng)
-----------------
[Prerequisites](#installation-prerequisites)

[Installation](#how-to-install-billingtrack)

[Upgrade](#how-to-upgrade-billingtrack)

[**IMPORTANT** UPGRADING FROM BILLINGTRACK 5.X.X](#upgrade-from-billingtrack-5xx)

[Live Demo](http://billingtrack-demo.cytech-eng.com)

# Installation Prerequisites
BillingTrack is web-based software, so to install and use it, you must
have a server environment of some sort. Please review the minimum
requirements below to determine whether or not you will be able to
install and use the software. .

- A web server of some sort - Apache, nginx, etc.
- PHP &gt;= 8.2
- MySQL or MariaDB
- A modern and updated web browser
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extensiom
- DOM PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
------------
PHP Extension list from "composer check-platform-reqs"

-   ext-ctype
-   ext-curl
-   ext-dom
-   ext-fileinfo
-   ext-filter
-   ext-iconv
-   ext-json
-   ext-libxml
-   ext-mbstring
-   ext-openssl
-   ext-pcre
-   ext-phar
-   ext-tokenizer
-   ext-xml
-   ext-xmlwriter
-   lib-pcre

**Composer installed**

Here is a good link with composer installation instructions for Ubuntu 20.04:
[Composer Install instructions](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04)

**Sample Apache2 virtual host conf:**

BillingTrack.conf

	<VirtualHost *:80>
		ServerAdmin webmaster@localhost

		DocumentRoot /var/www/BillingTrack/public
		ServerName BillingTrack
		ServerAlias BillingTrack
		<Directory />
			Options FollowSymLinks
			AllowOverride All
		</Directory>
		<Directory /var/www/BillingTrack/public/>
			Options Indexes FollowSymLinks MultiViews
			AllowOverride All
			Order allow,deny
			allow from all
		</Directory>

		ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
		<Directory "/usr/lib/cgi-bin">
			AllowOverride None
			Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
			Order allow,deny
			Allow from all
		</Directory>

		ErrorLog ${APACHE_LOG_DIR}/error.log

		# Possible values include: debug, info, notice, warn, error, crit,
		# alert, emerg.
		LogLevel warn

		CustomLog ${APACHE_LOG_DIR}/access.log combined

	    Alias /doc/ "/usr/share/doc/"
	    <Directory "/usr/share/doc/">
		Options Indexes MultiViews FollowSymLinks
		AllowOverride None
		Order deny,allow
		Deny from all
		Allow from 127.0.0.0/255.0.0.0 ::1/128
	    </Directory>


	</VirtualHost>

**Sample Nginx configuration:**

    server {
    listen 80;
    listen [::]:80;
    server_name BillingTrack;
    root /srv/BillingTrack/public;
    
        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";
     
        index index.php;
     
        charset utf-8;
     
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }
     
        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }
     
        error_page 404 /index.php;
     
        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }
     
        location ~ /\.(?!well-known).* {
            deny all;
        }
    }


# How to Install BillingTrack

1. Clone or download the repository to a new web directory.
2. Run "composer install" in web directory
3. create a NEW BillingTrack database.
4. Copy .env.example to .env
5. edit .env and change:
-   DB\_HOST=
-   DB\_DATABASE=
-   DB\_USERNAME=
-   DB\_PASSWORD=  
    to your \*\*NEW\*\* database settings.  
NOTE: Do not change APP_ENV=local. Changing this will cause migrations to fail without feedback. Changing this value to "production" has no beneficial effect on BillingTrack operation.
6. save .env file.
-   Run "php artisan key:generate"
-   This copies the app key into the .env file, attached to the APP_KEY= line.
7. Set permissions for your site.
8. Start YOUR\_BILLINGTRACK\_WEBSITE/setup
9. After database configuration finishes (this may take a while. 10 minutes is not unusual):

Note: In some instances a fresh install will throw an "unknown error" alert box. If this happens, dismiss the alert box and continue. In all reported cases the migration completed properly but some timeout was thrown that causes the error.


- Create new account -&gt; creates fresh installation with account
10. sign in

# How to Upgrade BillingTrack
**Upgrade Existing v6.x.x installation to v7.0.0**  
**Do not attempt to upgrade a Billingtrack version older than v6.x.x [SEE UPGRADING FROM BILLINGTRACK 5.X.X](#upgrade-from-billingtrack-5xx)**
- **The upgrade migration is very complex and can take a long time (a test database containing 24,000 documents takes 4 hours to complete)**
- **The migration function removes PHP memory_limit and max_execution_time limits during processing (restores them to original when complete)**
- **This could severely affect your server performance**

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
- Create a new installation/site. (see [How to Install BillingTrack](#how-to-install-billingtrack))
- Although it is theoretically possible to do so, do not attempt to upgrade an existing BillingTrack 5.x.x Site.
- This is a new repository and existing cloners will not be able to pull the update.
- This will upgrade the existing BillingTrack database, but it is **HIGHLY** recommended to backup the existing database and copy to a new one.
- After copying old database to new and modifying the .env file per the installation instructions to reflect the NEW copied database:
- Start-> YOUR\_BILLINGTRACK\_WEBSITE/setup
- After migration completes (this may take a while. 10 minutes is not unusual), signin. 
