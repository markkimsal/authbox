Initial Setup
==
```
composer install
#create a database named authbox_dev
 PHINX_DBUSER={...} PHINX_DBPASS={...} php local/bin/phinx migrate  -e development -c phinx/phinx.yml 
 PHINX_DBUSER={...} PHINX_DBPASS={...} php local/bin/phinx seed:run -e development -c phinx/phinx.yml 
```

```
Copy etc/dsn.example.php   to etc/dsn.dev.php

Copy etc/email.example.php to etc/email.dev.php
```


Login
===
Use root@example.com and authbox as the username and password
