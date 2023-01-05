<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(0);

if($_SESSION['ADMIN_LOGGED_IN']){
	alertandreplace("Already Logged In","admin_home.php");
	exit;
}


$uid = $_COOKIE["Univ_Roll_no"];

/*
$hide_box = "";
if($uid == ''){
	$hide_box = "margin-bottom:10%;display:none;'";
	$msg_show = "Not Permitted, Please fill the sign in form first.";
	echo "<script> var popup_show = 'yes';</script>";
	
}
*/


//student_details;
$cuid = oci_parse(ORACLE_CONN, "SELECT * FROM AD_STUDENT_MSTS where SOL_ROLL_NO='".$uid."'");
oci_execute($cuid);
$row_cu = oci_fetch_array($cuid, OCI_ASSOC + OCI_RETURN_NULLS);
	//print_r($row_cu);
	
	$college_sel = $row_cu['CAMPUS_CODE'];
	$course_sel = $row_cu['COURSE_CODE'];
	$u_roll_no = $row_cu['SOL_ROLL_NO'];
	$student_name = $row_cu['STUDENT_NAME'];
	$student_academic_session = $row_cu['ACADEMIC_SESSION_ID'];
	$GENDER = $row_cu['SEX'];

//student_details;



$dt=date('Y-m-d');
$now=strtotime('now');
if($_REQUEST['user_submit']){
	
	$college_sel = $_REQUEST['college_sel'];
	$course_sel = $_REQUEST['course_sel'];
	$prospetcus_id = $_REQUEST['u_roll_no'];
	$student_name = $_REQUEST['student_name'];	
	$College_Roll_No = $_REQUEST['College_Roll_No'];	
	$email = $_REQUEST['email'];	
	$mobile = $_REQUEST['mobile'];	
	$gender = $GENDER;
	$student_admitted = 'P';
	$academic_session = $_REQUEST['student_academic_session'];
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$student_password = rand(1000,9999);
	$sts = "";
	$msg = "";
	
	
$sql = 'BEGIN Ps_Web_Exam_Registration(:i_email_id, :i_Name, :i_mobile, :i_gender, :i_Student_admitted, :i_AcademicSessionId, :i_IPAddress, :i_Prospectus_id, :i_pasword, :i_College_Roll_No, :o_status, :o_message); END;';

$stid = oci_parse(ORACLE_CONN,$sql);



oci_bind_by_name($stid, ':i_email_id', $email);
oci_bind_by_name($stid, ':i_Name', strtoupper($student_name));
oci_bind_by_name($stid, ':i_mobile', $mobile);
oci_bind_by_name($stid, ':i_gender', $gender);
oci_bind_by_name($stid, ':i_Student_admitted', $student_admitted);
oci_bind_by_name($stid, ':i_AcademicSessionId', $academic_session);
oci_bind_by_name($stid, ':i_IPAddress', $ip_address);
oci_bind_by_name($stid, ':i_Prospectus_id', $prospetcus_id);
oci_bind_by_name($stid, ':i_pasword', $student_password);
oci_bind_by_name($stid, ':i_College_Roll_No', $College_Roll_No);

oci_bind_by_name($stid, ':o_status', $sts,200);
oci_bind_by_name($stid, ':o_message', $msg,200);


oci_execute($stid);  // executes and commits

if($sts == 1){
	


$stud_name = str_replace(' ', '%20', $student_name);
$course_year = str_replace(' ', '%20', $course_sel);

$url = MY_URL."sms_XML.php?mob=".$mobile."&email=".$email."&stud_name=".$stud_name."&student_password=".$student_password."&flag=reg";  

$json_data=file_get_contents($url); 
$json_data=simplexml_load_string(trim($json_data));
$json_data=(array)$json_data;
//print_r($json_data);	
	
	
	

$body = "Dear ".$student_name.", Thank you for registering with online fee payment for UG, University of Delhi. Your User ID : ".$email." and Password : ".$student_password." ";

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
$mail->addAddress($email, 'Acknowledgement of registration from University of Delhi');
//$mail->addAddress(AR_ADMISSION_EMAIL, 'Acknowledgement of payment from University of Delhi');

//Set the subject line
$mail->Subject = "Acknowledgement of registration from University of Delhi";//EMAIL_SUBJECT;

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
	//echo '<center><p style="color:red;font-weight:bold;font-size:15px;">Oops! An error occurred. Try sending you message again.</p></center>';
} else {
   //echo '<center><p style="color:green;font-weight:bold;font-size:15px;">Thank you for your message, we will get back to you soon.</p></center>';

}
	
	
	
	
	
	
	
	echo "<script>alert('".$msg."'); location.href='login.php'</script>";
}
else{
	echo "<script>alert('".$msg."')</script>";
	
}
oci_free_statement($stid);
oci_close($conn);	
	
	
	
	
	
	
	
	
	
}




