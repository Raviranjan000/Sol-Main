<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(0);

/*
if($_SESSION['ADMIN_LOGGED_IN']){
	alertandreplace("Already Logged In","admin_home.php");
	exit;
}
*/


if($_SESSION['CHK_PAYMENT_STATUS_SESS'] == 'true'){ // New Chganges at 2-aug-2017
	
	if($_REQUEST['Response_Code'] == 'E000'){
		
		
			//$merchant_ref_no = explode("_",$order_id);
			$university_roll_no = $_SESSION['STUDENT_PROSPECTUS_ID'];//$merchant_ref_no[0];

			$fee_amount = $_REQUEST['Transaction_Amount'];
			$pay_date = date('d-M-y',strtotime($_REQUEST['Transaction_Date'])); // date('d-M-y')
			$TXNID = $_REQUEST['Unique_Ref_Number'];//$responseParamList['TXNID'];

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
		
			//echo "<script>location.href='view_fee_receipt.php'</script>";
			
	}
	
	
}


//////////////////////////////// checking payment //////////////////////
$sql = 'BEGIN Ps_Web_Du_PART_BATCH_RECORD(:i_SOL_ROLL_NO, :o_PART, :o_RECEIPT_NO); END;';
$stid = oci_parse(ORACLE_CONN,$sql);
$part = "";
$receipt_no = "";
oci_bind_by_name($stid, ':i_SOL_ROLL_NO', $_SESSION['STUDENT_PROSPECTUS_ID']);

oci_bind_by_name($stid, ':o_PART', $part,200);
oci_bind_by_name($stid, ':o_RECEIPT_NO', $receipt_no,200);

oci_execute($stid);  // executes and commits

if($receipt_no == 'X'){
	echo "<center><br><br><h1>Invalid Access!!!</h1><br><br><a href='admin_home.php'><b>Go Back</b></a></center>";
	exit;
}
//////////////////////////////// checking payment //////////////////////




$sql = "SELECT sm.sol_roll_no,sm.student_name,DECODE(sm.sex,'F','Female','M','Male','Other') AS Gender,sm.father_name, sph.created_by ,sph.receipt_no, 
 TO_CHAR(sph.payment_date,'dd/mm/yyyy') AS payment_Date,Pkg_Admission_Common_Function.Fn_Ad_Get_Part_Name(sat.part) AS part, 
 Pkg_Admission_Common_Function.Fn_Ad_Get_Course_Name(sm.course_code) AS COURSE_NAME,wad.COLLEGE_ROLL_NO,sm.EMAIL_ID,sm.TEL_NO,spd.AMOUNT,spd.DD_NO AS ref_no,SUBSTR(spd.DD_ISSUE_DATE,1,10) AS pmt_Date,
 Pkg_Admission_Common_Function.fn_ad_get_pay_mode_name(spd.PAYMENT_MODE) AS mode_of_payment , Pkg_Admission_Common_Function.fn_ad_get_bank_name(spd.BANK_CODE) AS bank_name,
 UPPER(Pkg_Common.FN_GET_CAMPUS_NAME(sm.CAMPUS_CODE)) AS college_name
 FROM  AD_STUDENT_MSTS sm,AD_STUDENT_PAYMENT_HDRS sph,AD_STUD_ADMISSION_TRNS sat,AD_STUDENT_PAYMENT_dtls spd ,WEB_ADM_REGISTRATION_DETAILS wad
 WHERE  sm.campus_code = sph.campus_code 
 AND    sm.sol_roll_no = sph.sol_roll_no 
 AND sat.campus_code = sph.campus_code 
 AND sat.sol_roll_no = sph.sol_roll_no 
 AND    sat.academic_session_id = sph.academic_session_id 
 AND    sat.campus_code = sm.campus_code 
 AND    sat.sol_roll_no = sm.sol_roll_no 
 AND 	sph.RECEIPT_NO = spd.RECEIPT_NO
 AND 	sph.SOL_ROLL_NO = spd.SOL_ROLL_NO
 AND 	wad.PROSPECTUS_ID = sm.SOL_ROLL_NO
 AND    sph.receipt_no = sat.FEE_RECEIPT_NO
 AND    sat.exam_type = 'A' 
 AND    sph.campus_code = '".$_SESSION['CAMPUS_CODE']."' 
 AND    sph.receipt_no = '".$receipt_no."' ";

 

