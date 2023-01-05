<?php
/*

- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.

*/
/*
print_r($_SESSION);

include("../../config_manual.php");
include("../../include/functions.php");


$sql_c = "select MID,MERCHANT_KEY from PAYTM_MSTS where campus_code='".$_SESSION['CAMPUS_CODE']."'";
$cuid_c = oci_parse(ORACLE_CONN, $sql_c);
oci_execute($cuid_c);
$row_c = oci_fetch_array($cuid_c, OCI_ASSOC + OCI_RETURN_NULLS);
*/ 
 
 
define('PAYTM_ENVIRONMENT', 'PROD'); //  TEST
define('PAYTM_MERCHANT_KEY', $_SESSION['MERCHANT_KEY']); //Change this constant's value with Merchant key downloaded from portal    ====     yo_iVJKKC#4UeTL4
define('PAYTM_MERCHANT_MID', $_SESSION['MID']); //Change this constant's value with MID (Merchant ID) received from Paytm =====  Delhi493922220665686

///define('PAYTM_MERCHANT_KEY', '!wU2j9NltJ!DRNJ7'); //Change this constant's value with Merchant key downloaded from portal    ====     yo_iVJKKC#4UeTL4
//define('PAYTM_MERCHANT_MID', 'DUacha23115899215522'); //Change this constant's value with MID (Merchant ID) received from Paytm =====  Delhi493922220665686

/*
define('PAYTM_ENVIRONMENT', 'PROD'); // 
define('PAYTM_MERCHANT_KEY', 'c2B9F18K4LMwRlKE'); //Change this constant's value with Merchant key downloaded from portal
define('PAYTM_MERCHANT_MID', 'Delhiu60151077561535'); //Change this constant's value with MID (Merchant ID) received from Paytm
*/

define('PAYTM_MERCHANT_WEBSITE', 'DelhiUniversity'); //Change this constant's value with Website name received from Paytm

$PAYTM_DOMAIN = "pguat.paytm.com";
if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_DOMAIN = 'secure.paytm.in';
}

define('PAYTM_REFUND_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/REFUND');
define('PAYTM_STATUS_QUERY_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/TXNSTATUS');
define('PAYTM_TXN_URL', 'https://'.$PAYTM_DOMAIN.'/oltp-web/processTransaction');

?>