//college_details;

$ctid = oci_parse(ORACLE_CONN, "SELECT CAMPUS_NAME FROM EB_CAMPUS_MSTS where CAMPUS_CODE='".$college_sel."'");
oci_execute($ctid);
$row = oci_fetch_array($ctid, OCI_ASSOC + OCI_RETURN_NULLS);
	//$college_details .= "<option value='".$row['CAMPUS_CODE']."'>".$row['CAMPUS_NAME']."</option>";
	$college_name = $row['CAMPUS_NAME'];
//college_details;


//course_details;
$couese_details = "";
$csid = oci_parse(ORACLE_CONN, "SELECT COURSE_NAME FROM AD_COURSE_MSTS where COURSE_CODE='".$course_sel."'");
oci_execute($csid);
$row_cs = oci_fetch_array($csid, OCI_ASSOC + OCI_RETURN_NULLS);
	//$couese_details .= "<option value='".$row_cs['COURSE_CODE']."'>".$row_cs['COURSE_NAME']."</option>";
	$course_name = $row_cs['COURSE_NAME'];
//course_details;








//include('header.php');
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UG Online fee collection for Academic Session 2016-17</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   
   <link href="assets/css/style.css" rel="stylesheet" />
   
<script>
function value_check_data(){
	var msg = '<div style="margin-top:10px;width:95%;" class="error_msg">	<p class="img"></p>	<p>Required Information is missing. Please correct the filed marked red.</p><div class="cl"></div></div>';
	var flag = true;


       if(document.add_frm.email.value == '' || document.add_frm.email.value.match('^[\s ]*$')){
			document.add_frm.email.style.border='1px solid red';
			flag = false;
		}
		else {
			document.add_frm.email.style.border='1px solid black';
		}
                
	if(document.add_frm.email_confirm.value == '' || document.add_frm.email_confirm.value.match('^[\s ]*$')){
			document.add_frm.email_confirm.style.border='1px solid red';
			flag = false;
		}
		else {
			document.add_frm.email_confirm.style.border='1px solid black';
		}

                    
         if(document.add_frm.email.value != '' && document.add_frm.email_confirm.value !=''){           
                if(document.add_frm.email_confirm.value != document.add_frm.email.value){
					document.add_frm.email.style.border='1px solid red';
                    document.add_frm.email_confirm.style.border='1px solid red';
					flag = false;
				}
				else {
					document.add_frm.email.style.border='1px solid black';
                        document.add_frm.email_confirm.style.border='1px solid black';
				}                   
        }            
		
		if(!flag){
			document.getElementById('result_login').innerHTML = msg;
			
			return false;
		}
		else {
                    document.getElementById("add_frm").submit();
                    return true;
		}
		return false;
	
}




function nospaces(t){
	if(t.value.match(/\s/g)){
	alert('Sorry, you are not allowed to enter any spaces');
	t.value=t.value.replace(/\s/g,'');
	}
}

function isNumberKeyquty(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode == 8 || (charCode >= 48 && charCode <= 57))
           return true;
    return false;
}



