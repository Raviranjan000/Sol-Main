<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(1);


$dt=date('Y-m-d');
$now=strtotime('now');
//////////////////////////////////////////////////////////////////////////////////////////////////////////

$merchant_ref_no = explode("_",$_REQUEST['ORDERID']);
$university_roll_no = $merchant_ref_no[0];

$fee_amount = $_REQUEST['TXNAMOUNT'];
$TXNID = $_REQUEST['TXNID'];

$split_var = "|";
$payment_string = "1".$split_var.$fee_amount.$split_var.$TXNID.$split_var.date('d-M-y').$split_var."1";



$hide_box = "";	
if($_REQUEST['STATUS'] != 'TXN_SUCCESS'){
	
	echo "<script>location.href='failed_transaction.php?ORDERID=".$_REQUEST['ORDERID']."&TXNID=".$_REQUEST['TXNID']."'</script>";
}
else{
	




$sql = "SELECT a.CAMPUS_CODE, a.COURSE_CODE, a.SOL_ROLL_NO AS University_roll_no,Pkg_Common.FN_GET_CAMPUS_NAME(a.CAMPUS_CODE) AS college_name,Pkg_Admission_Common_Function.Fn_Ad_Get_Course_Name(a.COURSE_CODE) AS course_name,
a.STUDENT_NAME,a.FATHER_NAME,d.STUDENT_GENDER,d.STUDENT_EMAILID,d.STUDENT_MOBILENUMBER,'16' AS year,b.part,
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

$url = MY_URL."sms_XML.php?mob=".$mobile."&course_sel=".$course_year."&stud_name=".$stud_name."&university_roll_no=".$university_roll_no."&flag=payment ";  

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
	echo "Mailer Error: " . $mail->ErrorInfo;
	
} else {
	echo "Successfully done.";
}





}
else{
	$output_message = $msg;

}
oci_free_statement($stid);
oci_close($conn);	


$MERC_UNQ_REF = explode('|',$_REQUEST['MERC_UNQ_REF']);


$email = $MERC_UNQ_REF[0];
$mobile = $MERC_UNQ_REF[1];



	
echo "<script>location.href='view_fee_receipt.php'</script>";	
	
}



?>
 