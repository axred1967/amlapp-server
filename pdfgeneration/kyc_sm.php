<?php
$aggK='';
if (substr($kyc_update['state'],0,3)=='agg'){
  $aggK="Aggiornamento ";
}
//error_log("firme"."contractor".$contractor['sign'].PHP_EOL."agent". $agent_sign);
$pdf->SetFillColor(255, 255, 255);
$pdf->writeHTMLCell(80,80, 118, 215, $sign, 0, 0, 1, true, 'L', true);
//error_log("signature".$agent_settings['sign']. $sign.$agent_sign. PHP_EOL);
$pdf->writeHTMLCell(80,80, 118, 225, $agent_sign, 0, 0, 1, true, 'L', true);
$pdf->SetFont('helvetica', '', 13);

$pdf->SetFillColor(180, 180, 180);
$pdf->SetY(8);
$pdf->SetX(5);
$txt=$kyc_update['state']. " Scheda Cliente per persona fisica";
if ($contract['act_for_other']==2){
  $txt=$kyc_update['state']. " Scheda Cliente per delegato di persone fisiche";
}
if ($contract['act_for_other']==1){
  $txt=$kyc_update['state']. " Scheda Cliente per Persona Giuridica";
}
$txt.="<sup>1</sup>";
$pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);

$pdf->SetFillColor(255, 255, 255);
$y = $pdf->getY()+16;


$pdf->SetFont('helvetica', '', 10);


$txt="<p style='line-height:1px'>Codice Progressivo Univoco</p>
<p>(CPU):  N.<b>".$contract['CPU']." </b> del <b>".date('d/m/Y',strtotime($contract['contract_date']) )."</p>";
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
$pdf->writeHTMLCell(75, 4, '', $y, $txt, 1, 0, 1, true, 'J', true);
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

//$this->MultiCell(60, 0, $text,1, 'R', 1, 2, 0, '', true, 0);
$dt= "Data di identificazione: ";
if (substr($kyc_update['state'],0,3)=='agg'){
  $dt="Data di Aggiornamento: ";
}
error_log("kyc_update:".print_r($kyc_update,1).PHP_EOL);

$txt="<p>Luogo di identificazione:<b>".$kyc['place_of_identification']."</b></p>
<p>$dt<b>".date('d/m/Y',strtotime($kyc['date_of_identification']))."</b></p>";
$x = $pdf->getX();
$pdf->writeHTMLCell(105,2, $x, '', $txt, 0, 0, 1, true, 'R', true);

$style = array('width' => 0.2, 'color' => array(180,180,180));
$pdf->SetLineStyle($style);

$y=$pdf->getY()+30;
if (substr($kyc_update['state'],0,3)=='agg'){
  $y = $pdf->getY()+20;
  $txt="Motivo aggiornamento:";
  $pdf->writeHTMLCell(90,1, 108, $y, $txt, 0, 0, 1, true, 'R', true);
  $y = $pdf->getY()+8;
  $txt= $kyc_update['updateReasons'];
  $pdf->writeHTMLCell(180,1, 12, $y, $txt, 1, 0, 1, true, 'j', true);
  $y = $pdf->getY()+17;
}
$pdf->SetY($y);

//$pdf->writeHTMLCell(180, 4, '', $y, $txt, 0, 0, 1, true, 'L', true);
$pdf->MultiRow("Nome", $contractor['name'],0,1);
$pdf->MultiRow("Cognome", $contractor['surname'],0,1);
$pdf->MultiRow("Data e Luogo di nascita", date('d/m/Y',strtotime($contractor['dob']))." a: " .$contractor['birth_town'] ." - " .ucfirst($contractor['birth_country']) ,0,1);
$pdf->MultiRow("Residenza Anagrafica",$contractor['address_resi']." - ". $contractor['resi_town'] ."<br/>".ucfirst($contractor['resi_country']),0,1,2);
if ($contractor_data['check_residence'])
$txt="Domicilio e residenza coincidono";
else
$txt=$contractor['address_domi']." - ". $contractor['town_domi'] ."<br/>".ucfirst($contractor['domi_country']);



