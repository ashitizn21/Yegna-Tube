<?php
    require_once("includes/header.php");
    require_once("includes/classes/User.php");
    require_once("includes/classes/VideoPlayer.php");
    require_once("includes/classes/VideoInfoSection.php");
    require_once("includes/classes/CommentSection.php");

    // echo $_GET['id'];

    if(!isset($_GET['id'])) {
        echo "No url id passed into page";
        exit;
    }

    $video = new Video($con, $_GET['id'], $userLoggedInObj);
  
    $video->increamentViews();   // increament function

?>

<script src="assets/js/videoPlayerAction.js"></script>
<script src="assets/js/commentAction.js"></script>

<div class="watchLeftColumn">

    <?php

        $videoPlayer = new VideoPlayer($video);

        echo $videoPlayer->create(true);

        // call videoInfoSection
        $videoInfoSection = new videoInfoSection($con, $video, $userLoggedInObj);

        echo $videoInfoSection->create();

        $commentSection = new CommentSection($con, $video, $userLoggedInObj);

        echo $commentSection->create();

    ?>
</div>

<div class="suggestions">
    
    <?php
    
        $videoSysGrid = new VideoSgsGrid($con, $userLoggedInObj);
        echo $videoSysGrid->create(null, null, false);
    ?>
</div>

<?php require_once("includes/footer.php"); ?>