$cuid = oci_parse(ORACLE_CONN, $sql);
oci_execute($cuid);
$row_cu = oci_fetch_array($cuid, OCI_ASSOC + OCI_RETURN_NULLS);

	
	$SOL_ROLL_NO = $row_cu['SOL_ROLL_NO'];
	$STUDENT_NAME = $row_cu['STUDENT_NAME'];
	$GENDER = $row_cu['GENDER'];
	$FATHER_NAME = $row_cu['FATHER_NAME'];
	$RECEIPT_NO = $row_cu['RECEIPT_NO'];
	$PAYMENT_DATE = $row_cu['PAYMENT_DATE'];
	$PART = $row_cu['PART'];
	$COURSE_NAME = $row_cu['COURSE_NAME'];
	$COLLEGE_ROLL_NO = $row_cu['COLLEGE_ROLL_NO'];
	$EMAIL_ID = $row_cu['EMAIL_ID'];
	$TEL_NO = $row_cu['TEL_NO'];
	$AMOUNT = $row_cu['AMOUNT'];
	$REF_NO = $row_cu['REF_NO'];
	$PMT_DATE = $row_cu['PMT_DATE'];
	$MODE_OF_PAYMENT = $row_cu['MODE_OF_PAYMENT'];
	$BANK_NAME = $row_cu['BANK_NAME'];
	$COLLEGE_NAME = $row_cu['COLLEGE_NAME'];


	
	
?>



<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
   <title>Fee Receipt</title>
    <style type="text/css">
        .headerText
        {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 13px;
        }
        .bigBoldText
        {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 11px;
            font-weight: bold;
        }
        .bigText
        {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 11px;
        }
        .smBoldText
        {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10px;
            font-weight: bold;
        }
        .smText
        {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10px;
        }       
        </style>
