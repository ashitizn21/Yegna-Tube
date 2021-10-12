
<?php require_once("includes/config.php"); 
      require_once("includes/classes/Account.php");
      require_once("includes/classes/FormInputSanitizer.php");
      require_once("includes/classes/Constants.php");

    $account = new Account($con);

    if(isset($_POST['submitButton']))
    {
        $username = FormInputSanitizer::sanitizeUsernameInput($_POST['username']);
        $password = FormInputSanitizer::sanitizePasswordInput($_POST['password']);

        $wasSuccessful = $account->login($username, $password);

        if($wasSuccessful){
            $_SESSION['userLoggedIn'] = $username;
            header('Location: index.php');
        }
    }

    function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp to YT </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/photo_2021-07-27_01-09-48.jpg" >

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>
    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <img src="assets/images/yyegna.jpg" title="logo" alt="site logo">
                <h3>Sign In </h3>
                <span>to continue to YEGNA TUBE</span>
            </div>

            <div class="loginForm">
                <form action="signIn.php" method="post">
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                    <input type="text" name="username" placeholder="Username" value="<?php getInputValue('username'); ?>"
                     autocomplete="of" required>
                    <input type="password" name="password" placeholder="Password"  autocomplete="of" required>                   
                    <input type="submit" value="SUBMIT" name="submitButton">
                </form>
            </div>

            <a href="signUp.php" class="signInMessage">
                Need an account? Sign up here!
            </a>
        </div>
    </div>    
</body>
</html>




