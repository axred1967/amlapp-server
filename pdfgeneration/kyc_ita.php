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
$txt="MODULO DI DICHIARAZIONI DEL CLIENTE IN RELAZIONE AGLI OBBLIGHI ANTIRICICLAGGIO";
$pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);

$pdf->SetFillColor(255, 255, 255);
$y = $pdf->getY()+18;


$pdf->SetFont('helvetica', '', 10);
$txt="PREVISTI DAL D.LGS. N. 231/2007 ED AI FINI DELLA NORMATIVA SULLA PRIVACY (D. LGS. 196/2003)";
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
$pdf->writeHTMLCell(185, 4, 10, $y, $txt, 0, 0, 1, true, 'C', true);
$y = $pdf->getY();
/*
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

$y=$pdf->getY()+20;
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

$pdf->SetFont('helvetica', '', 9);
*/
$txt="In ottemperanza alle disposizioni del d. lgs. 22/11/2007 n. 231 e successive modifiche ed integrazioni e del d. lgs. 30/06/2003 n. 196 e successive modifiche ed integrazioni, io sottoscritto fornisco, qui di seguito, le mie generalità e le sottostanti informazioni, consapevole del disposto dell’art. 22, comma 1 del d. lgs. 22/11/2007 n. 231<sup>1</sup> , assumendomi tutte le responsabilità di natura civile, amministrativa e penale per dichiarazioni non veritiere; consapevole altresì che i dati forniti dal sottoscritto potranno essere trattati nel rispetto della vigente normativa sulla privacy, anche con procedure informatizzate, per gli scopi e le finalità tutte richieste dal d. lgs. 22/11/2007 n. 231 e successive modifiche e integrazioni e provvedimenti attuativi.";
$pdf->writeHTMLCell(185, 4, 10, $y+10, $txt, 0, 0, 1, true, 'j', true);

$style = array('width' => 0.2, 'color' => array(180,180,180));
$y = $pdf->getY()+37;
$pdf->SetY($y);


