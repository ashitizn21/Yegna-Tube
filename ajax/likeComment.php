<?php
    require_once("../includes/config.php");
    require_once("../includes/classes/Comment.php");
    require_once("../includes/classes/User.php");

$videoId = $_POST['videoId'];
$username = $_SESSION['userLoggedIn'];
$commentId = $_POST['commentId'];
// echo "### $username ##";

$userLoggedInObj = new User($con, $username);

$comment =  new Comment($con, $userLoggedInObj, $videoId, $commentId);

echo (int)$comment->like();
?>