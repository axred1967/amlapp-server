<?php
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
$txt= <<<EOD
<h4>A1. Natura Giuridica</h4>
          <table align="left" cellspacing='0' cellpadding="10" >
              <tr  style="font-size:14px;font-weight:bold;color:white;background-color:#1C224B;" >
                <td style="border: 1px solid black;" width="65%" >Descrizione Tipologia di Rischio</td>
                <td style="border: 1px solid black;" width="20%">Punteggio Max</td>
                <td style="border: 1px solid black;"width="15%" >Assegnato</td>
              </tr>
              <tr >
                <td  style="border: 1px solid black;" width="65%" align="left"  >
                  Congruità della natura giuridica prescelta in relazione all'attività svolta e alle dimensioni
                </td >
                <td style="border: 1px solid black;" width="20%" align="right">4</td>
                <td style="border: 1px solid black;" width="15%" align="right">

                    {{Risk.risk_data.AspConnCli.a1.congr}}


                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                  Articolazione organizzativa, complessità e opacità della struttura volte ad ostacolare l'identificazione del titolare effettivo o all'attività concretamente svolta
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                4
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a1.artOrg}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Partecipazione di persone politicamente esposte (cliente, soggetto per conto, titolare effettivo) - Cariche politiche istituzionali, funzioni svolte nell’ambito della PA  soprattutto se connesse con l’erogazione di fondi
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                2
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a1.partPEP}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Incarichi in società, associazioni, fondazioni, organizzazioni non lucrative, soprattutto se aventi sede in paesi ad alto rischio o non collaborativi
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                2
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a1.lavConRisk}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Processi penali o indagini in corso –Misure di prevenzione o provvedimenti di sequestro - Familiarità/stretti
                      legami con soggetti sottoposti a procedimenti penali o provvedimenti di sequestro o censiti nelle liste delle persone
                      o degli enti attivi nel finanziamento
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                2
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a1.pendPenali}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;background-color:gray;" width="65%"  >
                Subtotale A1.
                </td>
                <td style="border: 1px solid black;;background-color:gray;" width="20%" align="right">
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a1.subtot}}

                </td>
              </tr>
            </table>

EOD;
$subtot=0;
foreach ($rd['AspConnCli']['a1'] as $value){
  $subtot+=$value;
}
$rd['AspConnCli']['a1']['subtot']=$subtot;
$txt = preg_replace_callback('/\{\{(?<var>.+?)\}\}/', function($params) use ($rd) {
    $x=(string)$params['var'];
        list($a,$b,$c,$d,$e) = split('\.',$x);
        error_log('split'.$a."-".$b."-".$c."-".$d.PHP_EOL);

        error_log('var'.$params['var'].PHP_EOL);
        if (isset($rd[$c][$d][$e])) {
            return $rd[$c][$d][$e];
        } else {
            return '';
        }
    },
$txt);
$pdf->writeHTML($txt,true, false, false, false, '');
$pdf->AddPage('P', 'A4');
$txt= <<<EOD
<h4>A2. Prevalente attività svolta</h4>
          <table align="left" cellspacing='0' cellpadding="10" >
              <tr  style="font-size:14px;font-weight:bold;color:white;background-color:#1C224B;" >
                <td style="border: 1px solid black;" width="65%" >Descrizione Tipologia di Rischio</td>
                <td style="border: 1px solid black;" width="20%">Punteggio Max</td>
                <td style="border: 1px solid black;"width="15%" >Assegnato</td>
              </tr>
              <tr >
                <td  style="border: 1px solid black;" width="65%" align="left"  >
                Attività esposta al rischio di infiltrazioni criminali (appalti, sanità, raccolta e smaltimento rifiuti, energie rinnovabili, giochi)
                </td >
                <td style="border: 1px solid black;" width="20%" align="right">
                  7
                </td>
                <td style="border: 1px solid black;" width="15%" align="right">

                    {{Risk.risk_data.AspConnCli.a2.higRisk}}


                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                  Articolazione organizzativa, complessità e opacità della struttura volte ad ostacolare l'identificazione del titolare effettivo o all'attività concretamente svolta
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                  5
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a2.strCoe}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Conformità dell’attività svolta rispetto a quella indicata nell’atto costitutivo
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                5
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a2.attConf}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;background-color:gray;" width="65%"  >
                Subtotale A2.
                </td>
                <td style="border: 1px solid black;;background-color:gray;" width="20%" align="right">
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a2.subtot}}

                </td>
              </tr>
            </table>

