<?php
   ob_start();
   session_start();
?>
<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 0);
?>

 <html>
	<head>
		<title>Tracking</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		
	</head>
	<body>
	        
	<?php include('sidebar.php')?>
	        <!-- Page Content -->
<div style="margin-left:16%">

<div class="w3-container w3-teal">
  <h1>Tracking Category</h1>
</div>

<div class="w3-container">
 <?php 
include('db.php');
require_once('xeroFunction.php');
?>
<?php
/***************** get tracking api ********************/
$access = json_decode($_COOKIE['accessToken'],1);
ini_set('display_errors', 'On');
	require __DIR__ . '/vendor/autoload.php';

	require_once('storage.php');
//	require_once('example.php');

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();
	$clientId = getenv('CLIENT_ID');
	$clientSecret = getenv('CLIENT_SECRET');
	$redirectUri = getenv('REDIRECT_URI');

	// Storage Classe uses session
    $storage = new StorageClass();
    	// ALL methods are demonstrated using this class
	$ex = new XeroFunctionClass();

    $dbc = new db();
	$appDetail = $dbc->getAll("SELECT refreshToken,tenantId FROM token_detail WHERE userRef ='" . $_COOKIE['userId']. "'"); 
		$xeroTenantId = $appDetail[0]['tenantId'];
    //echo $xeroTenantId;die;
	// Check if Access Token is expired
	// if so - refresh token
	//if ($storage->getHasExpired()) { 
	    
		$provider = new \League\OAuth2\Client\Provider\GenericProvider([
			'clientId'                => $clientId,   
			'clientSecret'            => $clientSecret,
			'redirectUri'             => $redirectUri,
        	'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
        	'urlAccessToken'          => 'https://identity.xero.com/connect/token',
        	'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
		]);
        $newAccessToken = $provider->getAccessToken('refresh_token', [
	        'refresh_token' => $appDetail[0]['refreshToken']
	    ]);
	      //echo '<pre>';print_r($newAccessToken);die;
	    // Save my token, expiration and refresh token
         // Save my token, expiration and refresh token
		 $storage->setToken(
            $newAccessToken->getToken(),
            $newAccessToken->getExpires(), 
            $xeroTenantId,
            $newAccessToken->getRefreshToken(),
            $newAccessToken->getValues()["id_token"] );
           
	//}
	 //echo  '<pre>';print_r($newAccessToken);die;
	 /************ store new detail in database...***********************/
	 $newToken =  $newAccessToken->getToken();
	 $newRefreshToken =  $newAccessToken->getRefreshToken();
	 $newExpire = $newAccessToken->getExpires();
	 $dbc = new db();
	 
    $appDetail = $dbc->execute("UPDATE token_detail SET accessToken ='".$newToken."', refreshToken ='".$newRefreshToken."' , expires = '".$newExpire."' WHERE userRef= '".$_COOKIE['userId']."'");

	 /************************************/
	$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$newToken);		  
	$accountingApi = new XeroAPI\XeroPHP\Api\AccountingApi(
	    new GuzzleHttp\Client(),
	    $config
	);
	    $result = $ex->getTrackingCategory($xeroTenantId,$accountingApi,true);
	    
	    for($i=0;$i<count($result);$i++)
	    {
	          $tracking_category_id = $result->getTrackingCategories()[$i]->getTrackingCategoryId();
	             $dbc4 = new db();
                $app4 = $dbc4->getAll("SELECT * FROM tracking WHERE tracking_category_id ='" . $tracking_category_id. "'"); 
                if(count($app4) > 0)
                {
                    continue;
                }
	            $tracking_option_id = $result->getTrackingCategories()[$i]->getTrackingOptionId();
	            $trackName = $result->getTrackingCategories()[$i]->getName();
	            $trackOption = $result->getTrackingCategories()[$i]->getOption();
	            $trackStatus =  $result->getTrackingCategories()[$i]->getStatus();
	            $addedBy = $_COOKIE['userId'];
	          
	            $track1[] = "('','$tracking_category_id', '$tracking_option_id','$trackName', '$trackOption','$trackStatus','$addedBy')";

	           for($j=0;$j<count($result->getTrackingCategories()[$i]->getOptions());$j++)
	           {
	                     $tracking_option_id = $result->getTrackingCategories()[$i]->getOptions()[$j]->getTrackingOptionId();
	                     $optionName =$result->getTrackingCategories()[$i]->getOptions()[$j]->getName();
	                     $optionStatus =$result->getTrackingCategories()[$i]->getOptions()[$j]->getStatus();
	                    $op1[] = "('','$tracking_category_id', '$tracking_option_id','$optionName', '$optionStatus')";
	           }
	    }
	    // echo '<pre>';print_r($result); 
	    
	     /********* store in database..****************/
	     if(!empty($track1))
	     {
	          $values =''; $val = '';
	    $dbc = new db();
       $values .= implode(',', $track1);
        $appDetail = $dbc->execute("INSERT INTO tracking  VALUES $values");
	$dbc1 = new db();
       $val .= implode(',', $op1);
        $appDetail = $dbc1->execute("INSERT INTO  tracking_option  VALUES $val");
	     }
	    
	/*************************************************************/	
		     
 ?>
  <table class="w3-table-all">
    <thead>
      <tr class="w3-red">
          <th>Tracking Name</th>
       <th>Tracking Id</th>
        <th>Tracking Status</th>
        <th colspan="5"> Options</th>
      </tr>
    </thead>
    <?php 
      $dbc2 = new db();
    	  $app = $dbc2->getAll("SELECT * FROM tracking WHERE addedBy ='" . $_COOKIE['userId']. "'"); 
    	  if(count($app) > 0){
         for($i=0;$i<count($app);$i++){
    ?>
    <tr>
         <td><?php echo $app[$i]['name']?></td>
      <td><?php echo $app[$i]['tracking_category_id']?></td>
      <td><?php echo $app[$i]['status']?></td>
      <td>
        
          <table>
              <tr>
                  <th>Option Id</th>
                  <th>Name</th>
                  <th>Status</th>
              </tr>
                <?php 
           $dbc3 = new db();
    	  $app2 = $dbc3->getAll("SELECT * FROM tracking_option WHERE tracking_category_id ='" . $app[$i]['tracking_category_id']. "'"); 
        for($j=0;$j<count($app2);$j++){
          ?>
              <tr>
                  <td><?php echo $app2[$j]['tracking_option_id']?></td>
                  <td><?php echo $app2[$j]['optionName']?></td>
                  <td><?php echo $app2[$j]['optionStatus']?></td>
              </tr>
               <?php }?>
          </table>
         
      </td>
    </tr>
  <?php } } else {
   echo '<tr><td> NO Record Found....<td></tr>';
   }?>
  </table>
</div>

</div>
   
</html>
