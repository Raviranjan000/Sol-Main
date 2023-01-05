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
$hide_box = "";
$ACADEMIC_SESSION_ID = '19';

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


	$sql_c = "SELECT a.RECEIPT_NO FROM AD_STUDENT_PAYMENT_dtls a,AD_STUDENT_PAYMENT_HDRS b WHERE  a.RECEIPT_NO = b.RECEIPT_NO AND  a.SOL_ROLL_NO = '".$_SESSION['STUDENT_PROSPECTUS_ID']."' and b.PART = '".$row_part_c['PART']."' AND ACADEMIC_SESSION_ID='".$ACADEMIC_SESSION_ID."'";
	$cuid_c = oci_parse(ORACLE_CONN, $sql_c);
	oci_execute($cuid_c);
	$row_cu_c = oci_fetch_array($cuid_c, OCI_ASSOC + OCI_RETURN_NULLS);

	if($row_cu_c['RECEIPT_NO'] != ''){
		echo "<center><br><br><h1>Invalid Access!!!</h1><br><br><a href='admin_home.php'><b>Go Back</b></a></center>";
		exit;
	}

}
//////////////////////////////// checking payment //////////////////////


$sql_w = "SELECT count(*) as CNT FROM WEB_EXAM_FORM WHERE SOL_ROLL_NO= '".$_SESSION['STUDENT_PROSPECTUS_ID']."'";
$cuid_w = oci_parse(ORACLE_CONN, $sql_w);
oci_execute($cuid_w);
$row_cu_w = oci_fetch_array($cuid_w, OCI_ASSOC + OCI_RETURN_NULLS);

if($row_cu_w['CNT'] == '0'){
	$hide_box = 'display:none;';	
	$msg_show = "Not Permitted,<br> Please Save the View Details first.";
	echo "<script> var popup_show = 'yes';</script>";
}






$btnName='btnSubmit';
$btnValue='Save';


$sql = "SELECT a.CAMPUS_CODE, a.COURSE_CODE, a.SOL_ROLL_NO AS University_roll_no,Pkg_Common.FN_GET_CAMPUS_NAME(a.CAMPUS_CODE) AS college_name,Pkg_Admission_Common_Function.Fn_Ad_Get_Course_Name(a.COURSE_CODE) AS course_name,
a.STUDENT_NAME,a.FATHER_NAME,d.STUDENT_GENDER,d.STUDENT_EMAILID,d.STUDENT_MOBILENUMBER,(SELECT MAX(ACADEMIC_SESSION_ID) FROM EX_CURRENT_SESSION_MSTS) AS year,b.part,
FN_WEB_ADM_FEE_AMOUNT(b.ACADEMIC_SESSION_ID,b.COURSE_CODE,b.PART,b.FEE_TYPE_CODE,b.FEE_CATEGORY_CODE,a.CAMPUS_CODE) AS Fee_amount,b.FINAL_RESULT,d.REGISTRATION_ID,d.COLLEGE_ROLL_NO
FROM ad_student_msts a,VW_WEB_EXAM_QUERY b,WEB_ADM_REGISTRATION_DETAILS d
WHERE a.SOL_ROLL_NO = b.SOL_ROLL_NO
AND a.SOL_ROLL_NO = d.PROSPECTUS_ID AND a.SOL_ROLL_NO = '".$_SESSION['STUDENT_PROSPECTUS_ID']."'";

 

$cuid = oci_parse(ORACLE_CONN, $sql);
oci_execute($cuid);
$row_cu = oci_fetch_array($cuid, OCI_ASSOC + OCI_RETURN_NULLS);


$mob_count = strlen($row_cu['STUDENT_MOBILENUMBER']);

if($row_cu == ''){
	$msg = "Fees not define!  Please contact your college/Department";	
	$hide_div = "display:none;";
}
else if($mob_count < 10){
	$msg = "Mobile Number not correct, Please update your mobile number from <a href='college_details.php'><b>View Details</b></a> page";	
	$hide_div = "display:none;";
}
else{
	$hide_div = "";
	
	$registration_id = $row_cu['REGISTRATION_ID'];
	$university_roll_no = $row_cu['UNIVERSITY_ROLL_NO'];
	$college_name = substr($row_cu['COLLEGE_NAME'],0,90);
	
	$college_name = str_replace('(','_',$college_name);
	$college_name = str_replace(')','_',$college_name);
	$college_name = str_replace('~','_',$college_name);
	$college_name = str_replace(',','_',$college_name);	
	
	
	
	$college_code = $row_cu['CAMPUS_CODE'];
	
	
	$course_sel = $row_cu['COURSE_NAME'];
	$course_code = trim($row_cu['COURSE_CODE']);
	
	$student_name = trim($row_cu['STUDENT_NAME']);
		
	$student_name = str_replace('(','_',$student_name);
	$student_name = str_replace(')','_',$student_name);
	$student_name = str_replace('~','_',$student_name);
	$student_name = str_replace(',','_',$student_name);
	
	
	$father_name = $row_cu['FATHER_NAME'];
	$email = trim($row_cu['STUDENT_EMAILID']);
	$mobile = trim($row_cu['STUDENT_MOBILENUMBER']);
	if($row_cu['STUDENT_GENDER'] == 'M')
		$gender = 'Male';
	else if($row_cu['STUDENT_GENDER'] == 'F')
		$gender = 'Female';
	else
		$gender = 'Other';
	
	$college_roll_no = $row_cu['COLLEGE_ROLL_NO'];
	
	
	$fee_amount = $row_cu['FEE_AMOUNT'];
	$final_result = $row_cu['FINAL_RESULT'];
	$academic_session = $row_cu['YEAR'];
	$semester = $row_cu['PART'];

	$merchant_ref_no = $university_roll_no."_".$now;
	
	$split_var = "|";
	$payment_string = "1".$split_var.$fee_amount.$split_var."0".$split_var.date('d-M-y').$split_var."1";
	
	
        
        $merc_unq_ref = str_replace(' ',' ',$student_name)."|".$university_roll_no."|".$college_name."|".$course_code."|".$email."|".$mobile;
	//$merc_unq_ref = $email."|".$mobile."|".str_replace(' ','_',$student_name)."|".$course_code;//."|".str_replace(' ','_',$student_name); //"aa@a.com|8877665432|54321|B.com|semester3|COL_CODE|STU_NAME";
	$i_adm_late_fee = "0";

	
	
}





