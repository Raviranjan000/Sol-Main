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
		$hide_button = " style='display:none;'";
		$read_only = "disabled";
	}

}
//////////////////////////////// checking payment //////////////////////



echo "<script> var popup_show = 'no';</script>";
echo "<script> var popup_show_false = 'no';</script>";

$btnName='btnSubmit';
$btnValue='Save';



$sql_f = "SELECT a.FEE_SUBMISSION_LAST_DATE FROM EX_FEE_SUBMISSION_BATCH_HDRS a,EX_FEE_SUBMISSION_BATCH_DTLS b WHERE a.BATCH_ID=b.batch_id and a.campus_code=b.campus_code AND b.SOL_ROLL_NO = '".$_SESSION['STUDENT_PROSPECTUS_ID']."' AND b.ACADEMIC_SESSION_ID=(SELECT MAX(ACADEMIC_SESSION_ID) FROM EX_CURRENT_SESSION_MSTS)";
$cuid_f = oci_parse(ORACLE_CONN, $sql_f);
oci_execute($cuid_f);
$row_cf = oci_fetch_array($cuid_f, OCI_ASSOC + OCI_RETURN_NULLS);

if($row_cf['FEE_SUBMISSION_LAST_DATE'] == ''){
    $msg = "Your Fees not defined, please contact your college/department for further query.";	
    echo "<script> var popup_show_false = 'yes';</script>";
    
}
else if(strtotime(date('Y-m-d')) > strtotime($row_cf['FEE_SUBMISSION_LAST_DATE'])){
	$msg = "Last date was ".$row_cf['FEE_SUBMISSION_LAST_DATE'].",  now contact your college/department for further query.";	
	echo "<script> var popup_show_false = 'yes';</script>";
}
else{



	$sql = "SELECT a.CAMPUS_CODE,a.COURSE_CODE,a.SOL_ROLL_NO AS University_roll_no,Pkg_Common.FN_GET_CAMPUS_NAME(a.CAMPUS_CODE) AS college_name,Pkg_Admission_Common_Function.Fn_Ad_Get_Course_Name(a.COURSE_CODE) AS course_name,
	a.STUDENT_NAME,a.FATHER_NAME,d.STUDENT_GENDER,d.STUDENT_EMAILID,d.STUDENT_MOBILENUMBER,(SELECT MAX(ACADEMIC_SESSION_ID) FROM EX_CURRENT_SESSION_MSTS) AS year,b.part,
	FN_WEB_ADM_FEE_AMOUNT(b.ACADEMIC_SESSION_ID,b.COURSE_CODE,b.PART,b.FEE_TYPE_CODE,b.FEE_CATEGORY_CODE,a.CAMPUS_CODE) AS Fee_amount,b.FINAL_RESULT,b.FEE_TYPE_CODE,b.FEE_CATEGORY_CODE
	FROM ad_student_msts a,VW_WEB_EXAM_QUERY b,WEB_ADM_REGISTRATION_DETAILS d
	WHERE a.SOL_ROLL_NO = b.SOL_ROLL_NO
	AND a.SOL_ROLL_NO = d.PROSPECTUS_ID AND a.SOL_ROLL_NO = '".$_SESSION['STUDENT_PROSPECTUS_ID']."'";

	 

	$cuid = oci_parse(ORACLE_CONN, $sql);
	oci_execute($cuid);
	$row_cu = oci_fetch_array($cuid, OCI_ASSOC + OCI_RETURN_NULLS);


	if($row_cu == ''){
		$msg = "Fees not define!  Please contact your college/Department";	
		echo "<script> var popup_show_false = 'yes';</script>";
	}
	else{

		$university_roll_no = $row_cu['UNIVERSITY_ROLL_NO'];
		$college_name = $row_cu['COLLEGE_NAME'];
		$college_code = $row_cu['CAMPUS_CODE'];
		$course_sel = $row_cu['COURSE_NAME'];
		$course_code = $row_cu['COURSE_CODE'];
		$student_name = $row_cu['STUDENT_NAME'];
		$father_name = $row_cu['FATHER_NAME'];
		$email = $row_cu['STUDENT_EMAILID'];
		$mobile = $row_cu['STUDENT_MOBILENUMBER'];
		if($row_cu['STUDENT_GENDER'] == 'M')
			$gender = 'Male';
		else if($row_cu['STUDENT_GENDER'] == 'F')
			$gender = 'Female';
		else
			$gender = 'Other';
		
		
		$fee_amount = $row_cu['FEE_AMOUNT'];
		$final_result = $row_cu['FINAL_RESULT'];
		$academic_session = $row_cu['YEAR'];
		$semester = $row_cu['PART'];
		$fee_type_code = $row_cu['FEE_TYPE_CODE'];
		$fee_category_code = $row_cu['FEE_CATEGORY_CODE'];
		
		
		//$split_var = "|";
		//$payment_string = "1".$split_var.$fee_amount.$split_var."0".$split_var.date('d-M-y').$split_var."1";
		
		$payment_string = "1¶".$fee_amount."¶0¶".date('d-M-y')."¶2";
		
		$i_exam_type = "A";
		$i_stud_admission_type = "P";
		$i_adm_late_fee_amt = "0";
		$i_exam_late_fee_amt = "0";
		$i_extra_exam_fee = "0";
		$i_fee_concession_amt = "0";
		$i_short_fee_amt = "0";
		$i_excess_fee_amt = "0";
		$i_adjusted_fee_amt = "0";
		$i_fee_concession_applied = "N";
		$i_book_bank_applied = "N";
		$i_exam_center_code = "1";
		$i_delhi_exam_center_zone = "1";
		$i_paper_string = "";
	}


}// checking the last date


