  <?php   
    require_once "includes/header.php";     ?>

           Hello Yegna tube
  <?php
    
    if(isset($_SESSION['userLoggedIn']))
    {
      echo "user is logged as ". $userLoggedInObj->getUsername();
    }else
    {
      echo "\nNot Logged in\n";
    }
  ?>
           
 <?php   require_once "includes/footer.php";  ?>