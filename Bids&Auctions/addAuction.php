<?php
    require "app/include/header.php";
    require_once "app/include/path.php";
    require "app/classes/Auction.php"
?>
<html lang=""><body>
<h1>Create New Auction</h1>

<form action="" method="post">
    <style>
        textarea {
            width: 500px;
            height: 200px;
        }
    </style>
    <label for = "title">Title:<br/>
        <input type="text" placeholder="Enter Title" name="title" required/> <br/>
    </label>

    <label for = "description"> Description:<br/></label>
        <textarea name="description" id = "description"></textarea>
<!--        <input type="text" placeholder="Enter Description" name="description" maxlength="4" size="4"><br><br>-->

    <br/>
    <label for="endDate">Auction ending time</label>
    <input type="datetime-local" id="endDate"
           name="endDate" required>
    <br/>
    <label for="startPrice">Starting Price:</label>
    <input type="number" id="startPrice" name="startPrice" min="0" step="0.01" required><br>

    <label for="buyNowPrice"> Buy It Now Price:</label>
    <input type="number" id="buyNowPrice" name="buyNowPrice" min="0" step="0.01"/> <br/>


    <input type="submit" value="Place an auction" name="btn"/>
</form>


<?php
    if(isset($_POST['title']) and isset($_POST['endDate']) and isset($_POST['startPrice'])){
        var_dump($_POST);
        try{
            require "app/database/ConnectDB.php";
            $auction = new Auction($_POST, $_SESSION['userID']);
        }catch(Exception $exception){
             echo $exception->getMessage();
        }


    }
?>
</body></html>




