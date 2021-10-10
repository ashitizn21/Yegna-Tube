<?php
    require_once ("../includes/config.php");
    require_once "../includes/classes/User.php";
    require_once "../includes/classes/Comment.php";

    if(isset($_POST['commentText']) && isset($_POST['videoId']) && isset($_POST['postedBy']))
    {
        $videoId = $_POST['videoId'];
        $responseTo = $_POST['responseTo'] == null ? 0 :  $_POST['responseTo'];
        $postedBy = $_POST['postedBy'];
        $commentText = $_POST['commentText'];
        
        $userloggedInOBJ = new User($con, $_SESSION['userLoggedIn']);

        $query = $con->prepare("INSERT INTO comments(postedBy, videoId, responseTo, body)
                                VALUES(:postedBy, :videoId, :responseTo, :body)");
        $query->bindParam(":postedBy", $postedBy);
        $query->bindParam(":responseTo", $responseTo);
        $query->bindParam(":videoId", $videoId);
        $query->bindParam(":body", $commentText);

        $query->execute();

            // display it below
        $id = $con->lastInsertId();
        $newComment = new Comment($con, $userloggedInOBJ, $videoId, $id);
        echo $newComment->create();
    } else
    {
        echo "something went wrong!";
    }


?>