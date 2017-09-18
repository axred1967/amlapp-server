<?php
// vecchia valutazione del cliente
$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(4);
$txt="Profilo soggettivo del Cliente";
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 10);
$txt="Natura Giuridica:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['oldRiskSm']['natgiu']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="natgiu" id="natgiu-1" value="1" readonly="true" '.$alto.' /> RPS';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="natgiu" id="natgiu-0" value="1" readonly="true" '.$basso.' /> RPI';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//una riga
$pdf->Ln(10);
$txt="Prevalente Attività Svolta:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['oldRiskSm']['mainact']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="pep" id="mainact-1" value="1" readonly="true" '.$alto.' /> RPS';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="pep" id="mainact-0" value="1" readonly="true" '.$basso.' /> RPI';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Comportamento del Cliente:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['oldRiskSm']['beahvior']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="beahvior" id="beahvior-1" value="1" readonly="true" '.$alto.' /> RPS';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="beahvior" id="beahvior-0" value="1" readonly="true" '.$basso.' /> RPI';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Comportamento del Cliente:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['oldRiskSm']['beahvior']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="beahvior" id="beahvior-1" value="1" readonly="true" '.$alto.' /> RPS <br/>poco o troppo collaborativo';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="beahvior" id="beahvior-0" value="1" readonly="true" '.$basso.' /> RPI <br/>collaborativo';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga

$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(23);
$txt="Profilo Oggettivo del Cliente";
$pdf->writeHTMLCell(180, 1, '', '', html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->Ln(13);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 10);
//una riga
$pdf->Ln(10);
$txt="Tipologia e Concreta Modalità di esecuzione:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['oldRiskSm']['tip_ese']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="beahvior" id="beahvior-1" value="1" readonly="true" '.$alto.' /> RPS';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="beahvior" id="beahvior-0" value="1" readonly="true" '.$basso.' /> RPI';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Ammontare e la frequenza del Rapporto:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['oldRiskSm']['amm_freq']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="amm_freq" id="amm_freq-1" value="1" readonly="true" '.$alto.' /> RPS';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="amm_freq" id="amm_freq-0" value="1" readonly="true" '.$basso.' /> RPI';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Coerenza della Operazione in relazione alle informazioni assunte:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['oldRiskSm']['coerenza']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="coerenza" id="coerenza-1" value="1" readonly="true" '.$alto.' /> RPS';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="coerenza" id="coerenza-0" value="1" readonly="true" '.$basso.' /> RPI';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
//una riga
$pdf->Ln(10);
$txt="Area Geografica della operazione:";
$pdf->writeHTMLCell(80, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'R', true);
if($rd['oldRiskSm']['country']==1){
  $alto='checked="checked"';
  $basso='';
}else{
  $basso='checked="checked"';
  $alto='';
}
$txt='<input type="radio" name="country" id="country-1" value="1" readonly="true" '.$alto.' /> RPS';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
$txt='<input type="radio" name="country" id="country-0" value="1" readonly="true" '.$basso.' /> RPI';
$pdf->writeHTMLCell(35, 1, '', '', html_entity_decode(($txt)), 0, 0, 1, true, 'C', true);
//fine riga
$pdf->AddPage('P', 'A4');
?>
