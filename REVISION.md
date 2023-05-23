revision history
===================  
v0.3.15 (2023-05-23)  
Check if PHPMailer is installed and configured.  
Minor css changes.  
Test on latest PHPMailer version.

-----------------  
v0.3.05 (2023-05-23)  
Fix css when changes in  @media width.  
Added a javascript for substring mail template field data when in low resolution.  

-----------------
v0.3.04 (2023-01-19)  
Changed sorting order on closed cases  
Minor design changes in case and mail pages  
Removed unused code  

-----------------  
v0.3.03 (2023-01-18)  

Editor extended with more buttons  
Style change font  
Style change read_mail  
Add button to "My page" to navbar  
Add case id to mail subject  

-----------------
v03.02 (2023-01-16)  

New db table store sent emails  
New page to list emails  
New page to read emails  
Update on db email table install in casemgmt.sql  

-----------------
v0.3.01 (2023-01-12)  
change of columname in fmcm_users time to sesstime  
Fixed assign engineer  

------------------
v0.3 (2023-01-12)  

Fixed "Trying to access array offset on value of type bool" in Todo.php  
Fixed search bar design  
Removed many of "ALTER TABLE" to full db install in file casemgmt.sql  

------------------  
v0.2.86 (2022-04-13)  

Fixed some style issues  
Added new sitevars  

-------------------  
v0.2.85 (2022-04-08)  

Fixed "Trying to access array offset on value of type bool" in Todo.php.  
Added new value case id when posting new/updates to case, due to user id could get same value.  
Added value Moderator to users_adm at creation of new user.  
Fixed Case status (open & close) in navbar case butdton dropdown.  
Changes to redirecting to pages.  
Added new class for Navbar.  

-------------------  
v0.2.84 (2021-08-18)  

Enabled mail send through PHPMailer.  
Added new table for mail settings.
New page for settings created where most of the site settings will be.  
Create and edit mailtemplates are possible.
Add template to mail.
Added a new type of user, Moderator
Admin and moderators can edit templates.

-------------------  
v0.2.83 (2021-08-10)  

Update to search, email and phone number.  
Removed unused css code.  
Updated db view 'v_fmcm_caseinfo' with email and phone.  
Add pagination to list_contacts and list_users  

-------------------  
v0.2.82 (2021-08-09)  

Added date to search function.  
Added pagination to search result page.  

-------------------  

v0.2.81 (2021-08-06)

Added a search function to cases.  

-------------------  
v0.2.8 (2021-08-03)  

Fixed design flaws  

-------------------  
v0.2.79 (2021-08-03)  

Design changes at user and customer listing.  
It is now responsive.  

-------------------  

v0.2.78 (2021-07-02)

Added new column, closedby, to database view  
The graph has now counter on logged in users closed cases.  
Moved the global dashboard value to left menu globals in Sitevars.php  
Added functions to texteditor.  
Removed unused fonts.  
Nav menu buttons Open and Close case changes by status and if no case is selected the buttons will be disabled.  


-------------------  

v0.2.77 (2021-06-17)  

Minor css fixes to pagination. That it wont disappear at a specific @media value.  
Fixed css border top and bottom in closed cases list.  
Fixed width @media on new case.  
Fixed column name @media on case view.  
Removed added html tags from texfield to database.  
Added a dashboard with a graph from: [https://www.chartjs.org](https://www.chartjs.org/)  
Fixed html in case info text to display format.  
Added a column for not assigned cases to dashboard.  

-------------------  

v0.2.75 (2021-06-02)  

Removed local fontawesome and linked to it in header.  
All case pages has now a simple pagination.  

-------------------  
v0.2.74 (2021-05-11)  

Fixed left menu design flaws.  

------------------
v0.2.73 (2021-05-09)  

Left menu is more responsive.  

------------------

v0.2.72 (2021-05-08)  

Design more or less responsive.  
Add create view to database, for case views.  

------------------
v0.2.71 (2021-05-02)  

Design Changes  
Removed CKeditor5 source  

------------------
v0.2.70 (2020-01-07)  

Added edit contact form.  
Minor css fixes.  

------------------
v0.2.69 (2019-12-31)  

Cleaning some HTML from methods and moved it into front controllers.

------------------
v0.2.68 (2019-12-30)  

Fixed headers already sent error.  
removed db dump function in TODO list.  

------------------
v0.2.67 (2019-11-26)  

Minor design fixes.  
Edit on sitevars.  
Todo changes.  

------------------
v0.2.66 (2019-11-20)  

Can now set a password when creating new user.  

------------------
v0.2.65 (2019-11-20)  

And update of revision (forget to often).  

------------------

v0.2.64 (2019-11-20)  

Fixed add user error  
More globals added to src/Sitevars  

------------------

v0.2.61 (2019-11-19)  

More globals added to src/Sitevars  

------------------

v0.2.6 (2019-11-19)  

Added src/Sitevars.php with variables to make it easier to change titles and stuff.  
Updated latest push on default branch by Travis.  
Some changes to future projects.  

-------------------

(2019-11-15)  

Some design changes.  
Updated a sammle code vulnerability check badge,  javascript.  

-------------------


(2019-10-29)  

Fixed som design flaws  

-------------------

(2019-10-22)  

Design changes in admin page and at few case related divs.  

-------------------

(2019-10-18)  

Display text when no entries are available, assigned to user, active and closed cases.  
Changed case status in SQL install file from int() to varchar().  
Removed unused code from Todo.php  

--------------------

v0.2.4 (2019-10-15)  

Open and close case added to navbar under "Save".  
Case status marker at update button on case.  
Minor changes in how cases are displayed in list.  
Commented out DROP TABLE IF EXISTS on database creation so no tables will be dropped automagically.  


-------------------

v0.2.3 (2019-10-12)

Assign user to new case.  
Assign user to existing cases.  
Changes in Database fmcm_todo.assign to VARCHAR save username.  
Added username to edit user page.  
Additional heading to editor added.  
Minor design changes.  
Sceditor replaced with CKeditor.  

-------------------

v0.2.1 (2019-09-20)

Added Sceditor-2.1.3
Minor design changes.

-------------------

v0.2 (2019-09-16)

Minor design changes in user admin

-------------------

v0.1 (2019-09-16)

First release
-------------------
