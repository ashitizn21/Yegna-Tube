  <?php   require_once "includes/header.php";     ?>

           Hello Yegna tube
  <?php
    session_start();
    if(isset($_SESSION['userLoggedIn']))
    {
      echo "user is logged as ".$_SESSION['userLoggedIn'];
    }else
    {
      echo "\nNot Logged in\n";
    }
  ?>
           
 <?php   require_once "includes/footer.php";  ?>