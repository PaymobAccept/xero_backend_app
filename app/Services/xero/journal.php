<?php echo view('home.common.header'); ?>

<div class="w3-container w3-teal">
  <h1>Create Journal</h1>
</div>

<div class="w3-container">
    <?php 
//include('db.php');
require_once('xeroFunction.php');
?>
<?php
/***************** get journal api ********************/
$access = json_decode($_COOKIE['accessToken'],1);
ini_set('display_errors', 'On');
	require __DIR__ . '/vendor/autoload.php';

	require_once('storage.php');
	//require_once('example.php');

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
	  //	$result = $ex->createPayment($xeroTenantId,$accountingApi,true);
// 		echo '<pre>';print_r($result);die;
	    /*** run account api to get account id *************/
	    $dbc1 = new db();
	    $journal = $dbc1->getAll("SELECT id,AccountDesc,Debit,Credit,Reference FROM journal "); 
	    for($i=0;$i<count($journal);$i=$i+2)
	    { 
	        if($journal[$i]['Type'] == 'debit')
	        {
	            $deditCode = $ex->getAccount($xeroTenantId,$accountingApi,$journal[$i]['AccountDesc'],false);
	            $debitDesc = $journal[$i]['AccountDesc'];
	            $CreditCode = $ex->getAccount($xeroTenantId,$accountingApi,$journal[$i+1]['AccountDesc'],false);
	            $creditDesc = $journal[$i+1]['AccountDesc'];
	        }else
	        {
	            $creditCode = $ex->getAccount($xeroTenantId,$accountingApi,$journal[$i]['AccountDesc'],false);
	            $creditDesc = $journal[$i]['AccountDesc'];
	            $deditCode = $ex->getAccount($xeroTenantId,$accountingApi,$journal[$i+1]['AccountDesc'],false);
	            $debitDesc = $journal[$i+1]['AccountDesc'];
	        }
	        
	        
	       if($deditCode == 0 || $creditCode == 0)
	       { 
	          
	           // stop the process,store in database.. hat has no account related to journal..
	           $dbc = new db();
	           $appDetail = $dbc->execute("INSERT INTO  whereStop (journal_Id,journal_desc,createdDate) VALUES (".$journal[$i]['id']." ,'".$journal[$i]['AccountDesc']."' , '".date('Y-m-d')."')");
	           echo 'account not found stop the process...';
	           break;
	       }else {
	           // create journal to xero,...
	          if($journal[$i]['Debit'] == 0)
	           {
	               $credit = $journal[$i]['Credit']; $debit = "-".$journal[$i]['Credit'];
	           }else
	           {
	               $credit = $journal[$i]['Debit']; $debit = "-".$journal[$i]['Debit'];
	           }
	            $myArray[] = array(
                'credit' => $credit, 
                'debit' => $debit, 
                'creditCode' => $creditCode,
                'debitCode' => $deditCode,
                'debitDesc' => $debitDesc,
                'creditDesc' => $creditDesc,
                'narration' => $journal[$i]['Reference']
         );
	       } 
	       
	    }
	    //echo '<pre>';print_r($myArray);
		$result = $ex->createManualJournals($xeroTenantId,$accountingApi,$myArray,true);
		//echo '<pre>';print_r($result);
	   
 ?>
 <h4>Successfully added Journal ... check you xero</h4>
 </div>

</div>
   
</html>