EOD;
$subtot=0;
foreach ($rd['AspConnCli']['a2'] as $value){
  $subtot+=$value;
}
$rd['AspConnCli']['a2']['subtot']=$subtot;
$txt = preg_replace_callback('/\{\{(?<var>.+?)\}\}/', function($params) use ($rd) {
    $x=(string)$params['var'];
        list($a,$b,$c,$d,$e) = split('\.',$x);
        error_log('split'.$a."-".$b."-".$c."-".$d.PHP_EOL);

        error_log('var'.$params['var'].PHP_EOL);
        if (isset($rd[$c][$d][$e])) {
            return $rd[$c][$d][$e];
        } else {
            return '';
        }
    },
$txt);
$pdf->writeHTML($txt,true, false, false, false, '');
$txt= <<<EOD
<h4>A3. Comportamento tenuto al momento del conferimento dell'incarico </h4>
          <table align="left" cellspacing='0' cellpadding="10" >
              <tr  style="font-size:14px;font-weight:bold;color:white;background-color:#1C224B;" >
                <td style="border: 1px solid black;" width="65%" >Descrizione Tipologia di Rischio</td>
                <td style="border: 1px solid black;" width="20%">Punteggio Max</td>
                <td style="border: 1px solid black;"width="15%" >Assegnato</td>
              </tr>
              <tr >
                <td  style="border: 1px solid black;" width="65%" align="left"  >
                Cliente non presente fisicamente
                </td >
                <td style="border: 1px solid black;" width="20%" align="right">
                  2
                </td>
                <td style="border: 1px solid black;" width="15%" align="right">

                    {{Risk.risk_data.AspConnCli.a3.nnPresFis}}


                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Presenza di soggetti con ruolo non definito
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                  3
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a3.beahvnnTr}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;background-color:gray;" width="65%"  >
                Subtotale A3.
                </td>
                <td style="border: 1px solid black;;background-color:gray;" width="20%" align="right">
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a3.subtot}}

                </td>
              </tr>
            </table>

EOD;
$subtot=0;
foreach ($rd['AspConnCli']['a3'] as $value){
  $subtot+=$value;
}
$rd['AspConnCli']['a3']['subtot']=$subtot;
$txt = preg_replace_callback('/\{\{(?<var>.+?)\}\}/', function($params) use ($rd) {
    $x=(string)$params['var'];
        list($a,$b,$c,$d,$e) = split('\.',$x);
        error_log('split'.$a."-".$b."-".$c."-".$d.PHP_EOL);

        error_log('var'.$params['var'].PHP_EOL);
        if (isset($rd[$c][$d][$e])) {
            return $rd[$c][$d][$e];
        } else {
            return '';
        }
    },
$txt);
$pdf->writeHTML($txt,true, false, false, false, '');
$txt= <<<EOD
<h4>A4. Area geografica di residenza del cliente</h4>
          <table align="left" cellspacing='0' cellpadding="10" >
              <tr  style="font-size:14px;font-weight:bold;color:white;background-color:#1C224B;" >
                <td style="border: 1px solid black;" width="65%" >Descrizione Tipologia di Rischio</td>
                <td style="border: 1px solid black;" width="20%">Punteggio Max</td>
                <td style="border: 1px solid black;"width="15%" >Assegnato</td>
              </tr>
              <tr >
                <td  style="border: 1px solid black;" width="65%" align="left"  >
                Residenza in comune a rischio a causa dell’utilizzo eccessivo di contante - Residenza in Stati extra UE con regime antiriciclaggio non equivalente o in territori offshore – Residenza in Stati extra UE o in territori stranieri che impongono obblighi equivalenti
                </td >
                <td style="border: 1px solid black;" width="20%" align="right">
                  7
                </td>
                <td style="border: 1px solid black;" width="15%" align="right">

                    {{Risk.risk_data.AspConnCli.a4.resPlaceRisk}}


                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Lontananza della residenza del cliente rispetto alla sede del  professionista
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                  5
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a4.resLontana}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;background-color:gray;" width="65%"  >
                Subtotale A4.
                </td>
                <td style="border: 1px solid black;;background-color:gray;" width="20%" align="right">
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.AspConnCli.a4.subtot}}

                </td>
              </tr>
            </table>

