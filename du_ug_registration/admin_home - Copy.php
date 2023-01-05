<?php
session_start();
include("config_manual.php");
include("include/functions.php");
	
error_reporting(1);


if(!$_SESSION['ADMIN_LOGGED_IN']){
	alertandreplace("Login First","login.php");
	exit;
}


$dt=date('Y-m-d');
$now=strtotime('now');
$hide_button_box = "";


$sql = 'BEGIN Ps_Web_Du_PART_BATCH_RECORD(:i_SOL_ROLL_NO, :o_PART, :o_RECEIPT_NO); END;';
$stid = oci_parse(ORACLE_CONN,$sql);
$part = "";
$receipt_no = "";
oci_bind_by_name($stid, ':i_SOL_ROLL_NO', $_SESSION['STUDENT_PROSPECTUS_ID']);

oci_bind_by_name($stid, ':o_PART', $part,200);
oci_bind_by_name($stid, ':o_RECEIPT_NO', $receipt_no,200);

oci_execute($stid);  // executes and commits

if($part == '0'){
    $link_show_box = "Payment";
    $link_tab_box = "payment_details.php";
}
else{
    
    	if($receipt_no == 'X'){
		$link_show_box = "Payment";
		$link_tab_box = "payment_details.php";
	}
	else{
		$link_show_box = "Fee-Receipt";
		$link_tab_box = "view_fee_receipt.php";
		$hide_button_box = " style='display:none;'";
	}
    
}


//////////////////////////////// checking payment //////////////////////
/*
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

	if($row_cu_c['RECEIPT_NO'] == ''){
		$link_show_box = "Payment";
		$link_tab_box = "payment_details.php";
	}
	else{
		$link_show_box = "Fee-Receipt";
		$link_tab_box = "view_fee_receipt.php";
		$hide_button_box = " style='display:none;'";
	}
}
//////////////////////////////// checking payment //////////////////////

*/


include('header.php');

?>
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-lg-12">
                     <h2>ADMISSION DASHBOARD</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="alert alert-info">
                             <strong>Welcome !!! <?=$_SESSION['STUDENT_NAME']?></strong>, Your University Roll Number is <?=$_SESSION['STUDENT_PROSPECTUS_ID']?>
                        </div>
                       
                    </div>
                    </div>
                  <!-- /. ROW  --> 
                            <div class="row text-center pad-top">
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                      <div class="div-square">
                           <a href="guidelines.php" >
 <i class="fa fa-circle-o-notch fa-5x"></i>
                      <h4>Guidelines </h4>
                      </a>
                      </div>
                     
                     
                  </div> 
                                
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" <?=$hide_button_box?> >
                      <div class="div-square">
                          <a href="college_details.php" >
 <i class="fa fa-clipboard fa-5x"></i>
                      <h4>View Details</h4>
                      </a>
                      </div>
                     
                     
                  </div>     

<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                      <div class="div-square">
                          <a href="<?=$link_tab_box?>" >
 <i class="fa fa-rocket fa-5x"></i>
                      <h4><?=$link_show_box?></h4>
                      </a>
                      </div>
					 </div> 
				  
                 
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                      <div class="div-square">
                           <a href="logout.php" >
 <i class="fa fa-user fa-5x"></i>
                      <h4>Logout</h4>
                      </a>
                      </div>
                     
                     
                  </div>

              </div>
                 <!-- /. ROW  --> 
                
                

    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
 
<?php 
include('footer.php');
?>
