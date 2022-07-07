<?php
namespace App\Http\Model;
use DB;
use Hash;
use Session;
use App\Helpers\GlobalFunction as Helper;
use Illuminate\Database\Eloquent\Model;
class Xero extends Model
{
    public static function userLogin($data){
    	//DB::enableQueryLog();
        $result =  DB::table('users')->select('*')
        ->where('email',$data['email'])
        ->where('password',md5($data['password']))
        ->where('status',1)
        ->get();
        //$query = DB::getQueryLog();
    	
        return $result;
    }
        
    public static function saveAccessToken($data)
    {
        $arr = array(
            'clientRef' => Session::get('uniqueId'),
            'accessToken' => $data[0]->accessToken,
            'expires' => $data[0]->expires,
            'refreshToken' => $data[0]->refreshToken,
            'id_token' => $data[0]->id_token,
            'token_type' => $data[0]->token_type,
            'tenantId' => $data[0]->tenantId,
            'tenantName' => $data[0]->tenantName,
        );
        $result = DB::table('xerotokendetail')->insert($arr);
        return $result;
    }
    
    public static function getCompanyName()
    {
        $result = DB::table('xerotokendetail')->select('tenantName')->where('clientRef',Session::get('uniqueId'))->get();
        return $result;
    }
    public static function xeroDisconnect()
    {
        $result = DB::table('xerotokendetail')->where('clientRef',Session::get('uniqueId'))->delete();
        return $result;
    }
     public static function getBasicAppDetail($clientId)
    {
        $result = DB::table('xeroauth')->select('*')->first();
        return $result;
    }
    public static function getRefershToken($clientId)
    {
        $data = DB::table('xerotokendetail')->select('*')->where('clientRef','=',$clientId)->first();
        return $data;
    }
    public static function updateToken($newToken,$newRefreshToken,$newExpire,$clientId)
    {
         $data = array(
            'accessToken' => $newToken ,
            'expires' => $newExpire ,
            'refreshToken' =>$newRefreshToken,
           
          );
          //$userId = Session::get('userUnique');
          $result = DB::table('xerotokendetail')->where('clientRef',$clientId)->update($data);
          return $result;
    }
    public static function getContact()
    {
        return $result = DB::table('contact')->get();
    }
    public static function getContactDetail($uniqueId)
    {
        return $result = DB::table('contact')->select('contact.*')->where('contact.uniqueId',$uniqueId)->get();
    }
    public static function getTokenDetail($contactId)
    {
        return $result = DB::table('cardToken')->select('cardToken.*')->where('cardToken.contactId',$contactId)->get();
    }
    public static function checkEmailExistance($email)
    {
        return $result = DB::table('contact')->select('*')->where('email',$email)->get();
    }
    public static function saveCustomURL($data){
        $result = DB::table('contact')->insert($data);
        $record = array(
            'uniqueId' => $data['uniqueId'],
            'name' => $data['firstName'] .' ' .$data['lastName'],
            'email' => $data['email'],
            'password' =>$data['password'],
            'status' => 1 // active
            );
            DB::table('users')->insert($record);
          return $result;
    }
    public static function checkXeroConnectivity($clientId)
    {
        return $data = DB::table('xerotokendetail')->select('*')->where('clientRef',$clientId)->get();
    }
    public static function getCustomerDetail()
    {
        return $result = DB::table('contact')->select('*')->where('uniqueId',Session::get('uniqueId'))->get();
    }
    public static function updateSettings($uniqueId,$data)
    {
        $result = DB::table('contact')->where('uniqueId',$uniqueId)->update($data);
        return $result;
        
    }
    
    
}