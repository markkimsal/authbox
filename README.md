Initial Setup
==
```
composer install
#create a database named authbox_dev
 PHINX_DBUSER={...} PHINX_DBPASS={...} php local/bin/phinx migrate  -e development -c phinx/phinx.yml 
 PHINX_DBUSER={...} PHINX_DBPASS={...} php local/bin/phinx seed:run -e development -c phinx/phinx.yml 
```


Login
===
Use root@example.com and authbox as the username and password
