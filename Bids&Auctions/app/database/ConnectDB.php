<?php
    $dbms = 'pgsql';
    $host = 'localhost';
    $dbname = 'auction';
    $username = 'postgres';
    $password = "11111111";
    try{
        $pdo = new PDO("$dbms:dbname=$dbname;host=$host", $username, $password);
    } catch (PDOException $exception){
        die("Error while connecting to DataBase");
    }
