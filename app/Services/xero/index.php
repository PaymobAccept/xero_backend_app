
<?php
   ob_start();
   session_start();
?>

<?
   // error_reporting(E_ALL);
   // ini_set("display_errors", 1);
?>
<?php require('dbConnect.php');
include('db.php');
?>

 <?php
            $msg = '';
            
            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
				
				$dbc = new db();
                $appDetail = $dbc->getAll("SELECT * FROM login WHERE userName ='" . $_POST['username']. "' AND password= md5('" .$_POST['password']. "')");
                
                if (count($appDetail) > 0) {
                     setcookie('userId', $appDetail[0]['uniqueId'], time() + (86400 )); // 86400 = 1 day
                     /********** now check company connect or not************************/
                    
            //     $app = $dbc->getAll("SELECT * FROM organisation_detail WHERE userRef ='" . $_COOKIE['userId']. "'"); 
                
            //   if (!empty($app)) {
            //         setcookie('companyName', $app[0]['org_name'], time() + (86400 ), "/"); // 86400 = 1 day
            //     }
                   
                    header("Location: http://yourhelpgroup.com/ilease/xeroConnect.php"); 
                    exit();
 
                } else {
                     $msg = 'Wrong username or password';
                    }

            }
         ?>


<html>
  <head>

  <link href="css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link href="css/login.css" rel="stylesheet" id="bootstrap-css">
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
  </head>
<body id="LoginForm">
<div class="container">
<!--<h1 class="form-heading">login Form</h1>-->
<div class="login-form">
<div class="main-div">
    <div class="panel">
   <h2>Admin Login</h2>
   <p>Please enter your username and password</p>
   </div>
  
    <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
            ?>" method = "post" id="Login">
  <h5 class = "form-signin-heading" style="color:red"><?php echo $msg; ?></h5>
        <div class="form-group">

<input type = "text" class = "form-control" name = "username" placeholder = "username" required autofocus id="inputEmail">
            
        </div>

        <div class="form-group">
<input type = "password" class = "form-control" name = "password" placeholder = "password" required id="inputPassword">
           
        </div>
<!--        <div class="forgot">-->
<!--        <a href="reset.html">Forgot password?</a>-->
<!--</div>-->
      
 <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "login">Login</button>
    </form>
    </div>
<!--<p class="botto-text"> Designed by Yo</p>-->
</div></div></div>


</body>
</html>
