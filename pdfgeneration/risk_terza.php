<?php
// nuova Valutazione
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(4);
$txt="Profilo soggettivo del Cliente";
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 10);
$txt="il Cliente è PEP:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['subjectiveProfile']['pep']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//una riga
$pdf->Ln(10);
$txt="il Cliente ha precedenti penali:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['subjectiveProfile']['criminalprecedings']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//una riga
$pdf->Ln(10);
$txt="il Cliente è presente in liste:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['subjectiveProfile']['presenceinlist']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(10);
$txt="<b>" .$rd['partial']['subjectiveProfile']. "</b>";
$pdf->MultiRow("Rischio Parziale Profilo Soggettivo:", $txt,0,1);

$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(13);
$txt="Residenza";
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 10);
$txt="il Cliente risiede in Paesi a Rischio:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['Residence']['riskCountry']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//una riga
$pdf->Ln(10);
$txt="il Cliente svolge la principale attività in paesi a rischio:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['Residence']['activityInRiskCountry']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
$pdf->Ln(10);
$txt="<b>" .$rd['partial']['Residence']. "</b>";
$pdf->MultiRow("Rischio Parziale Residenza:", $txt,0,1);



//una riga

$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(13);
$txt="Attivita'";
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 10);
//-->
$txt="Il Cliente possiede le competenze per svolgere l'attività principale:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['mainActivity']['skills']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//una riga
$pdf->Ln(10);
$txt="Attività principale con alto volume finanziario:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['mainActivity']['highFinancialMovment']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Attività principale finanziata con fondi pubblici:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['mainActivity']['pubblicSectorFinancing']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Attività principale finanziata con fondi europei:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['mainActivity']['EUFinancing']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Attività principale con alto uso di contante:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['mainActivity']['cash']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
$pdf->Ln(10);
$txt="<b>" .$rd['partial']['mainActivity']. "</b>";
$pdf->MultiRow("Rischio Parziale Attività:", $txt,0,1);

$pdf->AddPage('P', 'A4');

//sezione
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(13);
$txt="Comportamento del Cliente";
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);
$pdf->SetFillColor(255, 255, 255);
//una riga
$pdf->Ln(10);
$txt="Il Cliente è collaborativo:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['behavior']['collaborative']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Il Cliente è troppo collaborativo:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['behavior']['tooMuchCollaborative']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Il Cliente non è collaborativo per nulla:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['behavior']['atAllCollaborative']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine rigA
$pdf->Ln(10);
$txt="<b>" .$rd['partial']['behavior']. "</b>";
$pdf->MultiRow("Rischio Parziale comportamento del cliente:", $txt,0,1);
//fine sezione
//sezione
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(13);
$txt="Frequenza";
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);
$pdf->SetFillColor(255, 255, 255);
//una riga
$pdf->Ln(10);
$txt="Attività ad Alta frequenza:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['frequency']['highFrequency']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Frequenza e durata Consistente con il profilo economico del Cliente:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['frequency']['consistent']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
$pdf->Ln(20);
$txt="<b>" .$rd['partial']['frequency']. "</b>";
$pdf->MultiRow("Rischio Parziale Frequenza:", $txt,0,1);
//sezione
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(13);
$txt="Consistenza";
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);
$pdf->SetFillColor(255, 255, 255);
//una riga
$pdf->Ln(10);
$txt="Continuità di relazione consistente con il profilo economico del cliente:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['Consistency']['relation']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Dimensione del giro di affari consistente con il profilo patrimoniale del Cliente:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['Consistency']['dimension']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
$pdf->Ln(20);
$txt="<b>" .$rd['partial']['Consistency']. "</b>";
$pdf->MultiRow("Rischio Parziale Frequenza:", $txt,0,1);
// fine sezione
$pdf->AddPage('P', 'A4');
if ($contract['act_for_other']==1){
  //sezione
  $pdf->SetFont('helvetica', '', 11);
  $pdf->SetFillColor(180, 180, 180);
  $pdf->Ln(13);
  $txt="Profilo dell Società";
  $pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
  $pdf->Ln(13);
  $pdf->SetFillColor(255, 255, 255);
  //una riga
  $pdf->Ln(10);
  $txt="Struttura Societaria non chiara e comprensibile:";
  $pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
  if($rd['Company']['ownership_compreensive']==1){
    $alto='checked="checked"';
    $basso='';
  }else{
    $basso='checked="checked"';
    $alto='';
  }
  $txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
  $pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
  $txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
  $pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
  //fine riga
  //una riga
  $pdf->Ln(10);
  $txt="Struttura Partecipativa Complessa:";
  $pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
  if($rd['Company']['ownership_link']==1){
    $alto='checked="checked"';
    $basso='';
  }else{
    $basso='checked="checked"';
    $alto='';
  }
  $txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
  $pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
  $txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
  $pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
  //fine riga
  //una riga
  $pdf->Ln(10);
  $txt="Connessioni con Paesi a Rischio:";
  $pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
  if($rd['Company']['ownership_country']==1){
    $alto='checked="checked"';
    $basso='';
  }else{
    $basso='checked="checked"';
    $alto='';
  }
  $txt='<input type="radio" name="pep" id="pep-1" value="1" readonly="true" '.$alto.' /> Alto';
  $pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
  $txt='<input type="radio" name="pep" id="pep-0" value="1" readonly="true" '.$basso.' /> Basso';
  $pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
  //fine riga
  //fine riga
  $pdf->Ln(20);
  $txt="<b>" .$rd['partial']['Company']. "</b>";
  $pdf->MultiRow("Rischio Parziale Profilo Società:", $txt,0,1);
  // fine sezione
}
//settings
?>
