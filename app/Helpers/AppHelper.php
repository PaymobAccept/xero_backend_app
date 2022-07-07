<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Session;
use DB;
use Mail;

use SendGrid\Mail\Footer;
use SendGrid\Mail\Personalization;


class AppHelper {
    	
		public static function unqNum(){
			$length = 6;
			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			$randomString1 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			$randomString = $randomString.$randomString1;
			return $randomString;
        }
        
        public static function getSendGridMail($data)
        { 
            require_once(app_path() . '/Services/sendgrid/vendor/autoload.php');
        	$email = new \SendGrid\Mail\Mail(); 
            $email->setFrom("yourhelpgroup@gmail.com", "Xero Payment");
            $email->setSubject($data['subject']);
            //$email->addTo($data['to'], $data['toName']);
            $email->addTo('raman73preet@gmail.com', 'Raman Preet');
            //$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
            $email->addContent(
                "text/html", $data['file']
            );
           
             if(isset($data['attatchment'])){
                 $file_encoded = base64_encode($data['attatchment']);
            $email->addAttachment(
               $file_encoded,
               "application/pdf",
               "invoice.pdf",
               "attachment"
            );
        }
        
        /***********************/
        //  $footer = new Footer();
        //         $footer->setEnable(true);
        //         //$footer->setText("Footer Text");
        //         $footertext = self::addFooterSendGrid();
        //         $footer->setHtml($footertext);
        //         $email->setFooter($footer);
        /***********************/
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";die;
            return $response;
        } catch (Exception $e) {
            //echo 'Caught exception: '. $e->getMessage() ."\n";
        }
	    
	}
public static function getEmailContent($pass)
{
    $table =  '<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8" style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: "Open Sans", sans-serif;">
        <tbody><tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tbody><tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                          <a href= '.URL('/').' title="logo" target="_blank">
                            <img width="180" src=" '.URL('/').'/assets/image/xero.png" title="logo" alt="logo">
                          </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tbody><tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:"Rubik",sans-serif;">'.$pass['title'].'</h1>
                                        <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            Dear '.$pass['fullName'].',<br><br> ';
                                            
        if($pass['type'] == 'CustomUrl') {
            $table = $table .' Please click on below link and and login with following credential  <br> ' .
                                URL('/') . '<br> Email - '. $pass['email'] .' <br> Password - '. $pass['password'] . '<br> Note - After login connnect with Xero account and you got 
                                    xero custom URL and paste that URL into your xero account . ';
        }
       
                    $table = $table .' </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr><tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">© <strong>Xero Payment</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </tbody></table>
            </td>
        </tr>
    </tbody></table>
    
    ';  
    return $table;
}


}
