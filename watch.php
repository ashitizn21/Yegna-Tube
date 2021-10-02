<?php
    require_once("includes/header.php");
    require_once("includes/classes/User.php");
    require_once("includes/classes/VideoPlayer.php");

    // echo $_GET['id'];

    if(!isset($_GET['id'])) {
        echo "No url id passed into page";
        exit;
    }

    $video = new Video($con, $_GET['id'], $userLoggedInObj);
  
    // $video->increamentViews();   // increament function

    // echo $video->getViews();
?>


<div class="watchLeftColumn">

<?php

    $videoPlayer = new VideoPlayer($video);

    echo $videoPlayer->create(true);

?>
</div>

<div class="suggestions">
    
</div>

<?php require_once("includes/footer.php"); ?>