$pdf->MultiRow("Domicilio<br/> <i>Se diverso da Residenza</i>", $txt,0,1,2);
$txt="";
if (strlen($contractor['tel'])>0)
$txt.=" - Tel:".$contractor['tel'];
if (strlen($contractor['mobile'])>0)
$txt.=" - Mobile:".$contractor['mobile'];


$pdf->MultiRow("Recapiti Telefonici", $txt,0,1);
$txt=$contractor['profession'] . " - " . ucfirst($contractor['main_activity_country']);
$pdf->MultiRow("Tipo di Professione e zona geografica nella quale si svolge prevalentemente", $txt,0,1,2);
$txt=$contractor['id_type'] . " - N." .$contractor['id_number'].
"<br/>Rilasciata il:". date('d/m/Y',strtotime($contractor['id_release_date'])) . " - Validità:". date('d/m/Y',strtotime($contractor['id_validity'])).
"<br/>Rilasciata da: ". $contractor['id_authority_name'];
$pdf->MultiRow("Tipologia ed estremi del documento di riconoscimento", $txt,0,1,3);

$txt="per se stesso";
switch ($contract['act_for_other']){
  case 0:
  $txt="<b>per se stesso</b>";
  break;
  case 1:
  $txt=strtoupper("<b>per conto di una persona giuridica</b><br/>");
  $txt.="in qualità di <b>" .strtoupper($contract['role_for_other'])."</b>";
  break;
  case 2:
  $txt=strtoupper("<b>per contro di altre persone fisiche</b><br/>");
  $txt.="in qualità di <b>" .strtoupper($contract['role_for_other'])."</b>";
  break;
}
$pdf->MultiRow("Dichiara di Agire ", ($txt),0,1,2);
$pdf->MultiRow("Note Eventuali", "",0,1);


$y = $pdf->getY()+5;
$style = array('width' => 0.2, 'color' => array(62,62,62));
$pdf->SetLineStyle($style);
$pdf->Ln(15);
$y = $pdf->getY();
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
//195,1, 5, $y, $txt, 1, 0, 1, true, 'j', true)
//$pdf->writeHTMLCell(80,80, 118, 215, $sign, 0, 0, 1, true, 'L', true);
//error_log("signature".$agent_settings['sign']. $sign.$agent_sign. PHP_EOL);
//$pdf->writeHTMLCell(80,80, 118, 225, $agent_sign, 0, 0, 1, true, 'L', true);

$pdf->writeHTMLCell(90,1,30,232,"Firma Cliente o di chi lo rappresenta:", 0,0,1,true,"R",true);
$pdf->Line(120, 232+8, 190, 232+8, $style);
$pdf->writeHTMLCell(90,1,30,247,"Firma del professionista o del collaboratore:", 0,0,1,true,"R",true);
$pdf->Line(120, 247+8, 190, 247+8, $style);
//$pdf->MultiCell(70,1,"Firma del professionista o del collaboratore:", 10, 'R', 0, '', 40, $y+12, true, 0);
//$pdf->Line(120, $y+20, 190, $y+20, $style);
/*
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
$pdf->MultiCell(70,1,"Firma Cliente o di chi lo rappresenta:", 10, 'R', 0, '', 0, $y, true, 0);
$pdf->Line(70, $y+8, 140, $y+8, $style);
$pdf->MultiCell(70,1,"Firma del professionista o del collaboratore:", 10, 'R', 0, '', 0, $y+12, true, 0);
$pdf->Line(70, $y+20, 140, $y+20, $style);
*/
//$pdf->Image($file);
//$pdf->Image("FB.jpg");

$pdf->SetFont('helvetica', '', 7);
$pdf->SetY(-35);
$y=$pdf->getY();
$style = array('width' => 0.2, 'color' => array(180,180,180));
$pdf->Line(4, $y, 44, $y, $style);

