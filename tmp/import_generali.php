<?php
$_REQUEST['action']='login';
include_once("../config.php");
$sql="SELECT g.* , co.id as contract_id FROM generali as g left join contract as co on co.number=g.number and co.CPU=g.CPU and co.agency_id=g.agency_id" ;
$rows=$db->getRows($sql);
$i=1;  # code...
foreach ($rows as $row){
  if (!$row['contract_id']>0){
    $db->insertAry("contract", $row);
    $SQL="SELECT id  FROM contract  WHERE number=".$row['number']. " and CPU=".$row['CPU']." and agency_id=".$row['agency_id'];
    $row['contract_id']=$db->getVal($SQL);

  }
  print_r($row);
  $SQL="SELECT contractor_data FROM kyc WHERE contract_id=".  $row['contract_id'];
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

  }
  else {
    $contractor=json_decode($kyc['contractor_data'],true);

  }
  if ($row['checkpep']=='si'){
    $contractor['check_pep']=1;
  }
  if (strlen($row['Note'])>0){
    $contractor['notes']=$row['Note'];
  }
  $new_time = explode("/",$row['idValidity']);
  print_r($new_time);
//  echo "time" .strtotime($new_time[2].".".$new_time[1].".".$new_time[0]);
  $date = $new_time[2]."-".$new_time[1]."-".$new_time[0];
  $contractor['id_validity']=$date;
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
