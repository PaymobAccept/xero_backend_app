<?php
   ob_start();
   //session_start();
?>
<?
    // error_reporting(E_ALL);
    // ini_set("display_errors", 1);
?>
<?php 
//include('db.php');
require_once('xeroFunction.php');
session_start();
?>
<?php 
//$access = json_decode($_COOKIE['accessToken'],1);
$access = $_SESSION['token'];
//echo '<pre>after';print_r($access);die;
/*********** store access token id into database..**********/
// $dbc = new db();
// $appDetail = $dbc->execute("INSERT INTO token_detail (userRef, accessToken, expires, refreshToken, id_token, token_type, tenantId) VALUES ('".$_COOKIE['userId'] ."', '".$access['accessToken']."', '".$access['expires']."','".$access['refreshToken']."','".$access['id_token']."','".$access['token_type']."','".$access['tenantId']."')");

 ini_set('display_errors', 'On');
	require __DIR__ . '/vendor/autoload.php';

	require_once('storage.php');
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$env =	$dotenv->load();
	$clientId = $env['CLIENT_ID'];
	$clientSecret = $env['CLIENT_SECRET'];
	$redirectUri = $env['REDIRECT_URI'];

	// Storage Classe uses session
    $storage = new StorageClass();
    	// ALL methods are demonstrated using this class
	$ex = new XeroFunctionClass();
	
	$length = 5;
	$orgArr = array();
	$accessData = array();
	for($i=0;$i<count($access);$i++){
	    if($access[$i]['tenantName'] == Session::get('orgName')){
	    $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$access[$i]['accessToken'] );		  
	    $accountingApi = new XeroAPI\XeroPHP\Api\AccountingApi(
	        new GuzzleHttp\Client(),
	        $config
    	);
    	
    	$accessData[] = array(
               // 'clientRef' => Session::get('orgRef'),
                'accessToken' => $access[$i]['accessToken'] ,
                'expires' => $access[$i]['expires'] ,
                'refreshToken' =>$access[$i]['refreshToken'],
                'id_token' => $access[$i]['id_token'],
                'token_type' => $access[$i]['token_type'],
                'tenantId' => $access[$i]['tenantId'],
                'tenantName'=>$access[$i]['tenantName']
              );
        
    	$result = $ex->getOrganisation($access[$i]['tenantId'],$accountingApi);
            
    	$orgArr[] = array(
    	   // 'orgid' => $result->getOrganisations()[0]->getOrganisationId(),
    	   // 'orgName' =>  $result->getOrganisations()[0]->getName(),
    	   // 'legalName' => $result->getOrganisations()[0]->getLegalName()
    	    'org_id' => $result->getOrganisations()[0]->getOrganisationId(),
    	    'org_name' =>  $result->getOrganisations()[0]->getName(),
    	    'org_legal_name' => $result->getOrganisations()[0]->getLegalName(),
    	     'clientRef' => Session::get('orgRef')
    	 );
    	    $apiDetails = Session::get('api');
    	   //echo '<pre>';print_r($apiDetails);die;
    	    $contactDetails = array();
    	    $glCode = array();
    	    if(is_array($apiDetails) == 1){
    	    if (in_array("glApi", $apiDetails)){
    	        $account = $ex->getAccounts($access[$i]['tenantId'],$accountingApi);
    	       // echo '<pre>';print_r($account);die;
    	        for($j=0;$j< count($account);$j++){
    	         
		        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		        $randomString1 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    	        $glCode[] = array(
    	            'codeRef' =>$randomString.$randomString1,
    	            'codeNumber'=>$account->getAccounts()[$j]->getCode(),
    	            'codeName'=>$account->getAccounts()[$j]->getName(),
    	            'type' =>$account->getAccounts()[$j]->getType(),
    	            'xeroId' => $account->getAccounts()[$j]->getAccountId(),
    	            'clientRef'=>Session::get('orgRef'),
    	            'codeNature'=>1
    	            );
    	        }
    	    }
    	    
    	       
            if (in_array("supplierApi", $apiDetails)){
    	    $contacts = $ex->getAllSupplier($access[$i]['tenantId'],$accountingApi);
    	     for($k=0;$k< count($contacts);$k++){
    	         
		        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		        $randomString1 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    	        $contactDetails[] = array(
    	            'supplierName' => $contacts->getContacts()[$k]->getName(),
    	            'supplierRef' => $randomString.$randomString1,
    	            'phoneNumber' => $contacts->getContacts()[$k]->getPhones()[3]->getPhoneNumber(),
    	            'supplierMail' => $contacts->getContacts()[$k]->getEmailAddress(),
    	            'suppliercurrency' => $contacts->getContacts()[$k]->getDefaultCurrency(),
    	            'suppliercountry' => $contacts->getContacts()[$k]->getAddresses()[0]->getCountry(),
    	            'defaultAccount' => $contacts->getContacts()[$k]->getBankAccountDetails(),
    	            'createdBy'=>Session::get('userUnique'),
                    'status'=>1,
                    'createDate'=>date('Y-m-d'),
                    'clientId'=>Session::get('orgRef'),
                    'supplierType'=>1,
                    'xeroId'=>$contacts->getContacts()[$k]->getContactId()
    	       );
    	    }
	}
	}
	
	$taxRates = $ex->getTaxRate($access[$i]['tenantId'],$accountingApi);
	$taxRateArr = array();
	if(count($taxRates)){
	for($t=0;$t<count($taxRates);$t++){
	    $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $randomString1 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $taxRateArr[]=array(
            'taxName'=>$taxRates->getTaxRates()[$t]->getName(),
            'taxType'=>$taxRates->getTaxRates()[$t]->getTaxType(),
            'taxRef'=>$randomString.$randomString1,
            'clientId'=>Session::get('orgRef')
        );
	}
	}
	//echo '<pre>';print_r($taxRates);die;
    //	$orgid = $result->getOrganisations()[0]->getOrganisationId();
    //	$orgName = $result->getOrganisations()[0]->getName();
    //	$legalName = $result->getOrganisations()[0]->getLegalName();
    
	}
	}

	//$appDetail = $dbc->execute("INSERT INTO organisation_detail (userRef, org_id, org_name, org_legal_name) VALUES ('".$_COOKIE['userId'] ."', '".$orgid."', '".$orgName."','".$legalName."')");
  // setcookie('companyName', $orgName, time() + (86400 ), "/");
     //header('Location: ' . './');
?>