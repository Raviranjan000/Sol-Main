<!DOCTYPE html>
<html lang="en">
<head>
  <title>School of Open Learning - Welcome</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <style>
  body {
      font: 20px Montserrat, sans-serif;
      line-height: 1.5;
      color: #f5f6f7;
	  background:#061d4c;
  }
  p {font-size: 13px;}
  .margin {margin-bottom: 8px;}
  .bg-1 { 
      color: #ffffff;
  }
  .bg-2 { 
      background-color: #061d4c; /* Dark Blue */
      color: #ffffff;
  }
  .container-fluid {
      padding:0;
  }
  .panel-body{padding:5px 10px !important;}
  </style>
  
</head>
<body>

<div class="container-fluid bg-1 text-center" style="position:relative; margin-bottom:33px;">
  <img src="images/logo2.jpg" class="img-responsive" style="display:inline; -webkit-box-shadow: 1px 1px 49px -6px rgba(0,0,0,0.24);
-moz-box-shadow: 1px 1px 49px -6px rgba(0,0,0,0.24);
box-shadow: 1px 1px 49px -6px rgba(0,0,0,0.24);" alt="1" width="1400">
<!--
  <div style="position:absolute; bottom:-32px; left:0%">
	<a type="button" href="hindi.php" class="btn btn-primary" style="font-weight:bold; color:#FFF; font-size:small;">हिंदी के लिए</a>
</div>-->
<div style="position:absolute; bottom:-32x; right:0%">
	<a type="button" href="linux-test-lb-1822088355.ap-south-1.elb.amazonaws.com" class="btn btn-primary" style="font-weight:bold; color:#FFF; font-size:small;">Continue to website >> </a>
</div>
</div>


<div class="container bg-2 text-center">
	<div class="row">
		 <div class="col-md-12">
			<div class="panel-group">
				<div class="panel panel-primary">
				<div class="panel-heading">Undergraduate (UG) Admissions 2020-2021  
				<!--<marquee width="100%" behavior="alternate" bgcolor="red">  
<b>Last Date 15-September-2018</b>
</marquee>--> </div>
				  <h1 style="background:#FFF; color:#000; font-size:18px; font-weight:bold; padding:1%; margin:0; text-shadow: 0px 1px 1px rgba(150, 150, 150, 1);">
  School of Open Learning (SOL), University of Delhi (DU) welcomes you to Online Admission facility for UG.</h1>
<h2 style="font-size:16px; font-weight:bold; text-decoration:underline; background:#FFF; color:#000; padding:1%; margin:0;">Undergraduate (UG) Admission: Academic Session 2020-2021.</h2>


<a href="#" target="_blank">Undergraduate (UG) <span style="color:#ee3514;">Admissions 2020-21</span> Notice </a>


				 
				  <div class="panel-body" style="color:#000;">
					<h3 class="margin" style="font-size:15px; font-weight:bold;">Dear Students and Parents</h3>
  <p style="text-align:center; font-weight:bold;">
	
	Enjoy hassle free, safe and secure admission. Save your precious time, energy and money. It is convenient. You can do it anytime and from anywhere. Use it !<br>
	For any query related to Online Registration / Admission, please 
	
	Helpline No.(North): 27008300, 27008301 (10 lines - Office Hours: 9:00 AM - 5:30 PM) <br>
	<span style="margin-left:-265px;">Helpline No.(South): 24151600, 24151602 </span><br>
	We wish you a Happy Admission and joyful learning.<br> 
	<!--<a style="font-size: 35px;;word-break:break-all; color:#880000;" href="https://sol.du.ac.in/admission/LoginRegistration.aspx">For new UG Admission - Click Here</a>-->
	</p>
	<hr style="color:#000; border-top:1px solid #000;">
						
<p style="text-align:center;">
	
		<a type="button" href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal" style="font-weight:bold; color:#061d4c; font-size:small;" onclick="return video_url('053SnK5Alao')">Video Tutorial</a>
	
		<!--<a type="button" href="#" class="btn btn-warning"  style="font-weight:bold; color:#061d4c; font-size:small;" onclick="location.href='https://sol.du.ac.in/admission/LoginRegistration.aspx'">New User</a>
		<a type="button" href="#" class="btn btn-warning" style="font-weight:bold; color:#061d4c; font-size:small;" onclick="location.href='https://sol.du.ac.in/admission/'">Registered User</a>-->
		
		
	</p>
				  </div>
				</div>
			</div>
		 </div>

	</div>
</div>



<div class="container">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width:80%;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" onclick="close_all_next()">&times;</button>
          <h4 class="modal-title" style="color:#000;"><img src="images/sol_admission_small.gif"> <span id="type_name"></span></h4>
        </div>
        <div class="modal-body">
			<p id="float_div"></p> 
          
        </div>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-primary" onclick="location.href='../index.php'">Continue To Admission...</button> -->
		  <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="close_all_next()">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container" id="first_box">
  <div class="modal fade" id="myModalFirst" role="dialog">
    <div class="modal-dialog" style="width:80%;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="color:#000;"><img src="images/sol_admission.gif"></h4>
        </div>
        <div class="modal-body">
			<p id="float_div_first"></p> 
          
        </div>
        <div class="modal-footer">
          <p id="type_button"></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


<script>

function video_url(video_url){
	document.getElementById("float_div").innerHTML = '<iframe width="100%" height="400" src="https://www.youtube.com/embed/'+video_url+'?rel=0&fs=0" frameborder="0" allowfullscreen></iframe>';
	//$("#float_div_1").html('<iframe width="100%" height="400" src="https://www.youtube.com/embed/'+video_url+'?rel=0&fs=0" frameborder="0" allowfullscreen></iframe>');
	return true;
}
function close_all_next(){
	//$("#float_div_1").html('');	
	document.getElementById("float_div").innerHTML = "";
}
</script>

</body>
</html