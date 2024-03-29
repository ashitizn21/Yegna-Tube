<?php

    class User 
    {
        private $con, $sqlData;

        public function __construct($con, $username)
        {
            $this->con = $con;

            $query = $this->con->prepare("SELECT * FROM users WHERE username=:un");
            $query->bindParam(":un", $username);

            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        public static function isLoggedIn()
        {
            return isset($_SESSION['userLoggedIn']);
        }

        public function getUsername()
        {
            return isset($this->sqlData['username']) ? $this->sqlData['username'] : "";
        }
        public function getFullName()
        {
            return $this->sqlData['firstName']." ". $this->sqlData['lastName'];
        }
        public function getId()
        {
            return $this->sqlData['id'];
        }
        public function getSignUpDate()
        {
            return $this->sqlData['signUpDate'];
        }
        public function getProfilPic()
        {
            return isset($this->sqlData['profilePic']) ? $this->sqlData['profilePic'] : "";
        }
        public function getEmail()
        {
            return $this->sqlData['email'];
        }
        public function getFirstName()
        {
            return $this->sqlData['firstName'];
        }
        public function getLastName()
        {
            return $this->sqlData['lastName'];
        }

        public function isSubscribedTo($userTo)
        {
            $username = $this->getUsername();

            $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
            $query->bindParam(":userTo", $userTo);
            $query->bindParam(":userFrom", $username);

            $query->execute();
            
            return $query->rowCount() > 0;
        }
        public function getSubscribersCount()
        {
            $username = $this->getUsername();
            $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
            $query->bindParam(":userTo", $username);

            $query->execute();

            return $query->rowCount();
        }
    }

?>