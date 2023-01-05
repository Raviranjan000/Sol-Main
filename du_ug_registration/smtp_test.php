<?php
//require_once(dirname(__FILE__) . '/../config.php');

//require_once(dirname(__FILE__) . '/config_manual.php'); // Oracle and other setting
require_once(dirname(__FILE__) . '/smtp_setting/PHPMailerAutoload.php'); // SMTP Setting files 


$body = "Acknowledgement of question from SOL server!!! ";
$email = "raviran@gmail.com";

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = 'mail.du.ac.in';//'smtp.office365.com';//'email.du.ac.in';//'smtp.gmail.com';
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;//25;//465;//995
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "finance7@admin.du.ac.in";//"admission@sol.du.ac.in";//"dusol.exp@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "PMFaug#308";//"Xola@3423";//"Ma!ldu2806";//"dusol123";	
	
	
	
//Set who the message is to be sent from
$mail->setFrom("finance7@admin.du.ac.in", 'DU Team');

//Set who the message is to be sent to
$mail->addAddress($email, 'SOL student question forum');
//$mail->addAddress(STUDENT_QUERY_OFFICIAL_EMAIL, 'SOL student question forum');

//Set the subject line
$mail->Subject = "Student question forum";//EMAIL_SUBJECT;

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('smtp_setting/contents.html'), dirname(__FILE__));

//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';


$mail->Body    = $body;//'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = $body;//'This is the body in plain text for non-HTML mail clients';

if(!$mail->Send())
{
echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
echo "Message has been sent...";
}
?>