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
if ($_GET['agg']>0){
  $sql="SELECT * from risk_log where id='".$_GET['agg']."'";

}
else {
  $sql="SELECT * from risk where contract_id='".$_GET['id']."'";
}
$risk = $db->getRow($sql);


$rd=json_decode($risk['risk_data'],true);
$risk_update=json_decode($risk['risk_update'],true);
//error_log("risk".print_r($risk,1).PHP_EOL);
//error_log("risk_data".print_r($rd,1).PHP_EOL);

  $sql="SELECT * from kyc where contract_id='".$risk['contract_id']."'";

$kyc = $db->getRow($sql);
$company=json_decode($kyc['company_data'],true);
$other=json_decode($kyc['owner_data'],true);
$contractor=json_decode($kyc['contractor_data'],true);
$contract=json_decode($kyc['contract_data'],true);
//echo $sql;
//error_log("kyc:".print_r($kyc,1).PHP_EOL);
//error_log("contractor:".print_r($contractor,1).PHP_EOL);
//error_log("contract:".print_r($contract,1).PHP_EOL);
//error_log("other:".print_r($other,1).PHP_EOL);
//error_log("company".print_r($company,1).PHP_EOL);
$countryList = $db->getRows("SELECT * FROM countries ORDER BY country_name ASC");
//error_log("country".print_r($countrylist,1).PHP_EOL);
$cl=array();
foreach ($countryList as $countryVal) {

  $cl[$countryVal['country_id']]=$countryVal['country_name'];

}
$agent = $db->getRow("SELECT u.* FROM users u join agent a on a.user_id =u.user_id where a.agent_id=".$contract['agent_id']);
$agency = $db->getRow("SELECT u.* FROM users as u join agency as a on u.user_id=a.user_id  where a.agency_id=". $_GET['pInfo']['agency_id']);
//error_log("agent".print_r($agent,1).PHP_EOL);
//$agent_settings=json_decode($agent['settings'],true);
$agent_settings=json_decode($agency['settings'],true);
//error_log("agent".print_r($agent,1).PHP_EOL);
error_log("agent settings".print_r($agent_settings,1).PHP_EOL);

