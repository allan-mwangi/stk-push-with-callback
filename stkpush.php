<?php
if(isset($_POST["submit"]))
{
    
    $mobile_number=$_POST["mobile_number"];
    $mobile_validator="/^[0-9]{10,12}$|^\+2547[0-9]{8,12}$/im";
    
    if(!preg_match($mobile_validator,$mobile_number))
    {
    echo "<script>alert('Invalid Mobile Number');</script>";
    echo 'Invalid Mobile Number';
    return;
    }
    
    if(substr($mobile_number,0,1)=="0")
    {
    //echo "<script>alert('First Digit is 0 in Mobile Number');</script>";
    //echo 'First Digit is 0 in Mobile Number';
    substr_replace($mobile_number,"254",0);
    $mobile_number="254".substr($mobile_number,1,strlen($mobile_number));
    }
    else if(substr($mobile_number,0,4)=="+254")
    {
        $mobile_number=substr($mobile_number,1,strlen($mobile_number));
    }
    date_default_timezone_set('Africa/Nairobi');
    
    $consumerKey = ''; //Fill with your app Consumer Key
	$consumerSecret = ''; // Fill with your app Secret
	$headers = ['Content-Type:application/json; charset=utf8'];
	$access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
	$access_token_curl = curl_init($access_token_url);
	curl_setopt($access_token_curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($access_token_curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($access_token_curl, CURLOPT_HEADER, FALSE);
	curl_setopt($access_token_curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
	$result = curl_exec($access_token_curl);
	$status = curl_getinfo($access_token_curl, CURLINFO_HTTP_CODE);
	$result = json_decode($result);

	$access_token = $result->access_token;

    
	$register_url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

	//$access_token = ''; // check the mpesa_accesstoken.php file for this. No need to writing a new file here, just combine the code as in the tutorial.
	$shortCode = '174379'; // provide the short code obtained from your test credentials

	// This two files are provided in the project. 
	$confirmationUrl = ''; //fill in your confirmation or callback url here  //path to your confirmation url. can be IP address that is publicly accessible or a url
	$validationUrl = 'https://creativecomputersolutions.co.ke/mpesa/mpesa_callback.php'; // path to your validation url. can be IP address that is publicly accessible or a url

	$register_curl = curl_init();
	curl_setopt($register_curl, CURLOPT_URL, $register_url);
	curl_setopt($register_curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header

	$register_curl_post_data = array(
	  //Fill in the request parameters with valid values
	  'ShortCode' => $shortCode,
	  'ResponseType' => 'Confirmed',
	  'ConfirmationURL' => $confirmationUrl,
	  'ValidationURL' => $validationUrl
	);

	$data_string = json_encode($registecurl_post_data);

	curl_setopt($register_curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($register_curl, CURLOPT_POST, true);
	curl_setopt($register_curl, CURLOPT_POSTFIELDS, $data_string);

	$register_curl_response = curl_exec($register_curl);
	print_r($register_curl_response);

	//echo $register_curl_response;
    
  	$confirmationUrl = ''; //fill in your confirmation or callback url here  //path to your confirmation url. can be IP address that is publicly accessible or a url
  $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
  
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token));
  $BusinessShortCode = '174379';
  $Timestamp = date('YmdHis');
  $Passkey='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
  
  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => '174379',
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => '1',
    'PartyA' => $mobile_number,
    'PartyB' => '174379',
    'PhoneNumber' => $mobile_number,
    'CallBackURL' => $confirmationUrl,
    'AccountReference' => 'STKPush Test ',
    'TransactionDesc' => 'Testing stk push on sandbox'
  );
  
  $data_string = json_encode($curl_post_data);
  
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  
  $curl_response = curl_exec($curl);
  $curl_response =json_decode($curl_response);
  print_r($curl_response);
  
  //echo "<br><br><br>Description ".$curl_response->ResponseDescription."<br><br>Response Code ".$curl_response->ResponseCode;
  if($curl_response->ResponseDescription=="Success. Request accepted for processing" && $curl_response->ResponseCode=="0")
  {
      echo "<script>alert('Lipa Na MPESA Prompt sent to ".$mobile_number."');</script>";
      echo 'Lipa Na MPESA Prompt sent to '.$mobile_number;
  }
  else
  {
      echo "<script>alert('Lipa Na MPESA Prompt request sent to ".$mobile_number." has failed. \\nUser might be offline of out of network coverage');</script>";
      echo 'Lipa Na MPESA Prompt sent to '.$mobile_number.' has failed. <br><br>User might be offline of out of network coverage';
  }
}
else
{
    echo "NICE TRY!!";
}

  ?>
