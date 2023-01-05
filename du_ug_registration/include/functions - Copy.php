<?php

include_once('connect.php');

function msg($msg){
	echo "<center><font color=green size=3><B> $msg </B></font></center>";
}

function errmsg($str){
	echo "<center><font color=red> $msg </font></center>";
}

function alert($str)
{
	echo "<SCRIPT> alert('$str');</SCRIPT>";
}

function alertandreplace($str,$loc)
{
	echo "<SCRIPT> alert('$str'); location.replace('$loc');</SCRIPT>";
}

function location($str)
{
	echo "<SCRIPT> location.replace($str); </SCRIPT>";
}

// Remove special characters from a string
function stripSpclChrs($inputString){
    /*
      Remove special characters from a string
    */
    //$title = strtolower(strip_tags($inputString));
	$title = strip_tags($inputString);
    $title = ereg_replace("[^A-Za-z0-9_\-\./\&\, ]", "", $title); // remove special characters
    $title = str_replace(array('-','/',','), " ", $title); // replace legal characters with spaces
    $title = trim($title); // remove leading and trailing whitespace
   // $title = ereg_replace(" {1,}", "-", $title); // convert one or more consecutive spaces to a single underscore
   //$title = ereg_replace("_{2,}", "-", $title); // convert two or more consecutive underscores to a single underscore

    return $title;
  }

// Success message 
function successMsgShow($text){
	if($text != ''){
		return "<span style='height:20px;'><font color='green' style='font-size:10px;'>".$text.".</font></span> ";
		//return "<div class='valid_box'>".$text."</div> ";
	}
}

// Error message 
function errorMsgShow($text){
	if($text != ''){
		return "<span style='height:20px;'><font color='#8A2908' style='font-size:10px;'>".$text.".</font></span> ";
		//return "<div class='error_box'>".$text."</div> ";
	}
}


// getting the browser
function checkBrowser(){

	$browserChkIe = strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE');
	$browserChkChrome = strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome');
	$browserChkFirefox = strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox');

		if($browserChkIe == true)
			return "ie";
		else if($browserChkChrome == true)
			return "chrome";
		else if($browserChkFirefox == true)
			return "firefox";
		else
			return "other";

}// end of function checkBrowser
// getting the browser


// get details for user
function getMemberDetail($hid){
	$today = strtotime('now');
	$sql="SELECT * FROM register WHERE hid='$hid'";
	$res=mysql_query($sql) or die(mysql_error());
	$arr=Array();

	$row =mysql_fetch_assoc($res);
	
		$arr['MEMBERID']=$row['id'];
		$arr['FIRST_NAME']=ucfirst($row['first_name']);
		$arr['LAST_NAME']=$row['last_name'];
		$arr['ADDRESS1']=ucfirst($row['address1']);
		$arr['ADDRESS2']=ucfirst($row['address2']);
		$arr['CITY']=$row['city'];
		$arr['STATE']=$row['state'];
		$arr['COUNTRY']=$row['country'];
		$arr['PIN']=$row['pin'];
		$arr['MOBILE']=$row['mobile'];
		$arr['EMAIL']=$row['email'];
		$arr['REGISTER_DATE']=$row['register_date'];
	
return $arr;
}
// get details for user

/*
function imageCrop($uploadpath,$img_id,$ext){

	$uploadfile = "../".$uploadpath."/".$img_id.$ext;

	$file_info = getimagesize($uploadfile);
	  $width = $file_info[0] ;
	  $height = $file_info[1];

		//$new_width_m = 300; // for Medium image
		//$new_width_s = 200; // for small image
		$new_width_t = 86; // for thumb image

		//$percentage_m = round(($width/$new_width_m), 2); 
		//$new_height_m = round($height/$percentage_m);

		//$percentage_s = round(($width/$new_width_s), 2); 
		//$new_height_s = round($height/$percentage_s);

		$percentage_t = round(($width/$new_width_t), 2); 
		$new_height_t = round($height/$percentage_t);


		$filename=$uploadfile;
		$img = imagecreatefromjpeg($filename);
			
		 //$tmp_img_m = imagecreatetruecolor( $new_width_m, $new_height_m );
		 //$tmp_img_s = imagecreatetruecolor( $new_width_s, $new_height_s );
		 $tmp_img_t = imagecreatetruecolor( $new_width_t, $new_height_t );

		  // copy and resize old image into new image
		  //imagecopyresized( $tmp_img_m, $img, 0, 0, 0, 0, $new_width_m, $new_height_m, $width, $height );
		  //imagecopyresized( $tmp_img_s, $img, 0, 0, 0, 0, $new_width_s, $new_height_s, $width, $height );
		  imagecopyresized( $tmp_img_t, $img, 0, 0, 0, 0, $new_width_t, $new_height_t, $width, $height );

		  // save thumbnail into a file
		  //imagejpeg( $tmp_img_m, "../".$uploadpath."/M_".$contentid."_".$p_size[1].strtolower($fnArr), 100 );
		  //imagejpeg( $tmp_img_s, "../".$uploadpath."/S_".$contentid."_".$p_size[1].strtolower($fnArr), 100 );
		  imagejpeg( $tmp_img_t, "../".$uploadpath."/".$img_id."_T".$ext, 100 );

}


function image_resize($src, $dst, $width, $height, $crop=0){

  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

  $type = strtolower(substr(strrchr($src,"."),1));
  if($type == 'jpeg') $type = 'jpg';
  switch($type){
    case 'bmp': $img = imagecreatefromwbmp($src); break;
    case 'gif': $img = imagecreatefromgif($src); break;
    case 'jpg': $img = imagecreatefromjpeg($src); break;
    case 'png': $img = imagecreatefrompng($src); break;
    default : return "Unsupported picture type!";
  }

  // resize
  if($crop){
    if($w < $width or $h < $height) return "Picture is too small!";
    $ratio = max($width/$w, $height/$h);
    $h = $height / $ratio;
    $x = ($w - $width / $ratio) / 2;
    $w = $width / $ratio;
  }
  else{
    if($w < $width and $h < $height) return "Picture is too small!";
    $ratio = min($width/$w, $height/$h);
    $width = $w * $ratio;
    $height = $h * $ratio;
    $x = 0;
  }

  $new = imagecreatetruecolor($width, $height);

  // preserve transparency
  if($type == "gif" or $type == "png"){
    imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
    imagealphablending($new, false);
    imagesavealpha($new, true);
  }

  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

  switch($type){
    case 'bmp': imagewbmp($new, $dst); break;
    case 'gif': imagegif($new, $dst); break;
    case 'jpg': imagejpeg($new, $dst); break;
    case 'png': imagepng($new, $dst); break;
  }
  return true;
}
*/



?>

