<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(1);

$dt=date('Y-m-d');
$now=strtotime('now');
//////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////// checking payment //////////////////////

$sql_c = "SELECT RECEIPT_NO from AD_STUDENT_PAYMENT_dtls where SOL_ROLL_NO = '".$_SESSION['STUDENT_PROSPECTUS_ID']."'";
$cuid_c = oci_parse(ORACLE_CONN, $sql_c);
oci_execute($cuid_c);
$row_cu_c = oci_fetch_array($cuid_c, OCI_ASSOC + OCI_RETURN_NULLS);

if($row_cu_c['RECEIPT_NO'] != ''){
	echo "<center><br><br><h1>Invalid Access!!!</h1><br><br><a href='admin_home.php'><b>Go Back</b></a></center>";
	exit;
}
//////////////////////////////// checking payment //////////////////////


$ORDERID = $_REQUEST['ORDERID'];
$TXNID = $_REQUEST['TXNID'];


include('header.php');
?>

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>FAILED TRANSACTION DETAILS</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />




<!-- common for all pages -->


<div class="row">
<div class="col-lg-12">  
   
<h4>Please note the below ids for future queries in the reference of payment related queries.</h4>
   
<div class="col-lg-10 col-md-10" style="background:#e1ecff;border-radius: 10px;padding: 2%;"  >    

<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>Order ID:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		<?=$ORDERID?>
	</div>	
	</div>
</div>


<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>Transaction ID:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		<?=$TXNID?>
	</div>	
	</div>
</div>

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