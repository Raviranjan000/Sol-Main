<?php
include("config_manual.php");

$active_tab_0 = '';
$active_tab_1 = '';
$active_tab_2 = '';
$active_tab_3 = '';

$hide_button = "";

//////////////////////////////// checking payment //////////////////////
$sql = 'BEGIN Ps_Web_Du_PART_BATCH_RECORD(:i_SOL_ROLL_NO, :o_PART, :o_RECEIPT_NO); END;';
$stid = oci_parse(ORACLE_CONN,$sql);
$part = "";
$receipt_no = "";
oci_bind_by_name($stid, ':i_SOL_ROLL_NO', $_SESSION['STUDENT_PROSPECTUS_ID']);

oci_bind_by_name($stid, ':o_PART', $part,200);
oci_bind_by_name($stid, ':o_RECEIPT_NO', $receipt_no,200);

oci_execute($stid);  // executes and commits

if($part == '0'){
        $link_show = "<a href=\"payment_details.php\"><i class=\"fa fa-table\"></i>Payment </a>";
        $link_tab = "payment_details.php";
}
else{
	if($receipt_no == 'X'){
		$link_show = "<a href=\"payment_details.php\"><i class=\"fa fa-table\"></i>Payment </a>";
		$link_tab = "payment_details.php";
	}
	else{
		$link_show = "<a href=\"view_fee_receipt.php\"><i class=\"fa fa-table\"></i>Fee-Receipt </a>";
		$link_tab = "view_fee_receipt.php";
		$hide_button = " style='display:none;'";
	}

}
//////////////////////////////// checking payment //////////////////////


$url_data = explode('admin/', $_SERVER['REQUEST_URI']);

if (strpos($_SERVER['REQUEST_URI'], 'guidelines.php')) {
    $active_tab_1 = 'class="active-link"';
}
else if (strpos($_SERVER['REQUEST_URI'], 'college_details.php')) {     
    $active_tab_2 = 'class="active-link"';
}
else if (strpos($_SERVER['REQUEST_URI'], $link_tab)) {
    $active_tab_3 = 'class="active-link"';
}
else{
    $active_tab_0 = 'class="active-link"';
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
</head>
<body>

    <script>
        function close_click(){
            window.parent.location.reload();
        }
    </script>
        
           
          
    <div id="wrapper">
         <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="admin_home.php">
                        <!-- <img src="assets/img/logo.png" style="width:73%;" /> -->
                        <h2 style="color:#fff;"><img id="Image1" src="assets/img/logo_du.png" style="border-width:0px; width:7%;margin-top:-3%;margin-right:2%;" /><b>UG/ PG Online fee collection for Academic Session <?=date('Y')?>-<?=(date('y')+1);?></b></h2>
                    </a>
                    
                </div>
              
                
            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                 


                    <li <?=$active_tab_0?>>
                        <a href="admin_home.php" ><i class="fa fa-desktop "></i>Dashboard </a>
                    </li>
                   
<!--
                    <li>
                        <a href="ui.html"><i class="fa fa-table "></i>UI Elements  <span class="badge">Included</span></a>
                    </li>
                    <li>
                        <a href="blank.html"><i class="fa fa-edit "></i>Blank Page  <span class="badge">Included</span></a>
                    </li>
-->

                    <li <?=$active_tab_1?>>
                        <a href="guidelines.php"><i class="fa fa-qrcode "></i>Guidelines/FAQ's</a>
                    </li>
                    <li <?=$active_tab_2?> <?=$hide_button?>>
                        <a href="college_details.php"><i class="fa fa-bar-chart-o"></i>View Details</a>
                    </li>

                   <li <?=$active_tab_3?>>
                       <?=$link_show?>
                    </li>
					
					<li>
                        <a href="logout.php"><i class="fa fa-edit "></i>Logout</a>
                    </li>
                    
                </ul>
                            </div>

        </nav>
        <!-- /. NAV SIDE  -->