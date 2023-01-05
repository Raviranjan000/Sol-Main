<?php
session_start();
include("config_manual.php");
include("include/functions.php");
error_reporting(1);

$course_sel = $_REQUEST['course_sel'];

//course_details;
$couese_details = "<select style=\"margin-left: 10%;width: 75%;\" required class=\"form-control\" name=\"course_sel\">
									  <option value=\"\">Select Course</option>";
$csid = oci_parse(ORACLE_CONN, " SELECT Course_Code AS VALUE_FIELD,
 Course_Name || ' ; ' || EXAM_FLAG AS TEXT_FIELD 
 FROM ad_Course_Msts  
 WHERE course_code IN(SELECT DISTINCT COURSE_CODE FROM AD_STUDENT_MSTS WHERE CAMPUS_CODE='".$_REQUEST['col_sel']."') 
 ORDER BY Course_Name");
oci_execute($csid);
while ($row_cs = oci_fetch_array($csid, OCI_ASSOC + OCI_RETURN_NULLS)) {
	
	if(trim($course_sel) == trim($row_cs['VALUE_FIELD']))
		$selected_course = "SELECTED";	
	else
		$selected_course = "";	
	
	$couese_details .= "<option value='".$row_cs['VALUE_FIELD']."' ".$selected_course.">".$row_cs['TEXT_FIELD']."</option>";
}
//course_details;
	$couese_details .= "</select>";

echo $couese_details;









?>