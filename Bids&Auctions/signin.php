<?php
    require_once "app\include\path.php";
    if(isset($_POST['SubmitButton'])) { //check if form was submitted
        require("app\database\ConnectDB.php");
        require("app\classes\User.php");
        $email = trim($_POST['email']); //get input text
        $password = trim($_POST['password']); //get input text

        $user = new User($email, $password);
        try {
            $userInfo = $user->SingIn();
            $_SESSION['userID'] = $userInfo['id'];
            $_SESSION['userEmail'] = $userInfo['email'];
            $message = "Sign In success";
        } catch (Exception $exception) {
            $message = $exception->getMessage();
        }
    }
?>
<?php require "app/include/header.php"?>
<html lang=""><body>
    <form action="" method="post">
        <label for = "email">Email:<br/>
            <input type="text" placeholder="Enter Email" name="email"/> <br/>
        </label>

        <label for = "password"> Password:<br/>
            <input type="password" placeholder="Enter Password" name="password"/>
        </label>

        <br/>
        <input type="submit" value="Sign In" name="SubmitButton"/>
    </form>

    <?php
        if(isset($_POST['email']))
            echo "<br/>" . $message;
    ?>
</body></html>