EOD;
$subtot=0;
foreach ($rd['AspConnCli']['a4'] as $value){
  $subtot+=$value;
}
$rd['AspConnCli']['a4']['subtot']=$subtot;
$txt = preg_replace_callback('/\{\{(?<var>.+?)\}\}/', function($params) use ($rd) {
    $x=(string)$params['var'];
        list($a,$b,$c,$d,$e) = split('\.',$x);
        error_log('split'.$a."-".$b."-".$c."-".$d.PHP_EOL);

        error_log('var'.$params['var'].PHP_EOL);
        if (isset($rd[$c][$d][$e])) {
            return $rd[$c][$d][$e];
        } else {
            return '';
        }
    },
$txt);
$pdf->writeHTML($txt,true, false, false, false, '');

$pdf->AddPage('P', 'A4');
$y = $pdf->getY()-5;$pdf->SetFont('helvetica', '', 11);
$pdf->SetFillColor(180, 180, 180);
$pdf->Ln(13);
$txt="Aspetti Connessi alla Operazione";
$pdf->writeHTMLCell(180, 1, '', $y, html_entity_decode(strtoupper($txt)), 0, 0, 1, true, 'C', true);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255, 255, 255);
$pdf->Ln(13);
$txt= <<<EOD
<h4>B1. Tipologia dell'operazione, rapporto continuativo o prestazione professionale</h4>
          <table align="left" cellspacing='0' cellpadding="10" >
              <tr  style="font-size:14px;font-weight:bold;color:white;background-color:#1C224B;" >
                <td style="border: 1px solid black;" width="65%" >Descrizione Tipologia di Rischio</td>
                <td style="border: 1px solid black;" width="20%">Punteggio Max</td>
                <td style="border: 1px solid black;"width="15%" >Assegnato</td>
              </tr>
              <tr >
                <td  style="border: 1px solid black;" width="65%" align="left"  >
                Ordinaria rispetto al profilo soggettivo del cliente
                </td >
                <td style="border: 1px solid black;" width="20%" align="right">
                2
                </td>
                <td style="border: 1px solid black;" width="15%" align="right">

                    {{Risk.risk_data.aspConnOpe.b1.ordRispOp}}


                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Straordinaria rispetto al profilo soggettivo del cliente
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                2
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b1.strCoe}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Operazione che prevede schemi negoziali che possono agevolare l'opacità delle relazioni economiche e finanziarie intercorrenti tra il cliente e la controparte
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                6
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b1.schemiNeg}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;background-color:gray;" width="65%"  >
                Subtotale B1.
                </td>
                <td style="border: 1px solid black;;background-color:gray;" width="20%" align="right">
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b1.subtot}}

                </td>
              </tr>
            </table>

EOD;
$subtot=0;
foreach ($rd['aspConnOpe']['b1'] as $value){
  $subtot+=$value;
}
$rd['aspConnOpe']['b1']['subtot']=$subtot;
$txt = preg_replace_callback('/\{\{(?<var>.+?)\}\}/', function($params) use ($rd) {
    $x=(string)$params['var'];
        list($a,$b,$c,$d,$e) = split('\.',$x);
        error_log('split'.$a."-".$b."-".$c."-".$d.PHP_EOL);

        error_log('var'.$params['var'].PHP_EOL);
        if (isset($rd[$c][$d][$e])) {
            return $rd[$c][$d][$e];
        } else {
            return '';
        }
    },
$txt);
$pdf->writeHTML($txt,true, false, false, false, '');
$txt= <<<EOD

<h4>
  B2. Modalità di svolgimento dell'operazione, rapporto continuativo o prestazione professionale
