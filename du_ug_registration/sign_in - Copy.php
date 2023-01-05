<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(0);

if($_SESSION['ADMIN_LOGGED_IN']){
	alertandreplace("Already Logged In","admin_home.php");
	exit;
}

//print_r($_REQUEST);

$dt=date('Y-m-d');
$now=strtotime('now');
if($_REQUEST['user_submit']){
	
	$college_sel = $_REQUEST['college_sel'];
	$course_sel = $_REQUEST['course_sel'];
	$u_roll_no = trim($_REQUEST['u_roll_no']);
	$student_name = trim($_REQUEST['student_name']);

	
$sql = 'BEGIN Ps_Sc_Authenticate_User_Exam(:i_Univ_Roll_no, :i_Col_id, :i_Course_code, :i_Student_Name, :o_status, :o_error); END;';

$stid = oci_parse(ORACLE_CONN,$sql);
$sts = "";
$msg = "";

oci_bind_by_name($stid, ':i_Univ_Roll_no', $u_roll_no);
oci_bind_by_name($stid, ':i_Col_id', $college_sel);
oci_bind_by_name($stid, ':i_Course_code', $course_sel);
oci_bind_by_name($stid, ':i_Student_Name', strtoupper($student_name));

oci_bind_by_name($stid, ':o_status', $sts,200);
oci_bind_by_name($stid, ':o_error', $msg,200);


oci_execute($stid);  // executes and commits

if($sts == 0){
	setcookie("Univ_Roll_no", $u_roll_no, time()+300);  /* expire in 5 min */
	$msg_show = "Successfully done, Please fill the next form for your username and password.";
	echo "<script> var popup_show = 'yes';</script>";
}
else{
	$output_message = $msg;
	echo "<script> var popup_show = 'no';</script>";
	
}
oci_free_statement($stid);
oci_close($conn);
	
	
	
	

		

}
else{
	 echo "<script> var popup_show = 'no';</script>";
}

//college_details;
$college_details = "";
$ctid = oci_parse(ORACLE_CONN, "SELECT * FROM EB_CAMPUS_MSTS");
oci_execute($ctid);
while ($row = oci_fetch_array($ctid, OCI_ASSOC + OCI_RETURN_NULLS)) {
	if(trim($college_sel) == trim($row['CAMPUS_CODE']))
		$selected = "SELECTED";	
	else
		$selected = "";	
	
	$college_details .= "<option value='".$row['CAMPUS_CODE']."' ".$selected.">".$row['CAMPUS_NAME']."</option>";
}
//college_details;


//course_details;
$couese_details = "";
$csid = oci_parse(ORACLE_CONN, "SELECT * FROM AD_COURSE_MSTS where COURSE_LEVEL='UG' ORDER BY COURSE_NAME");
oci_execute($csid);
while ($row_cs = oci_fetch_array($csid, OCI_ASSOC + OCI_RETURN_NULLS)) {
	
	if(trim($course_sel) == trim($row_cs['COURSE_CODE']))
		$selected_course = "SELECTED";	
	else
		$selected_course = "";	
	
	$couese_details .= "<option value='".$row_cs['COURSE_CODE']."' ".$selected_course.">".$row_cs['COURSE_NAME']." - ".$row_cs['EXAM_FLAG']."</option>";
}
//course_details;





?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UG Online fee collection for Academic Session 2016-17</title>
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
                        <h2 style="color:#fff;"><img id="Image1" src="assets/img/logo_du.png" style="border-width:0px; width:24%;margin-top:-10%;margin-right:7%;" /><b>Sign In</b></h2>
                    </a>
                    
                </div>
              
                <span class="logout-spn" >
                    
                   <a href="index.php" style="color:#fff;">UG Online fee collection for Academic Session 2016-17</a><br>
                   <a href="index.php" style="color:#fff;float:right;margin-top:1%;">[ Home ]</a>

                </span>
            </div>
        </div>


<script>
function nospaces(t){
	if(t.value.match(/\s/g)){
	alert('Sorry, you are not allowed to enter any spaces');
	t.value=t.value.replace(/\s/g,'');
	}
}
</script>

      

	<div class="wrapper" style="  border-bottom: 1px solid #bbb;   box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);  padding: 6% 0;">
		<div class="container">
			
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<center><span id="result_login"><h3 style="color:red;"><?=$output_message?></h3></span></center>
					</div>
				</div>
				
				<div class="row">		
				<div class="module module-login span4 offset4">
				
				
				
					
						<div class="module-head">
							<span style="font-size:15px;font-weight:bold;">Sign In</span>
						</div>
						
						<div class="module-body">
							<br>	
							<form class="form-vertical" name="login_frm" action="" method="post">
							<div class="control-group">
								<div class="controls row-fluid">
								      <select style="margin-left: 10%;width: 75%;" required class="form-control" name="college_sel_first" onchange="getCourseAjax(this.value);">
									  <option value="">Select College</option>  
									  <?=$college_details?>  
									</select>
								</div>
							</div>
							</form>
							<br>
							<form class="form-vertical" name="login_frm_next" action="" method="post">
							<div class="control-group">
								<div class="controls row-fluid">
									<span id="college_data"></span> 
									 
								    <select style="margin-left: 10%;width: 75%;" required class="form-control" name="course_sel" id="course_data">
									  <option value="">Select Course</option>  
									  
									</select>
								</div>
							</div>
							<br>
							<div class="control-group">
								<div class="controls row-fluid">
                                    <input style="margin-left: 10%;width: 75%;" onkeyup="nospaces(this)" class="span12" type="text" id="u_roll_no" name="u_roll_no" placeholder="University Roll Number" required="required" value="<?=$u_roll_no?>" >
								</div>
							</div>
                            <br>  
							<div class="control-group">
								<div class="controls row-fluid">
									<input  style="margin-left: 10%;width: 75%;" class="span12" type="text" id="student_name" name="student_name" placeholder="Name" required="required" value="<?=$student_name?>">
								</div>
							</div>
						</div>
						<div class="module-foot">
							<div class="control-group">
								<div class="controls clearfix">
									<input type="hidden" name="user_submit" id="user_submit" value="1" />
									<button type="submit" class="btn btn-primary pull-right">Save</button>
                                                                       
                                                                        <!--<label style="min-height: 20px;    padding-left: 20px;"  >
                                                                            <input type="checkbox" style="float: left;  margin-left: -20px;"> 
                                                                                <span style="margin-top: 10px;">Remember me</span>
									</label>
                                                                        -->
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div><!--/.wrapper-->
    </div>        
	
	
	
<script>

    function getCourseAjax(col_sel) {  
	
		var course_sel = '<?=$course_sel?>';
        $.post("ajx_get_course.php",
                {
                    col_sel: col_sel,
                    course_sel: course_sel
     
                },
        function (data, status) { 
		
            if (data != '') {
                $('#college_data').html('<input type="hidden" name="college_sel" value="'+col_sel+'">');
                $('#course_data').html(data);
            }
            else {
                $('#course_data').html('<center>Have some problem, Please tyr again!</center><br><br>')
            }
			
            //.blur();

        });
        return false;
    }

</script>
	
		

	
	
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
		else{
			var college_sel = '<?=$college_sel?>';
			if(college_sel > 0)
				getCourseAjax(college_sel);
			
		}
		
		
    });
    
    
    function page_reload(){
        location.href='registration.php';
    }
    
</script>	


