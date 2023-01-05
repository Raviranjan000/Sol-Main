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
    <title>UG/ PG Online fee collection for Academic Session <?=date('Y')?>-<?=(date('y')+1);?></title>
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
                    <a class="navbar-brand" href="index.php">
                        <!-- <img src="assets/img/logo.png" /> -->
                        <h2 style="color:#fff;"><b>UG/ PG Online fee collection for Academic Session <?=date('Y')?>-<?=(date('y')+1);?></b></h2>
                    </a>
                    
                </div>
              
                <span class="logout-spn" >
                    
                   <!-- <a href="#" onclick="alert('Coming Soon...')" style="color:#fff;">Forgot your password?</a>  -->

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

      

	<div class="wrapper" style="  border-bottom: 1px solid #bbb;   box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);  padding: 3% 0;">
		<div class="container">
			<div class="row">
				<div>
				


<center>
        <div style="text-align: center; width: 85%;">
            
            <div id="Panel1" style="border-color:Gray;border-width:2px;border-style:solid;font-weight:bold;">
	
                <br />
                <img id="Image1" src="assets/img/logo_du.png" style="border-width:0px;" /><br />
                <br />
                <h2> UG Online fee Payment for Academic Session <?=date('Y')?>-<?=(date('y')+1);?>(2nd & 3rd Year) &</h2> 
				<h2> PG Online fee Payment for Academic Session <?=date('Y')?>-<?=(date('y')+1);?>(2nd Year)</h2>
                <br />
                Dear Students and Parents<br />
                <br />
                University of Delhi welcomes you to Online fee payment facility for UG students (2nd & 3rd Year) & PG Students (2nd Year)
				<br />
                <br />
                
				Enjoy hassle free, safe and secure fee payment. Save your precious time & energy. It is convenient. You can do it from anywhere. Use it! 

				<br />
                <br />
                <br />
                For any problem related to online fee payment, please contact your concerned college.   
                <br />
                <br />               
                                
                We wish you a Happy and joyful learning.
				<br />
                <br />                              
                <input type="submit" name="btnGo" value="New User" onclick="javascript:window.location='sign_in.php'" id="btnGo" title="New User" />&nbsp;&nbsp;
                <input type="submit" name="btnAR" value="Login User" onclick="javascript:window.location='login.php'" id="btnAR" title="Login User" />&nbsp;&nbsp;
				
				<button type="button" name="btnVideo" data-toggle="modal" data-target="#myModal" onclick="return video_url('video')" >UG Online Tutorial<img src="assets/img/speakerIcon_50x50.png" style="width:18px;margin-left:7px;margin-top:-3px;"></button>
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



		
<script>
function video_url(video_url){
	if(video_url == 'video'){
		$("#float_div").html('<iframe width="100%" height="460" src="https://www.youtube.com/embed/71AIjddhey0?rel=0&fs=0" frameborder="0" allowfullscreen></iframe>');
	}
	return true;
}

function close_all(){
	$("#float_div").html('');	
}

</script>		
		
<!------------------------------------------>

<div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog" style="width: 65%;">
<div class="modal-content">
<!-- dialog body -->
<div class="modal-body">
<button type="button" class="close" data-dismiss="modal" onclick="close_all()">&times;</button>
<p id="float_div"></p>
</div>
<!-- dialog buttons -->
<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="close_all()">Close</button></div>
</div>
</div>
</div>	

<!------------------------>		

	
<?php
include('footer.php');
?>