$txt="Cognome e Nome:";
$pdf->writeHTMLCell(55, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
$pdf->writeHTMLCell(134, 4, 55, $y, $contractor['surname'] ." ". $contractor['name'], 0, 0, 1, true, 'j', true);
$pdf->Line(55, $y+9, 184, $y+9, $style);

$pdf->SetY($y+9);
$y = $pdf->getY();

$txt="Luogo e data di nascita:";
$pdf->writeHTMLCell(55, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
$pdf->writeHTMLCell(134, 4, 55, $y, $contractor['birth_town'] ." il ". date('d/m/Y',strtotime($contractor['dob'])), 0, 0, 1, true, 'j', true);
$pdf->Line(55, $y+9, 184, $y+9, $style);

$pdf->SetY($y+9);
$y = $pdf->getY();

$txt="Nazionalità:";
$pdf->writeHTMLCell(55, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
$pdf->writeHTMLCell(134, 4, 55, $y, $contractor['main_nationality'] , 0, 0, 1, true, 'j', true);
$pdf->Line(55, $y+9, 184, $y+9, $style);

$pdf->SetY($y+9);
$y = $pdf->getY();

$txt="Codice Fiscale/Equipollente:";
$pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
$pdf->writeHTMLCell(134, 4, 58, $y, $contractor['fiscal_number'] , 0, 0, 1, true, 'j', true);
$pdf->Line(58, $y+9, 184, $y+9, $style);

$pdf->SetY($y+9);
$y = $pdf->getY();

$txt="Tipo di documento di identificazione:";
$pdf->writeHTMLCell(72, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
$pdf->writeHTMLCell(112, 4, 72, $y, $contractor['id_type'] , 0, 0, 1, true, 'j', true);
$pdf->Line(72, $y+9, 184, $y+9, $style);

$pdf->SetY($y+9);
$y = $pdf->getY();

$txt="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Numero:&nbsp;&nbsp;". $contractor['id_number'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rilasciata da: &nbsp;&nbsp;&nbsp;" . $contractor['id_authority_name'] ;
$pdf->writeHTMLCell(180, 2, 10, $y, $txt, 0, 0, 1, true, 'j', true);
$pdf->Line(36, $y+9, 55, $y+9, $style);
$pdf->Line(83, $y+9, 184, $y+9, $style);


$pdf->SetY($y+9);
$y = $pdf->getY();
$txt="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; il &nbsp;&nbsp;". date('d/m/Y',strtotime($contractor['id_release_date']))
      ." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;in corso di validità fino al &nbsp;&nbsp;&nbsp; " .date('d/m/Y',strtotime($contractor['id_validity'])) ."";
      $pdf->writeHTMLCell(180, 2, 10, $y, $txt, 0, 0, 1, true, 'j', true);
      $pdf->Line(28, $y+9, 55, $y+9, $style);
      $pdf->Line(100, $y+9, 184, $y+9, $style);

      $pdf->SetY($y+9);
      $y = $pdf->getY();

      $txt="Residente in:";
      $pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
      $pdf->writeHTMLCell(112, 4, 58, $y, $contractor['resi_town'] , 0, 0, 1, true, 'j', true);
      $pdf->Line(58, $y+9, 184, $y+9, $style);

      $pdf->SetY($y+9);
      $y = $pdf->getY();

      $txt="indirizzo:";
      $pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
      $pdf->writeHTMLCell(112, 4, 58, $y, $contractor['address_resi'] , 0, 0, 1, true, 'j', true);
      $pdf->Line(58, $y+9, 184, $y+9, $style);

      $pdf->SetY($y+9);
      $y = $pdf->getY();

      $txt="stato:";
      $pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
      $pdf->writeHTMLCell(112, 4, 58, $y, $contractor['resi_country'] , 0, 0, 1, true, 'j', true);
      $pdf->Line(58, $y+9, 184, $y+9, $style);

      $pdf->SetY($y+9);
      $y = $pdf->getY();

      $txt="Professione/Attività svolta:";
      $pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
      $pdf->writeHTMLCell(112, 4, 58, $y, $contractor['profession'] , 0, 0, 1, true, 'j', true);
      $pdf->Line(58, $y+9, 184, $y+9, $style);

      $pdf->SetY($y+9);
      $y = $pdf->getY();

      $txt="Esercitata prevalentemente nell'ambito territoriale:";
      $pdf->writeHTMLCell(95, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
      $pdf->writeHTMLCell(112, 4, 95, $y, $contractor['main_activity_country'] , 0, 0, 1, true, 'j', true);
      $pdf->Line(95, $y+9, 184, $y+9, $style);
//$pdf->writeHTMLCell(180, 4, '', $y, $txt, 0, 0, 1, true, 'L', true);
/*
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
$pdf->MultiRow("Note Eventuali", "",0,1);
*/
$y = $pdf->getY();
$txt="Dichiaro Inoltre:";
$pdf->writeHTMLCell(190, 4, 10, $y+10, $txt, 0, 0, 1, true, 'j', true);
$pdf->SetY($y+9);
$y = $pdf->getY()+10;

$txt="1) Di agire ";
$pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);


switch ($contract['act_for_other']){
  case 0:
  $pdf->SetY($y+9);
  $y = $pdf->getY();

  $txt="<b>In Proprio</b>";
  $pdf->writeHTMLCell(180, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
  break;
  case 1:
  $txt=strtoupper("<b>per conto della seguente persona giuridica</b><br/>");
  $pdf->writeHTMLCell(134, 4, 58, $y, $txt , 0, 0, 1, true, 'j', true);
  $pdf->SetY($y+9);
  $y = $pdf->getY();

  $txt="Ragione sociale:";
  $pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
  $pdf->writeHTMLCell(134, 4, 58, $y, $company['name'] , 0, 0, 1, true, 'j', true);
  $pdf->Line(58, $y+9, 184, $y+9, $style);

  $pdf->SetY($y+9);
  $y = $pdf->getY();

  $txt="Con sede legale in:";
  $pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
  $pdf->writeHTMLCell(134, 4, 58, $y, $company['town'] ." alla via ". $company['address'] . " - " .$company['country'] , 0, 0, 1, true, 'j', true);
  $pdf->Line(58, $y+9, 184, $y+9, $style);
  $pdf->SetY($y+9);
  $y = $pdf->getY();

  $txt="Iscritta al registro delle imprese di:";
  $pdf->writeHTMLCell(67, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
  $pdf->writeHTMLCell(123, 4, 67, $y, $company['authorised_by'] ." al n°  ". $company['authorizationNumber']  , 0, 0, 1, true, 'j', true);
  $pdf->Line(67, $y+9, 184, $y+9, $style);

  $pdf->SetY($y+9);
  $y = $pdf->getY();

  $txt ="In qualità di " ;
  $pdf->writeHTMLCell(58, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
  $pdf->writeHTMLCell(70, 4, 58, $y, "<b>".strtoupper($contract['role_for_other'])."</b>"  , 0, 0, 1, true, 'j', true);
  $pdf->Line(58, $y+9, 128, $y+9, $style);
  $pdf->writeHTMLCell(70, 4, 129 , $y, "munito dei necessari poteri"  , 0, 0, 1, true, 'j', true);

  break;
  case 2:
  $txt=strtoupper("<b>Per conto della Persona fisica</b> di cui alla sezione Titolari Effettivi della prestazione,
</b>");
  if (count($other)>=1){
    $txt=strtoupper("<b>Per conto delle Persone fisiche</b> di cui alla sezione Titolari Effettivi della prestazione,
</b>");
  }
  $txt.="in qualità di <b>" .strtoupper($contract['role_for_other'])."</b>";
  $pdf->SetY($y+9);
  $y = $pdf->getY();

  $pdf->writeHTMLCell(180, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);

  break;
}
$pdf->Line(58, $y+9, 184, $y+9, $style);
$pep_type=substr($contractor['pep_type'],4);
$pep_charge=$contractor['pep_charge'];
$pep="“Persona Politicamente Esposta”<sup>2</sup> $pep_type ai sensi dell’articolo 1, comma 2, lett. dd) del d. lgs. 22/11/2007 n. 231 e successive modifiche e integrazioni, in quanto: ";
$txt="2) ";
if ($contractor['check_pep']==1)
$txt.= "di <b>ESSERE</b> $pep";
else
$txt.= "di <b>NON ESSERE</b> $pep";

$pdf->Ln(8);
$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
//$pdf->MultiCell(180,3,$txt, 0, 'L', 0, '', '', '', true, 0);
if ($contractor['check_pep']==1){
  $y=$pdf->getY();
  $pdf->SetY($y+9);
  $y = $pdf->getY();

  $txt =$pep_charge ;
  $pdf->writeHTMLCell(180, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);
  $pdf->Line(13, $y+9, 185, $y+9, $style);

}



if (count($other)>0){
    $point=1;
    $txt="3) che i titolari effettivi sono coloro elencati nella sezione “Titolari Effettivi”:";
    if (count($other)==1)
      $txt="3) che il titolare effettivo è colui individuato nella seguente sezione “Titolare Effettivo”. ";

    $y=$pdf->getY();
    $pdf->SetY($y+9);
    $y = $pdf->getY();

    $pdf->writeHTMLCell(180, 4, 10, $y, $txt, 0, 0, 1, true, 'j', true);


}

/*
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
*/
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
$pdf->SetY(-20);
$y=$pdf->getY();
$style = array('width' => 0.2, 'color' => array(180,180,180));
$pdf->Line(4, $y, 44, $y, $style);

$txt="<p><sup>1</sup>Art. 22, comma 1 Obblighi del cliente “I clienti forniscono per iscritto, sotto la propria responsabilità, tutte le informazioni necessarie e aggiornate per consentire ai soggetti obbligati di adempiere agli obblighi di adeguata verifica.”</p>";
//$pdf->MultiCell(200, 0, $txt,0, 'J', 1, 2, 0, '', true, 0);
$pdf->writeHTMLCell(200, 4, 5, '', html_entity_decode($txt), 0, 0, 1, true, 'J', true);
$txt="<sup>2</sup> Cfr. seguente nota 3)";

$y=$pdf->getY();
$pdf->SetY($y+7);
$y = $pdf->getY();
$pdf->writeHTMLCell(200, 4, 5, '', html_entity_decode($txt), 0, 0, 1, true, 'J', true);

if (count($other)>0){
  $pdf->AddPage('P', 'A4');
//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
//  $pdf->Image($file, 45, 164, 937, 150, '', '', '', true, 300, '', false, false, 0, false, false, true);
  $pdf->writeHTMLCell(80,80, 118, 181, $sign, 0, 0, 1, true, 'L', true);
  $pdf->writeHTMLCell(80,80, 118, 191, $agent_sign, 0, 0, 1, true, 'L', true);

  $pdf->SetFont('helvetica', '', 11);
  $pdf->SetFillColor(180, 180, 180);
  $pdf->SetY(10);
  $pdf->SetX(5);
  $txt=strtoupper("Dati identificativi dei Titolari Effettivi")."<sup></sup>";
  if (count($other)==1){
    $txt=strtoupper("Dati identificativi del Titolare Effettivo")."<sup></sup>";

  }
  // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
  $pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode($txt), 0, 0, 1, true, 'C', true);


  //$pdf->MultiCell(190,1,$txt, 0, 'C', 80, '', '', '', true, 0);
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->Ln(20);



  $i=0;
  $txt="<p><sup>2</sup>Ai sensi dell’art. 1, comma 1 lettera r) della Legge n.92/2008 e successive modificazioni, integrazioni ed istruzioni applicative (cfr. Istr. AIF n.5/2009, Istr. AIF n. 5/2010, Istr. AIF n. 6/2010 è “titolare effettivo”: (I) la persona fisica che, in ultima istanza, possiede o controlla un cliente, quando questo è una persona giuridica o un ente privo di personalità giuridica; (II) la persona fisica per conto della quale il cliente agisce. In ogni caso, si considera titolare effettivo: 1) la persona fisica o le persone fisiche che, direttamente o indirettamente, sono titolari di più del 25% dei diritti di voto in una società o comunque, per effetto di accordi o in altro modo, sono in grado di esercitare diritti di voto pari a tale percentuale o di avere il controllo sulla direzione della società, purché non si tratti di una società ammessa alla quotazione su un mercato regolamentato sottoposta a obblighi di comunicazione conformi o equivalenti a quelli previsti dalla normativa comunitaria; 2) la persona fisica o le persone fisiche beneficiarie di più del 25% del patrimonio di una fondazione, di un trust o di altri enti con o senza personalità giuridica che amministrino fondi, ovvero, qualora i beneficiari non siano ancora determinati, la persona o le persone fisiche nel cui interesse principale è istituito o agisce l’ente; 3) la persona fisica o le persone fisiche che sono in grado di esercitare un controllo di più del 25% del patrimonio di un ente con o senza personalità giuridica</p>";
  $txt="<p><sup>3</sup>Ai sensi e per gli effetti dell’art.1 comma 2, lett. dd) del d. lgs. 22/11/2007 n. 231 e successive modifiche e integrazioni,”dd) persone politicamente esposte: le persone fisiche che occupano o hanno cessato di occupare da meno di un anno importanti cariche pubbliche, nonché i loro familiari e coloro che con i predetti soggetti intrattengono notoriamente stretti legami, come di seguito elencate: 1) sono persone fisiche che occupano o hanno occupato importanti cariche pubbliche coloro che ricoprono o hanno ricoperto la carica di: 1.1 Presidente della Repubblica, Presidente del Consiglio, Ministro, Vice-Ministro e Sottosegretario, Presidente di Regione, assessore regionale, Sindaco di capoluogo di provincia o città metropolitana, Sindaco di comune con popolazione non inferiore a 15.000 abitanti nonché cariche analoghe in Stati esteri; 1.2 deputato, senatore, parlamentare europeo, consigliere regionale nonché cariche analoghe in Stati esteri; 1.3 membro degli organi direttivi centrali di partiti politici; 1.4 giudice della Corte Costituzionale, magistrato della Corte di Cassazione o della Corte dei conti, consigliere di Stato e altri componenti del Consiglio di Giustizia Amministrativa per la Regione siciliana nonché cariche analoghe in Stati esteri; 1.5 membro degli organi direttivi delle banche centrali e delle autorità indipendenti; 1.6 ambasciatore, incaricato d'affari ovvero cariche equivalenti in Stati esteri, ufficiale di grado apicale delle forze armate ovvero cariche analoghe in Stati esteri; 1.7 componente degli organi di amministrazione, direzione o controllo delle imprese controllate, anche indirettamente, dallo Stato italiano o da uno Stato estero ovvero partecipate, in misura prevalente o totalitaria, dalle Regioni, da comuni capoluoghi di provincia e città metropolitane e da comuni con popolazione complessivamente non inferiore a 15.000 abitanti; 1.8 direttore generale di ASL e di azienda ospedaliera, di azienda ospedaliera universitaria e degli altri enti del servizio sanitario nazionale. 1.9 direttore, vicedirettore e membro dell'organo di gestione o soggetto svolgenti funzioni equivalenti in organizzazioni internazionali; 2) sono familiari di persone politicamente esposte: i genitori, il coniuge o la persona legata in unione civile o convivenza di fatto o istituti assimilabili alla persona politicamente esposta, i figli e i loro coniugi nonché le persone legate ai figli in unione civile o convivenza di fatto o istituti assimilabili; 3) sono soggetti con i quali le persone politicamente esposte intrattengono notoriamente stretti legami: 3.1 le persone fisiche legate alla persona politicamente esposta per via della titolarità effettiva congiunta di enti giuridici o di altro stretto rapporto di affari; 3.2 le persone fisiche che detengono solo formalmente il controllo totalitario di un'entità notoriamente costituita, di fatto, nell'interesse e a beneficio di una persona politicamente esposta” </p>";
  $txt="";
  $pdf->SetFont('helvetica', '', 7);
  $pdf->SetY(-45);
  $y=$pdf->getY();
  $style = array('width' => 0.2, 'color' => array(180,180,180));
  $pdf->SetLineStyle($style);
  //$pdf->Line(4, $y, 44, $y, $style);
  $pdf->writeHTMLCell(200, 4, 0, '', html_entity_decode($txt), 0, 0, 1, true, 'J', true);

  foreach ($other as $oth){
    if ($i>0){
      $pdf->AddPage('P', 'A4');
      $pdf->writeHTMLCell(80,80, 118, 181, $sign, 0, 0, 1, true, 'L', true);
      $pdf->writeHTMLCell(80,80, 118, 191, $agent_sign, 0, 0, 1, true, 'L', true);
      $pdf->SetFont('helvetica', '', 11);
      $pdf->SetFillColor(180, 180, 180);
      $txt=strtoupper("Dati identificativi del Titolari Effettivo ")."N.".($i+1)."<sup></sup>";
      $pdf->writeHTMLCell(190, 1, '', '', html_entity_decode($txt), 0, 0, 1, true, 'C', true);
      //$pdf->MultiCell(190,1,$txt, 0, 'C', 80, '', '', '', true, 0);
//      $pdf->Image($file, 45, 164, 937, 150, '', '', '', true, 300, '', false, false, 0, false, false, true);

    }
    $pdf->SetY(10);
    $pdf->SetX(5);
    $pdf->Ln(30);

    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('helvetica', '', 10);

    $pdf->MultiRow("Nome", $other[$i]['name'],0,1);
    $pdf->MultiRow("Cognome", $other[$i]['surname'],0,1);
    $pdf->MultiRow("Data e Luogo di nascita", date('d/m/Y',strtotime($other[$i]['dob']))." a: " .$other[$i]['birth_town'] ." - " .ucfirst($other[$i]['birth_country']) ,0,1);
    $pdf->MultiRow("Residenza Anagrafica",$other[$i]['address_resi']." - ". $other[$i]['town_resi'] ."<br/>".ucfirst($other[$i]['resi_country']),0,1,2);
    $pdf->MultiRow("Cittadinanza",$other[$i]['main_nationality'],0,1,2);


    $txt=$other[$i]['address_domi']." - ". $other[$i]['town_domi'] ."<br/>".ucfirst($other[$i]['domi_country']);
    if ($other[$i]['check_residence']==1){
      $pdf->MultiRow("Domicilio<br/><i> Se diverso da Residenza</i>", $txt,0,1,2);
    }
    $txt="";
    if (strlen($other[$i]['tel'])>0){
      $txt.=" - Tel:".$other[$i]['tel'];
      $tt=true;
    }
    if (strlen($other[$i]['mobile'])>0){
    $txt.=" - Mobile:".$other[$i]['mobile'];
      $tt=true;
    }

/*
    $pdf->MultiRow("Recapiti Telefonici", $txt,0,1);
*/
    $txt=$other[$i]['profession'] . " - " . ucfirst($other[$i]['main_activity_country']);
    if (strlen($other[$i]['profession'])>0 || strlen($other[$i]['main_activity_country'])>0)
    $pdf->MultiRow("Tipo di Professione e zona geografica nella quale si svolge prevalentemente", $txt,0,1,2);

    $txt=$other[$i]['id_type'] . " - N." .$other[$i]['id_number'].
    "<br/>Rilasciata il:". date('d/m/Y',strtotime($other[$i]['id_release_date'])) . " - Validità:". date('d/m/Y',strtotime($other[$i]['id_validity'])).
    "<br/>Rilasciata da: ". $other[$i]['id_authority_name'];

    if (strlen($other[$i]['id_type'] )>0||strlen($other[$i]['id_number'] )>0 )
    $pdf->MultiRow("Tipologia ed estremi del documento di riconoscimento", $txt,0,1,3);


    $txt=$other[$i]['notes'];
    if (strlen($other[$i]['notes'])>0)
    $pdf->MultiRow("Note Eventuali", $txt,0,1);
    $pep_type=$other[$i]['pep_type'];
    $pep="“Persona Politicamente Esposta”<sup>3</sup> $pep_type ai sensi dell’articolo 1, comma 2, lett. dd) del d. lgs. 22/11/2007 n. 231 e successive modifiche e integrazioni, in quanto: ";
    $txt="";
    if ($other[$i]['check_pep']==1)
    $txt.= $pep ." " .$pep_charge;
    else
    $txt.= "NON PEP";

    $pdf->Ln(8);
    $pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
    $y = $pdf->getY()+5;
    $style = array('width' => 0.2, 'color' => array(180,180,180));
    $pdf->SetLineStyle($style);
    $pdf->Ln(25);
    $y = $pdf->getY();
    // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
    $pdf->writeHTMLCell(90,1,30,222,"Firma Cliente o di chi lo rappresenta:", 0,0,1,true,"R",true);
    $pdf->Line(120, 222+8, 190, 222+8, $style);
    $pdf->writeHTMLCell(90,1,30,237,"Firma del professionista o del collaboratore:", 0,0,1,true,"R",true);
    $pdf->Line(120, 237+8, 190, 237+8, $style);



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

$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 10);

setlocale(LC_MONETARY, 'it_IT');
$value= money_format('%.2n', $contract['contract_value']) ;
if ($contract['value_det']!=1)
  $value= "NON DETERMINABILE";
$pdf->SetFillColor(255, 255, 255);
$point=3+$point;
switch ($contract['tipo_contratto']){
 case 0:
    $tc="del Rapporto Continuativo";
    break;
    case 1:
       $tc="della Prestazione Professionale";
       break;
       case 0:
          $tc="della Operazione Occasionale";
          break;

}
$txt="$point)	Che la natura del $tc è ";
/*
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
*/

//$pdf->MultiCell(180,3,$txt, 0, 'J', 0, '', '', '', true, 0);
$pdf->Ln(12);
$txt="$point)	Che la natura  $tc è ";
$point++;
$pdf->writeHTMLCell(180, 3, 10, '', html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
//$pdf->MultiCell(180,2,$txt, 0, 'L', 80, '', '', '', true, 0);

//$pdf->MultiCell(180,2,$txt, 0, 'L', 80, '', '', '', true, 0);
$txt=strtoupper($contract['nature_contract']);
//$pdf->Ln(8);
$y=$pdf->getY()+6;
$pdf->writeHTMLCell(180, 3, 10, $y, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
//$pdf->MultiCell(190,1,$txt, 0, 'L', 0, '', '', '', true, 0);

$pdf->Line(14, $y+9, 184, $y+9, $style);
$pdf->Line(14, $y+14, 184, $y+14, $style);
$pdf->Ln(12);
$txt="$point) che lo scopo  $tc  è:";
$point++;
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

$txt="$point) che il valore/corrispettivo $tc  è: <b>$value</b>";
$point++;

$y=$pdf->getY()+12;

//$pdf->MultiCell(180,1,$txt, 0, 'L', 80, '', '', '', true, 0);
$pdf->writeHTMLCell(180, 3, 10, $y, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$txt=strtoupper($contract['scope_contract']);

$fs=$contract['found_source'];
$txt="$point) che l'origine dei fondi per lo scopo $tc è: <b>$fs</b>";
if  (strlen($contract['found_source'])>0){
  $point++;

  $y=$pdf->getY()+12;

  //$pdf->MultiCell(180,1,$txt, 0, 'L', 80, '', '', '', true, 0);
  $pdf->writeHTMLCell(180, 3, 10, $y, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
  $y=$pdf->getY()+12;
  $fs=$contract['paymentInstrument'];
  $txt="$point) che i mezzi/modalità di pagamento per lo scopo $tc sono: <b>$fs</b>";

  //$pdf->MultiCell(180,1,$txt, 0, 'L', 80, '', '', '', true, 0);
  $pdf->writeHTMLCell(180, 3, 10, $y, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);

}
$y=$pdf->getY()+12;
$fs=$contractor['notes'];
$txt="Note: $fs";
if  (strlen($fs)>0)
//$pdf->MultiCell(180,1,$txt, 0, 'L', 80, '', '', '', true, 0);
$pdf->writeHTMLCell(180, 3, 10, $y, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);


$y=$pdf->getY()+12;

$txt="Ai sensi della vigente normativa antiriciclaggio, il sottoscritto dichiara, sotto la propria personale responsabilità, la veridicità dei dati, delle informazioni fornite e delle dichiarazioni rilasciate e in particolare di tutto quanto sopra dichiarato. Il sottoscritto dichiara di essere stato informato della circostanza che il mancato rilascio in tutto o in parte delle informazioni di cui sopra può pregiudicare la capacità dello Studio di dare esecuzione alla prestazione professionale richiesta e si impegna a comunicare senza ritardo allo Studio ogni eventuale integrazione o variazione che si dovesse verificare in relazione ai dati forniti con la presente dichiarazione.
Il sottoscritto, quanto alla vigente normativa sulla privacy, acquisita l’informativa ai sensi dell’art. 13 del D.lgs. 196/2003 e successive modifiche ed integrazioni presta il consenso al trattamento dei dati personali riportati nella presente dichiarazione e di quelli che saranno eventualmente in futuro forniti a integrazione e/o modifica degli stessi. Il sottoscritto prende altresì atto che la comunicazione a terzi dei dati personali sarà effettuata dal Professionista in adempimento degli obblighi di legge.";
//$pdf->MultiCell(180,1,$txt, 0, 'L', 80, '', '', '', true, 0);
$pdf->writeHTMLCell(180, 3, 10, $y, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);

$style = array('width' => 0.2, 'color' => array(62,62,62));
$pdf->SetLineStyle($style);
$pdf->Ln(25);
$txt='Data: '. date('d/m/Y');

$pdf->writeHTMLCell(180, 3, 10, 170, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$pdf->SetFont('helvetica', '', 10);

$y = $pdf->getY()+10;

$pdf->writeHTMLCell(90,1,30,$y,"Firma Cliente o di chi lo rappresenta:", 0,0,1,true,"R",true);
$pdf->Line(125, $y+8, 185, $y+8, $style);
$y = $pdf->getY()+12;
$pdf->writeHTMLCell(90,1,30,$y,"Firma del professionista o del collaboratore:", 0,0,1,true,"R",true);
$pdf->Line(125, $y+8, 185, $y+8, $style);
$pdf->SetFont('helvetica', '', 9);
$y = $pdf->getY()+12;
$pdf->writeHTMLCell(180,1,10,$y,"la presente scheda è stata compilata e sottoscritta dal cliente alla presenza di:", 0,0,1,true,"L",true);
$pdf->Line(125, $y+8, 185, $y+8, $style);
/*
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
$pdf->MultiCell(70,1,"Firma Cliente o di chi lo rappresenta:", 10, 'R', 0, '', 40, $y, true, 0);
$pdf->Line(120, 188, 190, 188, $style);
$pdf->MultiCell(80,1,"Firma del professionista o del collaboratore:", 10, 'R', 0, '', 40, $y+12, true, 0);
$pdf->Line(120, 208, 190, 208, $style);
$pdf->SetFont('helvetica', '', 9);
$pdf->MultiCell(140,1,"La presente scheda è stata compilata e sottoscritta dal cliente alla presenza di:", 0, 'R', 0, '', -20, $y+22, true, 0);
$pdf->Line(120, $y+30, 190, $y+30, $style);
*/
$txt="<p><sup>3</sup>Ai sensi e per gli effetti dell’art.1 comma 2, lett. dd) del d. lgs. 22/11/2007 n. 231 e successive modifiche e integrazioni,”dd) persone politicamente esposte: le persone fisiche che occupano o hanno cessato di occupare da meno di un anno importanti cariche pubbliche, nonché i loro familiari e coloro che con i predetti soggetti intrattengono notoriamente stretti legami, come di seguito elencate: 1) sono persone fisiche che occupano o hanno occupato importanti cariche pubbliche coloro che ricoprono o hanno ricoperto la carica di: 1.1 Presidente della Repubblica, Presidente del Consiglio, Ministro, Vice-Ministro e Sottosegretario, Presidente di Regione, assessore regionale, Sindaco di capoluogo di provincia o città metropolitana, Sindaco di comune con popolazione non inferiore a 15.000 abitanti nonché cariche analoghe in Stati esteri; 1.2 deputato, senatore, parlamentare europeo, consigliere regionale nonché cariche analoghe in Stati esteri; 1.3 membro degli organi direttivi centrali di partiti politici; 1.4 giudice della Corte Costituzionale, magistrato della Corte di Cassazione o della Corte dei conti, consigliere di Stato e altri componenti del Consiglio di Giustizia Amministrativa per la Regione siciliana nonché cariche analoghe in Stati esteri; 1.5 membro degli organi direttivi delle banche centrali e delle autorità indipendenti; 1.6 ambasciatore, incaricato d'affari ovvero cariche equivalenti in Stati esteri, ufficiale di grado apicale delle forze armate ovvero cariche analoghe in Stati esteri; 1.7 componente degli organi di amministrazione, direzione o controllo delle imprese controllate, anche indirettamente, dallo Stato italiano o da uno Stato estero ovvero partecipate, in misura prevalente o totalitaria, dalle Regioni, da comuni capoluoghi di provincia e città metropolitane e da comuni con popolazione complessivamente non inferiore a 15.000 abitanti; 1.8 direttore generale di ASL e di azienda ospedaliera, di azienda ospedaliera universitaria e degli altri enti del servizio sanitario nazionale. 1.9 direttore, vicedirettore e membro dell'organo di gestione o soggetto svolgenti funzioni equivalenti in organizzazioni internazionali; 2) sono familiari di persone politicamente esposte: i genitori, il coniuge o la persona legata in unione civile o convivenza di fatto o istituti assimilabili alla persona politicamente esposta, i figli e i loro coniugi nonché le persone legate ai figli in unione civile o convivenza di fatto o istituti assimilabili; 3) sono soggetti con i quali le persone politicamente esposte intrattengono notoriamente stretti legami: 3.1 le persone fisiche legate alla persona politicamente esposta per via della titolarità effettiva congiunta di enti giuridici o di altro stretto rapporto di affari; 3.2 le persone fisiche che detengono solo formalmente il controllo totalitario di un'entità notoriamente costituita, di fatto, nell'interesse e a beneficio di una persona politicamente esposta” </p>";
//$txt="";
$pdf->SetFont('helvetica', '', 7);
$pdf->SetY(-72);
$y=$pdf->getY();
$style = array('width' => 0.2, 'color' => array(180,180,180));
$pdf->SetLineStyle($style);
//$pdf->Line(4, $y, 44, $y, $style);
$pdf->writeHTMLCell(200, 4, 3, '', html_entity_decode($txt), 0, 0, 1, true, 'J', true);


$pdf->AddPage('P', 'A4');
$pdf->writeHTMLCell(80,80, 118, 185, $sign, 0, 0, 1, true, 'L', true);
$pdf->writeHTMLCell(80,80, 118, 195, $agent_sign, 0, 0, 1, true, 'L', true);
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->SetY(10);
$pdf->SetX(5);
$txt=strtoupper("SEZIONE INFORMATIVA ");
$pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
//  $pdf->MultiCell(190,1,$txt, 0, 'C', 80, '', '', '', true, 0);
//  $pdf->Image($file, 15, 154, 937, 150, '', '', '', true, 300, '', false, false, 0, false, false, true);

$pdf->Ln(15);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetFillColor(255, 255, 255);
$tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'
=> 0)));
$pdf->setHtmlVSpace($tagvs);
$pdf->SetCellPadding(0);
$txt= <<<EOD
<H2 style="text-align:center;margin:0px;padding:0px;line-height:1">INFORMATIVA SUGLI OBBLIGHI DI CUI ALLA NORMATIVA
ANTIRICICLAGGIO</H1>
<P  style="text-align:center;"> (decreto legislativo n. 231/07 e successive modificazioni ed
integrazioni)</P>
<H3 style="margin:0px;padding:0px;line-height:1" CLASS="western">DEFINIZIONE DI RICICLAGGIO Art. 2, comma 4, d.
lgs. n.231/2007</H3>
<P style="text-align:justify" >&ldquo;Ai fini di cui al comma 1, s'intende per riciclaggio:</P>
<P style="text-align:justify">a)	la conversione o il trasferimento di beni, effettuati essendo a
conoscenza che essi provengono da un'attivit&agrave; criminosa o da
una partecipazione a tale attivit&agrave;, allo scopo di occultare o
dissimulare l'origine illecita dei beni medesimi o di aiutare
chiunque sia coinvolto in tale attivit&agrave; a sottrarsi alle
conseguenze giuridiche delle proprie azioni;</P>
<P style="text-align:justify">b)	l'occultamento o la dissimulazione della reale natura,
provenienza, ubicazione, disposizione, movimento, propriet&agrave;
dei beni o dei diritti sugli stessi, effettuati essendo a conoscenza
che tali beni provengono da un'attivit&agrave; criminosa o da una
partecipazione a tale attivit&agrave;;</P>
<P style="text-align:justify">c)	l'acquisto, la detenzione o l'utilizzazione di beni essendo a
conoscenza, al momento della loro ricezione, che tali beni provengono
da un'attivit&agrave; criminosa o da una partecipazione a tale
attivit&agrave;;</P>
<P style="text-align:justify">d)	la partecipazione ad uno degli atti di cui alle lettere a), b)
e c), l'associazione per commettere tale atto, il tentativo di
perpetrarlo, il fatto di aiutare, istigare o consigliare qualcuno a
commetterlo o il fatto di agevolarne l'esecuzione.</P>
<P style="text-align:justify">5). Il riciclaggio &egrave; considerato tale anche se le attivit&agrave;
che hanno generato i beni da riciclare si sono svolte fuori dai
confini nazionali. La conoscenza, l'intenzione o la finalit&agrave;,
che debbono costituire un elemento delle azioni di cui al comma 4
possono essere dedotte da circostanze di fatto obiettive.</P>
<H3 CLASS="western">DEFINIZIONE DI FINANZIAMENTO DEL TERRORISMO Art.
2, comma 6, d. lgs. n.231/2007</H3>
<P style="text-align:justify"> &ldquo;Ai fini di cui al comma 1, s'intende per finanziamento del
terrorismo qualsiasi attivit&agrave; diretta, con ogni mezzo, alla
fornitura, alla raccolta, alla provvista, all'intermediazione, al
deposito, alla custodia o all'erogazione, in qualunque modo
realizzate, di fondi e risorse economiche, direttamente o
indirettamente, in tutto o in parte, utilizzabili per il compimento
di una o pi&ugrave; condotte, con finalit&agrave; di terrorismo
secondo quanto previsto dalle leggi penali ci&ograve;
indipendentemente dall'effettivo utilizzo dei fondi e delle risorse
economiche per la commissione delle condotte anzidette.&rdquo;</P>

<H3 CLASS="western">CONTENUTO DEGLI OBBLIGHI DI ADEGUATA VERIFICA
DELLA CLIENTELA &ndash; Art. 18, comma 1 del d. lgs. 231/07</H3>
<P style="text-align:justify">1. Gli obblighi di adeguata verifica della clientela si attuano
attraverso:</P>
<P style="text-align:justify">a)	l'identificazione del cliente e la verifica della sua identit&agrave;
attraverso riscontro di un documento d'identit&agrave; o di altro
documento di riconoscimento equipollente ai sensi della normativa
vigente nonch&eacute; sulla base di documenti, dati o informazioni
ottenuti da una fonte affidabile e indipendente. Le medesime misure
si attuano nei confronti dell'esecutore, anche in relazione alla
verifica dell'esistenza e dell'ampiezza del potere di rappresentanza
in forza del quale opera in nome e per conto del cliente;</P>
<P style="text-align:justify">b)	l'identificazione del titolare effettivo e la verifica della
sua identit&agrave; attraverso l'adozione di misure proporzionate al
rischio ivi comprese, con specifico riferimento alla titolarit&agrave;
effettiva di persone giuridiche, trust e altri istituti e soggetti
giuridici affini, le misure che consentano di ricostruire, con
ragionevole attendibilit&agrave;, l'assetto proprietario e di
controllo del cliente;</P>
<P style="text-align:justify">c)	l'acquisizione e la valutazione di informazioni sullo scopo e
sulla natura del rapporto continuativo o della prestazione
professionale, per tali intendendosi, quelle relative
all'instaurazione del rapporto, alle relazioni intercorrenti tra il
cliente e l'esecutore, tra il cliente e il titolare effettivo e
quelle relative all'attivit&agrave; lavorativa, salva la possibilit&agrave;
di acquisire, in funzione del rischio, ulteriori informazioni, ivi
comprese quelle relative alla situazione economico-patrimoniale del
cliente, acquisite o possedute in ragione dell'esercizio
dell'attivit&agrave;. In presenza di un elevato rischio di
riciclaggio e di finanziamento del terrorismo, i soggetti obbligati
applicano la procedura di acquisizione e valutazione delle predette
informazioni anche alle prestazioni o operazioni occasionali;</P>
<P style="text-align:justify">d)	il controllo costante del rapporto con il cliente, per tutta la
sua durata, attraverso l'esame della complessiva operativit&agrave;
del cliente medesimo, la verifica e l'aggiornamento dei dati e delle
informazioni acquisite nello svolgimento delle attivit&agrave; di cui
alle lettere a), b) e c), anche riguardo, se necessaria in funzione
del rischio, alla verifica della provenienza dei fondi e delle
risorse nella disponibilit&agrave; del cliente, sulla base di
informazioni acquisite o possedute in ragione dell'esercizio
dell'attivit&agrave;.</P>
<H3 CLASS="western">MODALIT&Agrave; DI ADEMPIMENTO DEGLI OBBLIGHI DI
ADEGUATA VERIFICA Art. 19, comma 1, d.lgs. n. 231/2007</H3>
<P style="text-align:justify">&ldquo;1. I soggetti obbligati assolvono agli obblighi di adeguata
verifica della clientela secondo le seguenti modalit&agrave;:</P>
<P style="text-align:justify">a)	l'identificazione del cliente e del titolare effettivo &egrave;
svolta in presenza del medesimo cliente ovvero dell'esecutore, anche
attraverso dipendenti o collaboratori del soggetto obbligato e
consiste nell'acquisizione dei dati identificativi forniti dal
cliente, previa esibizione di un documento d'identit&agrave; in corso
di validit&agrave; o altro documento di riconoscimento equipollente
ai sensi della normativa vigente, del quale viene acquisita copia in
formato cartaceo o elettronico. Il cliente fornisce altres&igrave;,
sotto la propria responsabilit&agrave;, le informazioni necessarie a
consentire l'identificazione del titolare effettivo. L'obbligo di
identificazione si considera assolto, anche senza la presenza fisica
del cliente, nei seguenti casi:</P>
<P style="text-align:justify">1) per i clienti i cui dati identificativi risultino da atti
pubblici, da scritture private autenticate o da certificati
qualificati utilizzati per la generazione di una firma digitale
associata a documenti informatici, ai sensi dell'articolo 24 del
decreto legislativo 7 marzo 2005, n. 82;</P>
<P style="text-align:justify">2) per i clienti in possesso di un'identit&agrave; digitale, di
livello massimo di sicurezza, nell'ambito del Sistema di cui
all'articolo 64 del predetto decreto legislativo n. 82 del 2005 e
successive modificazioni, e della relativa normativa regolamentare di
attuazione, nonch&eacute; di un'identit&agrave; digitale o di un
certificato per la generazione di firma digitale, rilasciati
nell'ambito di un regime di identificazione elettronica compreso
nell'elenco pubblicato dalla Commissione europea a norma
dell'articolo 9 del regolamento EU n. 910/2014;</P>
<P style="text-align:justify">3) per i clienti i cui dati identificativi risultino da
dichiarazione della rappresentanza e dell'autorit&agrave; consolare
italiana, come indicata nell'articolo 6 del decreto legislativo 26
maggio 1997, n. 153;</P>
<P style="text-align:justify">4) per i clienti che siano gi&agrave; stati identificati dal
soggetto obbligato in relazione ad un altro rapporto o prestazione
professionale in essere, purch&eacute; le informazioni esistenti
siano aggiornate e adeguate rispetto allo specifico profilo di
rischio del cliente;</P>
<P style="text-align:justify">5) per i clienti i cui dati identificativi siano acquisiti
attraverso idonee forme e modalit&agrave;, individuate dalle Autorit&agrave;
di vigilanza di settore, nell'esercizio delle attribuzioni di cui
all'articolo 7, comma 1, lettera a), tenendo conto dell'evoluzione
delle tecniche di identificazione a distanza;</P>
EOD;
$pdf->writeHTMLCell(180,'',10,'',$txt, 0, 0, 1, true, '', true);
$pdf->AddPage('P', 'A4');
$pdf->writeHTMLCell(80,80, 118, 185, $sign, 0, 0, 1, true, 'L', true);
$pdf->writeHTMLCell(80,80, 118, 195, $agent_sign, 0, 0, 1, true, 'L', true);
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->SetY(10);
$pdf->SetX(5);
$txt=strtoupper("SEZIONE INFORMATIVA ");
$pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
//  $pdf->MultiCell(190,1,$txt, 0, 'C', 80, '', '', '', true, 0);
//  $pdf->Image($file, 15, 154, 937, 150, '', '', '', true, 300, '', false, false, 0, false, false, true);

