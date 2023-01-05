<?php
$apikey = '95501130-729a-481e-99a8-45817157e439';
$value = 'http://www.google.com'; // a url starting with http or an HTML string.  see example #5 if you have a long HTML string
$result = file_get_contents("http://api.html2pdfrocket.com/pdf?apikey=" . urlencode($apikey) . "&value=" . urlencode($value));
file_put_contents('mypdf.pdf',$result);
	
?>

