<?php
   ob_start();
   session_start();
?>

<?php 
include('db.php');
require_once('xeroFunction.php');
?>
<?php
/***************** get journal api ********************/
$access = json_decode($_COOKIE['accessToken'],1);
ini_set('display_errors', 'On');
	require __DIR__ . '/vendor/autoload.php';

	require_once('storage.php');
	require_once('example.php');

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
	if ($storage->getHasExpired()) { 
	    
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
           
	}
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
	    //$result = $ex->getManualJournal($xeroTenantId,$accountingApi,true);
	    
	    /********* create ur own array***************/
	   $myArray = [ 
        '0' => [
                'credit' => "100.00", 
                'debit' => "-100.00", 
                'creditCode' => "400",
                'debitCode' => "620",
                'narration' => 'Demo Journal'
            ], 
        '1' => [
                'credit' => "100.00", 
                'debit' => "-100.00", 
                 'creditCode' => "400",
                'debitCode' => "620",
                'narration' => 'Demo Journal'
            ],  
       
    ];
		$result = $ex->createManualJournals($xeroTenantId,$accountingApi,$myArray,true);
		echo '<pre>';print_r($result);die;
	/*************************************************************/	
 ?>