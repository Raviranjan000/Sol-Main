<?php
session_start();
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
//require_once("./lib/config_paytm.php");
//require_once("./lib/encdec_paytm.php");

//////////////////////////////////////////////////////////////

include("../config_manual.php");
include("../include/functions.php");

if(!$_SESSION['ADMIN_LOGGED_IN']){
	alertandreplace("Login First","../login.php");
	exit;
}


$sql_c = "SELECT b.SOL_MER_REF_NO,b.CREATED_TIMESTAMP,ROUND(
    (cast(SYSDATE AS DATE) - cast(b.CREATED_TIMESTAMP  AS DATE))
    * 24 * 60
  ) AS diff_minutes
 FROM WEB_ADM_REGISTRATION_DETAILS a,WEB_ADM_TRANSACTION_DTLS b
 WHERE a.REGISTRATION_ID = b.REGISTRATION_ID
AND a.PROSPECTUS_ID = '".$_SESSION['STUDENT_PROSPECTUS_ID']."'
AND b.STATUS=1 ";
$cuid_c = oci_parse(ORACLE_CONN, $sql_c);
oci_execute($cuid_c);
$row_c = oci_fetch_array($cuid_c, OCI_ASSOC + OCI_RETURN_NULLS);


$_SESSION['CHK_PAYMENT_STATUS_SESS'] = 'false';


