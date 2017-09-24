<?php
$_REQUEST['action']='login';
include_once("../../config.php");
$sql="SELECT * FROM import_mularoni as g " ;
$rows=$db->getRows($sql);
$i=1;  # code...
foreach ($rows as $row){
  $sql="select * from contract where import_id=". $row['id'] . " and agency_id=".$row['agency_id'];
  $contract=$db->getRow($sql);
  print_r($contract);
  if (count($contract)==0){
    echo "non trovato" .count($kyc). print_r($kyc);

    switch (strtolower($row['tipo_contratto'])){
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
    if (strtolower($row['soggetto']=='persona giuridica') )
      $row['act_for_other']=1;
    $row['import_id']= $row['id'];
    $contract=$row;
    $contract['id']=Null;
    $db->insertAry("contract", $contract);
    $SQL="SELECT id  FROM contract  WHERE import_id=". $row['id'];
    $row['contract_id']=$db->getVal($SQL);
    $contract=$db->getRow("select * from contract where contract_id=". $row['contract_id'] );
    print_r($contract,1);
  }
  //print_r($row);
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
    $other=array();
    $company=array();


  }
  else {
    $contractor=json_decode($kyc['contractor_data'],true);
    $other=json_decode($kyc['other_data'],true);
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
      $company['name']=$row['NomeTemp'];
      break;
    }
   if (strlen($row['titolare effettivo0'])>0){
     $other[0]['name']=$row['titolare effettivo0'];
     $new_time = explode("/",$row['docBE1']);
     $date = $new_time[2]."-".$new_time[1]."-".$new_time[0];
     $other[0]['id_validity']=$date;
   }
   if (strlen($row['titolare effettivo1'])>0){
    $other[1]['name']=$row['titolare effettivo1'];
     $new_time = explode("/",$row['docBE1']);
     $date = $new_time[2]."-".$new_time[1]."-".$new_time[0];
     $other[1]['id_validity']=$date;
   }
   if (strlen($row['titolare effettivo2'])>0){
     $other[2]['name']=$row['titolare effettivo2'];
     $new_time = explode("/",$row['docBE2']);
     $date = $new_time[2]."-".$new_time[1]."-".$new_time[0];
     $other[2]['id_validity']=$date;
   }
   if (strlen($row['titolare effettivo3'])>0){
     $other[3]['name']=$row['titolare effettivo3'];
     $new_time = explode("/",$row['docBE4']);
     $date = $new_time[2]."-".$new_time[1]."-".$new_time[0];
     $other[3]['id_validity']=$date;
   }


  $contract=json_encode($contract,JSON_UNESCAPED_SLASHES);
  $contractor=json_encode($contractor,JSON_UNESCAPED_SLASHES);
  $company=json_encode($company,JSON_UNESCAPED_SLASHES);
  $other=json_encode($other,JSON_UNESCAPED_SLASHES);
//  echo "contractor";
//  echo ($contractor);
  echo "contract_id".$row['contract_id'].PHP_EOL;
  //echo ($contract);
  $kyc['contractor_data']=$contractor;
//
  $kyc['contract_data']=$contract;
  $kyc['contractor_data']=$contractor;
  $kyc['company_data']=$company;
  $kyc['other_data']=$other;
  if ($new){
      $kyc['contract_id']=$row['contract_id'];
      $kyc['agent_id']=$row['agent_id'];
      $kyc['agency_id']=$row['agency_id'];
      $db->insertAry("kyc", $kyc);

      }
      else {
      $db->updateAry("kyc", $kyc,"where contract_id=". $row['contract_id']);
      }
      print_r($kyc);

      $i++ ;

    }

//if ($i>10)
//die();


 ?>
