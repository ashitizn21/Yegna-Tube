<?php

    ob_start();
    date_default_timezone_set("Africa/Addis_Ababa");

    try{

        $server_host = "localhost";
        $username = "root";
        $password = "";
        $db_name = "YegnaTube";
    
        $con = new PDO("mysql:host=$server_host;dbname=$db_name", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    } catch(PDOException $e){
        echo "Connection Failed: ". $e->getMessage();
    }

    

?>