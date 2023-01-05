<?php
session_start();
include("include/functions.php");
error_reporting(0);

if($_SESSION['ADMIN_LOGGED_IN']){
	alertandreplace("Already Logged In","admin_home.php");
	exit;
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admission Online Guidelines</title>
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
                    <a class="navbar-brand" href="">
                        <!-- <img src="assets/img/logo.png" /> -->
                        <h2 style="color:#fff;"><b>Guidelines</b></h2>
                    </a>
                    
                </div>
              
                <span class="logout-spn" >
                    
                   <a href="index.php" style="color:#fff;">Admission Online</a>

                </span>
            </div>
        </div>


	<div class="wrapper" style="  border-bottom: 1px solid #bbb;   box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);  padding: 6% 0;">
		<div class="container">
			<div class="row">
				<div>
				


<center>
        <div style="text-align: center; width: 85%;">
            
            <div id="Panel1" style="border-color:Gray;border-width:2px;border-style:solid;font-weight:bold;">
	
                <br />
                <img id="Image1" src="assets/img/logo_du.png" style="border-width:0px;" /><br />
                <br />
                <h2>UG Admission Academic Session 2016-17</h2>
                <br />
                Dear Students and Parents<br />
                <br />
                University of Delhi welcomes you to Online Admission
                facility for UG.<br />
                <br />
                Enjoy hassle free, safe and secure admission. Save your precious time, energy and
                money. It is convenient. You can do it from anywhere. Use it !<br />
                <br />
                <br />
                For any problem related to Online Registration / Admission, please mail to 
                    <a href="mailto:admission@sol.du.ac.in" target="_blank" style="font-size: x-large;word-break:break-all">admission@du.ac.in</a><br />
                <br />               
                Helpline No.: 27008300, 27008301 (15 lines - Office Hours: 9:00 AM - 5:30 PM) <br /><br />                   
                We wish you a Happy admission and joyful learning.<br />
                <br />                              

                    <br />
                <br />
            
</div>
        </div>
    </center>


				
				
					
				</div>
			</div>
		</div>
	</div><!--/.wrapper-->
        

    </div>        
<?php
include('footer.php');
?>