$pdf->Ln(15);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetFillColor(255, 255, 255);
$tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'
=> 0)));
$pdf->setHtmlVSpace($tagvs);
$pdf->SetCellPadding(0);
$txt=<<<EOD
<P style="text-align:justify">b)	la verifica dell'identit&agrave; del cliente, del titolare
effettivo e dell'esecutore richiede il riscontro della veridicit&agrave;
dei dati identificativi contenuti nei documenti e delle informazioni
acquisiti all'atto dell'identificazione, laddove, in relazione ad
essi, sussistano dubbi, incertezze o incongruenze. Il riscontro pu&ograve;
essere effettuato attraverso la consultazione del sistema pubblico
per la prevenzione del furto di identit&agrave; di cui decreto
legislativo 11 aprile 2011, n. 64. La verifica dell'identit&agrave;
pu&ograve; essere effettuata anche attraverso il ricorso ad altre
fonti attendibili e indipendenti tra le quali rientrano le basi di
dati, ad accesso pubblico o condizionato al rilascio di credenziali
di autenticazione, riferibili ad una pubblica amministrazione nonch&eacute;
quelle riferibili a soggetti privati autorizzati al rilascio di
identit&agrave; digitali nell'ambito del sistema previsto
dall'articolo 64 del decreto legislativo n. 82 del 2005 ovvero di un
regime di identificazione elettronica compreso nell'elenco pubblicato
dalla Commissione europea a norma dell'articolo 9 del regolamento EU
n. 910/2014. Con riferimento ai clienti diversi dalle persone fisiche
e ai fiduciari di trust espressi, la verifica dell'identit&agrave;
del titolare effettivo impone l'adozione di misure, commisurate alla
situazione di rischio, idonee a comprendere la struttura di propriet&agrave;
e di controllo del cliente;</P>
<P style="text-align:justify">c) l'acquisizione e la valutazione di informazioni sullo scopo e
sulla natura del rapporto continuativo o della prestazione
professionale, verificando la compatibilit&agrave; dei dati e delle
informazioni fornite dal cliente con le informazioni acquisite
autonomamente dai soggetti obbligati, anche avuto riguardo al
complesso delle operazioni compiute in costanza del rapporto o di
altri rapporti precedentemente intrattenuti nonch&eacute;
all'instaurazione di ulteriori rapporti;</P>
<P style="text-align:justify">d) il controllo costante nel corso del rapporto continuativo o
della prestazione professionale si attua attraverso l'analisi delle
operazioni effettuate e delle attivit&agrave; svolte o individuate
durante tutta la durata del rapporto, in modo da verificare che esse
siano coerenti con la conoscenza che il soggetto obbligato ha del
cliente e del suo profilo di rischio, anche riguardo, se necessario,
all'origine dei fondi.&rdquo;</P>
<H3 CLASS="western">OBBLIGHI DEL CLIENTE &ndash; Art. 22, comma 1 e 2
del d. lgs. 231/07</H3>
<P style="text-align:justify">&ldquo;I clienti forniscono per iscritto, sotto la propria
responsabilit&agrave;, tutte le informazioni necessarie e aggiornate
per consentire ai soggetti obbligati di adempiere agli obblighi di
adeguata verifica.</P>
<P style="text-align:justify">Per le finalit&agrave; di cui al presente decreto, le imprese
dotate di personalit&agrave; giuridica e le persone giuridiche
private ottengono e conservano, per un periodo non inferiore a cinque
anni, informazioni adeguate, accurate e aggiornate sulla propria
titolarit&agrave; effettiva e le forniscono ai soggetti obbligati, in
occasione degli adempimenti strumentali all'adeguata verifica della
clientela.&rdquo;</P>
<H3 CLASS="western">ASTENSIONE &ndash; Art. 42 del d. lgs. 231/07</H3>
<P style="text-align:justify">&ldquo;1. I soggetti obbligati che si trovano nell'impossibilit&agrave;
oggettiva di effettuare l'adeguata verifica della clientela, ai sensi
delle disposizioni di cui all'articolo 19, comma 1, lettere a), b) e
c), si astengono dall'instaurare, eseguire ovvero proseguire il
rapporto, la prestazione professionale e le operazioni e valutano se
effettuare una segnalazione di operazione sospetta alla UIF a norma
dell'articolo 35.</P>
<P style="text-align:justify">2. I soggetti obbligati si astengono dall'instaurare il rapporto
continuativo, eseguire operazioni o prestazioni professionali e
pongono fine al rapporto continuativo o alla prestazione
professionale gi&agrave; in essere di cui siano, direttamente o
indirettamente, parte societ&agrave; fiduciarie, trust, societ&agrave;
anonime o controllate attraverso azioni al portatore aventi sede in
Paesi terzi ad alto rischio. Tali misure si applicano anche nei
confronti delle ulteriori entit&agrave; giuridiche, altrimenti
denominate, aventi sede nei suddetti Paesi, di cui non &egrave;
possibile identificare il titolare effettivo n&eacute; verificarne
l'identit&agrave;.</P>
<P style="text-align:justify">3. I professionisti sono esonerati dall'obbligo di cui al comma 1,
limitatamente ai casi in cui esaminano la posizione giuridica del
loro cliente o espletano compiti di difesa o di rappresentanza del
cliente in un procedimento innanzi a un'autorit&agrave; giudiziaria o
in relazione a tale procedimento, compresa la consulenza
sull'eventualit&agrave; di intentarlo o evitarlo.</P>
<P style="text-align:justify">4. &Egrave; fatta in ogni caso salva l'applicazione dell'articolo
35, comma 2, nei casi in cui l'operazione debba essere eseguita in
quanto sussiste un obbligo di legge di ricevere l'atto.&rdquo;</P>
<H3 CLASS="western">OBBLIGHI DI SEGNALAZIONE DELLE OPERAZIONI
SOSPETTE &ndash; Art. 35 d. lgs. 231/07</H3>
<P style="text-align:justify">&ldquo;1. I soggetti obbligati, prima di compiere l'operazione,
inviano senza ritardo alla UIF, una segnalazione di operazione
sospetta quando sanno, sospettano o hanno motivi ragionevoli per
sospettare che siano in corso o che siano state compiute o tentate
operazioni di riciclaggio o di finanziamento del terrorismo o che
comunque i fondi, indipendentemente dalla loro entit&agrave;,
provengano da attivit&agrave; criminosa. Il sospetto &egrave; desunto
dalle caratteristiche, dall'entit&agrave;, dalla natura delle
operazioni, dal loro collegamento o frazionamento o da qualsivoglia
altra circostanza conosciuta, in ragione delle funzioni esercitate,
tenuto conto anche della capacit&agrave; economica e dell'attivit&agrave;
svolta dal soggetto cui &egrave; riferita, in base agli elementi
acquisiti ai sensi del presente decreto. Il ricorso frequente o
ingiustificato ad operazioni in contante, anche se non eccedenti la
soglia di cui all'articolo 49 e, in particolare, il prelievo o il
versamento in contante di importi non coerenti con il profilo di
rischio del cliente, costituisce elemento di sospetto. La UIF, con le
modalit&agrave; di cui all'articolo 6, comma 4, lettera e), emana e
aggiorna periodicamente indicatori di anomalia, al fine di agevolare
l'individuazione delle operazioni sospette.</P>
<P style="text-align:justify">2. In presenza degli elementi di sospetto di cui al comma 1, i
soggetti obbligati non compiono l'operazione fino al momento in cui
non hanno provveduto ad effettuare la segnalazione di operazione
sospetta. Sono fatti salvi i casi in cui l'operazione debba essere
eseguita in quanto sussiste un obbligo di legge di ricevere l'atto
ovvero nei casi in cui l'esecuzione dell'operazione non possa essere
rinviata tenuto conto della normale operativit&agrave; ovvero nei
casi in cui il differimento dell'operazione possa ostacolare le
indagini. In dette ipotesi, i soggetti obbligati, dopo aver ricevuto
l'atto o eseguito l'operazione, ne informano immediatamente la UIF.</P>
<P style="text-align:justify">3. I soggetti obbligati effettuano la segnalazione contenente i
dati, le informazioni, la descrizione delle operazioni ed i motivi
del sospetto, e collaborano con la UIF, rispondendo tempestivamente
alla richiesta di ulteriori informazioni. La UIF, con le modalit&agrave;
di cui all'articolo 6, comma 4, lettera d), emana istruzioni per la
rilevazione e la segnalazione delle operazioni sospette al fine di
assicurare tempestivit&agrave;, completezza e riservatezza delle
stesse.</P>
<P style="text-align:justify">4. Le comunicazioni delle informazioni, effettuate in buona fede
dai soggetti obbligati, dai loro dipendenti o amministratori ai fini
della segnalazione di operazioni sospette, non costituiscono
violazione di eventuali restrizioni alla comunicazione di
informazioni imposte in sede contrattuale o da disposizioni
legislative, regolamentari o amministrative. Le medesime
comunicazioni non comportano responsabilit&agrave; di alcun tipo
anche nelle ipotesi in cui colui che le effettua non sia a conoscenza
dell'attivit&agrave; criminosa sottostante e a prescindere dal fatto
che l'attivit&agrave; illegale sia stata realizzata.</P>
<P style="text-align:justify">5. L'obbligo di segnalazione delle operazioni sospette non si
applica ai professionisti per le informazioni che essi ricevono da un
loro cliente o ottengono riguardo allo stesso nel corso dell'esame
della posizione giuridica o dell'espletamento dei compiti di difesa o
di rappresentanza del medesimo in un procedimento innanzi a
un'autorit&agrave; giudiziaria o in relazione a tale procedimento,
anche tramite una convenzione di negoziazione assistita da uno o pi&ugrave;
avvocati ai sensi di legge, compresa la consulenza sull'eventualit&agrave;
di intentarlo o evitarlo, ove tali informazioni siano ricevute o
ottenute prima, durante o dopo il procedimento stesso.</P>
EOD;
$pdf->writeHTMLCell(180,'',10,'',$txt, 0, 0, 1, true, '', true);
$pdf->AddPage('P', 'A4');
$pdf->writeHTMLCell(80,80, 118, 185, $sign, 0, 0, 1, true, 'L', true);
$pdf->writeHTMLCell(80,80, 118, 195, $agent_sign, 0, 0, 1, true, 'L', true);
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->SetY(10);
$pdf->SetX(5);
$txt=strtoupper("SEZIONE INFORMATIVA ");
$pdf->writeHTMLCell(195, 1, 5, '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
//  $pdf->MultiCell(190,1,$txt, 0, 'C', 80, '', '', '', true, 0);
//  $pdf->Image($file, 15, 154, 937, 150, '', '', '', true, 300, '', false, false, 0, false, false, true);

$pdf->Ln(15);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetFillColor(255, 255, 255);
$tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'
=> 0)));
$pdf->setHtmlVSpace($tagvs);
$pdf->SetCellPadding(0);
$txt=<<<EOD
<H3 CLASS="western">LIMITAZIONI ALL&rsquo;USO DI DENARO CONTANTE E
DEI TITOLI AL PORTATORE &ndash; Art. 49, comma 1 del d. lgs. 231/07</H3>
<P style="text-align:justify">&ldquo;&Egrave; vietato il trasferimento di denaro contante e di
titoli al portatore in euro o in valuta estera, effettuato a
qualsiasi titolo tra soggetti diversi, siano esse persone fisiche o
giuridiche, quando il valore oggetto di trasferimento, &egrave;
complessivamente pari o superiore a 3.000 euro. Il trasferimento
superiore al predetto limite, quale che ne sia la causa o il titolo,
&egrave; vietato anche quando &egrave; effettuato con pi&ugrave;
pagamenti, inferiori alla soglia, che appaiono artificiosamente
frazionati e pu&ograve; essere eseguito esclusivamente per il tramite
di banche, Poste italiane S.p.a., istituti di moneta elettronica e
istituti di pagamento, questi ultimi quando prestano servizi di
pagamento diversi da quelli di cui all'articolo 1, comma 1, lettera
b), numero 6), del decreto legislativo 27 gennaio 2010, n. 11. Il
trasferimento effettuato per il tramite degli intermediari bancari e
finanziari avviene mediante disposizione accettata per iscritto dagli
stessi, previa consegna ai medesimi intermediari della somma in
contanti. A decorrere dal terzo giorno lavorativo successivo a quello
dell'accettazione, il beneficiario ha diritto di ottenere il
pagamento nella provincia del proprio domicilio. La comunicazione da
parte del debitore al creditore della predetta accettazione produce
gli effetti di cui all'articolo 1277, primo comma, del codice civile
e, nei casi di mora del creditore, gli effetti di cui all'articolo
1210 del medesimo codice.&rdquo;</P>
<H3 CLASS="western">OBBLIGO DI COMUNICAZIONE AL MINISTERO
DELL'ECONOMIA E DELLE FINANZE DELLE INFRAZIONI DI CUI AL PRESENTE
TITOLO Art. 51, commi 1 e 3 del d. lgs. 231/07</H3>
<P style="text-align:justify">&ldquo;1. I soggetti obbligati che nell'esercizio delle proprie
funzioni o nell'espletamento della propria attivit&agrave; hanno
notizia di infrazioni alle disposizioni di cui all'articolo 49, commi
1, 5, 6, 7 e 12, e all'articolo 50 ne riferiscono entro trenta giorni
al Ministero dell'economia e delle finanze per la contestazione e gli
altri adempimenti previsti dall'articolo 14 della legge 24 novembre
1981, n. 689, e per la immediata comunicazione della infrazione anche
alla Guardia di finanza la quale, ove ravvisi l'utilizzabilit&agrave;
di elementi ai fini dell'attivit&agrave; di accertamento, ne d&agrave;
tempestiva comunicazione all'Agenzia delle entrate. La medesima
comunicazione &egrave; dovuta dai componenti del collegio sindacale,
del consiglio di sorveglianza, del comitato per il controllo sulla
gestione presso i soggetti obbligati, quando riscontrano la
violazione delle suddette disposizioni nell'esercizio delle proprie
funzioni di controllo e vigilanza.</P>
<P style="text-align:justify">2. ...omissis&hellip;</P>
<P style="text-align:justify">3. Qualora oggetto dell'infrazione sia un'operazione di
trasferimento segnalata ai sensi dell'articolo 35, non sussiste
l'obbligo di comunicazione di cui al comma 1.&raquo;.</P>
<H3 CLASS="western"> PERSONE POLITICAMENTE ESPOSTE &ndash; Art. 1,
co. 2, lett.dd) del d. lgs. 231/07</H3>
<P style="text-align:justify">dd) persone politicamente esposte: le persone fisiche che occupano
o hanno cessato di occupare da meno di un anno importanti cariche
pubbliche, nonch&eacute; i loro familiari e coloro che con i predetti
soggetti intrattengono notoriamente stretti legami, come di seguito
elencate:</P>
<P style="text-align:justify">1) sono persone fisiche che occupano o hanno occupato importanti
cariche pubbliche coloro che ricoprono o hanno ricoperto la carica
di:</P>
<P style="text-align:justify">1.1 Presidente della Repubblica, Presidente del Consiglio,
Ministro, Vice-Ministro e Sottosegretario, Presidente di Regione,
assessore regionale, Sindaco di capoluogo di provincia o citt&agrave;
metropolitana, Sindaco di comune con popolazione non inferiore a
15.000 abitanti nonch&eacute; cariche analoghe in Stati esteri;</P>
<P style="text-align:justify">1.2 deputato, senatore, parlamentare europeo, consigliere
regionale nonch&eacute; cariche analoghe in Stati esteri;</P>
<P style="text-align:justify">1.3 membro degli organi direttivi centrali di partiti politici;</P>
<P style="text-align:justify">1.4 giudice della Corte Costituzionale, magistrato della Corte di
Cassazione o della Corte dei conti, consigliere di Stato e altri
componenti del Consiglio di Giustizia Amministrativa per la Regione
siciliana nonch&eacute; cariche analoghe in Stati esteri;</P>
<P style="text-align:justify">1.5 membro degli organi direttivi delle banche centrali e delle
autorit&agrave; indipendenti;</P>
<P style="text-align:justify">1.6 ambasciatore, incaricato d'affari ovvero cariche equivalenti
in Stati esteri, ufficiale di grado apicale delle forze armate ovvero
cariche analoghe in Stati esteri;</P>
<P style="text-align:justify">1.7 componente degli organi di amministrazione, direzione o
controllo delle imprese controllate, anche indirettamente, dallo
Stato italiano o da uno Stato estero ovvero partecipate, in misura
prevalente o totalitaria, dalle Regioni, da comuni capoluoghi di
provincia e citt&agrave; metropolitane e da comuni con popolazione
complessivamente non inferiore a 15.000 abitanti;</P>
<P style="text-align:justify">1.8 direttore generale di ASL e di azienda ospedaliera, di azienda
ospedaliera universitaria e degli altri enti del servizio sanitario
nazionale.</P>
<P style="text-align:justify">1.9 direttore, vicedirettore e membro dell'organo di gestione o
soggetto svolgenti funzioni equivalenti in organizzazioni
internazionali;</P>
<P style="text-align:justify">2) sono familiari di persone politicamente esposte: i genitori, il
coniuge o la persona legata in unione civile o convivenza di fatto o
istituti assimilabili alla persona politicamente esposta, i figli e i
loro coniugi nonch&eacute; le persone legate ai figli in unione
civile o convivenza di fatto o istituti assimilabili;</P>
<P style="text-align:justify">3) sono soggetti con i quali le persone politicamente esposte
intrattengono notoriamente stretti legami:</P>
<P style="text-align:justify">3.1 le persone fisiche legate alla persona politicamente esposta
per via della titolarit&agrave; effettiva congiunta di enti giuridici
o di altro stretto rapporto di affari;</P>
<P style="text-align:justify">3.2 le persone fisiche che detengono solo formalmente il controllo
totalitario di un'entit&agrave; notoriamente costituita, di fatto,
nell'interesse e a beneficio di una persona politicamente esposta&rdquo;</P>
<H3 CLASS="western">TITOLARE EFFETTIVO &ndash; Art. 1, comma 2, lett.
pp) del d. lgs. 231/07</H3>
<P style="text-align:justify">&ldquo;pp) titolare effettivo: la persona fisica o le persone
fisiche, diverse dal cliente, nell'interesse della quale o delle
quali, in ultima istanza, il rapporto continuativo &egrave;
istaurato, la prestazione professionale &egrave; resa o l'operazione
&egrave; eseguita&rdquo;</P>
<br>
<P style="text-align:justify">Tanto premesso, Vi raccomandiamo di prestare la massima attenzione
alle situazioni dianzi evidenziate</P>
EOD;
$pdf->writeHTMLCell(180,'',10,'',$txt, 0, 0, 1, true, '', true);
$pdf->SetFont('helvetica', '', 10);

$txt='Data: '. date('d/m/Y');
$pdf->writeHTMLCell(180, 3, 10, 265, html_entity_decode(($txt)), 0, 0, 1, true, 'J', true);
$pdf->SetFont('helvetica', '', 10);

$y = $pdf->getY()+10;

$pdf->writeHTMLCell(90,1,30,$y,"Firma Cliente o di chi lo rappresenta:", 0,0,1,true,"R",true);
$pdf->Line(125, $y+8, 185, $y+8, $style);
?>