if($row_c['SOL_MER_REF_NO'] != ''){
	$order_id = $row_c['SOL_MER_REF_NO'];
	$CREATED_TIMESTAMP = $row_c['CREATED_TIMESTAMP'];
	$diff_minutes = $row_c['DIFF_MINUTES']; 
		
	if($diff_minutes <= 30){
		$gap_time = 30 - $diff_minutes;
		echo "<script> alert('Please wait for ".$gap_time." minutes. for the next payment.'); location.href='../admin_home.php'</script>";
		exit;
	}	
	
	
	$file_content = file_get_contents("https://eazypay.icicibank.com/EazyPGVerify?ezpaytranid=&amount=&paymentmode=&merchantid=".MERCHANT_ID."&trandate=&pgreferenceno=".$order_id."");

	$get_string = explode("&",$file_content);
	$get_status = explode("=",$get_string[0]);
	$return_status = $get_status[1];
	
	$get_ezpaytranid = explode("=",$get_string[1]);
	$return_ezpaytranid = $get_ezpaytranid[1];
	
	$get_amount = explode("=",$get_string[2]);
	$return_amount = $get_amount[1];




		//$ORDER_ID = '14001257010_1470716489';//$_REQUEST["ORDER_ID"];

		// Create an array having all required parameters for status query.
		//$requestParamList = array("MID" => PAYTM_MERCHANT_MID , "ORDERID" => $order_id);  

		// Call the PG's getTxnStatus() function for verifying the transaction status.
		//$responseParamList = getTxnStatus($requestParamList);
		
		//if($responseParamList['RESPCODE'] == '01'){
			
			//echo "<script> alert('Your payment is confirm and your order-id is ".$order_id." and transaction id is ".$responseParamList['TXNID']." Please wait for 5 working days for the fee receipt.'); location.href='../admin_home.php'</script>";
			//exit;
			
		if($return_status == 'RIP' || $return_status == 'SIP' || $return_status == 'Success'){			
		
			//$merchant_ref_no = explode("_",$order_id);
			$university_roll_no = $_SESSION['STUDENT_PROSPECTUS_ID'];//$merchant_ref_no[0];

			$fee_amount = $return_amount;//$_REQUEST['TXNAMOUNT'];
			$pay_date = $CREATED_TIMESTAMP; // date('d-M-y')
			$TXNID = $return_ezpaytranid;//$responseParamList['TXNID'];

			$split_var = "|";
			$payment_string = "1".$split_var.$fee_amount.$split_var.$TXNID.$split_var.$pay_date.$split_var."2";			
			
			
			
/////////////////////////////////////////////////////////////////////


			$sql = "SELECT a.CAMPUS_CODE, a.COURSE_CODE, a.SOL_ROLL_NO AS University_roll_no,Pkg_Common.FN_GET_CAMPUS_NAME(a.CAMPUS_CODE) AS college_name,Pkg_Admission_Common_Function.Fn_Ad_Get_Course_Name(a.COURSE_CODE) AS course_name,
			a.STUDENT_NAME,a.FATHER_NAME,d.STUDENT_GENDER,d.STUDENT_EMAILID,d.STUDENT_MOBILENUMBER,(SELECT MAX(ACADEMIC_SESSION_ID) FROM EX_CURRENT_SESSION_MSTS) AS year,b.part,
			FN_WEB_ADM_FEE_AMOUNT(b.ACADEMIC_SESSION_ID,b.COURSE_CODE,b.PART,b.FEE_TYPE_CODE,b.FEE_CATEGORY_CODE,a.CAMPUS_CODE) AS Fee_amount,b.FINAL_RESULT,d.REGISTRATION_ID
			FROM ad_student_msts a,VW_WEB_EXAM_QUERY b,WEB_ADM_REGISTRATION_DETAILS d
			WHERE a.SOL_ROLL_NO = b.SOL_ROLL_NO
			AND a.SOL_ROLL_NO = d.PROSPECTUS_ID AND a.SOL_ROLL_NO = '".$_SESSION['STUDENT_PROSPECTUS_ID']."'";

			 

			$cuid = oci_parse(ORACLE_CONN, $sql);
			oci_execute($cuid);
			$row_cu = oci_fetch_array($cuid, OCI_ASSOC + OCI_RETURN_NULLS);
				$registration_id = $row_cu['REGISTRATION_ID'];
				$university_roll_no = $row_cu['UNIVERSITY_ROLL_NO'];
				$college_name = $row_cu['COLLEGE_NAME'];
				$college_code = $row_cu['CAMPUS_CODE'];
				
				
				$course_sel = $row_cu['COURSE_NAME'];
				$course_code = $row_cu['COURSE_CODE'];
				
				$student_name = $row_cu['STUDENT_NAME'];
				$email = $row_cu['STUDENT_EMAILID'];	
				$mobile = $row_cu['STUDENT_MOBILENUMBER'];	
	

	
					
					

				$sql = 'BEGIN Ps_Solexam_Insert_Web(:i_sol_roll_no, :i_payment_string, :o_receipt_no, :o_status, :o_error_message); END;';

				$stid = oci_parse(ORACLE_CONN,$sql);
				$sts = "";
				$msg = "";
				$receipt_no = "";

				oci_bind_by_name($stid, ':i_sol_roll_no', $university_roll_no);
				oci_bind_by_name($stid, ':i_payment_string', $payment_string);

				oci_bind_by_name($stid, ':o_receipt_no', $receipt_no,200);
				oci_bind_by_name($stid, ':o_status', $sts,200);
				oci_bind_by_name($stid, ':o_error_message', $msg,200);



				oci_execute($stid);  // executes and commits





				if($sts == 1){


				$stud_name = str_replace(' ', '%20', $student_name);
				$course_year = str_replace(' ', '%20', $course_sel);

				$url = MY_URL."sms_XML.php?mob=".$mobile."&course_sel=".$course_year."&stud_name=".$stud_name."&university_roll_no=".$university_roll_no."&flag=payment";  

				$json_data=file_get_contents($url); 
				$json_data=simplexml_load_string(trim($json_data));
				$json_data=(array)$json_data;
				//print_r($json_data);	 




				$body = "Dear ".$student_name.", your fee has been received for the ".$course_sel." and University Roll No. : ".$university_roll_no." ";

				//Create a new PHPMailer instance
				$mail = new PHPMailer;
				//Tell PHPMailer to use SMTP
				$mail->isSMTP();
				//Enable SMTP debugging
				// 0 = off (for production use)
				// 1 = client messages
				// 2 = client and server messages
				$mail->SMTPDebug = 0;
				//Ask for HTML-friendly debug output
				$mail->Debugoutput = 'html';
				//Set the hostname of the mail server
				$mail->Host = SMTP_HOST;//'email.du.ac.in';//'smtp.gmail.com';
				//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
				$mail->Port = SMTP_PORT;//25;//465;
				//Set the encryption system to use - ssl (deprecated) or tls
				$mail->SMTPSecure = 'tls';
				//Whether to use SMTP authentication
				$mail->SMTPAuth = true;
				//Username to use for SMTP authentication - use full email address for gmail
				$mail->Username = SMTP_USERNAME;//"admission@sol.du.ac.in";//"dusol.exp@gmail.com";
				//Password to use for SMTP authentication
				$mail->Password = SMTP_PASSWORD;//"M@!ldu5170";//"dusol123";	
					
					
					
				//Set who the message is to be sent from
				$mail->setFrom(SMTP_USERNAME, 'DU Team');

				//Set who the message is to be sent to
				$mail->addAddress($email, 'Acknowledgement of payment from University of Delhi');
				//$mail->addAddress(AR_ADMISSION_EMAIL, 'Acknowledgement of payment from University of Delhi');

				//Set the subject line
				$mail->Subject = "Acknowledgement of payment from University of Delhi";//EMAIL_SUBJECT;

				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				//$mail->msgHTML(file_get_contents('smtp_setting/contents.html'), dirname(__FILE__));

				//Replace the plain text body with one created manually
				//$mail->AltBody = 'This is a plain-text message body';


				$mail->Body    = $body;//'This is the HTML message body <b>in bold!</b>';
				$mail->AltBody = $body;//'This is the body in plain text for non-HTML mail clients';


				//send the message, check for errors
				if (!$mail->send()) {
					//echo "Mailer Error: " . $mail->ErrorInfo;
					
				} else {
					//echo "Successfully done.";
				}





				}
				else{
					$output_message = $msg;

				}
				oci_free_statement($stid);
				oci_close($conn);	

					
				echo "<script>location.href='../view_fee_receipt.php'</script>";	


			
/////////////////////////////////////////////////////////////////////			
			
			
			
			
			
		}
	

	
	
}






