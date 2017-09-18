<?php
function cmp($a, $b)
{
    return strcmp($a["cliente"], $b["cliente"]);
}

$_REQUEST['action']='login';
include_once("../config.php");
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
    $this->SetFont('times', 'I', 7);
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
  }


}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetTitle('Amlapp - Documenti Scaduti ');
$pdf->SetDisplayMode('fullpage');
$pdf->SetFont('times', '', 10);
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
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-20);
$pdf->AddPage('P', 'A4');
// set cell padding
$pdf->setCellPaddings(3, 3, 3, 3);
// set cell margins
$pdf->setCellMargins(1, 1, 1, 1);
// set color for background
$pdf->SetFillColor(255, 255, 127);

$sql="SELECT a.*  from agency as a " ;
$agencies=$db->getRows($sql);


foreach ($agencies as $agency){
  $sql="SELECT co.* FROM  contract as co where  co.agency_id=" .$agency['agency_id'] ." order by agent_id ASC"  ;
  $contracts=$db->getRows($sql);
  $mail_admin="" ;
  $agent='';
  // gestione tabella PDF
  $pdf->Ln(13);
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->SetFont('helvetica', '', 11);
  $pdf->SetFillColor(180, 180, 180);
  $pdf->Ln(13);
  $txt="Aspetti Connessi al Clientee";
  $pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->SetFillColor(255, 255, 255);
  $pdf->Ln(13);
  $data=date('m/d/Y');
  $mail_admin= <<<EOD
  <h2>Documenti Identità scaduti per i seguenti contratti</h2>
  <h4>Elaborazione del $data </h4>
            <table align="left" cellspacing='0' cellpadding="10" >
                <tr  style="font-size:14px;font-weight:bold;color:white;background-color:#1C224B;" >
                  <td style="border: 1px solid black;" width="55%" >Nome Cliente</td>
                  <td style="border: 1px solid black;" width="15%">CPU</td>
                  <td style="border: 1px solid black;"width="15%" >Numero Contratto</td>
                  <td style="border: 1px solid black;"width="15%" >Scaduto il</td>
                </tr>

EOD;
$header=$mail_admin;
$list_mail_admin=array();
  foreach ($contracts as $contract){

    if ($agent!=$contract['agent_id']){
      $agent=$contract['agent_id'];
      if ($agent>0 && strlen($mail_agent)>0){
        echo "invia mail Agente";
        $mail_agent.="</table>";
        $SQL="SELECT concat(us.name,' ',us.surname) as fullname, us.email FROM agent as a join users as us on us.user_id=a.user_id WHERE agent_id=".  $agent;
        $email=$db->getRow($SQL);
        $vars = array(
          'data' => date('d/m/Y'),
          'mail_obj'=>$mail_agent
        );

        //////error_log($request['action']."prima -".print_r($vars,1) .$sql.  PHP_EOL);
        //mail_template($email['email'],'add_customer',$vars, 'it');

      }
      $mail_agent="";
      $list_mail_agent=array();
    }

    echo "id:".$contract['id'].PHP_EOL;
//  print_r($contracts);
    $SQL="SELECT contractor_data,owner_data FROM kyc WHERE contract_id=".  $contract['id'] ;
    $kyc=$db->getRow($SQL);
    $contractor=json_decode($kyc['contractor_data'],true);
    $owners=json_decode($kyc['owner_data'],true);
    print_r($contractor);
    if ($contractor['id_validity']<date('Y-m-d')){
      echo "scaduta" .$contractor['id_validity'];
      $time = strtotime($contractor['id_validity']);
      $time = date("m/d/Y", $time);
      if ($contract['contractor_id']>0){
        $SQL="SELECT concat(us.name,' ',us.surname) as fullname, us.email FROM email WHERE user_id=".  $contractor['contractor_id'];
        $user=$db->getRow($SQL);
        array_push($list_mail_admin,array('cliente'=>$user['fullname'],'numCon'=>$contract['number'],'CPU'=>$contract['CPU'],"scaduta"=>$time));
        $mail_admin.='
            <tr>
              <td style="border: 1px solid black;" width="65%" >'. $user['fullname'] .'</td>
              <td style="border: 1px solid black;" width="20%" >'. $contract['number'] .'</td>
              <td style="border: 1px solid black;" width="15%" >'. $time .'</td>
            </tr>';
        $mail_agent.='
            <tr>
              <td style="border: 1px solid black;" width="65%" >'. $user['fullname'] .'</td>
              <td style="border: 1px solid black;" width="20%">'. $contract['number'] .'</td>
              <td style="border: 1px solid black;" width="15%">'. $time .'</td>
            </tr>';
        array_push($list_mail_agent,array('cliente'=>$user['fullname'],'numCon'=>$contract['number'],'CPU'=>$contract['CPU'],"scaduta"=>$time));

        if (strlen($user['email'])>0){
          // invia mail cliente
          echo "invia mail cliente";
          $vars = array(
            'name' => $user['fullname'],
            'agency_name' => $agency['agency_name'],
            'number' => $contract['number']
          );

          //////error_log($request['action']."prima -".print_r($vars,1) .$sql.  PHP_EOL);
          //mail_template($user['email'],'doc_scaduti_cliente',$vars, 'it');
        }

      }
      else{
        $mail_admin.='
            <tr>
              <td style="border: 1px solid black;" width="65%">'. $contract['NomeTemp'] .'</td>
              <td style="border: 1px solid black;" width="20%">'. $contract['number'] .'</td>
              <td style="border: 1px solid black;" width="15%">'. $time .'</td>
            </tr>';
        array_push($list_mail_admin,array('cliente'=> $contract['NomeTemp'],'numCon'=>$contract['number'],'CPU'=>$contract['CPU'],"scaduta"=>$time));
        $mail_agent.='
            <tr>
            <td style="border: 1px solid black;" width="65%">'. $contract['NomeTemp'] .'</td>
            <td style="border: 1px solid black;" width="20%">'. $contract['number'] .'</td>
            <td style="border: 1px solid black;" width="15%">'. $time .'</td>
            </tr>';
        array_push($list_mail_agent,array('cliente'=> $contract['NomeTemp'],'numCon'=>$contract['number'],'CPU'=>$contract['CPU'],"scaduta"=>$time));
      }
    }
    if (is_array($owner)){
  //    print_r($owners);
      foreach ($owners as $owner){
        if ($contractor['id_validity']<date('Y-m-d')){

        }

      }
    }
  }
  if (strlen($mail_admin)>0){
    echo "invia mail Admin";
    $mail_admin.="</table>";
    $SQL="SELECT concat(us.name,' ',us.surname) as fullname, us.email FROM users as us join agency as a on a.user_id=us.user_id  WHERE a.agency_id=".  $agency['agency_id'];
    $email=$db->getRow($SQL);
    echo "email:".$email['email'];
    $mail_admin=$header;
    print_r($list_mail_admin);
    usort($list_mail_admin, "cmp");
    foreach($list_mail_admin as $row){
      $mail_admin.='          <tr>
                <td style="border: 1px solid black;" width="55%">'. $row['cliente'] .'</td>
                <td style="border: 1px solid black;" width="15%">'. $row['CPU'] .'</td>
                <td style="border: 1px solid black;" width="15%">'. $row['numCon'] .'</td>
                <td style="border: 1px solid black;" width="15%">'. $row['scaduta'] .'</td>
                </tr>';
    }
    $mail_admin.="</table>";
    $vars = array(
      'data' => date('d/m/Y'),
      'mail_obj'=>$mail_admin
    );
    print_r($list_mail_admin);

echo $mail_admin;

    //////error_log($request['action']."prima -".print_r($vars,1) .$sql.  PHP_EOL);
    mail_template($email['email'].",axred1967@gmail.com",'doc_scaduti',$vars, 'it');
    $list_mail_admin=array();
    die();


  }
}


 ?>
