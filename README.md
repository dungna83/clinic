# Clinic test

## Environment
- Homestead
- Mysql/5.6
- PHP/7.2
- PHPUnit/5.7.23
- Nginx/1.15.5

## Installation

- Dump clinic_db.sql to DB
- Config DB parameter in: config/env/.dev.env
```mysql
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=clinic
    DB_USERNAME=homestead
    DB_PASSWORD=secret
``` 
- config local domain (exp: test.dungna.com) point to public/index.php
- File Nginx to references:
```
server {
    listen 80;
    listen 443 ssl http2;
    server_name .test.dungna.com;
    root "/home/vagrant/www/test/test.dungna.com/public";

    index index.html index.htm index.php;
    charset utf-8;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log off;
    error_log  /var/log/nginx/test.dungna.com-error.log error;
    sendfile off;
    client_max_body_size 100m;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;


        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
    }

    location ~ /\.ht {
        deny all;
    }

    ssl_certificate     /etc/nginx/ssl/test.dungna.com.crt;
    ssl_certificate_key /etc/nginx/ssl/test.dungna.com.key;
}
```
- Unit Test: run phpunit at root web folder