// If a user is added
if($_REQUEST['btnSubmit']){
	
$mobile = $_REQUEST['student_mobile'];
	
	
$sql = 'BEGIN PS_EX_PROMOTED_ADM_VIEW_WEB(:i_campus_code, :i_academic_session_id, :i_sol_roll_no,:i_admission_date,:i_course_code,:i_part,:i_exam_type,:i_stud_admission_type,:i_fee_type_code,:i_fee_category_code,:i_payment_date,:i_paid_amount,:i_adm_late_fee_amt,:i_exam_late_fee_amt,:i_extra_exam_fee,:i_fee_concession_amt,:i_short_fee_amt,:i_excess_fee_amt,:i_adjusted_fee_amt,:i_fee_concession_applied,:i_book_bank_applied,:i_exam_center_code,:i_delhi_exam_center_zone,:i_creation_time,:i_payment_string,:i_paper_string,:i_Mobile,:i_EmailID,:o_status,:o_error_message ); END;';

$stid = oci_parse(ORACLE_CONN,$sql);
$sts = "";
$msg = "";
oci_bind_by_name($stid, ':i_campus_code', $college_code);
oci_bind_by_name($stid, ':i_academic_session_id', $academic_session);
oci_bind_by_name($stid, ':i_sol_roll_no', $university_roll_no);
oci_bind_by_name($stid, ':i_admission_date', date('d-M-Y'));
oci_bind_by_name($stid, ':i_course_code', $course_code);
oci_bind_by_name($stid, ':i_part', $semester);
oci_bind_by_name($stid, ':i_exam_type', $i_exam_type);
oci_bind_by_name($stid, ':i_stud_admission_type', $i_stud_admission_type);
oci_bind_by_name($stid, ':i_fee_type_code', $fee_type_code);
oci_bind_by_name($stid, ':i_fee_category_code', $fee_category_code);
oci_bind_by_name($stid, ':i_payment_date', date('d-M-Y'));
oci_bind_by_name($stid, ':i_paid_amount', $fee_amount);
oci_bind_by_name($stid, ':i_adm_late_fee_amt', $i_adm_late_fee_amt);
oci_bind_by_name($stid, ':i_exam_late_fee_amt', $i_exam_late_fee_amt);
oci_bind_by_name($stid, ':i_extra_exam_fee', $i_extra_exam_fee);
oci_bind_by_name($stid, ':i_fee_concession_amt', $i_fee_concession_amt);
oci_bind_by_name($stid, ':i_short_fee_amt', $i_short_fee_amt);
oci_bind_by_name($stid, ':i_excess_fee_amt', $i_excess_fee_amt);
oci_bind_by_name($stid, ':i_adjusted_fee_amt', $i_adjusted_fee_amt);
oci_bind_by_name($stid, ':i_fee_concession_applied', $i_fee_concession_applied);
oci_bind_by_name($stid, ':i_book_bank_applied', $i_book_bank_applied);
oci_bind_by_name($stid, ':i_exam_center_code', $i_exam_center_code);
oci_bind_by_name($stid, ':i_delhi_exam_center_zone', $i_delhi_exam_center_zone);
oci_bind_by_name($stid, ':i_creation_time', date('d-M-Y'));
oci_bind_by_name($stid, ':i_payment_string', $payment_string);
oci_bind_by_name($stid, ':i_paper_string', $i_paper_string);
oci_bind_by_name($stid, ':i_Mobile', $mobile);
oci_bind_by_name($stid, ':i_EmailID', $email);

oci_bind_by_name($stid, ':o_status', $sts,200);
oci_bind_by_name($stid, ':o_error_message', $msg,200);


oci_execute($stid);  // executes and commits

if($sts == 1){
	
	$msg_show = "Successfully done, Please fill the next form for your payment.";
	echo "<script> var popup_show = 'yes';</script>";
}
else{
	$output_message = $msg;
	echo "<script> var popup_show = 'no';</script>";
}
oci_free_statement($stid);
oci_close($conn);	
	
 

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
                        <h2>STUDENT DETAILS</h2>   
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
<div class="col-lg-1">  
   &nbsp; 
</div>    
<form name='frm_adduser' method="post" action="" onsubmit="return chk()">

<div class="col-lg-10 col-md-10" style="background:#e1ecff;border-radius: 10px;padding: 2%;">    
<input type="hidden" name="sid" value="<?=$_REQUEST['sid']?>">

<div class="form-group">
	<label>University Roll Number</label>
    <input disabled  placeholder="University Roll Number" class="form-control" type="text" name="v_uroll_no" id="v_uroll_no" value="<?=$university_roll_no?>" />
</div>

<div class="form-group">
	<label>College Name</label>
    <input disabled placeholder="College Name" class="form-control" type="text" name="college_name" id="college_name" value="<?=$college_name?>" />
</div>

<div class="form-group">
	<label>Course</label>
    <input disabled placeholder="Course" class="form-control" type="text" name="v_course" id="v_course" value="<?=$course_sel?>" />
</div>


<div class="row">
	<div class="form-group">
	<div class="col-lg-6 col-md-6">
		<label>Student Name</label>
		<input disabled class="form-control" id="student_name" name="student_name" placeholder="Student Name" value="<?=$student_name?>" >
	</div>
	<div class="col-lg-6 col-md-6">	
		<label>Fathers' Name</label>
		<input disabled class="form-control" id="father_name" name="father_name" placeholder="Father Name" value="<?=$father_name?>" >    
	</div>	
	</div>
</div>


<div class="row">
	<div class="form-group">
	<div class="col-lg-6 col-md-6">
		<label>Academic Session</label>
		<input disabled class="form-control" id="academic_session" name="academic_session" placeholder="Academic Session" value="20<?=$academic_session?>" >
	</div>
	<div class="col-lg-6 col-md-6">	
		<label>Gender</label>
		<input disabled class="form-control" id="gender" name="gender" placeholder="Gender" value="<?=$gender?>" >    
	</div>	
	</div>
</div>

<div class="row">
	<div class="form-group">
	<div class="col-lg-6 col-md-6">
		<label>Semester</label>
		<input disabled class="form-control" id="semester" name="semester" placeholder="Semester" value="<?=$semester?>" >
	</div>
	<div class="col-lg-6 col-md-6">	
		<label>Fees</label>
		<input disabled class="form-control" id="fee_amount" name="fee_amount" placeholder="Fees" value="<?=$fee_amount?>" >    
	</div>	
	</div>
</div>




<div class="row">
	<div class="form-group">
	<div class="col-lg-6 col-md-6">
		<label>Email</label>
		<input disabled class="form-control" id="student_email" name="student_email" placeholder="Email" value="<?=$email?>" >
	</div>
	<div class="col-lg-6 col-md-6">	
		<label>Mobile</label>
		<input class="form-control" id="student_mobile" name="student_mobile" placeholder="Mobile" maxlength="10" minlength="10" type="text" onkeypress="return isNumberKeyquty(event)" value="<?=$mobile?>"  > 	
	</div>	
	</div>
</div>


<div class="form-group">
	<label>Final Result</label>
    <input disabled  placeholder="Final Result" class="form-control" type="text" name="final_result" id="final_result" value="<?=$final_result?>" />
</div>


<hr>

<div class="form-group">
    <center><input class="btn btn-primary" type="submit" value="<?=$btnValue?>" name="<?=$btnName?>" <?=$hide_button?> ></center>
</div>



<div class="form-group">
<b>Note:</b><br>
<p style="font-size:12px;color:red;line-height:16px;">
* Only mobile number will be editable.
</p>
</div>	

</div>
    


    </form>
</div>


   
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
	

<!-- Modal -->
<!-- set up the modal to start hidden and fade in and out -->
<div id="failBox" class="modal fade" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
<div class="modal-content">
<!-- dialog body -->
<div class="modal-body">
<button type="button" class="close" data-dismiss="modal" onclick="fail_page_reload()">&times;</button>
<center><h3><?=$msg?></h3></center>
</div>
<!-- dialog buttons -->
<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="fail_page_reload()">OK</button></div>
</div>
</div>
</div>	
	
	
	
<script type="text/javascript">

    $(document).ready(function(){ 
		
        if(popup_show == 'yes'){
            $("#successBox").modal('show');
        }
		
		if(popup_show_false == 'yes'){
            $("#failBox").modal('show');
        }
		
    });
    
    
    function page_reload(){
        location.href='payment_details.php';
    }
    
	function fail_page_reload(){
        location.href='admin_home.php';
    }
    
</script>


