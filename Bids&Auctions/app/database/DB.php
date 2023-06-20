<?php

    require("ConnectDB.php");

    function tt($value): void
    {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }
    function selectAll($table){
        global $pdo;
        $sql = "SELECT * FROM $table";
        try {
            $query = $pdo->prepare($sql);
            $query->execute();
        }catch (PDOException $exception){
            die($exception->errorInfo[2]);
        }
        return $query -> fetchAll();

    }
    tt(selectAll("users"));