$sql = "SELECT a.CAMPUS_CODE, a.COURSE_CODE, a.SOL_ROLL_NO AS University_roll_no,Pkg_Common.FN_GET_CAMPUS_NAME(a.CAMPUS_CODE) AS college_name,Pkg_Admission_Common_Function.Fn_Ad_Get_Course_Name(a.COURSE_CODE) AS course_name,
a.STUDENT_NAME,a.FATHER_NAME,d.STUDENT_GENDER,d.STUDENT_EMAILID,d.STUDENT_MOBILENUMBER,(SELECT MAX(ACADEMIC_SESSION_ID) FROM EX_CURRENT_SESSION_MSTS) AS year,b.part,FN_WEB_ADM_FEE_AMOUNT(b.ACADEMIC_SESSION_ID,b.COURSE_CODE,b.PART,b.FEE_TYPE_CODE,b.FEE_CATEGORY_CODE,a.CAMPUS_CODE) AS Fee_amount,b.FINAL_RESULT,d.REGISTRATION_ID,d.COLLEGE_ROLL_NO
FROM ad_student_msts a,VW_WEB_EXAM_QUERY b,WEB_ADM_REGISTRATION_DETAILS d
WHERE a.SOL_ROLL_NO = b.SOL_ROLL_NO
AND a.SOL_ROLL_NO = d.PROSPECTUS_ID AND a.SOL_ROLL_NO = '".$_SESSION['STUDENT_PROSPECTUS_ID']."'";

 

$cuid = oci_parse(ORACLE_CONN, $sql);
oci_execute($cuid);
$row_cu = oci_fetch_array($cuid, OCI_ASSOC + OCI_RETURN_NULLS);
	
	$registration_id = $row_cu['REGISTRATION_ID'];
	$university_roll_no = $row_cu['UNIVERSITY_ROLL_NO'];
	$college_name = $row_cu['COLLEGE_NAME'];
	$college_code = $row_cu['CAMPUS_CODE'];
	
	
	$course_sel = $row_cu['COURSE_NAME'];
	$course_code = $row_cu['COURSE_CODE'];
	
	$student_name = trim($row_cu['STUDENT_NAME']);
	$father_name = $row_cu['FATHER_NAME'];
	$email = $row_cu['STUDENT_EMAILID'];
	$mobile = $row_cu['STUDENT_MOBILENUMBER'];
	if($row_cu['STUDENT_GENDER'] == 'M')
		$gender = 'Male';
	else if($row_cu['STUDENT_GENDER'] == 'F')
		$gender = 'Female';
	else
		$gender = 'Other';
	
	$college_roll_no = $row_cu['COLLEGE_ROLL_NO'];
	
	
	$fee_amount = $row_cu['FEE_AMOUNT'];
	$final_result = $row_cu['FINAL_RESULT'];
	$academic_session = $row_cu['YEAR'];
	$semester = $row_cu['PART'];
	
	
	
	
	$split_var = "|";
	$payment_string = "1".$split_var.$fee_amount.$split_var."0".$split_var.date('d-M-y').$split_var."2";
	
	//$merc_unq_ref = $email."|".$mobile."|".str_replace(' ','_',$student_name)."|".$course_code;//."|".$student_name; //"aa@a.com|8877665432|54321|B.com|semester3|COL_CODE|STU_NAME";
	$i_adm_late_fee = "0";
	



$merchant_ref_no = $_REQUEST['ORDER_ID'];

	
$sql = 'BEGIN Ps_Web_EXM_Transaction_Dtls(:i_RegistrationID, :i_Payment_string, :i_Email_ID, :i_Sol_mer_ref_no, :i_adm_late_fee); END;';
$stid = oci_parse(ORACLE_CONN,$sql);

