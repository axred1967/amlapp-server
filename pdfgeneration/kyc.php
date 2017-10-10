<?php
require_once("../../config.php");


if(isset($_GET['id'])){

  if(isset($_GET['cid'])){
    $id = $_GET['id'];
    $cid = $_GET['cid'];
    $pgurlId = "http://".$_SERVER['HTTP_HOST']."/amlapp/pdfgeneration/viewpdf.php?cid=$cid&id=$id";
  }else{
    $id = $_GET['id'];
    $pgurlId = "http://".$_SERVER['HTTP_HOST']."/amlapp/pdfgeneration/viewpdf.php?id=$id";
  }


}
else{
  $pgurlId = "http://".$_SERVER['HTTP_HOST']."/amlapp/pdfgeneration/scheda2.html";
  //$pgurlId = "http://".$_SERVER['HTTP_HOST']."/amlapp/pdfgeneration/1.html";
  //$pgurlId = "http://".$_SERVER['HTTP_HOST']."/amlapp/pdfgeneration/viewpdf.php";

}
// die($pgurlId)  ;



//require_once('../../_coach/tcpdf/config/lang/eng.php');
//require_once('TCPDF6/config/lang/eng.php');

//require_once('../../_coach/tcpdf/tcpdf.php');
if (strlen($_GET['id'])==0){
  $_GET['id']=89;
}
if ($_GET['agg']>0){
  $sql="SELECT * from kyc_log where id='".$_GET['agg']."'";

}
else {
  $sql="SELECT * from kyc where contract_id='".$_GET['id']."'";
}

$kyc = $db->getRow($sql);
$kyc_update=json_decode($kyc['kyc_update'],true);
$company=json_decode($kyc['company_data'],true);
$other=json_decode($kyc['owner_data'],true);
$contractor=json_decode($kyc['contractor_data'],true);
$contract=json_decode($kyc['contract_data'],true);

//echo $sql;
//error_log("kyc:".print_r($kyc,1).PHP_EOL);
//error_log("contractor:".print_r($contractor,1).PHP_EOL);
//error_log("other:".print_r($other,1).PHP_EOL);
error_log("xcompany:".print_r($company,1).PHP_EOL);
error_log("contract".print_r($contract,1).PHP_EOL);
$countryList = $db->getRows("SELECT * FROM countries ORDER BY country_name ASC");
//error_log("country".print_r($countrylist,1).PHP_EOL);
$cl=array();
foreach ($countryList as $countryVal) {

  $cl[$countryVal['country_id']]=$countryVal['country_name'];

}
$agent = $db->getRow("SELECT u.* FROM users u join agent a on a.user_id =u.user_id where a.agent_id=".$contract['agent_id']);
//$agency = $db->getRow("SELECT u.* FROM users as u join agency as a on u.user_id=a.user_id  where a.agency_id=". $contract['agency_id']);
//error_log("agent".print_r($agent,1).PHP_EOL);
//$agent_settings=json_decode($agent['settings'],true);
$agent_settings=json_decode($agency['settings'],true);
//error_log("agent settings".print_r($agent_settings,1).PHP_EOL);

//error_log("agent".print_r($agent,1).PHP_EOL);
$agency= $db->getRow("SELECT u.*, a.* FROM users u join agency a on a.user_id=u.user_id where a.agency_id=". $contract['agency_id']);
error_log("agency".print_r($agency,1).PHP_EOL);
////error_log("country".print_r($cl,1).PHP_EOL);

//die();

require_once('TCPDF6/tcpdf.php');
class MYPDF extends TCPDF {
  var $htmlHeader;

      public function setHtmlHeader($htmlHeader) {
          $this->htmlHeader = $htmlHeader;
      }
  //Page header
  public function Header($company) {
    // Logo
    //$image_file = K_PATH_IMAGES.'logo_example.jpg';
    //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // Set font
    $this->SetFont('helvetica', 'B', 8);
    $this->SetTextColor(28,34,75);
    // Title
    //error_log("company".$this->htmlHeader.PHP_EOL);
    $this->Cell(0, 1, $this->htmlHeader, 0, false, 'R', 0, '', 0, false, 'F', 'M');
  }

