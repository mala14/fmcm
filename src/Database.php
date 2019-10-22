<?php
/**
* db connection
*/
$servername = "localhost";
$username = "db user";
$password = "db passWord";
$database = "casemgmt";
$charset = "utf8mb4";

$dsn = "mysql:host=$servername;dbname=$database;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $username, $password, $opt);
} catch (PDOExeption $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
