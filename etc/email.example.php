<?php

#copy this file to etc/email.{APP_ENV}.php and change settings
#
_didef('emailService',  'email/swiftmailer.php', ['smtp_host'=>'smtp.gmail.com', 'smtp_port'=>465, 'smtp_security'=>'ssl', 'smtp_user'=>'user@gmail.example.com', 'smtp_password'=>'']);
