<?php
require_once("includes/header.php");
require_once("includes/classes/VideoUploadData.php");
require_once("includes/classes/VideoProcessor.php");
require_once("includes/classes/User.php");

if(!isset($_POST['uploadButton'])){
    echo "No file is sent!....";
    exit();
}

// 1) create file upload data

$videoUploadData = new VideoUploadData($_FILES['fileInput'],
                                        $_POST['titleInput'],
                                        $_POST['descriptionInput'],
                                        $_POST['categoryInput'],
                                        $_POST['privacyInput'],
                                        $userLoggedInObj->getUsername()
                                       );

// 2) process data

$videoProcess = new VideoProcessor($con);
$wasSuccessful = $videoProcess->upload($videoUploadData);

if($wasSuccessful)
{
    echo "\nupload Successful\n";
}
?>