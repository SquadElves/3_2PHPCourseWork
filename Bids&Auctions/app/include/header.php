<?php
require_once "path.php"?>
<html lang=""><head>
    <?php
        session_start();
        if(isset($_SESSION['userID']))
            echo $_SESSION['userEmail'];
        else
            echo 'Sing In';
    ?>
    <br/>
</head></html>

