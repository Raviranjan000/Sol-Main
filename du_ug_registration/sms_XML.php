<?php //header("Content-Type: text/plain");
 
// include('config.php');
extract($_REQUEST);

$mobileno=$mob; //if single sms need to be send use mobileno keyword
 
if($flag == 'reg'){
	$message="Dear ".$stud_name.", Thank you for registering with online fee payment for UG, University of Delhi. Your User ID : ".$email." and Password : ".$student_password." "; 
}
else if($flag == 'forgot'){
	$message="Dear ".$stud_name.", You are requested for password recovery. Your User ID : ".$email." and Password : ".$student_password.", DU Team. "; 
}
else{
   $message="Dear ".$stud_name.", your fee has been received for the ".$course_sel." and University Roll No. : ".$university_roll_no." "; 
}

$message = urlencode($message);
$mobileno = urlencode($mob);
file_get_contents("https://web.sol.du.ac.in/apis/sendsms.php?msg=$message&mob=$mobileno");
exit;
 
 
 /*
 
	//call method and pass value to send single sms, uncomment next line to use
	sendSingleSMS($username,$encryp_password,$senderid,$message,$mobileno,$deptSecureKey);

	
	//function to send sms using by making http connection
	 function post_to_url($url, $data) {
	 $fields = '';
	 foreach($data as $key => $value) {
	 $fields .= $key . '=' . urlencode($value) . '&';
	 }
	 rtrim($fields, '&');
	 $post = curl_init();
	 //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
     curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
	 curl_setopt($post,CURLOPT_SSL_VERIFYPEER, false);
	 curl_setopt($post, CURLOPT_URL, $url);
	 curl_setopt($post, CURLOPT_POST, count($data));
	 curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
	 curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
	 $result = curl_exec($post); //result from mobile seva server
	 print_r($result); 
	// echo "[[[[$result"]]]]; //output from server displayed
	 curl_close($post);
	 }

	

	 //function to convert unicode text in UTF-8 format
	 function string_to_finalmessage($message){
	 $finalmessage="";
	 $sss = "";
	 for($i=0;$i<mb_strlen($message,"UTF-8");$i++) {
	 $sss=mb_substr($message,$i,1,"utf-8");
	 $a=0;
	 $abc="&#".ordutf8($sss,$a).";";
	 $finalmessage.=$abc;
	 }
	 return $finalmessage;
	 }

	 //function to convet utf8 to html entity
	 function ordutf8($string, &$offset){
	 $code=ord(substr($string, $offset,1));
	 if ($code >= 128)
	 { //otherwise 0xxxxxxx
	 if ($code < 224) $bytesnumber = 2;//110xxxxx
	 else if ($code < 240) $bytesnumber = 3; //1110xxxx
	 else if ($code < 248) $bytesnumber = 4; //11110xxx
	 $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) -
	($bytesnumber > 3 ? 16 : 0);
	 for ($i = 2; $i <= $bytesnumber; $i++) {
	 $offset ++;
	 $code2 = ord(substr($string, $offset, 1)) - 128;//10xxxxxx
	 $codetemp = $codetemp*64 + $code2;
	 }
	 $code = $codetemp;

	 }
	 return $code;
	 }
 
	//Function to send single sms
	 function sendSingleSMS($username,$encryp_password,$senderid,$message,$mobileno,$deptSecureKey)
	 {
	 $key=hash('sha512',trim($username).trim($senderid).trim($message).trim($deptSecureKey));
	 
	 $data = array(
	 "username" => trim($username),
	 "password" => trim($encryp_password),
	 "senderid" => trim($senderid),
	 "content" => trim($message),
	 "smsservicetype" =>"singlemsg",
	 "mobileno" =>trim($mobileno),
	 "key" => trim($key)
	 
	 
	 );
	 post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequest",$data); //calling post_to_url to send sms
	 }
*/




   ?>