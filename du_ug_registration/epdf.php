<?php
/**
 * HTML2PDF Library - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @package   Html2pdf
 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
 * @copyright 2016 Laurent MINGUET
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'/fee_receipt.php?RECEIPT_NO=16-32-000017');
    //$content = ob_get_clean();
	
	echo $content = file_get_contents('http://10.32.1.245/du_ug_registration/fee_receipt.php?RECEIPT_NO=16-32-000017');
	exit;
    //$content = ob_get_clean();

    // convert in PDF
    require_once(dirname(__FILE__).'/htmltopdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
//      $html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('exemple00.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
