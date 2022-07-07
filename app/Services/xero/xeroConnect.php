<html>
	<head>
		<title>Ilease Pro</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js"  crossorigin="anonymous"></script>
		<script src="<?php echo path('public').'xero/xero-sdk-ui/xero.js'?>"  crossorigin="anonymous"></script>
		<script type="text/javascript">
		   	document.addEventListener("DOMContentLoaded", function() {
	    		loadIndex("Ilease3/public/xero",'authorization.php');
			});
   		</script>
   	
	</head>
	<body>
	        
	        <?php include('sidebar.php')?>
	        <!-- Page Content -->
<div style="margin-left:16%">

<div class="w3-container w3-teal">
  <h1>Xero Connectivity</h1>
</div>

<div class="w3-container">
 <?php 
	    include('db.php');
	    $dbc = new db();
	     $app = $dbc->getAll("SELECT * FROM organisation_detail WHERE userRef ='" . $_COOKIE['userId']. "'"); 
         if (!empty($app)) {
            setcookie('companyName', $app[0]['org_name'], time() + (86400 ), "/"); // 86400 = 1 day
         }
	    if(isset($_COOKIE['companyName']) && $_COOKIE['companyName'] != '')
	    { 
	        //echo $_COOKIE['companyName'];
	    ?>
	    <h5>You are connected to <?php echo $_COOKIE['companyName']; ?></h5>
	    <a class="btn btn-primary" href="disconnect.php">Disconnect</a>
	    
	    <?php 
	        
	    }
	    else
	    { 
	    
	    ?>
		<div id="req" class="container"></div>
		<?php
		       
		}
		?>
</div>

</div>
   
</html>