  // Page footer
  public function Footer($txt) {
    // Position at 15 mm from bottom
    $this->SetY(-10);
    // Set font
    $this->SetFont('helvetica', 'I', 7);
    // Page number

    $this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
  }

  public function MultiRow($left, $right,$border_left=1,$border_right=1,$righe=0) {
    // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

    $page_start = $this->getPage();
    $y_start = $this->GetY();

    // write the left cell
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
    $this->writeHTMLCell(80, 3, 10, '', html_entity_decode(($left)), $border_left, 0, 1, true, 'R', true);
    $this->writeHTMLCell(100, 3, '', '', html_entity_decode(($right)), $border_right, 0, 1, true, 'L', true);
    $this->LN(3*$righe);
    $this->LN(12);

    //$this->MultiCell(70, 0, $left, $border_left, 'R', 1, 2, 0, '', true, 0);
/*
    $page_end_1 = $this->getPage();
    $y_end_1 = $this->GetY();

    $this->setPage($page_start);

    // write the right cell
    $this->MultiCell(0, 0, $right, $border_right, 'L', 0, 1, $this->GetX() ,$y_start, true, 0);

    $page_end_2 = $this->getPage();
    $y_end_2 = $this->GetY();

    // set the new row position by case
    if (max($page_end_1,$page_end_2) == $page_start) {
      $ynew = max($y_end_1, $y_end_2);
    } elseif ($page_end_1 == $page_end_2) {
      $ynew = max($y_end_1, $y_end_2);
    } elseif ($page_end_1 > $page_end_2) {
      $ynew = $y_end_1;
    } else {
      $ynew = $y_end_2;
    }

    $this->setPage(max($page_end_1,$page_end_2));
    $this->SetXY($this->GetX(),$ynew);
*/
  }


}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$pdf->SetTitle('Amlapp - Print Kyc     ');
//error_log($agency['name'].PHP_EOL);
$pdf->SetDisplayMode('fullpage');
$pdf->SetFont('helvetica', '', 10);
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHtmlHeader($agency['name']);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(5 );
$pdf->SetFooterMargin(1);
//$pdf->setPrintHeader(true);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-20);
$pdf->AddPage('P', 'A4');
// set cell padding
$pdf->setCellPaddings(3, 3, 3, 3);

// set cell margins
$pdf->setCellMargins(1, 1, 1, 1);

