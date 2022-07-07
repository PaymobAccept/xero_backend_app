<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 0);
?>
<?php
$handle = curl_init();
 
$url = "https://sandbox-quickbooks.api.intuit.com/oauth2/v1/tokens/bearer?grant_type
  =authorization_code&code
  =AB11594195181taHwNS2D9N7ZZlEhicGxe9c3Fn8TOpfdEtQp2&re
  direct_uri=https://developer.intuit.com/v2
  /OAuth2Playground/RedirectUrl";
 
// Set the url
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/x-www-form-urlencoded',
                                            'Connection: Keep-Alive',
                                            'Accept :application/json',
                                            'Authorization : POST /oauth2/v1/tokens/bearer?grant_type
  =authorization_code&code
  =AB11594195181taHwNS2D9N7ZZlEhicGxe9c3Fn8TOpfdEtQp2&re
  direct_uri=https://developer.intuit.com/v2
  /OAuth2Playground/RedirectUrl

Content-Type: application/x-www-form-urlencoded
Accept: application/json
Authorization: Basic 
  QUJuRDhZNVRiZlBCcmphYnJ6OEZaSFoxWUdpeWpSR2g5djhaUG5VRz
  c0MFV3a3l5RXM6Q2lUYVlhQzZTc2Q2aEpBNVVUenlxdEtidUgwVzdX
  eEVuMUZ0eDdBTg==
'
                                            ));

//curl_setopt($ch, CURLOPT_HTTPHEADER,array("Expect:  "));
 $request=  curl_getinfo($ch);
    var_dump($request);


    $output = curl_exec($ch);
//$output = curl_exec($handle);
 
//curl_close($handle);
 
echo'<pre>';print_r($output);
?>
<!--POST /oauth2/v1/tokens/bearer?grant_type-->
<!--  =authorization_code&code-->
<!--  =AB11594195181taHwNS2D9N7ZZlEhicGxe9c3Fn8TOpfdEtQp2&re-->
<!--  direct_uri=https://developer.intuit.com/v2-->
<!--  /OAuth2Playground/RedirectUrl-->

<!--Content-Type: application/x-www-form-urlencoded-->
<!--Accept: application/json-->
<!--Authorization: Basic -->
<!--  QUJuRDhZNVRiZlBCcmphYnJ6OEZaSFoxWUdpeWpSR2g5djhaUG5VRz-->
<!--  c0MFV3a3l5RXM6Q2lUYVlhQzZTc2Q2aEpBNVVUenlxdEtidUgwVzdX-->
<!--  eEVuMUZ0eDdBTg==-->
