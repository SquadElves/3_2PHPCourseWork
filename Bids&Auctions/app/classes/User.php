<?php

class User
{

    //public string $username;
    public string $email;
    public string $password;
    function __construct(string $email, string $password) {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @throws Exception
     */
    function SingIn():array{
        global $pdo;
        $sql = "SELECT * FROM users where email = :email";
        $query = $pdo->prepare($sql);
        $query->execute(['email' => $this->email]);
        $date = $query -> fetchAll();
        if(count($date) < 1)
            throw new Exception("No user with this email");
        else if($date[0]["password"] != $this->password)
            throw new Exception("Password is incorrect");
        return $date[0];
    }

}