//////////////////////////////////////////////////////////////////////////////////////////////////////////








include('header.php');
?>

<script>
function chk(){
 var str = '';

	if(document.frm_adduser.v_email.value == '' || document.frm_adduser.v_email.value.match('^[\s ]*$')){
		str += 'v_email, ';
	}

	if(document.frm_adduser.v_name.value == '' || document.frm_adduser.v_name.value.match('^[\s ]*$')){
		str += 'v_name, ';
	}
    


	if(str != ''){
		document.getElementById('msg_box').innerHTML = "<?=errorMsgShow('Please enter details marked with *')?>";
		return false;
	}
	else{
		return true;
	}
}

function isNumberKeyquty(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode == 8 || (charCode >= 48 && charCode <= 57))
           return true;
    return false;
}

function nospaces(t){
	if(t.value.match(/\s/g)){
	alert('Sorry, you are not allowed to enter any spaces');
	t.value=t.value.replace(/\s/g,'');
	}
}


</script>



        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>PAYMENT DETAILS</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />




<!-- common for all pages -->

<center>
<span id='msg_box'>
<?=$msg?>
<?=$err?>
</span>
</center>

<div class="row">
<div class="col-lg-1">  
   &nbsp; 
</div>    


<div class="col-lg-10 col-md-10" style="background:#e1ecff;border-radius: 10px;padding: 2%;<?=$hide_div?><?=$hide_box?>"  >    



<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>Order ID:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		<?=$merchant_ref_no?>
	</div>	
	</div>
</div>

<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>University Roll Number:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		<?=$university_roll_no?>
	</div>	
	</div>
</div>


<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>College Name:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		<?=$college_name?>
	</div>	
	</div>
</div>

<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>Student Name:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		<?=$student_name?>
	</div>	
	</div>
</div>

<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>Course:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		<?=$course_sel?>
	</div>	
	</div>
</div>


<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>Semester:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		<?=$semester?>
	</div>	
	</div>
</div>

<div class="row">
	<div class="form-group">
	<div class="col-lg-4 col-md-4">
		<label>Fee Amount:</label>
	</div>
	<div class="col-lg-8 col-md-8">	
		Rs. <?=$fee_amount?>
	</div>	
	</div>
</div>

<hr>

<div class="form-group">
    <center><input class="btn btn-primary" type="submit" value="Pay Fee" name="IDBIpaymentBtn" onclick="document.myform.submit();" ></center>
	
</div>

<div class="form-group">
<b>Note:</b><br>
<p style="font-size:12px;color:red;line-height:16px;">
* In case of double payment, the amount will be refunded to the same bank through which the fee has been paid by the students. For refund of double payment the students shall take up the matter with the customer care division of their banker.
</p>
</div>
<div class="form-group">
<p style="font-size:12px;color:red;line-height:16px;">
* To avoid double payment of fee, students are advised to wait for at least four hours before making second/subsequent attempt for payment after failure of first attempt.
</p>
</div>


</div>
</div>


<form name="myform" method="post" action="PaytmKit/pgRedirect.php">

<input type="hidden" id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="<?=$merchant_ref_no?>">
<input type="hidden" id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?=$university_roll_no?>">
<input type="hidden" id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Education"> <!--   Retail -->
<input type="hidden" id="CHANNEL_ID" tabindex="4" maxlength="12" size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">
<input type="hidden" title="TXN_AMOUNT" tabindex="10"	type="text" name="TXN_AMOUNT" value="<?=$fee_amount?>">
<input type="hidden" title="MERC_UNQ_REF" tabindex="20" type="text" name="MERC_UNQ_REF" value="<?=str_replace(' ','',$merc_unq_ref)?>">
		
</form>

   
<!-- common for all pages -->


            </div>
        </div>

<!-- common for all pages -->
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
        location.href='college_details.php';
    }
    
</script>

