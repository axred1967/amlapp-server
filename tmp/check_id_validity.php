<?php
$_REQUEST['action']='login';
include_once("../config.php");
$sql="SELECT a.*  from agency as a " ;
$agencies=$db->getRows($sql);


foreach ($agencies as $agency){
  $sql="SELECT co.* FROM  contract as co where  co.agency_id=" .$agency['agency_id'] ." order by agent_id ASC"  ;
  $contracts=$db->getRows($sql);
  $mail_admin="" ;
  $agent='';
  foreach ($contracts as $contract){
    if ($agent!=$contract['agent_id']){
      $agent=$contract['agent_id'];
      if ($agent>0 && strlen($mail_agent)>0){
        echo "invia mail Agente";
        $SQL="SELECT concat(us.name,' ',us.surname) as fullname, us.email FROM email WHERE user_id=".  $agent;
        $agent=$db->getRow($SQL);
        $vars = array(
          'data' => date('d/m/Y'),
          'mail_obj'=>$mail_agent
        );

        //////error_log($request['action']."prima -".print_r($vars,1) .$sql.  PHP_EOL);
        mail_template($user['email'],'add_customer',$vars, 'it');

      }
      $mail_agent="";
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
        $mail_admin.=$user['fullname'] . " Documento scaduto il " .$time . "numero:". "<br/>";
        $mail_agent.=$user['fullname'] . " Documento scaduto il " .$time ."<br/>";
        if (strlen($user['email'])>0){
          // invia mail cliente
          echo "invia mail cliente";
          $vars = array(
            'name' => $user['fullname'],
            'agency_name' => $agency['agency_name'],
            'number' => $contract['number']
          );

          //////error_log($request['action']."prima -".print_r($vars,1) .$sql.  PHP_EOL);
          mail_template($user['email'],'add_customer',$vars, 'it');
        }

      }
      else{
        $mail_admin.=$contract['NomeTemp'] . " Documento scaduto il: " .$time ." Contratto Numero: " .$contract['number'] ."<br/>";
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
    $SQL="SELECT concat(us.name,' ',us.surname) as fullname, us.email FROM email WHERE user_id=".  $agency['user_id'];
    $agency=$db->getRow($SQL);
    $vars = array(
      'data' => date('d/m/Y'),
      'mail_obj'=>$mail_admin
    );
    echo $mail_admin;
    die();

    //////error_log($request['action']."prima -".print_r($vars,1) .$sql.  PHP_EOL);
    mail_template($agency['email'].",axred1967@gmail.com",'doc_scaduti',$vars, 'it');



  }
}


 ?>
