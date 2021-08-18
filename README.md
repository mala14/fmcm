FreeMasons Case Management System
==================
[![Total alerts](https://img.shields.io/lgtm/alerts/g/mala14/fmcm.svg?logo=lgtm&logoWidth=18)](https://lgtm.com/projects/g/mala14/fmcm/alerts/)
[![Build Status](https://travis-ci.com/mala14/fmcm.svg?branch=master)](https://travis-ci.com/mala14/fmcm)  
==================

FMCM is a simple case management system. Purpose of fmcm is to register cases with follow ups and add info.
It is not complete yet. More info in todo.md about project status and what is to be done.


Install instructions
===================

- Copy the folder structure to the webserver.
- Import the sql/casemgmt.sql through phpMyadmin or terminals etc.
- Configure the database connection in src/Database.php, set your credentials.
- After install of database login as admin:admin @your.site/Where fmcm resides/index.php
- Go to left menu click on button "Add users" to create a new case management user.
- Create a new superuser. Disable or delete (@ the moment delete through your database tool) the admin account.

PHPMailer instructions  
===================  

The PHPMailer is prepared for FMCM but repo need to be downloaded from: [PHPMailer](https://github.com/PHPMailer/PHPMailer)  
- Folder should reside under FMCM/vendor. If you decide to place it elsewhere, do edit send_mail.php for correct location.  
- Install new tables, fmcm_mailsettings and fmcm_mailtemplate for mailer system from FMCM/sql/casemgmt.sql.  
- Populate settings.php with correct mailhost, password etc.  
- Pass key is your encryption key to be used in the password encrypt and decrypt. The key need to be generated by you.  
