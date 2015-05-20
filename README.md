# dds
Demo application for ADUCID

Requirements
------------

- Apache HTTPd server with php 5
- php-pdo
- php-aducid 3.x

Installation
------------

- copy files from src somewhere under DOCUMENT_ROOT

        mkdir /var/www/html/dds
        cp -r src/* /var/www/html/dds/

- setup the config.php
- enable writing into the database for the webserver

        chown apache:apache /var/www/html/dds/db
        chmod 755 /var/www/html/dds/db