</script>   
   
   
</head>
<body>
     
           
          
    <div id="wrapper">
         <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">
                        <!-- <img src="assets/img/logo.png" /> -->
                        <h2 style="color:#fff;"><img id="Image1" src="assets/img/logo_du.png" style="border-width:0px; width:20%;margin-top:-9%;margin-right:7%;" /><b>Registration</b></h2>
                    </a>
                    
                </div>
              
                <span class="logout-spn" >
                    
                   <a href="index.php" style="color:#fff;">UG Online fee collection for Academic Session 2016-17</a><br>
					<a href="index.php" style="color:#fff;float:right;margin-top:1%;">[ Home ]</a>
                </span>
            </div>
        </div>



      

	<div class="wrapper" style="  border-bottom: 1px solid #bbb;   box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);  padding: 6% 0;<?=$hide_box?>" >
		<div class="container">
			<div class="row">
				<div class="module module-login span4 offset4">
				
				
				
					<form class="form-vertical" name="add_frm" action="" method="post" onsubmit="return value_check_data();">
						<div class="module-head">
							<span style="font-size:15px;font-weight:bold;">Registration</span>
						</div>
						<div class="module-body">


							<div class="control-group">
								<div class="controls row-fluid"  style="margin-left:10%">
									<input type="hidden" name="u_roll_no" value="<?=$u_roll_no?>">
                                    <label>University Roll Number:</label>&nbsp;
									<?=$u_roll_no?>
								</div>
							</div>
                                                    <br>
							<div class="control-group">
								<div class="controls row-fluid"  style="margin-left:10%">
									<input type="hidden" name="student_name" value="<?=$student_name?>">	
                                   <label>Name:</label>&nbsp; 
								   <?=$student_name?>
								</div>
							</div>
                            <br> 													
							<div class="control-group">
								<div class="controls row-fluid"  style="margin-left:10%">
									<input type="hidden" name="college_sel" value="<?=$college_sel?>">	
                                    <label>College Name:</label>&nbsp;
									<?=$college_name?>
								</div>
							</div>
                            <br>
							
							<div class="control-group">
								<div class="controls row-fluid"  style="margin-left:10%">
									<input type="hidden" name="course_sel" value="<?=$course_sel?>">	
                                    <label>Course Name:</label>&nbsp;
									<?=$course_name?>
								</div>
							</div>
                            <br>
														<div class="control-group">
								<div class="controls row-fluid">
									<input style="margin-left: 10%;width: 75%;" onkeyup="nospaces(this)" class="span12" type="text" id="College_Roll_No" name="College_Roll_No" placeholder="College Roll No" required="required">
								</div>
							</div>
							<br>
							
							<div class="control-group">
								<div class="controls row-fluid">
                                    <input style="margin-left: 10%;width: 75%;" onkeyup="nospaces(this)" class="span12" type="text" id="email" name="email" placeholder="Email-Id" required="required">
								</div>
							</div>
                                                    <br>  
							<div class="control-group">
								<div class="controls row-fluid">
                                    <input style="margin-left: 10%;width: 75%;" onkeyup="nospaces(this)" class="span12" type="text" id="email_confirm" name="email_confirm" placeholder="Confirm Email-ID" required="required">
								</div>
							</div>
                                                    <br>  
							<div class="control-group">
								<div class="controls row-fluid">
                                    <input style="margin-left: 10%;width: 75%;" maxlength="10" onkeyup="nospaces(this)" class="span12" type="text" id="mobile" name="mobile" placeholder="Mobile Number" required="required" onkeypress="return isNumberKeyquty(event)">
								</div>
							</div>

						</div>
						<div class="module-foot">
							<div class="control-group">
								<div class="controls clearfix">
								
								<span style="font-size:12px;color:red;line-height:18px;"><span id="result_login"></span></span>
								
								<input type="hidden" name="student_academic_session" id="student_academic_session" value="<?=$student_academic_session?>" />
								<input type="hidden" name="user_submit" id="user_submit" value="1" />
								<button type="submit" class="btn btn-primary pull-right">Register</button>
								</div><br>
								<p style="font-size:12px;color:red;line-height:16px;">
									* Password will be generated after registration and will be send in email as well as in the mobile.
								</p>
							</div>
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</div><!--/.wrapper-->
        

    </div>     
<br>	
<?php
include('footer.php');
?>
<!------------------------------------------>
<!-- Modal -->
<!-- set up the modal to start hidden and fade in and out -->
<div id="successBox" class="modal fade" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
<div class="modal-content">
<!-- dialog body -->
<div class="modal-body">
<button type="button" class="close" data-dismiss="modal" onclick="page_reload()">&times;</button>
<center><h3><?=$msg_show?></h3></center>
</div>
<!-- dialog buttons -->
<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="page_reload()">OK</button></div>
</div>
</div>
</div>	
	
<script type="text/javascript">
/*
    $(document).ready(function(){
		
        if(popup_show == 'yes'){
            $("#successBox").modal('show');
        }
    });
*/
    
    function page_reload(){
        location.href='sign_in.php';
    }


$(document).ready(function(){
  $('#email').bind("cut copy paste",function(e) {
  e.preventDefault();
 });
});
$(document).ready(function(){
  $('#email_confirm').bind("cut copy paste",function(e) {
  e.preventDefault();
 });
});
    
</script>