$txt="<p><sup>1</sup>Ai sensi dell’art.22, comma 2 della Legge n. 92/2008 “La clientela ha l’obbligo di fornire sotto la propria personale responsabilità in forma scritta,tutti i dati e le informazioni necessari e aggiornati per consentire ai soggetti designati di adempiere agli obblighi previsti dalla legge. Ai sensi dell’art. 54 della Legge n. 92/2008 “ 1. Salvo che il fatto costituisca più grave reato è punito con la prigionia o con la multa a giorni di  secondo grado chiunque omette di indicare le generalità del soggetto per conto del quale esegue l’operazione o le indica false, omette di indicare il titolare effettivo o lo indica falso. 2. La stessa pena prevista dal comma precedente si applica a chiunque non fornisce informazioni sullo scopo e  sulla natura del rapporto continuativo o dell’operazione occasionale ai sensi dell’art. 61 comma 4 delle legge n. 92/2008 “Salvo quanto previsto dall’articolo 54, la violazione degli obblighi di fornire informazioni necessarie per consentire l’adempimento degli   obblighi di adeguata verifica della clientela è punita con la sanzione amministrativa pecuniaria da 5.000,00 a 80.000,00 euro</p>";
//$pdf->MultiCell(200, 0, $txt,0, 'J', 1, 2, 0, '', true, 0);
$pdf->writeHTMLCell(200, 4, 5, '', html_entity_decode($txt), 0, 0, 1, true, 'J', true);


