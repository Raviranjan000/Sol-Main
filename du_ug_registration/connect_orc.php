<?php
echo "Help me";
// Connects to the XE service (i.e. database) on the "localhost" machine //14-1-16-004211
//$conn = oci_connect('dusolrd', 'dusolrd', 'dusolrd'); //username, password, connection_string //10.32.1.70
//echo $conn = oci_connect('phpadmin', 'phpadmin123', 'dusolrd'); //username, password, connection_string //10.32.1.70
echo "HERE-local...";
echo $conn = oci_pconnect('phpadmin', 'phpadmin123', 'soldb'); //username, password, connection_string //10.32.1.71
//echo $conn = oci_connect('dusol', 'sguptadu', 'southdb'); //username, password, connection_string //10.32.1.71
echo "else";
if (!$conn) {
echo "IN";	
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}


$std_details = oci_parse($conn, "SELECT * FROM dusol.ad_student_msts where sol_roll_no='15-1-16-007531'");
//$std_details = oci_parse($conn, "SELECT * FROM dusol.WEB_ADM_REGISTRATION_DETAILS where STUDENT_EMAILID='adipandit201193@gmail.com'");
oci_execute($std_details);
$row_details = oci_fetch_array($std_details, OCI_ASSOC+OCI_RETURN_NULLS);
print_r($row_details);


echo "Done";
?>
