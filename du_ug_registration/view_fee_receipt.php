<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(1);

$dt=date('Y-m-d');
$now=strtotime('now');
//////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////// checking payment //////////////////////

$sql_part = "SELECT a.PART FROM EX_FEE_SUBMISSION_BATCH_HDRS a,EX_FEE_SUBMISSION_BATCH_DTLS b WHERE a.BATCH_ID=b.batch_id AND a.campus_code=b.campus_code AND b.SOL_ROLL_NO =  '".$_SESSION['STUDENT_PROSPECTUS_ID']."'";
$cuid_part = oci_parse(ORACLE_CONN, $sql_part);
oci_execute($cuid_part);
$row_part_c = oci_fetch_array($cuid_part, OCI_ASSOC + OCI_RETURN_NULLS);

if($row_part_c['PART'] == ''){
	$link_show_box = "Payment";
	$link_tab_box = "payment_details.php";
}
else{


	$sql_c = "SELECT a.RECEIPT_NO FROM AD_STUDENT_PAYMENT_dtls a,AD_STUDENT_PAYMENT_HDRS b WHERE  a.RECEIPT_NO = b.RECEIPT_NO AND  a.SOL_ROLL_NO = '".$_SESSION['STUDENT_PROSPECTUS_ID']."' and b.PART = '".$row_part_c['PART']."'" ;
	$cuid_c = oci_parse(ORACLE_CONN, $sql_c);
	oci_execute($cuid_c);
	$row_cu_c = oci_fetch_array($cuid_c, OCI_ASSOC + OCI_RETURN_NULLS);

	if($row_cu_c['RECEIPT_NO'] != ''){
		$receipt_no = $row_cu_c['RECEIPT_NO'];
	}

}
//////////////////////////////// checking payment //////////////////////





include('header.php');
?>

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>FEE RECEIPT PAGE</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />




<!-- common for all pages -->

<span id='msg_box'>
<?=$msg?>
<?=$err?>
</span>


<div class="row">
<div class="col-lg-12">  
   
<div class="form-group" <?=$hide_box?>>
    <iframe src="fee_receipt.php?receipt_no=<?=$receipt_no?>" width="100%" height="700" scrolling="no" frameborder=0></iframe>
</div>
   
   
</div>    

</div>


   
<!-- common for all pages -->


            </div>
        </div>

<!-- common for all pages -->
<?php
include('footer.php');
?>