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