$agency= $db->getRow("SELECT u.* FROM users u join agency a on a.user_id=u.user_id where a.agency_id=". $contract['agency_id']);
//error_log("agency".print_r($agency,1).PHP_EOL);
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
    $this->SetFont('helvetica', 'B', 7);
    $this->SetTextColor(255,0,0);
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
  public function ColoredTable($header,$data) {
          // Colors, line width and bold font
          $this->SetFillColor(28, 34, 75);
          $this->SetTextColor(255);
          $this->SetDrawColor(45, 66, 206);
          $this->SetLineWidth(0.3);
          $this->SetFont('', 'B');
          // Header
          $w = array(140, 20, 20);
          $num_headers = count($header);
          for($i = 0; $i < $num_headers; ++$i) {
              $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
          }
          $this->Ln();
          // Color and font restoration
          $this->SetFillColor(224, 235, 255);
          $this->SetTextColor(0);
          $this->SetFont('');
          // Data
          $fill = 0;
          foreach($data as $row) {
              $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
              $this->Cell($w[1], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
              $this->Cell($w[2], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
              $this->Ln();
              $fill=!$fill;
          }
          $this->Cell(array_sum($w), 0, '', 'T');
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
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP-15, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(1);
$pdf->setPrintHeader(true);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-50);
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

//Firma Cliente
if (strlen($contractor['sign'])>0 && $agent_settings['sign']==1){
  $file="../uploads/document/contract_".$_GET['id'].DS.'firma'.DS.$contractor['sign'];
  $data=file_get_contents($file);
  $file = $contractor['sign'];
  file_put_contents($file, $data);
  $sign='<img height="120" src="'.$file.'" />';
}
else {
  $sign="";
}
if (strlen($agent['sign'])>0 && $agent_settings['sign']==1){
  $file="../uploads/document/users_".$agent['user_id'].DS. "firma".DS.$agent['sign'];
  $data=file_get_contents($file);
  $file = $agent['sign'];
  file_put_contents($file, $data);
  $agent_sign='<img height="120" src="'.$file.'" />';
}
else {
  $agent_sign='';
}
$aggK='';
if ($risk_update['state']=='aggiornamento'){
  $aggK="Aggiornamento ";
}
$pdf->SetFont('helvetica', '', 13);
$pdf->SetFillColor(180, 180, 180);
$pdf->SetY(10);
$pdf->SetX(5);
$txt=$aggK ."Valutazione del Rischio";
$txt.="<sup>1</sup>";
$pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);

$pdf->SetFillColor(255, 255, 255);

$y = $pdf->getY()+20;
$pdf->SetFont('helvetica', '', 10);

//die(print_r($risk));
$txt="<p style='line-height:1px'>Codice Progressivo Univoco</p>
<p>(CPU):  N.<b>".$contract['CPU']." </b> del <b>".date('d/m/Y',strtotime($contract['contract_date']) )."</p>";
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
$pdf->writeHTMLCell(75, 4, '', $y, $txt, 1, 0, 1, true, 'J', true);
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
$txt="<p>svolta a :<b>".$kyc['place_of_identification']."</b></p>
<p>il :<b>".date('d/m/Y',strtotime(substr($risk['risk_date'],0,10)))."</b></p>";
$x = $pdf->getX();
$pdf->writeHTMLCell(105,2, $x, '', $txt, 0, 0, 1, true, 'R', true);

$style = array('width' => 0.2, 'color' => array(180,180,180));
$pdf->SetLineStyle($style);

$y=$pdf->getY()+30;
$pdf->SetY($y);

//$this->MultiCell(60, 0, $text,1, 'R', 1, 2, 0, '', true, 0);
$txt=" svolta per il cliente: ";
$own=$contractor['name'] ." " . $contractor['surname'];
if ($contract['act_for_other']==1){
  $own=$company['name'];
}
if ($contract['act_for_other']==2){
  $own=$owner[0]['name'] ." " . $owner[0]['surname'];;
}
$pdf->MultiRow($txt,  $own,0,1);
$txt=" Identificazione svolta da: ";
$own=$agent['name'] ." " . $agent['surname'];
//error_log($contract['owner'].$contract['act_for_other']);
$pdf->MultiRow($txt,  $own,0,1);
switch ($agent_settings['risk_type']){
  case '1':
  include ('risk_sm.php');
  break;
  case '2':
  include ('risk_quarta.php');
  break;
  default:
  include ('risk_terza.php');

}
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(13);
$txt="Esito della Valutazione ". $aggK;
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);
$pdf->SetFillColor(255, 255, 255);
$pdf->Ln(10);
if (! $agency['name']=='Agenzia Generali di San Marino'){
  $txt="<b>" .$rd['riskCalculated']. "</b>";
  $pdf->MultiRow("Rischio Calcolato:", $txt,0,1);
  $pdf->Ln(10);

}
$txt="<b>" .$rd['riskAssigned']. "</b>";
$pdf->MultiRow("Rischio Assegnato:", $txt,0,1);
$pdf->Ln(10);
$txt='';
if ($risk_update['state']=='aggiornamento'){
  $txt='<b>Motivo del Aggiornamento:</b><div style="font-size:9px;line-height:10px">' .$risk_update['updateReasons'].'</div>';
}

$txt.='<b>Processo Valutativo:</b><div style="font-size:9px;line-height:10px">' .$rd['riskDescription'] .'</div>' ;

$txt.='<b>Note Eventuali:</b><div style="font-size:9px;line-height:10px">' .$rd['notes'].'</div>';
error_log("risk_de". $txt);
$pdf->writeHTMLCell(180, '', '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'l', true);




$txt='Data: '. date('d/m/Y');
$pdf->writeHTMLCell(40, 3, 20, 245, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$pdf->Ln(10);

$txt="Firma del Responsabile Incaricato:";
$pdf->writeHTMLCell(120, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$y=$pdf->getY();
$pdf->writeHTMLCell(80,80, 118, 235, $agent_sign, 0, 0, 1, true, 'L', true);
$pdf->Line(120, $y+8, 190, $y+8, $style);


//ob_clean();
ob_start();
$pdf->Output();
$file=ob_get_contents();
$fn='tmp/Risk'.$contract['contract_id'].'-'.$contract['agency_id'].'.pdf';
if ($_REQUEST['download']=="Y"){
  header('Content-type: '.mime_content_type_ax($fn));
  header('Content-Disposition: attachment; filename='.$fn);
}

echo $file;
file_put_contents($fn,$file);
@unlink($agent['sign']);
@unlink($contractor['sign']);
$dati=array(
'body'=>'In Allegato modulo PDF di cui si è richiesta la stampa per il cliente',
'subject'=>'Modulo di Valutazione del rischio per il  CPU',
'mailto'=>array($agent['email']),
'CPU'=>$contract['CPU'],'fullname'=>$contractor['fullname'],
'file'=>$fn);

if ($agent_settings['sendKycRisk']==1)
  email_attach($dati);

?>
