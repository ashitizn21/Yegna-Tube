<?php
    require_once("includes/header.php");
    require_once("includes/classes/User.php");
    require_once("includes/classes/VideoPlayer.php");
    require_once("includes/classes/VideoInfoSection.php");

    // echo $_GET['id'];

    if(!isset($_GET['id'])) {
        echo "No url id passed into page";
        exit;
    }

    $video = new Video($con, $_GET['id'], $userLoggedInObj);
  
    // $video->increamentViews();   // increament function

    // echo $video->getViews();
?>

<script src="assets/js/videoPlayerAction.js"></script>

<div class="watchLeftColumn">

<?php

    $videoPlayer = new VideoPlayer($video);

    echo $videoPlayer->create(true);

    // call videoInfoSection
    $videoInfoSection = new videoInfoSection($con, $video, $userLoggedInObj);

    echo $videoInfoSection->create();

?>
</div>

<div class="suggestions">

</div>

<?php require_once("includes/footer.php"); ?>