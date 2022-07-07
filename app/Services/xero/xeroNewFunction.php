<?php
    // Use this class to deserialize error caught
    use XeroAPI\XeroPHP\AccountingObjectSerializer;
    use XeroAPI\XeroPHP\PayrollAuObjectSerializer;
   	require __DIR__ . '/vendor/autoload.php';

	require_once('storage.php');
	
	
	function getOrganisation($xeroTenantId,$apiInstance,$returnObj=false)
	{ 
		$str = '';

//[Organisations:Read]
$result = $apiInstance->getOrganisations($xeroTenantId);  						
//[/Organisations:Read]

		$str = $str . "Get Organisations: " . $result->getOrganisations()[0]->getName() . "<br>";
	
		if($returnObj) {
			return $result->getOrganisations()[0];
		} else {
			return $result;
		}
	}
    function createInvoice($xeroTenantId,$apiInstance,$returnObj=false,$postDt)
	{
	    //echo '<pre>';print_r($postDt);die;
	     $contactId = getContactExistOrNot($xeroTenantId,$apiInstance,$postDt['vendor'][0]->xeroId);
	    if($contactId == 1) { 
            // not found contact then send error message first add contact...
            return 'c1';
        }else{ 
             $lineitems = [];
             unset($postDt['vendor']);
              foreach($postDt as $details){
                  array_push($lineitems, getInvoiceLineItem($details->description,$details->account,$details->unitAmount,$details->vatrate));
              }
              
            $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setContactId($contactId);
            
             $arr_invoices = [];	
            
            
            $duedate = $postDt[0]->dueDate;
            
            $invoicedate = $postDt[0]->invoiceDate;

            $invoice_1 = new XeroAPI\XeroPHP\Models\Accounting\Invoice;
            $invoice_1
                ->setInvoiceNumber($postDt[0]->invoiceNo)
                ->setDate($invoicedate)
            	->setDueDate($duedate)
            	->setContact($contact)
            	->setLineItems($lineitems)
            	->setStatus(XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_AUTHORISED)
            	->setType(XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCPAY)
            	->setLineAmountTypes(\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes::EXCLUSIVE);	
            array_push($arr_invoices, $invoice_1);
            
            $invoices = new XeroAPI\XeroPHP\Models\Accounting\Invoices;
            $invoices->setInvoices($arr_invoices);
           //echo '<pre>';print_r($invoices);die;
            try {
                $result = $apiInstance->createInvoices($xeroTenantId,$invoices); 
                //echo 'hey-<pre>';print_r($result);die;
                 if($result->getInvoices()[0]->getStatusAttributeString() == 'ERROR')
                {
                    $erros = $result->getInvoices()[0]->getValidationErrors() ;
                   
                    // foreach($result->getInvoices()[0]->getValidationErrors() as $errors){
                    //     return array(false,$errors->getMessage());
                    // }
                    if(sizeof($erros)>0){
                        return array(false,$result->getInvoices()[0]->getValidationErrors()[0]->getMessage());
                    }
                    else{
                        return array(false,'Error Details not provide by xero');
                    }
                }else {
                    return array(true,$result->getInvoices()[0]->getInvoiceId());
                }
            } catch (\XeroAPI\XeroPHP\ApiException $e) {
                    	$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);
                    	//echo '<pre>';print_r($error);
                    	$re = $error->getMessage();
                    return array(false,$re);
                    	//return array(false,$re ,$newContactId);
                    }
        }
	}
	
	
	function getContactExistOrNot($xeroTenantId,$apiInstance,$contactId)
	{
	  
		$str = '';
     //$contactId = '6e0c3ea5-2962-4fa2-bb63-3cbcb161107a';
        $result = $apiInstance->getContact($xeroTenantId,$contactId);
        //echo '<pre>';print_r($result);die;
        if(isset($result->getContacts()[0])){
            // return $result->getContacts()[0]->getContactId(); // found and return contact id
            return $result->getContacts()[0]->getName(); // found and return contact id
        }else{
            return 1; // not found
        }
        // $contactEmail = $result->getContacts()[0]->getContactId();
        
        // return $result;
		
	}
	function getContactDetail($xeroTenantId,$apiInstance,$contactId)
	{
	    $str = '';
       $result = $apiInstance->getContact($xeroTenantId,$contactId);
      // echo '<pre>';print_r($result);die;
         if(isset($result->getContacts()[0])){
            // return $result->getContacts()[0]->getContactId(); // found and return contact id
            $data = array(
                'contactId' => $result->getContacts()[0]->getContactId(),
                'email' => $result->getContacts()[0]->getEmailAddress(),
                'firstName' => $result->getContacts()[0]->getFirstName(),
                'lastName' => $result->getContacts()[0]->getLastName(),
                'streetAddress' => $result->getContacts()[0]->getAddresses()[0]->getAddressLine1(),
                'city' => $result->getContacts()[0]->getAddresses()[0]->getCity() ,
                'postalCode' => $result->getContacts()[0]->getAddresses()[0]->getPostalCode() ,
                'country' => $result->getContacts()[0]->getAddresses()[0]->getCountry(),
                'state' => $result->getContacts()[0]->getAddresses()[0]->getRegion(),
                'phoneNo' => $result->getContacts()[0]->getPhones()[1]->getPhoneNumber(),
               
                );
            return $data;
        }else{
            return 1; // not found
        }
       
	}


    function checkContactExistOrNot($xeroTenantId,$apiInstance,$emailId)
	{
	    
	     $where = 'EmailAddress =="' . $emailId .'"';
	      try {
         $result = $apiInstance->getContacts($xeroTenantId, null, $where); 
         //echo '<pre>';print_r($result);die;
         //echo count($result->getContacts());die;
         if(count($result->getContacts()) == 0){
             return array(false,'no contct exist');
         }else{
             return array(true,$result->getContacts()[0]->getContactId());
         }
        } catch (\XeroAPI\XeroPHP\ApiException $e) {
        	$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);
        	//echo '<pre>';print_r($error);
        	$re = $error->getMessage();
        	//echo  $re;die;
        	return array(false,$re);
        }
	}
	
	function createNewContact($xeroTenantId,$apiInstance,$contactDetail)
	{
	   $arr_contacts = [];	
        
        $contact_1 = new XeroAPI\XeroPHP\Models\Accounting\Contact;
        $contact_1->setName($contactDetail->title)
        	->setFirstName($contactDetail->first_name)
        	->setLastName("")
        	->setIsSupplier(false)
        	->setIsCustomer(true)
        	->setEmailAddress($contactDetail->value);
        	//->setContactID($contactDetail['client_id'])    /* this is not exist in xero thats why comment it  * /
        	//->setDefaultCurrency($contactDetail['default_currency'])
        	//->setEmailAddress($contactDetail['email']);
        array_push($arr_contacts, $contact_1);
        	
        
        $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
        $contacts->setContacts($arr_contacts);
        try {
            $result = $apiInstance->createContacts($xeroTenantId,$contacts); 
            //echo '<pre>';print_r($result);die;
            $poId = $result->getContacts()[0]->getStatusAttributeString();
            if($poId == 'ERROR'){
                 return array(false,$result[0]->getValidationErrors()[0]->getMessage());
                                             
            }else{
                return array(true,$result->getContacts()[0]->getContactId());
            }
            } catch (\XeroAPI\XeroPHP\ApiException $e) {
            	$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);
            	//echo '<pre>';print_r($error);
            	$re = $error->getMessage();
            	//echo  $re;die;
	             return array(false,$re);
            }



		
	}

  
    function checkInvoiceExistOrnot($xeroTenantId,$apiInstance,$invoiceNo)
	{
	    $where = 'InvoiceNumber =="' . $invoiceNo .'" AND Status != "VOIDED"';
	    
	    //$result->getInvoices()[0]->getInvoiceId();
            $result2 = $apiInstance->getInvoices($xeroTenantId, null, $where);
            //echo '<pre>';print_r($result2);die;
           $poId = $result2->getInvoices()[0]->getStatusAttributeString();
            if($poId == 'ERROR'){
                 return $result2[0]->getValidationErrors()[0]->getMessage();
                                             
            }else{
                $contactName = getContactExistOrNot($xeroTenantId,$apiInstance,$result2->getInvoices()[0]->getContact()->getContactId());
                if($contactName == 1){
                    return 'Contact Not Found';
                }
                   $arr = array(
                'contactId' => $result2->getInvoices()[0]->getContact()->getContactId(),
                'contactName' => $contactName,
                'invoiceNo' => $result2->getInvoices()[0]->getInvoiceNumber(),
                'date' => $result2->getInvoices()[0]->getDate(),
                'amountDue' => $result2->getInvoices()[0]->getAmountDue(),
                'currency_code' => $result2->getInvoices()[0]->getCurrencyCode(),
                'amountPaid' => $result2->getInvoices()[0]->getAmountPaid(),
                'status' => $result2->getInvoices()[0]->getStatus(),
                'subTotal'=> $result2->getInvoices()[0]->getSubTotal(),
                'totalTax' => $result2->getInvoices()[0]->getTotalTax(),
                'total' => $result2->getInvoices()[0]->getTotal(),
                'lineItem' => $result2->getInvoices()[0]->getLineItems(),
                'invoiceId' => $result2->getInvoices()[0]->getInvoiceId(),
                'dueDate' => $result2->getInvoices()[0]->getDueDate(),
                'refernceNo'=>$result2->getInvoices()[0]->getReference(),
                );
            //echo '<pre>';print_r($arr);die;
            return $arr;
            }
            
       
	}
	

	
	function getInvoiceLineItem($desc,$accountCode,$unitAmt,$taxType)
	{
	    $lineitem = new XeroAPI\XeroPHP\Models\Accounting\LineItem;

        $lineitem->setDescription($desc)
			->setQuantity(1)
			->setUnitAmount($unitAmt)
			->setTaxType($taxType)
			->setAccountCode($accountCode);
		//	->setItemCode('DevH');
        
		return $lineitem;
	}
	
	
 function createContacts($xeroTenantId,$apiInstance,$returnObj=false,$contactDetail)
	{
	   /********* first check contact exist or not **************/
	   $emailDetail = getEmailExistOrNot($xeroTenantId,$apiInstance,$contactDetail['supplierReference']);
	   /**********************************************************/
	   if ($emailDetail == 1){
	       
	       return 'found';
	   }else {
	       $str = '';

            $arr_contacts = [];	
            
            $contact_1 = new XeroAPI\XeroPHP\Models\Accounting\Contact;
            if($contactDetail['supplierMail'] != ''){
                $contact_1->setName($contactDetail['supplierName'])
            	->setFirstName($contactDetail['supplierName'])
            	->setLastName("")
            	->setAccountNumber($contactDetail['supplierReference'])
            	->setIsSupplier(true)
            	->setIsCustomer(false)
            	->setDefaultCurrency($contactDetail['suppliercurrency'])
            	->setPurchasesDefaultAccountCode($contactDetail['defaultAccount'])
            	->setEmailAddress($contactDetail['supplierMail']);
            }else{
                $contact_1->setName($contactDetail['supplierName'])
            	->setFirstName($contactDetail['supplierName'])
            	->setLastName("")
            	->setAccountNumber($contactDetail['supplierReference'])
            	->setIsSupplier(true)
            	->setIsCustomer(false)
            	->setDefaultCurrency($contactDetail['suppliercurrency'])
            	->setPurchasesDefaultAccountCode($contactDetail['defaultAccount']);
            
            }
            array_push($arr_contacts, $contact_1);
            	
            
            $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $contacts->setContacts($arr_contacts);
            
            
              try {
            $result = $apiInstance->createContacts($xeroTenantId,$contacts); 
            //echo '<pre>';print_r($result);die;
            $poId = $result->getContacts()[0]->getStatusAttributeString();
            if($poId == 'ERROR'){
                 return array('no',$result[0]->getValidationErrors()[0]->getMessage());
                                             
            }else{
                return array('yes',$result->getContacts()[0]->getContactId());
            }
            } catch (\XeroAPI\XeroPHP\ApiException $e) {
            	$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);
            	//echo '<pre>';print_r($error);
            	$re = $error->getMessage();
            	//echo  $re;die;
	             return array(false,$re);
            }
		    //return $result->getContacts()[0]->getContactId();

	   }
		
	}
	
	function getEmailExistOrNot($xeroTenantId,$apiInstance,$emailId)
	{
  
            $where = 'AccountNumber=="' . $emailId .'"';
            $result2 = $apiInstance->getContacts($xeroTenantId, null, $where); 

	         if(isset($result2->getContacts()[0]))
	         {
                return 1; // found
            }else
            {
                return 0; // not found
            }
		
	}
	
	function createAccount($xeroTenantId,$apiInstance,$returnObj=false,$postDt)
	{       
	        $name = $postDt['codeName'];
	    	$where = 'name=="'.$name.'"'; 
	    	$accounts = $apiInstance->getAccounts($xeroTenantId, null, $where);
	    	if(isset($accounts->getAccounts()[0]))
	         {
	              $status = 2;
	             $re = 'Account Already Exist';
	             return array($status,$re);
	             
	         }else{
	             $account = new XeroAPI\XeroPHP\Models\Accounting\Account;
	             $account->setCode($postDt['codeNumber']);
                 $account->setName($postDt['codeName']);
                 $account->setType($postDt['glType']);
                 try {
                	$result = $apiInstance->createAccount($xeroTenantId,$account); 
                	$re = $result->getAccounts()[0]->getAccountId();
                	$status = 1;
                } catch (\XeroAPI\XeroPHP\ApiException $e) {
                	$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);
                	$re = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
                	$status = 0;
                }
                return array($status,$re);
	            
	         }
	    
	}
	
    function refreshToken($appDetail,$basicDetail){
        $storage = new StorageClass();
        $xeroTenantId = $appDetail->tenantId;
	  $clientId = $basicDetail->clientId;
    	$clientSecret = $basicDetail->secretKey;
    	$redirectUri = $basicDetail->returnUrl;
		$provider = new \League\OAuth2\Client\Provider\GenericProvider([
			'clientId'                => $clientId,   
			'clientSecret'            => $clientSecret,
			'redirectUri'             => $redirectUri,
        	'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
        	'urlAccessToken'          => 'https://identity.xero.com/connect/token',
        	'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
		]);
		//echo '<pre>';print_r($provider);die;
		$newAccessToken = $provider->getAccessToken('refresh_token', [
	        'refresh_token' => $appDetail->refreshToken
	    ]);
	    //	echo $newAccessToken;die;
	    $storage->setToken(
            $newAccessToken->getToken(),
            $newAccessToken->getExpires(), 
            $xeroTenantId,
            $newAccessToken->getRefreshToken(),
            $newAccessToken->getValues()["id_token"] );
             //echo  '<pre>';print_r($newAccessToken);die;
             $newToken =  $newAccessToken->getToken();
	         $newRefreshToken =  $newAccessToken->getRefreshToken();
	         $newExpire = $newAccessToken->getExpires();
	        
	         $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$newToken);		  
	         $accountingApi = new XeroAPI\XeroPHP\Api\AccountingApi(
	         new GuzzleHttp\Client(),
	         $config
        	);
        	return array ($newToken,$newRefreshToken,$newExpire,$xeroTenantId,$accountingApi);
    }
    
    function updateContact($xeroTenantId,$apiInstance,$returnObj=false,$contactDetail,$xeroId)
	{
		$str = '';
		
		//$new = $this->createContacts($xeroTenantId,$apiInstance,true);
		$contactId = $xeroId;								
					
//[Contact:Update]
$contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
//$contact->setName("Goodbye" . $this->getRandNum());	

    if($contactDetail['supplierMail'] != ''){
                $contact->setName($contactDetail['supplierName'])
            	->setFirstName($contactDetail['supplierName'])
            	->setLastName("")
            	->setAccountNumber($contactDetail['supplierReference'])
            	->setIsSupplier(true)
            	->setIsCustomer(false)
            	->setDefaultCurrency($contactDetail['suppliercurrency'])
            	->setPurchasesDefaultAccountCode($contactDetail['defaultAccount'])
            	->setEmailAddress($contactDetail['supplierMail']);
            }else{
                $contact->setName($contactDetail['supplierName'])
            	->setFirstName($contactDetail['supplierName'])
            	->setLastName("")
            	->setAccountNumber($contactDetail['supplierReference'])
            	->setIsSupplier(true)
            	->setIsCustomer(false)
            	->setDefaultCurrency($contactDetail['suppliercurrency'])
            	->setPurchasesDefaultAccountCode($contactDetail['defaultAccount']);
           }
            
$result = $apiInstance->updateContact($xeroTenantId,$contactId,$contact); 

$poId = $result->getContacts()[0]->getStatusAttributeString();
            if($poId == 'ERROR'){
                 return array('no',$result[0]->getValidationErrors()[0]->getMessage());
           }else{
                return array('yes',$result->getContacts()[0]->getContactId());
            }

	}
	
	 function createPayment($xeroTenantId,$apiInstance,$data)
	{
	   //echo  '<pre>';print_r($data);die;
	  
		$str = '';

	    $newAcct = getBankAccount($xeroTenantId,$apiInstance);
	    //echo '<pre>';print_r($newAcct);die;
 		$accountId = $newAcct->getAccounts()[0]->getAccountId(); 
 		$accountCode = $newAcct->getAccounts()[0]->getCode(); 
        $invoiceId = $data['invoiceId'];
         $amount = number_format($data['enterAmount'],2) ;
        $amt = (float)$amount;
		
		
		$invoice = new XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $invoice->setInvoiceID($invoiceId);

        $bankaccount = new XeroAPI\XeroPHP\Models\Accounting\Account;
        $bankaccount->setAccountID($accountId);
        //$bankaccount->setCode($accountCode);
        $date = date('Y-m-d');
        $payment = new XeroAPI\XeroPHP\Models\Accounting\Payment;
        $payment->setInvoice($invoice)
    	    ->setAccount($bankaccount)
	        ->setAmount($amt)
	        ->setDate($date)
	        ->setCode($accountCode);
	       //->setAmount("20.00");
	        
	
	    //echo '<pre>';print_r($payment);


        $result = $apiInstance->createPayment($xeroTenantId,$payment);
//[/Payments:Create]
        //echo '<pre>';print_r($result);die;
return $result->getPayments()[0]->getPaymentID();
//echo '<pre>';print_r($result);die;

// 		$str = $str . "Create Payment ID: " . $result->getPayments()[0]->getPaymentID() . "<br>" ;
		
// 		if($returnObj) {
// 			return $result;
// 		} else {
// 			return $str;
// 		}
	}
	
	 function getBankAccount($xeroTenantId,$apiInstance)
	{
		// READ only ACTIVE
		$where = 'Status=="' . \XeroAPI\XeroPHP\Models\Accounting\Account::STATUS_ACTIVE .'" AND Type=="' .  \XeroAPI\XeroPHP\Models\Accounting\Account::BANK_ACCOUNT_TYPE_BANK . '"';
		$result = $apiInstance->getAccounts($xeroTenantId, null, $where); 

		return $result;
	}
	function updateInvoice($xeroTenantId,$apiInstance,$invoiceId)
	{
	}
	
    
?>