// set color for background
$pdf->SetFillColor(255, 255, 127);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pgurlId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$html = curl_exec($ch);
curl_close($ch);
/* $js = <<<EOD
{
alert('welcome);
}
EOD;
$pdf->IncludeJS($js); */
//header('Content-Disposition: attachment; filename="downloaded.pdf"');
//header('Content-Disposition: attachment; filename=document-name.pdf');
//$pdf->writeHTML($html, true, false, true, false, '');
//Firma
if (strlen($contractor['sign'])>0 && $agent_settings['sign']==1){
  /*
  $contractor['sign']=substr($contractor['sign'],22);
  $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $contractor['sign']));
  $imgdata = base64_decode($contractor['sign']);
  $file = 'tmp/signature.png';
  file_put_contents($file, $data);
  $im = imagecreatefrompng($file);
  $black = imagecolorallocate($im, 255, 255, 255);
  $size = intval(min(imagesx($im), imagesy($im)));
  $middle=intval($size/2)+200;
  $im2 = mycrop($im, ['x' => $middle+100, 'y' => 0, 'width' => $size+400, 'height' => $size]);
  if ($im2 !== FALSE) {
      imagecolortransparent($im2, $black);
      imagealphablending($im2, false);
      imagepng($im2, $file);
  }
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  */
  //$file="../service/file_down.php?file=".$contractor['sign'] ."&tipo=firma&entity=contract&entity_key=". $_GET['id'] ."&".http_build_query(array('pInfo' => $_REQUEST['pInfo']));
  $file="../uploads/document/contract_".$_GET['id'].DS.'firma'.DS.$contractor['sign'];
  $data=file_get_contents($file);
  $file = $contractor['sign'];
  file_put_contents($file, $data);
  $sign='<img height="120" src="'.$file.'" />';
}
if (strlen($agent['sign'])>0 && $agent_settings['sign']==1){
  /*
  $agent['sign']=substr($agent['sign'],22);
  $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $agent['sign']));
  $imgdata = base64_decode($agent['sign']);
  $file = 'tmp/agent_signature.png';
  file_put_contents($file, $data);
  $im = imagecreatefrompng($file);
  $black = imagecolorallocate($im, 255, 255, 255);
  $size = intval(min(imagesx($im), imagesy($im)));
  $middle=intval($size/2)+200;
  $im2 = mycrop($im, ['x' => $middle+100, 'y' => 0, 'width' => $size+400, 'height' => $size]);
  if ($im2 !== FALSE) {
      imagecolortransparent($im2, $black);
      imagealphablending($im2, false);
      imagepng($im2, $file);
  }
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  */
  //$file="../service/file_down.php?file=".$agent['sign'] ."&tipo=firma&entity=users&entity_key=". $agent['user_id'] ."&". http_build_query(array('pInfo' => $_REQUEST['pInfo']));
  $file="../uploads/document/users_".$agent['user_id'].DS. "firma".DS.$agent['sign'];
  $data=file_get_contents($file);
  $file = $agent['sign'];
  file_put_contents($file, $data);
  $agent_sign='<img height="120" src="'.$file.'" />';
}
else {
  $agent_sign='';
}
switch (strtolower($agency['tipo_cliente_app'])){
  case 'agenzia assicurazioni':
  switch (strtolower($agent_settings['country'])){
    case 'san marino':
    include ('kyc_sm.php');
    break;
    case 'italia':
    include ('kyc_ita.php');
    break;
    default:
    include ('kyc_ita.php');
    break;
  }
  break;
  case 'studio commercialisti':
  switch (strtolower($agent_settings['country'])){
    case 'san marino':
    include ('kyc_sm.php');
    break;
    case 'italia':
    include ('kyc_ita.php');
    break;
    default:
    include ('kyc_ita.php');
    break;
  }
  break;
  case 'studio notarile':
  switch (strtolower($agent_settings['country'])){
    case 'san marino':
    include ('kyc_sm.php');
    break;
    case 'italia':
    include ('kyc_ita.php');
    break;
    default:
    include ('kyc_ita.php');
    break;

  }
  break;
  default:
  switch (strtolower($agent_settings['country'])){
    case 'san marino':
    include ('kyc_sm.php');
    break;
    case 'italia':
    include ('kyc_ita.php');
    break;
    default:
    include ('kyc_ita.php');
    break;

  }
  break;
}

//ob_clean();
ob_start();
$pdf->Output();
$file=ob_get_contents();
$fn='tmp/kyc'.$contract['id'].'-'.$contract['agency_id'].'.pdf';
if ($_REQUEST['download']=="Y"){
  header('Content-type: '.mime_content_type_ax($fn));
  header('Content-Disposition: attachment; filename='.$fn);

}
echo $file;
@unlink($agent['sign']);
@unlink($contractor['sign']);

file_put_contents($fn,$file);
error_log("nome file".$fn.PHP_EOL);
$dati=array('mailto'=>array($contractor['email'],$agent['email']),
'body'=>'In Allegato modulo PDF di cui si è richiesta la stampa per il cliente',
'subject'=>'Modulo di Adeguata Verifica per CPU',

'CPU'=>$contract['CPU'],
'fullname'=>$contractor['fullname'],
'file'=>$fn);
error_log("send... file".$agent_settings['sendKycRisk'].PHP_EOL);
if ($agent_settings['sendKycRisk']==1)
  email_attach($dati);

?>
