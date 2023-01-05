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
	<a type="button" href="https://sol.du.ac.in" class="btn btn-primary" style="font-weight:bold; color:#FFF; font-size:small;">Back to Home Page >> </a>
</div>
</div>
<?php
/*
$dt = date('Y-m-d');
if($dt <= '2021-06-28'){
	echo "--";
}
*/
?>

<div class="container bg-2 text-center">
	<div class="row">
		 <div class="col-md-12">
			<div class="panel-group">
				<div class="panel panel-primary">
				<div class="panel-heading" style="font-size:30px;">Important Notices - ABE 2020-21<br>
<!--				(CBCS Semester - IV & ANNUAL Part - II Students only)-->
				</div>
				


				 
				  <div class="panel-body" style="color:#000;">
					
	
	<hr style="color:#000; border-top:1px solid #000;">
	
<!--<a href="https://web.sol.du.ac.in/uploads/pdfs/2021/Notice_reupload_abe_21.pdf" style="color:#000000;font-size:16px;" target="_blank" class='btn btn-info btn-lg'><b>IMPORTANT NOTICE ABOUT RE-UPLOADING OF ANSWER SHEETS</b></a>
<br><br>	-->

<a href="https://assignment.sol.du.ac.in/uploads/pdf/Important%20Notice%20for%20B.A%20(Prog)%20and%20B.Com%20Semester%204%20CBCS.pdf" style="color:#000000;font-size:16px;" target="_blank" class='btn btn-info btn-lg'><b>IMPORTANT NOTICE-1 FOR CBCS B.A. (Prog.) & B.COM SEMESTER – IV STUDENTS</b></a>
<br><br>	
<a href="https://web.sol.du.ac.in/uploads/pdfs/2021/NOTICE FOR SEM-IV BCOM AND BA -P.pdf" style="color:#000000;font-size:16px;" target="_blank" class='btn btn-info btn-lg'><b>IMPORTANT NOTICE-2 FOR CBCS B.A. (Prog.) & B.COM SEMESTER – IV STUDENTS</b></a>
<br><br>
<a href="https://web.sol.du.ac.in/uploads/pdfs/2021/Important Notice for B.A (Prog) Semester 4 CBCS.pdf" style="color:#000000;font-size:16px;" target="_blank" class='btn btn-info btn-lg'><b>IMPORTANT NOTICE-3 FOR CBCS B.A. (Prog.) SEMESTER – IV STUDENTS</b></a>
<br><br>
<a href="https://web.sol.du.ac.in/uploads/pdfs/2021/NOTICE FOR B.A. H EN SMESTER-IV STUDENTS.pdf" style="color:#000000;font-size:16px;" target="_blank" class='btn btn-info btn-lg'><b>IMPORTANT NOTICE FOR CBCS B.A. (H) ENGLISH SEMESTER – IV STUDENTS</b></a>
<br><br>
<!--
	<a href="https://web.sol.du.ac.in/uploads/pdfs/2021/ABE-Grievance-notice.pdf" style="color:#000000;font-size:16px;" target="_blank" class='btn btn-info btn-lg'><b>ABE (UG) Grievance Notice</b></a>
<br><br>
-->	
	

<!--<a href="https://web.sol.du.ac.in/uploads/pdfs/2021/NOTE FOR REOPENING LINES FOR EXAM. FORMS_13 July_2021.pdf" style="color:#000000;font-size:16px;" target="_blank" class='btn btn-info btn-lg'><b>LAST CHANCE FOR SUBMISSION OF ABE EXAMINATION FORMS</b></a>
<br><br>-->







	
					
<p style="text-align:center;">
	
	
	</p>
	
	
	
	<hr>
	
	

	
	
	
	
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

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-159416930-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-159416930-1');
</script>

</body>
</html>