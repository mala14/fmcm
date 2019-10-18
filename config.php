<?php

/**
* main configuration, routes and settings etc.
*
*/
/*
* Error reporting.
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '' , __DIR__));
session_start();


/**
* define main Paths and files
*/
define('MAIN_PATH', __DIR__ . '/.');
require_once('src/Database.php');

function myAutoloader($class) {
  $path = "src/{$class}.php";
  if(is_file($path)) {
    include($path);
  }
  else {
    throw new Exception("Classfile '{$class}' does not exists.");
  }
}
spl_autoload_register('myAutoloader');

/**
* Set the default timezone
*
*/
date_default_timezone_set('Europe/Stockholm');