oci_bind_by_name($stid, ':i_RegistrationID', $registration_id);
oci_bind_by_name($stid, ':i_Payment_string', $payment_string);
oci_bind_by_name($stid, ':i_Email_ID', $email);
oci_bind_by_name($stid, ':i_Sol_mer_ref_no', $merchant_ref_no);
oci_bind_by_name($stid, ':i_adm_late_fee', $i_adm_late_fee);


oci_execute($stid);  // executes and commits
oci_free_statement($stid);
oci_close($conn);	
    
   

//////////////////////////////////////////////////////////////////////////////////////////////////////////



$checkSum = "";
$paramList = array();

$ORDER_ID = $_POST["ORDER_ID"]; // unique order idmerchant_ref_no
$CUST_ID = $_POST["CUST_ID"]; // university_roll_no
$INDUSTRY_TYPE_ID = $_POST["INDUSTRY_TYPE_ID"];
$CHANNEL_ID = $_POST["CHANNEL_ID"];
$TXN_AMOUNT = $_POST["TXN_AMOUNT"];
$MERC_UNQ_REF = $_POST["MERC_UNQ_REF"];

/*
// Create an array having all required parameters for creating checksum.
$paramList["MID"] = PAYTM_MERCHANT_MID;
$paramList["ORDER_ID"] = $ORDER_ID;
$paramList["CUST_ID"] = $CUST_ID;
$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
$paramList["CHANNEL_ID"] = $CHANNEL_ID;
$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
$paramList["MERC_UNQ_REF"] = $MERC_UNQ_REF;//"email|mobile|college_roll_no"; // this will return as it is
$paramList["THEME"] = "merchant3|DU_Univ";
$paramList["CALLBACK_URL"] = "http://".$_SERVER['SERVER_NAME']."/du_ug_registration/return_paytm.php";
*/
/*
$paramList["MSISDN"] = $MSISDN; //Mobile number of customer
$paramList["EMAIL"] = $EMAIL; //Email ID of customer
$paramList["VERIFIED_BY"] = "EMAIL"; //
$paramList["IS_USER_VERIFIED"] = "YES"; //

*/

//Here checksum string will return by getChecksumFromArray() function.
//$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);

$key = AESKEY;
$mad_field = $ORDER_ID.'|65|'.$TXN_AMOUNT;
$mad_field_ency = aes128Encrypt($mad_field,$key);

$opt_field = $MERC_UNQ_REF;
$opt_field_ency = aes128Encrypt($opt_field,$key);

$UPIVPA = "NA"; // changed on 1-8-2018
$opt_field_ency_new = aes128Encrypt($UPIVPA,$key);

$mad_field_new = $mad_field."|".$opt_field;
$mad_field_ency = aes128Encrypt($mad_field_new,$key);

$ref_field = $ORDER_ID;
$ref_field_ency = aes128Encrypt($ref_field,$key);

$_SESSION['CHK_PAYMENT_STATUS_SESS'] = 'true';

$str1 = "https://eazypay.icicibank.com/EazyPG?merchantid=".MERCHANT_ID."&mandatory fields=".$mad_field_ency."&optional fields=".$opt_field_ency_new."&returnurl=".aes128Encrypt('https://sol.du.ac.in/du_ug_registration/fee_receipt.php',$key)."&Reference No=".$ref_field_ency."&submerchantid=".aes128Encrypt('65',$key)."&transaction amount=".aes128Encrypt($TXN_AMOUNT,$key)."&paymode=".aes128Encrypt('9',$key)."";


//$str1 = "https://eazypay.icicibank.com/EazyPG?merchantid=".MERCHANT_ID."&mandatory fields=".$mad_field_ency."&optional fields=".$opt_field_ency."&returnurl=".aes128Encrypt('https://sol.du.ac.in/du_ug_registration/fee_receipt.php',$key)."&Reference No=".$ref_field_ency."&submerchantid=".aes128Encrypt('65',$key)."&transaction amount=".aes128Encrypt($TXN_AMOUNT,$key)."&paymode=".aes128Encrypt('9',$key)."";


?>
<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
	<center><h1>Please do not refresh this page...</h1></center>
	
<script>
location.href="<?=$str1?>";

</script>	
	
	
	<!--
		<form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">
		<table border="1">
			<tbody>
			<?php
			foreach($paramList as $name => $value) {
				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
			?>
			<input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
			</tbody>
		</table>
		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
	-->
</body>
</html>