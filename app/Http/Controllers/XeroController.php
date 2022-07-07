<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Mail;
use Helper;
use DB;
use Redirect;
use Config;
use App\Http\Model\Xero as Xero;
class XeroController extends Controller
{
    public function logout()
    {
        Session::flush();
		Session::regenerate();
		
		return redirect('/');
    }
    
    /* User and Admin both login on same page */
    public function loginUser(Request $request)
    {
        
        $resultVal = Xero::userLogin($request->all());
        if(count($resultVal) < 1)
            {
                $output['success']      = false;
                $output['message']  = '<div class="alert alert-danger" role="alert">Username or Password is incorrect.</div>';
                return redirect('/')->withErrors($output)->withInput();
          }else
           {
               Session::put('uniqueId',$resultVal[0]->uniqueId);
               Session::put('email',$resultVal[0]->email);
               Session::put('name',$resultVal[0]->name);
               Session::put('type',$resultVal[0]->type);
                return redirect('dashboard');
               
               
           }
    }
    
    
    /* 
        Check the User already connected Xero company or not,
         if connected then show company name otherwise show Xero Connect Button 
    */
    
    
    public function xeroIndex()
    {
        if(!isset($_COOKIE['accessToken'])) {
                $result = Xero::getCompanyName();
                if(count($result) > 0){
                    $save['companyName'] = $result[0]->tenantName;
                }else{
                    $save['companyName'] = '';
                }
                return view('xero.connectXero',$save);
	        }else{
	            $result = json_decode($_COOKIE['accessToken']);
	            $save['data'] = Xero::saveAccessToken($result);
	            $save['companyName'] = $result[0]->tenantName;
	            unset($_COOKIE['accessToken']);
                setcookie('accessToken', null, -1, '/');
	            return view('xero.connectXero',$save);
	        }
        
    }
    
    /* Redirect to Xero Login Page For Authorization */
        public function xeroConnect(Request $request){
            require_once(app_path() . '/Services/xero/authorization.php');
            
        }
        
        /* After Connect Xero user redirect to this function  */
        
         public function callback()
	    {
	        require_once(app_path() . '/Services/xero/callback.php');
	        
    	}
    	
    	
    	
        public function xeroDisconnect()
        { 
            $result = Xero::xeroDisconnect();
            return view('xero.connectXero');
        }
        
        
        /* Refresh the Xero The Access Token by pass refresh token  */
        
        
        public function refreshToken($clientId)
        	{ 
            	include(app_path().'/Services/xero/vendor/autoload.php');
               // include(app_path().'/Services/xero/storage.php');
                include(app_path().'/Services/xero/xeroNewFunction.php');
                $basicDetail = Xero::getBasicAppDetail($clientId);
                $appDetail = Xero::getRefershToken($clientId); 
        	    //echo '<pre>';print_r($appDetail);die;
        	    if(isset($appDetail->tenantId)){ 
        	        $details = refreshToken($appDetail,$basicDetail);
        	        //echo '<pre>';print_r($details);die;
        	    	$data1 = xero::updateToken($details[0],$details[1],$details[2],$clientId);
                    return array($details[3],$details[4]); 
                   } else{ 
                       return array();
        	    }
                	
        	}
        	
        public function contactCustomUrl()
        	{
        	    $check['data'] = Xero::checkXeroConnectivity(Session::get('uniqueId'));
        	    echo view('xero.contactCustomUrl',$check);
        	}
    public function xeroRedirect()
    	{ 
    	   $ex = $this->refreshToken($_GET['uniqueId']); 
    	   if(count($ex) > 0){
    	       $inv['data'] = checkInvoiceExistOrnot($ex[0],$ex[1],$_GET['inv']);
                $inv['uniqueId'] = $_GET['uniqueId'];
                //echo '<pre>';print_r($inv['data']);die;
                return view('xero.showInvoice',$inv);
    	   }else{
    	       echo 'There are some problem with this merchant Id , please check';
    	   }
            
    	  
    	}
    	public function loadPaymentForm(){
    	    return view('xero.paymobPage');
    	}
    	
