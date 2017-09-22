<?php
$_REQUEST['action']='login';
include_once("../config.php");
$sql="SELECT * FROM import_mularoni as g " ;
$rows=$db->getRows($sql);
$i=1;  # code...
foreach ($rows as $row){
  $sql="select * from contract where import_id=". $row['id']
  $contract=$db->getRows($sql);
  if (!count($contract)>0){
    switch(strtolower($row['tipo_contratto']){
      case 'continuativo':
        $row['tipo_contratto']=0;
        break;
      case 'professionale':
          $row['tipo_contratto']=1;
          break;
      case 'occasionale':
          $row['tipo_contratto']=2;
          break;

    }
    if (strtolower($row['soggetto']=='persona fisica') )
      $row['act_for_other']=0;
    if (strtolower($row['soggetto']=='persona giuridica') ){
      $row['act_for_other']=1;


    $db->insertAry("contract", $row);
    $SQL="SELECT id  FROM contract  WHERE import_id=". $row['id'];
    $row['contract_id']=$db->getVal($SQL);

  }
  print_r($row);
  $SQL="SELECT other_data,company_data, contractor_data FROM kyc WHERE contract_id=".  $row['contract_id'];
  $kyc=$db->getRow($SQL);
/*
  $sqlco="SELECT * FROM contract WHERE id=".  $row['contract_id'];
  $co=$db->getRow($sqlco);
  $contract=stripslashes(json_encode($co,JSON_UNESCAPED_SLASHES));
*/
  echo "trovato" .count($kyc). print_r($kyc);
  if (count($kyc)==0){
    $new=true;
    $contractor=array();
    $others=array();

  }
  else {
    $contractor=json_decode($kyc['contractor_data'],true);
    $others=json_decode($kyc['other_data'],true);
    $company=json_decode($kyc['company_data'],true);
  }
  if ($row['checkpep']=='si'){
    $contractor['check_pep']=1;
  }

  if (strlen($row['note'])>0){
    $contractor['notes']=$row['Note'];
  }
  $new_time = explode("/",$row['id_validity']);
  print_r($new_time);
//  echo "time" .strtotime($new_time[2].".".$new_time[1].".".$new_time[0]);
  $date = $new_time[2]."-".$new_time[1]."-".$new_time[0];
  $contractor['id_validity']=$date;
  switch($row['act_for_other']){
    case 0:
      $contractor['name']=$row['NomeTemp'];
      break;
    case 1:
      $contractor['name']=$row['NomeTempOther'];
      $company['name']=$row['NomeTemp']
      break;
    }
   if (strlen($row['titolare effettivo0'])>0){
     $other[0]['name']=
   }


  $contractor=json_encode($contractor,JSON_UNESCAPED_SLASHES);
//  echo "contractor";
//  echo ($contractor);
  echo "contract_id".$row['contract_id'].PHP_EOL;
  //echo ($contract);
  $kyc['contractor_data']=$contractor;
//
  if ($new){
      $kyc['contract_id']=$row['contract_id'];
      $kyc['agent_id']=$row['agent_id'];
      $kyc['agency_id']=$row['agency_id'];
      $kyc['contract_data']=$contract;
      $kyc['contractor_data']=$contractor;
      $db->insertAry("kyc", $kyc);

      }
      else {
      $db->updateAry("kyc", $kyc,"where contract_id=". $row['contract_id']);
      }
$i++ ;

//if ($i>10)
//die();
}


 ?>
