<?php header("Content-Type: text/plain");
 
// include('config.php');
 extract($_REQUEST);
	
if($mob!=''){
	
       // $sms_text='Your One Time Password for Notification Entry SOL is '.$otp_code.'. Donot share password with anyone.';
	   
	   if($flag == 'reg'){
			$sms_text="Dear ".$stud_name.", Thank you for registering with online fee payment for UG, University of Delhi. Your User ID : ".$email." and Password : ".$student_password." "; 
	   }
	   else if($flag == 'forgot'){
			$sms_text="Dear ".$stud_name.", You are requested for password recovery. Your User ID : ".$email." and Password : ".$student_password.", DU Team. "; 
	   }
	   else{
		   $sms_text="Dear ".$stud_name.", your fee has been received for the ".$course_sel." and University Roll No. : ".$university_roll_no." "; 
	   }
		
		
		
		
		
		$postdata = http_build_query(
    array(
         'smsservicetype'=>'singlemsg',
         'senderid'=>'MSOLDU',
         'username'=>'DUSOL',
         'password'=>'sol@1962',
         'mobileno'=>$mob,
         'content'=>$sms_text     
    )
);
 
$url = "http://msdgweb.mgov.gov.in/esms/sendsmsrequest"; 
//$url = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi";
//initialize curl handle
$curl_connection = curl_init($url);
curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $postdata); //set the POST variables
curl_setopt($curl_connection, CURLOPT_HEADER, 0);
curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);  
$result = curl_exec($curl_connection); //run the whole process and return the response
print_r($result);  
curl_close($curl_connection);  //close the curl handle        

}
   ?>