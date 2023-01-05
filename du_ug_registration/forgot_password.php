<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(0);



$dt=date('Y-m-d');
$now=strtotime('now');

if($_REQUEST['user_submit']){
	
	

$email = $_REQUEST['email'];

$ctid = oci_parse(ORACLE_CONN, "SELECT STUDENT_NAME,STUDENT_MOBILENUMBER,STUDENT_PASSWORD FROM WEB_ADM_REGISTRATION_DETAILS where STUDENT_EMAILID='".$email."'");
oci_execute($ctid);
$row = oci_fetch_array($ctid, OCI_ASSOC + OCI_RETURN_NULLS);

	$student_name = $row['STUDENT_NAME'];
	$mobile = $row['STUDENT_MOBILENUMBER'];
	$student_password = $row['STUDENT_PASSWORD'];
	

if($row != ''){
	


$stud_name = str_replace(' ', '%20', $student_name);

$url = "https://sol.du.ac.in/du_ug_registration/sms_XML.php?mob=".$mobile."&email=".$email."&stud_name=".$stud_name."&student_password=".$student_password."&flag=forgot";  



$json_data=file_get_contents($url); 
$json_data=simplexml_load_string(trim($json_data));
$json_data=(array)$json_data;
//print_r($json_data);	
	
	
	

$body = "Dear ".$student_name."\n\n, Thank you for registering with online fee payment for UG, University of Delhi.<hr>\n You are requested for password recovery.<hr>\n Your User ID : ".$email."<br> Password : ".$student_password." <hr>\n\n Thanks & Regards,\n DU Team.";

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
$mail->addAddress($email, 'Forgot password recovery from University of Delhi');
//$mail->addAddress(AR_ADMISSION_EMAIL, 'Acknowledgement of payment from University of Delhi');

//Set the subject line
$mail->Subject = "Forgot password recovery from University of Delhi";//EMAIL_SUBJECT;

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
	
	
	
	
	
	
	
	echo "<script>alert('Password is send in the given mail as well as in registered mobile'); location.href='login.php'</script>";
}
else{
	echo "<script>alert('Invalid Email.')</script>";
	
}
	
	
	
	
	
	
	
	
	
}



//include('header.php');
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UG Online fee collection for Academic Session <?=date('Y')?>-<?=(date('y')+1);?></title>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   
   <link href="assets/css/style.css" rel="stylesheet" />

   
   
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
                        <h2 style="color:#fff;"><img id="Image1" src="assets/img/logo_du.png" style="border-width:0px; width:20%;margin-top:-9%;margin-right:7%;" /><b>Forgot Password</b></h2>
                    </a>
                    
                </div>
              
                <span class="logout-spn" >
                    
                   <a href="index.php" style="color:#fff;">UG Online fee collection for Academic Session <?=date('Y')?>-<?=(date('y')+1);?></a><br>
					<a href="index.php" style="color:#fff;float:right;margin-top:1%;">[ Home ]</a>
                </span>
            </div>
        </div>



      

	<div class="wrapper" style="  border-bottom: 1px solid #bbb;   box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);  padding: 6% 0;<?=$hide_box?>" >
		<div class="container">
			<div class="row">
				<div class="module module-login span4 offset4">
				
				<span id="result_login"><?=$output_message?></span>
				
					<form class="form-vertical" name="add_frm" action="" method="post" >
						<div class="module-head">
							<span style="font-size:15px;font-weight:bold;">Forgot Password</span>
						</div>
						<div class="module-body">


							
							
							<div class="control-group">
								<div class="controls row-fluid">
                                    <input style="margin-left: 10%;width: 75%;" onkeyup="nospaces(this)" class="span12" type="text" id="email" name="email" placeholder="Email-Id" required="required">
								</div>
							</div>
                             <br>  

						</div>
						<div class="module-foot">
							<div class="control-group">
								<div class="controls clearfix">
								
								<input type="hidden" name="user_submit" id="user_submit" value="1" />
								<button type="submit" class="btn btn-primary pull-right">Submit</button>
								</div><br>
								<p style="font-size:12px;color:red;line-height:16px;">
									* Password will be send in email as well as in the mobile.
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

    $(document).ready(function(){
		
        if(popup_show == 'yes'){
            $("#successBox").modal('show');
        }
    });
    
    
    function page_reload(){
        location.href='sign_in.php';
    }
    
</script>