</head>
<body>
	<span style="float:right;margin-right:10%;margin-top:-1%;"><a href="javascript:void()" onclick="window.print()">Print</a></span>
	<br>

    <form id="form1" runat="server">   
						<table width='850px' border='0' cellspacing='0' cellpadding='0' style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px; text-align:left;background: url('assets/img/bgdu2.png') no-repeat top left;">
            <tr>
                <td style="width:20%;">                
                
                </td>
                <td style="font-size: 13px; text-align:center; width:50%;">
                    <b>Delhi University</b><br />
                    <b><?=$COLLEGE_NAME?></b><br />
					<b>Delhi</b>
                </td>
                <td style="font-size: 10px; vertical-align:bottom; width:30%;">
                    <b>Report Date:</b> <?=date('d-M-Y')?>
                    <br />
                    <b>Report Time:</b> <?=date('H:i:s')?>
                </td>
            </tr>
            <tr align='center'>
                <td colspan='3' style='border-bottom: 2px solid #000000'>&nbsp;</td>
            </tr>
            <tr style="height:25px;">
                <td>&nbsp;</td>
                <td style="font-size: 13px; font-weight:bold; text-align:center;">
                    <u><b>Fee Receipt </b></u>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr style="height:25px;">                
                <td style="text-align:left;">
                    
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                
            </tr>
            <tr>
                <td colspan='3'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr style="height:25px;">
                            <td width='15%'>&nbsp;</td>
                            <td align='center' width='3%'>&nbsp;</td>
                            <td width='28%'>&nbsp;</td>
                            <td width='7%'>&nbsp;</td>
                            <td align='center' width='3%'>&nbsp;</td>
                            <td width='9%'>&nbsp;</td>
                            <td style="font-size: 13px; font-weight: bold;" width='12%'>Receipt No</td>
                            <td align='center' width='3%'>:</td>
                            <td width='20%' style="font-size: 10px;"> <?=$RECEIPT_NO?>                   
                            </td>
                        </tr>
                        <tr style="height:25px;">
                            <td style="font-size: 11px; font-weight: bold;">University Roll No</td>
                            <td align='center'>:</td>
                            <td style="font-size: 10px;"> <?=$SOL_ROLL_NO?>
                    
                            </td>
                            <td style="font-size: 11px; font-weight: bold;"></td>
                            <td align='center'></td>
                            <td style="font-size: 10px;"> 
                    
                            </td>
							<td style="font-size: 11px; font-weight: bold;">Admission Date</td>
                            <td align='center'>:</td>
                            <td style="font-size: 10px;"> <?=$PAYMENT_DATE?>
                    
                            </td>
                        </tr>
                        <tr style="height:25px;">
                            <td style="font-size: 11px; font-weight: bold;">Course</td>
                            <td align='center'>:</td>
                            <td style="font-size: 10px;"> <?=$COURSE_NAME?>
                    
                            </td>
                            <td style="font-size: 11px; font-weight: bold;"></td>
                            <td align='center'></td>
                            <td style="font-size: 10px;"> 
                    
                            </td>
                            <td style="font-size: 11px; font-weight: bold;"></td>
                            <td align='center'></td>
                            <td style="font-size: 10px;">
                    
                            </td>
                        </tr>
                        <tr style="height:25px;">
                            <td style="font-size: 11px; font-weight: bold;">Name</td>
                             <td align='center'>:</td>
                            <td colspan='4' style="font-size: 10px;">
                               <?=$STUDENT_NAME?>          
                            </td>
                            <td style="font-size: 11px; font-weight: bold;">College Roll No.</td>
                             <td align='center'>:</td>
                            <td style="font-size: 10px;">
                                <?=$COLLEGE_ROLL_NO?>
                            </td>
                        </tr>
                        <tr style="height:25px;">
                            <td style="font-size: 11px; font-weight: bold;">
                                Father's Name
                            </td>
                             <td align='center'>:</td>
                            <td colspan='4' style="font-size: 10px;">
                                <?=$FATHER_NAME?>
                    
                            </td>
    
                            <td style="font-size: 11px; font-weight: bold;">
                                Gender
                            </td>
                            <td align='center'>:</td>
                            <td style="font-size: 10px;">
                                <?=$GENDER?>
                    
                            </td>
                        </tr>
                        <tr style="height:25px;">
                            <td style="font-size: 11px; font-weight: bold;">
                            Semester    
                            </td>
                            <td align='center'>:</td>
                            <td colspan='4' style="font-size: 10px;">
                            <?=$PART?>    
                    
                            </td>
                            <td style="font-size: 11px; font-weight: bold;">
                             
                            </td>
                            <td align='center'></td>
                            <td style="font-size: 10px;">
                                
                    
                            </td>
                          </tr>
 
                         <tr style="height:25px;">
                            <td style="font-size: 11px; font-weight: bold;">
                                Email Id
                            </td>
                             <td align='center'>:</td>
                            <td colspan='4' style="font-size: 10px;">
                                <?=$EMAIL_ID?>
                    
                            </td>
                            <td style="font-size: 11px; font-weight: bold;">
                                Phone No
                            </td>
                             <td align='center'>:</td>
                            <td style="font-size: 10px;">
                                <?=$TEL_NO?>
                    
                            </td>
                        </tr>
                        
                    </table>
                </td>
            </tr> 
            <tr>
                <td colspan='3'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td style="width:90%;">
                                <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                        <td colspan='5' style="font-size: 13px; font-weight: bold; height:50px; vertical-align:middle;">                                
                                            Payment Details
                                        </td>
                                    </tr>
                                    <tr style="height:25px;">
                                        <td width='20%' style="font-size: 11px; font-weight: bold;">Pay Mode</td>
                                        <td width='15%' style="font-size: 11px; font-weight: bold;" align='center'>Refe.No.</td>
                                        <td width='15%' align='center' style="font-size: 11px; font-weight: bold;">Trans. Date</td>
                                        <td width='35%' style="font-size: 11px; font-weight: bold;">Bank</td>
                                        <td width='15%' style="font-size: 11px; font-weight: bold; text-align:right">Amount</td>
                                    </tr>
                                   
                                    <tr align='center'>
                                        <td colspan='5' style='border-bottom: 2px solid #000000'>&nbsp;</td>
                                    </tr>
                                    <tr style="height:20px;">
                                        <td width='20%' style="font-size: 11px; font-weight: bold;"><?=$MODE_OF_PAYMENT?></td>
                                        <td width='15%' style="font-size: 11px; font-weight: bold;" align='center'><?=$REF_NO?></td>
                                        <td width='15%' align='center' style="font-size: 11px; font-weight: bold;"><?=$PMT_DATE?></td>
                                        <td width='35%' style="font-size: 11px; font-weight: bold;"><?=$BANK_NAME?></td>
                                        <td width='15%' style="font-size: 11px; font-weight: bold; text-align:right"><?=$AMOUNT?></td>                        
                                    </tr>
                                    <tr>
                                        <td colspan='5' style='border-bottom: 2px solid #000000'>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan='5' style="font-size: 10px; text-align:justify;">
                                            <b>Note:</b> Admission is provisional subject to the verification of original documents
                                            by the office .Since this is computer generated receipt, no seal/signature are required.
                                            It is advised to check the details on the receipt. In case of any discrepancy, please contact your concerned college.<br /><br />
                                                                                        
                                        </td>
                                    </tr>
                                    
                                </table>
                            </td>
                            <td style="width:10%;"></td>
                        </tr>
                    </table>                    
                </td>
             </tr>                       
        </table>        
        
    
    </form>
</body>
</html>


