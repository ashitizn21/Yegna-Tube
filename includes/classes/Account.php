<?php

class Account
{
    private $con;
    private $errorArray = array();

    public function __construct($con)
    {
        $this->con = $con;
    }
    public function register($fn, $ln, $us, $em, $em2, $pw, $pw2)
    {
        $this->validateFirstNameInput($fn);
        $this->validateLastNameInput($ln);
        $this->validateUsername($us);
        $this->validateEmail($em, $em2);
        $this->validatePassword($pw, $pw2);
        
        if(empty($this->errorArray))
        {
            return  $this->insertUserDetails($fn, $ln, $us, $em, $pw);
        }else
        {
            return false;
        }
    }
    private function validateFirstNameInput($fn)
    {
        if(strlen($fn) > 25 || strlen($fn) < 2)
        {
            array_push($this->errorArray, Constants::$firstNameCharacters);
        }
    }
    private function validateLastNameInput($ln)
    {
        if(strlen($ln) > 25 || strlen($ln) < 2)
        {
            array_push($this->errorArray, Constants::$lastNameCharacters);
        }
    }

    private function validateUsername($us)
    {
        if(strlen($us) > 25 || strlen($us) < 5)
        {
            array_push($this->errorArray, Constants::$usernameCharacter);
            return;
        }

        $query= $this->con->prepare("SELECT username FROM users WHERE username=:un");
        $query->bindParam(":un", $us);
        $query->execute();

        if($query->rowCount() !=0 )
        {
            array_push($this->errorArray, Constants::$takenUsername);
        }

    }
    private function validateEmail($em, $em2)
    {
        if( $em != $em2 ){
            array_push($this->errorArray, Constants::$notMatchEmail);
            return;
        }

        if( !filter_var($em, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email=:em");
        $query->bindParam(":em", $em);
        $query->execute();

        if($query->rowCount() != 0 ){
            array_push($this->errorArray, Constants::$takenEmail);
        }
    }
    private function validatePassword($pw, $pw2)
    {
        if( $pw != $pw2){
            array_push($this->errorArray, Constants::$passwordNotMatch);
            return;
        }

        if(preg_match("/[^a-zA-Z0-9]/", $pw)){
            array_push($this->errorArray, Constants::$passwordNotAlphaNumeric);
            return;
        }

        if(strlen($pw) > 30 || strlen($pw) < 5){
            array_push($this->errorArray, Constants::$passwordLength);
        }
    }
    public function getError($error)
    {
        if(in_array($error, $this->errorArray))
        {
            return "<span class='errorMessage'>$error</span>";
        }
    }

    public function insertUserDetails($fn, $ln, $us, $em, $pw)
    {
        $pw = hash("sha512", $pw);
        $profilePic = "assets/images/profilePictures/default.png";
        
        $query = $this->con->prepare("INSERT INTO users (firstName, lastName, username, email, password, profilePic)
                                        VALUES(:fn, :ln, :us, :em, :pw, :pic)");
        $query->bindParam(":fn", $fn);
        $query->bindParam(":ln", $ln);
        $query->bindParam(":us", $us);
        $query->bindParam(":em", $em);
        $query->bindParam(":pw", $pw);
        $query->bindParam(":pic", $profilePic);

        return $query->execute();
    }

    public function login($us, $pw)
    {
        $pw = hash("sha512", $pw);

        $query = $this->con->prepare("SELECT username, password FROM users WHERE username=:un AND password=:pw");
        $query->bindParam(":un", $us);
        $query->bindParam(":pw", $pw);

        $query->execute();

        if($query->rowCount() == 1){
            return true;
        }else{
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }
}

?>