
<?php  require_once("includes/config.php");   
        require_once("includes/classes/User.php");
        require_once("includes/classes/Video.php");


    $usernameLoggedIn = User::isLoggedIn() ? $_SESSION['userLoggedIn'] : "";

    $userLoggedInObj = new User($con, $usernameLoggedIn);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ዬኛ Tube</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/photo_2021-07-27_01-09-48.jpg" >

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="assets/js/userAction.js"></script>
</head>

<body>
    <div id="pageContent">
        <div id="mastHeadContainer">
            <button class="navShowHide"> <img src="assets/images/icons/menu.png" alt=""> </button>

            <a href="index.php" class="logoContainer">
                <img src="assets/images/photo_2021-07-27_01-09-48.jpg" title="logo" alt="site logo">
            </a>

            <div class="searchBarContainer">
                <form action="search.php" method="get">
                    <input type="text" name="term" class="searchBar" placeholder="Search here...">
                    <button class="searchButton"><img src="assets/images/icons/search.png" alt=""></button>
                </form>
            </div>

            <div class="rightIcons">
                <a href="upload.php">
                    <img src="assets/images/icons/upload.png" alt="upload here">
                </a>

                <a href="#">
                    <img src="assets/images/profilePictures/default.png" alt="user profile">
                </a>
            </div>
        </div>

        <div id="sideNavContainer" style="display: none;">

        </div>

        <div id="mainSectionContainer">
            <div id="mainContentContainer">
