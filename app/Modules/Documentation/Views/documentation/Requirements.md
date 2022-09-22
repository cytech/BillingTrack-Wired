Requirements
---

---

BillingTrack is web-based software, so to install and use it, you must
have a server environment of some sort. Please review the minimum
requirements below to determine whether or not you will be able to
install and use the software. .

- A web server of some sort - Apache, nginx, etc.
- PHP &gt;= 8.1
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
            fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }
     
        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
