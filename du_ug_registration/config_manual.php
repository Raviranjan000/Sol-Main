<?php  

// ORACLE credentials
define("ORACLE_USERNAME", "du");
define("ORACLE_PASSWORD", "du123");
define("ORACLE_CONNECTION_STRING", "DU");// Test - SOLTEST // live - soldb // Live - DU


define("ACADEMIC_SESSION", "16"); // du test


/*
define("ORACLE_USERNAME", "phpadmin");
define("ORACLE_PASSWORD", "phpadmin123");
define("ORACLE_CONNECTION_STRING", "soldb"); // live
//define("ORACLE_CONNECTION_STRING", "solrd"); // test
//define("ORACLE_CONNECTION_STRING", "dusolrd");
*/
// Connects to the XE service (i.e. database) on the "localhost" machine //14-1-16-004211
$conn = oci_pconnect(ORACLE_USERNAME, ORACLE_PASSWORD, ORACLE_CONNECTION_STRING); //username, password, connection_string //10.32.1.70
if (!$conn) {
    $e = oci_error();
    //trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

define("ORACLE_CONN", $conn);
define("ORACLE_TABLE_PREFIX", "dusol"); // live


//SMTP CREDENTILS DETAILS
define("SMTP_USERNAME", "finance7@admin.du.ac.in"); //ugfeecollection@pns.du.ac.in
define("SMTP_PASSWORD", "PMFaug#308"); // u3DWTt5cKU //Xola@325#neW(OLD)
define("SMTP_HOST", "mail.du.ac.in"); // mail.pns.du.ac.in
define("SMTP_PORT", "587");
define("EMAIL_SUBJECT", "Registration");

define("SMTP_SENT_FROM", "finance7@admin.du.ac.in");
define("SMTP_SENT_FROM_TEXT", "DU Team");

//define("AESKEY", "1302272719605001");
//define("MERCHANT_ID", "131960");
define("AESKEY", "1802273157401004"); // changed on 1-8-2018
define("MERCHANT_ID", "185746"); // changed on 1-8-2018


/*
//SMTP CREDENTILS DETAILS
define("SMTP_USERNAME", "admission@sol.du.ac.in");
define("SMTP_PASSWORD", "Ma!ldu2806");
define("SMTP_HOST", "email.du.ac.in");
define("SMTP_PORT", "25");
define("EMAIL_SUBJECT", "Feedback");

define("SMTP_SENT_FROM", "admission@sol.du.ac.in");
define("SMTP_SENT_FROM_TEXT", "SOL");
*/



//define('MY_URL','http://'.$_SERVER['SERVER_NAME'].'/du_ug_registration/');
define('MY_URL','http://10.32.1.11/du_ug_registration/');



require_once(dirname(__FILE__) . '/smtp_setting/PHPMailerAutoload.php'); // SMTP Setting files 


?>