    	/* Make the Payments, create Intention  */
    	
    	
    	public function savePayment()
    	{
    	  $ex = $this->refreshToken($_GET['uniqueId']);
          $record = checkInvoiceExistOrnot($ex[0],$ex[1],$_GET['invoiceNo']);
    	  $amount = (int)$record['total'];
    	  $contact = getContactDetail($ex[0],$ex[1],$record['contactId']); 
    	  //echo '<pre>';print_r($contact);die;
    	  $invoiceNumber = $_GET['invoiceNo'];
    	  $userId = $_GET['uniqueId'];
    	  if($contact['streetAddress'] == '') 
    	      $contact['streetAddress'] = 'Ethan Land';
    	  if($contact['city'] == '')
    	      $contact['city'] = 'NILL';
    	  if($contact['state'] == '')
    	      $contact['state'] = 'NILL';
    	  if($contact['postalCode'] == '')
    	      $contact['postalCode'] = 'NILL';
    	  if($contact['phoneNo'] == '')
    	      $contact['phoneNo'] = '+201010101010';
    	  if($contact['country'] == '')
    	      $contact['country'] = 'NILL';
    	   
    	    $client = new \GuzzleHttp\Client();
    	    $response = $client->request('POST', 'https://flashapi.paymob.com/v1/intention/', [
              'body' => '{
                  "amount":"'.$amount.'",
                  "currency":"EGP",
                  "payment_methods":['.Config::get('global.payment_methods').'],
                  "billing_data":{
                      "apartment":"NILL",
                      "email":"'.$contact['email'].'",
                      "floor":"0",
                      "first_name":"'.$contact['firstName'].'",
                      "street":"'.$contact['streetAddress'].'",
                      "building":"0",
                      "phone_number":"'.$contact['phoneNo'].'",
                      "shipping_method":"PKG",
                      "postal_code":"'.$contact['postalCode'].'",
                      "city":"'.$contact['city'].'",
                      "country":"'.$contact['country'].'",
                      "last_name":"'.$contact['lastName'].'",
                      "state":"'.$contact['state'].'"},
                      "customer":{
                          "first_name":"'.$contact['firstName'].'",
                          "last_name":"'.$contact['lastName'].'",
                          "email":"'.$contact['email'].'"
                      },
                   
                    "extras":{"invoiceNumber":"'.$invoiceNumber.'","userId":"'.$userId.'"}}',
              'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Token '. Config::get('global.authorization'),
                'Content-Type' => 'application/json',
              ],
            ]);
            $contents = $response->getBody()->getContents();
          //echo 'Intention....<pre>';print_r(json_decode($contents));
           return $contents;
    
    	}
    	
    	
    	
    /* Create New Merchant Customer  */
    
    public function registerUser(Request $request)
    {
        $check = array();
        $arr = $request->all();
        if(isset($arr['firstName'])){
            $check = Xero::checkEmailExistance($arr['email']);
            if(count($check) >0){
                $check['message'] = '<div class="alert alert-danger" role="alert">Email already exist!</div>';
            }else {
                $length = 6;
                $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
                $randomString1 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
                $randomString = $randomString.$randomString1;
            $data = array(
                'uniqueId' => $randomString,
                'firstName' => $arr['firstName'],
                'lastName' => $arr['lastName'],
                'email' => $arr['email'],
                'password' => md5($arr['password']),
                );
                $result = Xero::saveCustomURL($data);
                $check['message'] = '<div class="alert alert-success" role="alert">Contact Save Successfully!</div>';
                 
                
                 /* send email */
                $pass = array(
                    'fullName' => $arr['firstName'] . ' ' .$arr['lastName'],
                    'email' => $arr['email'] ,
                    'type' =>'CustomUrl',
                    'title' => 'Xero Payment App Custom URL',
                    'email' =>  $arr['email'] ,
                    'password' => $arr['password'],
                    'url' => URL('/')."/xero-redirect?inv=[INVOICENUMBER]&uniqueId=".$randomString,
                    );
                $message = Helper::getEmailContent($pass);
                $data = array(
                    'subject' => "Xero Payment App Custom URL",
                    'to' => $arr['email'],
                    'toName' => $arr['firstName'] . ' ' .$arr['lastName'],
                    'file' => $message,
                    );
                $email = Helper::getSendGridMail($data);
                /***************/
            }
             
        }
        return view('common.login',$check);
    }
    
    /* webhook response of payments  read here and mark paid the invoice in Xero */ 
    public function paymentDoneCallback()
    {
        $data = file_get_contents("php://input"); 
        $res = json_decode($data,true);
        
         
         
        if(isset($res['intention']['transactions'][0]['status']) && $res['intention']['transactions'][0]['status'] == 'Success'){
            $ex = $this->refreshToken($res['intention']['extras']['creation_extras']['userId']);
            
            $inv = checkInvoiceExistOrnot($ex[0],$ex[1],$res['intention']['extras']['creation_extras']['invoiceNumber']);
            $pay = array(
                'invoiceId' => $inv['invoiceId'],
                'enterAmount' => $inv['total'],
                
                );
            $payDone = createPayment($ex[0],$ex[1],$pay);
            
        }
    }
    public function paymentReturn()
    {
        echo view('xero.successMessage');
    }
    public function updateSettings(Request $request)
    {
        if(isset($_POST['firstName'])){
            $detail  =array(
                'firstName' => $_POST['firstName'],
                'lastName' => $_POST['lastName'],
                'email' => $_POST['email'],
                );
                if(isset($_POST['password']) && $_POST['password'] != ''){
                    $detail['password'] = md5($_POST['password']);
                }
               $check =  Xero::updateSettings($_POST['uniqueId'],$detail);
               $result['message'] = '<div class="alert alert-success" role="alert">Data Updated Successfully</div>';
        }
           $result['data'] =  Xero::getCustomerDetail();
            echo view('xero.updateSettings',$result);
        
    }
}
?>