if ($contract['act_for_other']<>0){
  $pdf->AddPage('P', 'A4');
//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
//  $pdf->Image($file, 45, 164, 937, 150, '', '', '', true, 300, '', false, false, 0, false, false, true);
  $pdf->writeHTMLCell(80,80, 118, 181, $sign, 0, 0, 1, true, 'L', true);
  $pdf->writeHTMLCell(80,80, 118, 191, $agent_sign, 0, 0, 1, true, 'L', true);

  $pdf->SetFont('helvetica', '', 11);
  $pdf->SetFillColor(180, 180, 180);
  $pdf->SetY(10);
  $pdf->SetX(5);
  $txt=strtoupper("Dati identificativi dei Titolari Effettivi")."<sup>2</sup>";
  // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
  $pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode($txt), 0, 0, 1, true, 'C', true);


  //$pdf->MultiCell(190,1,$txt, 0, 'C', 80, '', '', '', true, 0);
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->Ln(20);
  $txt= "<b>".$contract['role_for_other'] ."</b>";
  $pdf->MultiRow("Il Sottoscritto dichiara che agisce in qualità di:", strtoupper($txt),0,1);
  //$txt="Il Sottoscritto dichiara di agire per conto di  " . $contract['own'];
  if ($contract['act_for_other']==1){
    $txt= "<b>" . strtoupper($company['name']) ."</b>";
    $pdf->MultiRow("della Persona Giuridica: ", $txt,0,1);
    $txt="con i seguenti n.".count($other)." titolari effettivi: ";
    if (count($other)==1) $txt="con il seguente titolare effettivo: ";
    $pdf->Ln(0);
    $pdf->writeHTMLCell(190, 1, 10, '', html_entity_decode(strtoupper("</b>".$txt."</b>")), 0, 0, 1, true, 'J', true);

  }

  if($contract['act_for_other']==2){
      $txt="per i seguenti n.".count($other)." titolari effettivi: ";
      if (count($other)==1) $txt="per il seguente titolare effettivo: ";
      $pdf->Ln(0);

      $pdf->writeHTMLCell(190, 1, 10, '', html_entity_decode($txt), 0, 0, 1, true, 'J', true);
  }
  $i=0;
  foreach ($other as $oth){
    if ($i>0){
      $pdf->AddPage('P', 'A4');
      $pdf->writeHTMLCell(80,80, 118, 181, $sign, 0, 0, 1, true, 'L', true);
      $pdf->writeHTMLCell(80,80, 118, 191, $agent_sign, 0, 0, 1, true, 'L', true);
      $pdf->SetFont('helvetica', '', 11);
      $pdf->SetFillColor(180, 180, 180);
      $pdf->SetY(10);
      $pdf->SetX(5);
      $txt=strtoupper("Dati identificativi del Titolari Effettivo ")."N.".($i+1)."<sup>2</sup>";
      $pdf->writeHTMLCell(190, 1, '', '', html_entity_decode($txt), 0, 0, 1, true, 'C', true);
      $pdf->SetFillColor(255, 255, 255);
      $pdf->SetFont('helvetica', '', 10);
      $pdf->Ln(13);
      //$pdf->MultiCell(190,1,$txt, 0, 'C', 80, '', '', '', true, 0);
//      $pdf->Image($file, 45, 164, 937, 150, '', '', '', true, 300, '', false, false, 0, false, false, true);

    }
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('helvetica', '', 10);

    $pdf->Ln(10);
    $pdf->MultiRow("Nome", $other[$i]['name'],0,1);
    $pdf->MultiRow("Cognome", $other[$i]['surname'],0,1);
    $pdf->MultiRow("Data e Luogo di nascita", date('d/m/Y',strtotime($other[$i]['dob']))." a: " .$other[$i]['birth_town'] ." - " .ucfirst($other[$i]['birth_country']) ,0,1);
    $pdf->MultiRow("Residenza Anagrafica",$other[$i]['address_resi']." - ". $other[$i]['town_resi'] ."<br/>".ucfirst($other[$i]['resi_country']),0,1,2);
    if ($contractor_data['check_residence'])
    $txt="Domicilio e residenza coincidono";
    else
    $txt=$other[$i]['address_domi']." - ". $other[$i]['town_domi'] ."<br/>".ucfirst($other[$i]['domi_country']);



    $pdf->MultiRow("Domicilio<br/><i> Se diverso da Residenza</i>", $txt,0,1,2);
    $txt="";
    if (strlen($other[$i]['tel'])>0)
    $txt.=" - Tel:".$other[$i]['tel'];
    if (strlen($other[$i]['mobile'])>0)
    $txt.=" - Mobile:".$other[$i]['mobile'];


    $pdf->MultiRow("Recapiti Telefonici", $txt,0,1);
    $txt=$other[$i]['profession'] . " - " . ucfirst($other[$i]['main_activity_country']);
    $pdf->MultiRow("Tipo di Professione e zona geografica nella quale si svolge prevalentemente", $txt,0,1,2);
    $txt=$other[$i]['id_type'] . " - N." .$other[$i]['id_number'].
    "<br/>Rilasciata il:". date('d/m/Y',strtotime($other[$i]['id_release_date'])) . " - Validità:". date('d/m/Y',strtotime($other[$i]['id_validity'])).
    "<br/>Rilasciata da: ". $other[$i]['id_authority_name'];
    $pdf->MultiRow("Tipologia ed estremi del documento di riconoscimento", $txt,0,1,3);


    $pdf->MultiRow("Note Eventuali", "",0,1);
    $y = $pdf->getY()+5;
    $style = array('width' => 0.2, 'color' => array(62,62,62));
    $pdf->SetLineStyle($style);
    $pdf->Ln(25);
    $y = $pdf->getY();
    // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
    $pdf->writeHTMLCell(90,1,30,222,"Firma Cliente o di chi lo rappresenta:", 0,0,1,true,"R",true);
    $pdf->Line(120, 222+8, 190, 222+8, $style);
    $pdf->writeHTMLCell(90,1,30,237,"Firma del professionista o del collaboratore:", 0,0,1,true,"R",true);
    $pdf->Line(120, 237+8, 190, 237+8, $style);

    $txt="<p><sup>2</sup>Ai sensi dell’art. 1, comma 1 lettera r) della Legge n.92/2008 e successive modificazioni, integrazioni ed istruzioni applicative (cfr. Istr. AIF n.5/2009, Istr. AIF n. 5/2010, Istr. AIF n. 6/2010 è “titolare effettivo”: (I) la persona fisica che, in ultima istanza, possiede o controlla un cliente, quando questo è una persona giuridica o un ente privo di personalità giuridica; (II) la persona fisica per conto della quale il cliente agisce. In ogni caso, si considera titolare effettivo: 1) la persona fisica o le persone fisiche che, direttamente o indirettamente, sono titolari di più del 25% dei diritti di voto in una società o comunque, per effetto di accordi o in altro modo, sono in grado di esercitare diritti di voto pari a tale percentuale o di avere il controllo sulla direzione della società, purché non si tratti di una società ammessa alla quotazione su un mercato regolamentato sottoposta a obblighi di comunicazione conformi o equivalenti a quelli previsti dalla normativa comunitaria; 2) la persona fisica o le persone fisiche beneficiarie di più del 25% del patrimonio di una fondazione, di un trust o di altri enti con o senza personalità giuridica che amministrino fondi, ovvero, qualora i beneficiari non siano ancora determinati, la persona o le persone fisiche nel cui interesse principale è istituito o agisce l’ente; 3) la persona fisica o le persone fisiche che sono in grado di esercitare un controllo di più del 25% del patrimonio di un ente con o senza personalità giuridica</p>";
    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetY(-45);
    $y=$pdf->getY();
    $style = array('width' => 0.2, 'color' => array(180,180,180));
    $pdf->SetLineStyle($style);
    $pdf->Line(4, $y, 44, $y, $style);
    $pdf->writeHTMLCell(200, 4, 5, '', html_entity_decode($txt), 0, 0, 1, true, 'J', true);

    //$pdf->MultiCell(200, 0, $txt,0, 'J', 1, 2, 0, '', true, 0);
    $i++;
  }
}
  $pdf->AddPage('P', 'A4');
  $pdf->writeHTMLCell(80,80, 118, 185, $sign, 0, 0, 1, true, 'L', true);
  $pdf->writeHTMLCell(80,80, 118, 195, $agent_sign, 0, 0, 1, true, 'L', true);
  $pdf->SetFont('helvetica', '', 11);
  $pdf->SetFillColor(180, 180, 180);
  $pdf->SetY(10);
  $pdf->SetX(5);
  $txt=strtoupper("informazioni sulla operazione");
  $pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