</h4>
          <table align="left" cellspacing='0' cellpadding="10" >
              <tr  style="font-size:14px;font-weight:bold;color:white;background-color:#1C224B;" >
                <td style="border: 1px solid black;" width="65%" >Descrizione Tipologia di Rischio</td>
                <td style="border: 1px solid black;" width="20%">Punteggio Max</td>
                <td style="border: 1px solid black;"width="15%" >Assegnato</td>
              </tr>
              <tr >
                <td  style="border: 1px solid black;" width="65%" align="left"  >
                Utilizzo di mezzi di pagamento non tracciati
                </td >
                <td style="border: 1px solid black;" width="20%" align="right">
                1
                </td>
                <td style="border: 1px solid black;" width="15%" align="right">

                    {{Risk.risk_data.aspConnOpe.b2.metPagnnTr}}


                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Utilizzo di conti non propri per trasferire/ricevere fondi
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                1
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b2.contnnPr}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Ricorso reiterato a procure
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                1
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b2.procure}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                Ricorso a domiciliazioni di comodo
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                1
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b2.dom}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;background-color:gray;" width="65%"  >
                Subtotale B2.
                </td>
                <td style="border: 1px solid black;;background-color:gray;" width="20%" align="right">
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b2.subtot}}

                </td>
              </tr>
            </table>

EOD;
$subtot=0;
foreach ($rd['aspConnOpe']['b2'] as $value){
  $subtot+=$value;
}
$rd['aspConnOpe']['b2']['subtot']=$subtot;
$txt = preg_replace_callback('/\{\{(?<var>.+?)\}\}/', function($params) use ($rd) {
    $x=(string)$params['var'];
        list($a,$b,$c,$d,$e) = split('\.',$x);
        error_log('split'.$a."-".$b."-".$c."-".$d.PHP_EOL);

        error_log('var'.$params['var'].PHP_EOL);
        if (isset($rd[$c][$d][$e])) {
            return $rd[$c][$d][$e];
        } else {
            return '';
        }
    },
$txt);
$pdf->writeHTML($txt,true, false, false, false, '');
$txt= <<<EOD
<h4>
  B3. Ammontare dell'operazione
</h4>
          <table align="left" cellspacing='0' cellpadding="10" >
              <tr  style="font-size:14px;font-weight:bold;color:white;background-color:#1C224B;" >
                <td style="border: 1px solid black;" width="65%" >Descrizione Tipologia di Rischio</td>
                <td style="border: 1px solid black;" width="20%">Punteggio Max</td>
                <td style="border: 1px solid black;"width="15%" >Assegnato</td>
              </tr>
              <tr >
                <td  style="border: 1px solid black;" width="65%" align="left"  >
                Coerenza dell'ammontare rispetto al profilo economico e finanziario del cliente
                </td >
                <td style="border: 1px solid black;" width="20%" align="right">
                4
                </td>
                <td style="border: 1px solid black;" width="15%" align="right">

                    {{Risk.risk_data.aspConnOpe.b3.ammCoe}}


                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;" width="65%" >
                  Presenza di frazionamenti artificiosi
                </td>
                <td style="border: 1px solid black;" width="20%" align="right">
                2
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b3.fraz}}

                </td>
              </tr>
              <tr>
                <td style="border: 1px solid black;background-color:gray;" width="65%"  >
                Subtotale B2.
                </td>
                <td style="border: 1px solid black;;background-color:gray;" width="20%" align="right">
                </td>
                <td style="border: 1px solid black;" width="15%" align="right" >

                    {{Risk.risk_data.aspConnOpe.b3.subtot}}

                </td>
              </tr>
            </table>

EOD;
$subtot=0;
foreach ($rd['aspConnOpe']['b3'] as $value){
  $subtot+=$value;
}
$rd['aspConnOpe']['b3']['subtot']=$subtot;
$txt = preg_replace_callback('/\{\{(?<var>.+?)\}\}/', function($params) use ($rd) {
    $x=(string)$params['var'];
        list($a,$b,$c,$d,$e) = split('\.',$x);
        error_log('split'.$a."-".$b."-".$c."-".$d.PHP_EOL);

        error_log('var'.$params['var'].PHP_EOL);
        if (isset($rd[$c][$d][$e])) {
            return $rd[$c][$d][$e];
        } else {
            return '';
        }
    },
$txt);
$pdf->writeHTML($txt,true, false, false, false, '');

$pdf->AddPage('P', 'A4');

 ?>
