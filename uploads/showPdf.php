<?php
$pgurlId = "http://transtrategypartners.com/_coach/viewpdf.php?id=".$_GET['id'];	
require_once('../coach_new/tcpdf/config/lang/eng.php');
require_once('../coach_new/tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->SetDisplayMode('fullpage');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->AddPage('P', 'A4');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pgurlId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$html = curl_exec($ch);
curl_close($ch);
$pdf->writeHTML($html, true, false, true, false, '');
//$pdfFileName = "invoice_pdf/".$_GET['id'].".pdf";
$pdf->Output("example.pdf", 'I');
?>