//  $pdf->MultiCell(190,1,$txt, 0, 'C', 80, '', '', '', true, 0);
//  $pdf->Image($file, 15, 154, 937, 150, '', '', '', true, 300, '', false, false, 0, false, false, true);

$pdf->Ln(14);
$pdf->SetFont('helvetica', '', 10);

setlocale(LC_MONETARY, 'it_IT');
$txt= money_format('%.2n', $contract['contract_value']) ;
if ($contract['value_det']!=1)
  $txt= "NON DETERMINABILE";
$pdf->SetFillColor(255, 255, 255);

//$pdf->MultiRow("Valore economico dell'operazione", $txt,0,1);
$pdf->writeHTMLCell(70, 3, 10, '', html_entity_decode(("Valore economico dell'operazione: ")), 0, 0, 1, true, 'L', true);

$pdf->writeHTMLCell(75, 3, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
$y=$pdf->getY();
$pdf->Line(70, $y+9, 190, $y+9, $style);

$txt=("L’area geografica in cui si deve svolgere l’operazione (indicare lo Stato)");
$pdf->Ln(8);
$pdf->writeHTMLCell(130, 1, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
//$pdf->MultiCell(190,1,$txt, 0, 'L', 80, '', '', '', true, 0);

$txt=ucfirst($contract['activity_country']);
$pdf->writeHTMLCell(50, 1, 130, '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'J', true);
//$pdf->MultiCell(190,1,$txt, 0, 'L', 0, '', '', '', true, 0);
$y=$pdf->getY();
$pdf->Line(130, $y+9, 190, $y+9, $style);

$txt="Il sottoscritto dichiara  ";
if ($contractor['check_pep']==1)
$txt.= "di <b>ESSERE</b> “Persona Politicamente Esposta“<sup>3</sup>";
else
$txt.= "di <b>NON ESSERE</b> “Persona Politicamente Esposta“<sup>3</sup>";

$txt.=" ai sensi dell’articolo 1 comma 1 lettera N della Legge 17/06/2008 n° 92 e dell’allegato tecnico della predetta legge.\n ";
$pdf->Ln(8);
$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
//$pdf->MultiCell(180,3,$txt, 0, 'L', 0, '', '', '', true, 0);


if($contract['act_for_other']>0){
  $txt="Il sottoscritto inoltre dichiara  ";
  $pep=false;
  $npep=0;
  foreach ($other as $key => $value) {
      if ($value['check_pep']==1)
        $pep=true;
        $npep++;
  }
  if ($pep) {
    if ($npep>1)
    $txt.= 'che un  Titolare Effettivo <b style="color:#7b0d1a">RISULTA ESSERE</b> “Persona Politicamente Esposta“<sup>3</sup>';
    else
    $txt.='che ' . numero_lettere($npep). ' Titolari Effettivi <b>RISULTANO ESSERE</b> “Persone Politicamente Esposte“<sup>3</sup>';

  }
  else{
    $txt.= ' tra i titolari effettivi <b style="color:#1c224b">NON RISULTANO ESSERE</b> “Persone Politicamente Esposte“<sup>3</sup>';

  }
  $txt.=" ai sensi dell’articolo 1 comma 1 lettera N della Legge 17/06/2008 n° 92 e dell’allegato tecnico della predetta legge.\n ";
  $pdf->Ln(12);
  $pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);

}

//$pdf->MultiCell(180,3,$txt, 0, 'J', 0, '', '', '', true, 0);
$pdf->Ln(20);
$txt="Il sottoscritto dichiara, inoltre,<br/>1) che la natura dell’operazione per la quale si richiede la prestazione è:";
$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
//$pdf->MultiCell(180,2,$txt, 0, 'L', 80, '', '', '', true, 0);

//$pdf->MultiCell(180,2,$txt, 0, 'L', 80, '', '', '', true, 0);
$txt=strtoupper($contract['nature_contract']);
//$pdf->Ln(8);
$y=$pdf->getY();
$pdf->writeHTMLCell(180, 3, 10, $y+10, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
//$pdf->MultiCell(190,1,$txt, 0, 'L', 0, '', '', '', true, 0);
$y=$pdf->getY();
$pdf->Line(14, $y+9, 184, $y+9, $style);
$pdf->Line(14, $y+14, 184, $y+14, $style);
$pdf->Ln(12);
$txt="2) che lo scopo dell’operazione è:";
//$pdf->MultiCell(180,1,$txt, 0, 'L', 80, '', '', '', true, 0);
$pdf->writeHTMLCell(130, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$txt=strtoupper($contract['scope_contract']);
$y=$pdf->getY();
//$pdf->Ln(8);
//$pdf->MultiCell(190,1,$txt, 0, 'L', 0, '', '', '', true, 0);
$pdf->writeHTMLCell(135, 3, 10, $y+5, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$y=$pdf->getY();
$pdf->Line(14, $y+9, 184, $y+9, $style);
$pdf->Line(14, $y+14, 184, $y+14, $style);
$pdf->Ln(12);
$txt="3) ai sensi della vigente normativa antiriciclaggio, sotto la propria personale responsabilità, la veridicità dei dati, delle informazioni fornite e delle dichiarazioni rilasciate e in particolare di quanto dichiarato in relazione alle persone fisiche per conto delle quali, eventualmente, opera, impegnandosi altresì ad informarVi di eventuali variazioni che dovessero interessare i dati di cui sopra (cfr. Nota n.1 sugli obblighi di comunicazione della clientela ed apparato sanzionatorio ex art. 22, comma 2 , art. 54 ed art.61 comma 4 della Legge n. 92/2008).\n";
//$pdf->MultiCell(180,4,$txt, 0, 'J', 80, '', '', '', true, 0);
$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$txt="4) di confermare che i fondi e le risorse economiche eventualmente utilizzati per lo svolgimento dell’operazione sono compatibili con il reddito e la situazione patrimoniale del Cliente";
$pdf->Ln(28);
$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$txt='5) di essere stato informato della circostanza che il mancato rilascio in tutto o in parte delle informazioni di cui sopra può pregiudicare la capacità della Società di dare esecuzione alla prestazione richiesta e si impegna a comunicare senza ritardo ogni eventuale integrazione o variazione che si dovesse verificare in relazione ai dati forniti con la presente dichiarazione;';
$pdf->Ln(10);
$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);

$txt='6) di prestare il consenso al trattamento dei dati personali riportati nella presente dichiarazione e di quelli che saranno eventualmente in futuro forniti ad integrazione e/o modifica degli stessi. Il sottoscritto prende altresì atto che la comunicazione a terzi dei dati personali sarà effettuata dal professionista esclusivamente in adempimento degli obblighi di legge.';
$pdf->Ln(20);
$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);

$style = array('width' => 0.2, 'color' => array(62,62,62));
$pdf->SetLineStyle($style);
$pdf->Ln(25);
$txt='Data: '. date('d/m/Y');

$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$pdf->Ln(10);
$y = $pdf->getY();
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
$pdf->MultiCell(70,1,"Firma Cliente o di chi lo rappresenta:", 10, 'R', 0, '', 40, $y, true, 0);
$pdf->Line(120, $y+8, 190, $y+8, $style);
$pdf->MultiCell(80,1,"Firma del professionista o del collaboratore:", 10, 'R', 0, '', 40, $y+12, true, 0);
$pdf->Line(120, $y+20, 190, $y+20, $style);
$pdf->SetFont('helvetica', '', 9);
$pdf->MultiCell(140,1,"La presente scheda è stata compilata e sottoscritta dal cliente alla presenza di:", 0, 'R', 0, '', -20, $y+22, true, 0);
$pdf->Line(120, $y+30, 190, $y+30, $style);


$txt="<sup>3</sup> Ai sensi dell’art. 1, comma 1 lettera n) della Legge 92/2008 e successive modificazioni ed integrazioni è “n) “persona politicamente esposta”: la persona fisica, individuata sulla base dei criteri di cui all'allegato tecnico alla presente legge, che occupa o ha occupato, a San Marino o all’estero, importanti cariche pubbliche”. Ai sensi dell’Allegato Tecnico alla Legge 17 giugno 2008 n. 92 Art. 1 1. Per “persona politicamente esposta” si intende la persona fisica, che occupa o ha occupato, a San Marino o all’estero, importanti cariche pubbliche, comprese quelle di seguito indicate, anche se diversamente denominate: 1) capo di Stato, capo di Governo, ministro, vice ministro, sottosegretario, parlamentare, alto funzionario di partito politico o politico di alto livello. 2) membro di organi giudiziari le cui decisioni non sono generalmente soggette ad ulteriore impugnazione, 3) membro di consiglio di amministrazione di banche centrali o di autorità di vigilanza, 4) ambasciatore, incaricato d’affari, ufficiale di alto livello delle forze armate, 5) membro di organi di amministrazione, direzione o vigilanza di imprese possedute dallo Stato, 6) membro di direzione, di consiglio
di amministrazione o avente equivalente posizione apicale in un'organizzazione internazionale; 2. Devono essere trattate come persone politicamente esposte le seguenti persone: a) i familiari diretti delle persone indicate al comma precedente o coloro con i quali tali persone intrattengono notoriamente stretti legami, inclusi i seguenti soggetti: 1) il coniuge o il partner considerato equivalente al coniuge, 2) i figli e i loro coniugi, 3) i genitori; b) la persona fisica che notoriamente abbia  con una persona di cui al precedente comma 1 la titolarità effettiva di società o entità giuridiche; c) la persona fisica che sia unico titolare effettivo di società o entità giuridiche o istituti giuridici notoriamente creati di fatto a beneficio di una delle persone di cui al precedente comma 1. 3. La cessazione della carica non esonera i soggetti designati dall’adempiere, in funzione del rischio, gli obblighi rafforzati di adeguata verifica della clientela.”. 4. Non rientrano nella definizione di cui al comma 1 del presente articolo le persone fisiche che ricoprono le precedenti cariche a livello inferiore a quelli di vertice.”";
$pdf->SetFont('helvetica', '', 7);
$pdf->SetY(-52);
$y=$pdf->getY();
$style = array('width' => 0.2, 'color' => array(180,180,180));
$pdf->SetLineStyle($style);
$pdf->Line(4, $y, 44, $y, $style);
$pdf->writeHTMLCell(200, 4, 5, '', html_entity_decode($txt), 0, 0, 1, true, 'J', true);

?>
