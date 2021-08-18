<?php

/**
* The site variables. These $GLOBALS change the text/titles in whole site.
* Easify it to set own stamps.
*/

/**
* The html and header vars
*
*/
$title = "FreeMasons Case Management system"; // The html title
$siteName = "FreeMasons Case Management system"; // Site name.
$lang = "en"; // The language setting

/**
* The main content variables
*
*/
$loginTitle = "";             // Login title
$logoutTitle = "";           // Logout title
$loginSubmit = "Submit";                // The value on login button
$logOutSubmit = "Logout";               // The value on logout button
$loginHolder = "User name";             // The User name placeholder in login page
$loginPassHolder = "Password";          // The password placeholder in login page

$navNew = "New";                        // The navbar menu object New title
$navCase = "Case";                      // The navbar menu object Case title

$caseId = "Case id";                    // The case id title at case listing
$created = "Created";                   // Created title, at case listing
$caseTitle = "Title";	                  // Case title, at case listing
$assignedTo = "Assigned to";	          //  Assigned to title, at case listing
$closed = "Closed";                     // Closed title, at closed case listing
$closedBy = "Closed by";                // Closed by title, at closed case listing

$userId = "User id";                    // The id of user in db
$contact = "Customer";                  // The case form Contact title & navbar new/contact
$name = "Name";                         // The case form Name title
$userTitle = "Title";                   // The case form Title title
$phone = "Phone";                       // The case form Phone title
$phoneNum = "Phone number";             // The customer list phone number column title
$email = "Email";                       // The case form Email title
$office = "Office";                     // The case form Office title
$address = "Address";                   // The case form Address title
$userCreated = "Created";               // The case form Created title

$assigned = "Assigned";                 // The case form Assigned titles
$issue = "Issue";                       // The case form Issue title
$caseUpdate = "Update";                 // The case update button value
$updatedBy = "Updated by";              // The case commentfield update info
$description = "Description";           // The case description title
$save = "save";                         // The case save butdton value
$caseIssueHolder = "Case title";        // The case issue title placeholder
$closeCase = "Close case";              // The close case button value
$openCase = "Open case";                // The open case button value

$reset = "Reset";                       // The case field reset button value
$search = "Search";                     // The seaarch contact form title
$searchContactHolder = "Search contact";// The search contact placeholder

/**
* The left menu variables
*
*/
$home = "My page";                      // My page button
$activeCases = "Active";                // Active cases button
$closedCases = "Closed";                // Closed cases button
$newCase = "New case";                  // New case button
$contacts = "Customer";                 // List of customers
$dashboard = "Dashboard";               // The dashboard title
$settings = "Settings";                 // The site settings button

/**
* Left menu variables, Admin buttons and admin vars
*
*/
$addUsers = "Add users";                // Add users button
$editUsers = "Edit users";              // Edit users button
$username = "Username";                 // The add user Username title
$surname = "Lastname";                  // The add user Surname title
$type = "Type";                         // The type of user permission
$usrType = "User";                      // The user value in dropdown select type
$adminType = "Admin";                   // The admin value in the drop down select type
$modType = "Moderator";                 // The moderator value in the drop down select type
$userEdit = "Edit";                     // The edit user link title
$templates = "Templates";               // The templates link title

$firstName = "First name";              // The edit user firstname title
$status = "Status";                     // The edit user status title
$userActive = "Active";                 // The user status Active
$userDisabled = "Disabled";             // The user status Disabled
$updateUser = "Update";                 // The update user button value

$setPassWord = "Password";              // The update password title
$confPassWord = "Confirm";              // The confirm password title
$setPassWordHolder = "Password";        // The update password placeholder
$confPassWordHolder = "Confirm password";// The confirm password placeholder
$lastLogin = "Last login";              // The last login title at list users
$jobTitle = "Job title";                // The add contact job title
$fullname = "Full name";                // First and last name

/**
* The error message variables
*
*/
$emptyUname = "Username is required";   // The edit user empty username error message
$emptyLname = "Last name is required";  // The edit user empty last name error message
$emptyFname = "First name is required"; // The edit user empty först name error message
$emptyEmail = "Email is required";      // The edit user empty email error message

$emptyPassword  = "Password field can not be empty!";   // The empty password field error message
$emptyConfPassword  = "Confirm field can not be empty!"; // The empty confirm password field error message
$passwordNoMatch = "Passwords do not match!"; // The passwords do not match message
$passwordSuccess = "Password has been changed!"; // The password change success message
$errorLogin = "Wrong User Name or password"; // The login error message

$caseEmptyTitle = "Title is required."; // The add case empty title error message
$caseEmptyMessage = "Message is required."; // The add case empty messsage error message
$caseEmptyContact = "A contact is required."; // The add case empty title error message
$emptySearch = "Search field can not be empty"; // The seaarch contact empty field error message
$noEntriesDb = "No entries in database"; // The message when no cases are registered
$noComment = "You have to leave a comment"; // The empty comment error message

/**
* The email variables
*
*/
$send = "Send";                         // Send button text value
$createTemplate = "Create template";    // Create new template button text value
$selectTemplate = "Select mail template"; // Text for select box for mail templates
$mailMessage = "Message";               // The send mail message title at mail textarea
$mailSubject = "Case id:";              // Subject field case id text
/**
* The footer vars
*
*/
$copyright = "&copy; mala14 www.freemasons.se"; // The creator
$github = "<a href='https://github.com/mala14/fmcm'><i class='fab fa-github fa-2x'></i></a>"; // FMCM GitHub repo
