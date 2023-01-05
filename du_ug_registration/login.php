<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(0);

if($_SESSION['ADMIN_LOGGED_IN']){
	alertandreplace("Already Logged In","admin_home.php");
	exit;
}

$dt=date('Y-m-d');
$now=strtotime('now');
if($_REQUEST['user_submit']){
	
	$email = $_REQUEST['email'];
	$student_password = $_REQUEST['student_password'];
	
	
//student_details;
$cuid = oci_parse(ORACLE_CONN, "SELECT b.STUDENT_NAME,a.STUDENT_EMAILID,a.PROSPECTUS_ID,b.CAMPUS_CODE, c.MID,c.MERCHANT_KEY FROM WEB_ADM_REGISTRATION_DETAILS a,ad_student_msts b,PAYTM_MSTS c WHERE a.PROSPECTUS_ID = b.SOL_ROLL_NO and b.CAMPUS_CODE = c.CAMPUS_CODE AND STUDENT_EMAILID='".$email."' AND STUDENT_PASSWORD='".$student_password."'");
oci_execute($cuid);
$row_cu = oci_fetch_array($cuid, OCI_ASSOC + OCI_RETURN_NULLS);

if($row_cu['PROSPECTUS_ID'] != ''){
	
	$_SESSION['ADMIN_LOGGED_IN']=true;
	$_SESSION['CAMPUS_CODE']=$row_cu['CAMPUS_CODE'];
	$_SESSION['MID']=trim($row_cu['MID']);
	$_SESSION['MERCHANT_KEY']=trim($row_cu['MERCHANT_KEY']);
	
	
	$_SESSION['STUDENT_NAME']=$row_cu['STUDENT_NAME'];
	$_SESSION['STUDENT_EMAILID']=$row_cu['STUDENT_EMAILID'];
	$_SESSION['STUDENT_PROSPECTUS_ID']=$row_cu['PROSPECTUS_ID'];
	
	echo "<SCRIPT> location.replace('admin_home.php');</SCRIPT>";
}
else{
	$msg_show = "Invalid Email ID/Password.<br> Please try again with valid Email ID and Password.";
	echo "<script> var popup_show = 'yes';</script>";
}

}


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UG Online fee collection for Academic Session <?=date('Y')?>-<?=(date('y')+1);?></title>
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
                        <h2 style="color:#fff;"><img id="Image1" src="assets/img/logo_du.png" style="border-width:0px; width:24%;margin-top:-9%;margin-right:7%;" /><b>Login</b></h2>
                    </a>
                    
                </div>
              
                <span class="logout-spn" >
                    
                   <a href="index.php" style="color:#fff;">UG Online fee collection for Academic Session <?=date('Y')?>-<?=(date('y')+1);?></a><br>
				   <a href="forgot_password.php" style="color:#fff;float:right;margin-top:1%;">&nbsp;[ Forgot Password ]</a> 
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
				<div class="module module-login span4 offset4">
				
				<span id="result_login"><?=$output_message?></span>
				
					<form class="form-vertical" name="login_frm" action="" method="post">
						<div class="module-head">
							<span style="font-size:15px;font-weight:bold;">Login</span>
						</div>
						
						<div class="module-body">
							<br>	

							<div class="control-group">
								<div class="controls row-fluid">
                                    <input style="margin-left: 10%;width: 75%;" onkeyup="nospaces(this)" class="span12" type="text" id="email" name="email" placeholder="Email ID" required="required">
								</div>
							</div>
                                                    <br>  
							<div class="control-group">
								<div class="controls row-fluid">
									<input  style="margin-left: 10%;width: 75%;" class="span12" type="password" id="student_password" name="student_password" placeholder="Password" required="required">
								</div>
							</div>
						</div>
						<div class="module-foot">
							<div class="control-group">
								<div class="controls clearfix">
									<input type="hidden" name="user_submit" id="user_submit" value="1" />
									<button type="submit" class="btn btn-primary pull-right">Login</button>

								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div><!--/.wrapper-->


		
		
		

    </div>        
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
        location.href='login.php';
    }
    
</script>	



