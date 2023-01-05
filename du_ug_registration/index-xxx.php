<?php
session_start();
include("include/functions.php");
error_reporting(0);



$dt=date('Y-m-d');
$now=strtotime('now');
if($_REQUEST['user_submit']){
	
	$username=$_REQUEST['username'];
	$password = $_POST['password'];
	
	$s="select * from admin where username='$username' and password='$password'";
	$rs=mysql_query($s) or die (mysql_error());
	$cnt=mysql_num_rows($rs);
	if($cnt==0 || $cnt="")	{
		$output_message="<center><font color=red>Incorrect username or password...</font></center>";
	}
	else{
		$row = mysql_fetch_assoc($rs);
					
			$_SESSION['ADMIN_LOGGED_IN']=true;
			$_SESSION['USER']=$username;
			$_SESSION['USER_ID']=$row['id'];
			
			echo "<SCRIPT> location.replace('admin_home.php');</SCRIPT>";
		
	}
}

//include('header.php');
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admission For UG (Sports)</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   
   <link href="res/css/style.css" rel="stylesheet" />
   
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
                        <h2 style="color:#fff;"><b>Admission For UG (Sports)</b></h2>
                    </a>
                    
                </div>
              
                <span class="logout-spn" >
                    
                    <a href="#" onclick="alert('Coming Soon...')" style="color:#fff;">Forgot your password?</a>  

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
							<h3>Sign In</h3>
						</div>
						<div class="module-body">
							<div class="control-group">
								<div class="controls row-fluid">
                                                                    <input style="margin-left: 10%;width: 75%;" onkeyup="nospaces(this)" class="span12" type="text" id="username" name="username" placeholder="Username" required="required">
								</div>
							</div>
                                                    <br>  
							<div class="control-group">
								<div class="controls row-fluid">
									<input  style="margin-left: 10%;width: 75%;" class="span12" type="password" id="password" name="password" placeholder="Password" required="required">
								</div>
							</div>
						</div>
						<div class="module-foot">
							<div class="control-group">
								<div class="controls clearfix">
									<input type="hidden" name="user_submit" id="user_submit" value="1" />
									<button type="submit" class="btn btn-primary pull-right">Login</button>
                                                                       
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
<?php
include('footer.php');
?>