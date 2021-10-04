<?php
    require_once("../includes/config.php");

if(isset($_POST['userTo']) && isset($_POST['userFrom']))
{
    $userTo = $_POST['userTo'];
    $userFrom = $_POST['userFrom'];
    // check if its subsc
    $query = $con->prepare("SELECT *FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
    $query->bindParam(":userTo", $userTo);
    $query->bindParam(":userFrom", $userFrom);

    $query->execute();


    if($query->rowCount() > 0)
    {
        // delete
       $q_query = $con->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
       $q_query->bindParam(":userTo", $userTo);
        $q_query->bindParam(":userFrom", $userFrom);

        $q_query->execute();
    }else
    {
        // if not insert
        $q_query = $con->prepare("INSERT INTO subscribers(userTo, userFrom) VALUES(:userTo , :userFrom)");
        $q_query->bindParam(":userTo", $userTo);
        $q_query->bindParam(":userFrom", $userFrom);

        $q_query->execute();
    }

    $query = $con->prepare("SELECT *FROM subscribers WHERE userTo=:userTo ");
    $query->bindParam(":userTo", $userTo);
    $query->execute();
    
    echo $query->rowCount();
} else
{
    echo "One or more parameter is not passed to subscribe.php file";
}


?>