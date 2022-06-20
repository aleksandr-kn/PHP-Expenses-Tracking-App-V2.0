<?php
// Database credentials. 

function create_connection()
{
    $credentials = parse_ini_file(__DIR__ . "/../database/connect.ini");

    /* Attempt to connect to database */
    $pdo = new PDO("mysql:host={$credentials['host']};dbname={$credentials['db']}", $credentials['user'], $credentials['password']);
    // $db = mysqli_connect("host={$credentials['host']} port={$credentials['port']} dbname={$credentials['db']} user={$credentials['user']} password={$credentials['password']}");

    // Check connection
    if ($pdo === false) {
        die("ERROR: Could not connect. ");
    }

    return $pdo;
}
