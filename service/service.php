<?php
include_once("../../config.php");
//error_log("passato2 ".$_SERVER['$request_METHOD']. $request['action'].print_r($request,1).PHP_EOL);
function doAction($request,$files,$db,$data=array(),$firsAction){
  error_log("DoAction-Start ". $request['action'].print_r($request,1).PHP_EOL);
  if ($request['action']=='signUp'){
    error_log('vvvvvvvvvvv');
    if ($request['other_actions'][0]['action']=='saveOb' && $request['other_actions'][1]['action']=='mail_template' &&
        $request['other_actions'][2]['action']=='mail_template' && count($request['other_actions'])==3){
          error_log('qqqqqqqqqq');
      $data['RESPONSECODE']=1;
      return $data;
    }
    else{
      error_log('zzzzzzzzz');
      $data['RESPONSECODE']=0;
      return $data;
    }
  }

  if ($request['action']=='login'){
      if ($request['username']=='' || $request['password']==''){
        $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Immettere username e password");
        echo json_encode($data);
        return $data;
      }
        //$sql="SELECT * FROM users  WHERE BINARY lower(email)='".trim(strtolower($request['username']))."' AND BINARY password='".$request['password']."' AND (user_type = '1' OR user_type = '2' ) ";
      $sql="SELECT * FROM users  WHERE BINARY lower(email)='".trim(strtolower($request['username']))."' AND BINARY password='".$request['password']."' ";
      $chkuser=$db->getRow($sql);

      if(!$chkuser['user_id']>0){
          $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Username / Password non corrispondono" );
          echo json_encode($data);
          return $data;
      }


      if($chkuser['status']=='2')
      {
        $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> " Your Account is Deleted");
        echo json_encode($data);
        return $data;
      }
      if(!($chkuser['user_type'] =='-1' || $chkuser['user_type'] =='1' || $chkuser['user_type'] =='2' || $chkuser['user_type'] =='3' ) )
      {
        $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Tipo utente non consentito");
        echo json_encode($data);
        return $data;
      }

      if($chkuser['user_type'] =='1')
      {
        $set = $db->getRow("SELECT agency_id,country,tipo_cliente_app FROM agency WHERE user_id = '".$chkuser['user_id']."'  ");
        $agencyId =$set['agency_id'];
        $paese = trim(strtolower($set['country']));
        $tipo_cliente = trim(strtolower($set['tipo_cliente_app']));
        $agentId = -1;
        $agentPriviledge =-1;
        $settings=$chkuser['settings'];

      }
      if($chkuser['user_type'] =='2')
      {
        $set = $db->getRow("SELECT agency_id,agent_id,agent_previledge FROM agent WHERE user_id = '".$chkuser['user_id']."'  ");
        $agencyId = $set['agency_id'];
        $agentId = $set['agent_id'];
        $agentPriviledge=$set['agent_previledge'];
        $set = $db->getRow("SELECT a.country,a.tipo_cliente_app,u.settings FROM agency as a join users as u on a.user_id=u.user_id  WHERE a.agency_id = '".$agencyId."'  ");
        $paese = trim(strtolower($set['country']));
        $tipo_cliente = trim(strtolower($set['tipo_cliente_app']));
        $settings=$set['settings'];

      }
      if($chkuser['user_type'] =='-1'){
        $agencyId = -1;
        $agentId = -1;
        $paese="san marino";
        $tipo_cliente="agenzia di assicurazioni";
        $agentPriviledge =-1;
      }
      if($chkuser['user_type'] =='3'){
        $agencyId = -3;
        $agentId = -3;
        $agentPriviledge =-3;
        $set = $db->getRow("SELECT a.country,a.tipo_cliente_app,u.settings FROM agency as a join users ad u on a.user_id=u.user_id  WHERE a.agency_id = '".$request['pInfo']['agency_id']."'  limit 1 ");
        $paese = trim(strtolower($set['country']));
        $tipo_cliente = trim(strtolower($set['tipo_cliente_app']));
        $settings=$set['settings'];
      }

      $cookie=rand(0,10000000);
      setcookie("user".TIPO_CONF.$chkuser['user_id'],$cookie,time()+3600*48,DS.TIPO_CONF);

      if($chkuser['status']=='99')
      {
        $data = array('RESPONSECODE'=> 3 ,'RESPONSE'=> "Completa la Registrazione!\n clicca sul link di verifica sulla tua email di iscrizione",
                        "userId"=>$chkuser['user_id'],"agentId"=>$agentId,"agencyId"=>$agencyId,
                        "email" => $chkuser['email'],"name"  =>$chkuser['name'],
                        "usertype" => $chkuser['user_type'], "image_name" => $chkuser["image"],
                        "priviledge" => $agentPriviledge,"settings" => $settings,
                        "paese"=>$paese,"tipo_cliente"=>$tipo_cliente,
                         "cookie"=>$cookie );
        echo json_encode($data);
        return $data;
      }


      $data = array('RESPONSECODE'=> 1 ,"userId"=>$chkuser['user_id'],"agentId"=>$agentId,"agencyId"=>$agencyId,
                      "email" => $chkuser['email'],"name"  =>$chkuser['name'],
                      "usertype" => $chkuser['user_type'], "image_name" => $chkuser["image"],
                      "priviledge" => $agentPriviledge,"settings" => $settings,
                      "paese"=>$paese,"tipo_cliente"=>$tipo_cliente,
                       "cookie"=>$cookie );
      //////error_log(print_r($data,1));
      echo json_encode($data);
      die();
  }
  if  ($firsAction!='signUp' ){
    if (strlen($request['pInfo']['agency_id'])==0 ||strlen($request['pInfo']['user_type'])==0 ||strlen($request['pInfo']['agent_id'])==0 ||strlen($request['pInfo']['agency_id'])==0 ){
      $data=array(  'RESPONSECODE'	=>  -1,   'RESPONSE'	=> "Credeziali non valide 1", "var"=>print_r($_REQUEST,1).print_r($_COOKIE,1));
      //error_log("credenziali non valide". print_r($_REQUEST,1));
      echo json_encode($data);
      die();

    }
    if ($request['pInfo']['user_type']>1 && $request['pInfo']['user_type']<3){
      $SQL="select a.agent_id,a.agency_id,u.status from agent as a
          join users as u on a.user_id=u.user_id
          where a.agent_id='".$request['pInfo']['agent_id']."'
          and a.agency_id='".$request['pInfo']['agency_id']."'";
      $us=$db->getRow($SQL);
      error_log("us".print_r($us,1));
      if ( $us['agent_id']!=$request['pInfo']['agent_id'] || $us['agency_id']!=$request['pInfo']['agency_id'] ){
        $data=array(  'RESPONSECODE'	=>  -1,   'RESPONSE'	=> "Credeziali non valide", "var"=>print_r($_REQUEST,1).print_r($_COOKIE,1));
        //error_log("credenziali non valide". print_r($_REQUEST,1));
        echo json_encode($data);
        die();
      }

    }
    if ($request['pInfo']['user_type']>=3){
      $SQL="select u.status from users as u
          where u.user_id='".$request['pInfo']['user_id']."'";
      $us=$db->getRow($SQL);
      error_log("us".print_r($us,1));
      if (-3!=$request['pInfo']['agent_id'] || -3!=$request['pInfo']['agency_id'] ){
        $data=array(  'RESPONSECODE'	=>  -1,   'RESPONSE'	=> "Credeziali non valide", "var"=>print_r($_REQUEST,1).print_r($_COOKIE,1));
        //error_log("credenziali non valide". print_r($_REQUEST,1));
        echo json_encode($data);
        die();
      }

    }
    if ($request['pInfo']['user_type']==1){
      $SQL="select a.agency_id,u.status  from agency as a
          join users as u on a.user_id=u.user_id
          where a.user_id='".$request['pInfo']['user_id']."'
          and a.agency_id='".$request['pInfo']['agency_id']."'";
      $us=$db->getRow($SQL);
      error_log("us".print_r($us,1));
      if ($us['agency_id']!=$request['pInfo']['agency_id']){
        $data=array(  'RESPONSECODE'	=>  -1,   'RESPONSE'	=> "Credeziali non valide", "var"=>print_r($_REQUEST,1).print_r($_COOKIE,1));
        //error_log("credenziali non valide". print_r($_REQUEST,1));
        echo json_encode($data);
        die();
      }
    }
    if ($request['pInfo']['user_type']==-1){
      $SQL="select u.* from users as u
          where u.user_id='".$request['pInfo']['user_id']."' ";
          $db->getRows($SQL);
          $us=$db->getRow($SQL);
          error_log("us".print_r($us,1));
          if ($us['user_id']!=$request['pInfo']['user_id']){
            $data=array(  'RESPONSECODE'	=>  -1,   'RESPONSE'	=> "Credeziali non valide", "var"=>print_r($_REQUEST,1).print_r($_COOKIE,1));
            //error_log("credenziali non valide". print_r($_REQUEST,1));
            echo json_encode($data);
            die();
          }

    }
    if ($us['status']!=1 && !( strtolower($request['pInfo']['action'])=='signup' || strtolower($request['pInfo']['action'])=='completesignup') ){
      $data=array(  'RESPONSECODE'	=>  -1,   'RESPONSE'	=> "Utente non verificato", "var"=>print_r($us,1).print_r($_REQUEST,1).print_r($_COOKIE,1));
      //error_log("credenziali non valide". print_r($_REQUEST,1));
      echo json_encode($data);
      die();

    }
  }

  switch($request['action'])
  {
    case 'countryList' :
    $countryList = $db->getRows("SELECT * FROM countries  ORDER BY FIELD( country_name,  'San Marino','Italia' ) DESC, country_name ASC ");
    $data = array('countryList'=>$countryList);
    echo json_encode($data);
    break;


      case 'Profile_info' :
    {
      if($request['id']!='' && $request['id']!='')
      {
        $userDetails=$db->getRow("SELECT users.* FROM  users WHERE user_id='".trim($request['id'])."'");
        if($userDetails['user_type'] == '1'  )
        {
          $type =1;
          $plan_name=$db->getVal("SELECT pl.plan_name FROM plan pl JOIN agency ay ON pl.plan_id =ay.plan_id  WHERE ay.user_id='".$request['id']."' ");
        }
        else if($userDetails['user_type'] == '2')
        {
          $type =2;
          $plan_name=$db->getVal("SELECT name FROM users  us JOIN agent ay ON us.user_id = ay.agency_id WHERE ay.user_id='".$request['id']."' ");
        }

        if($userDetails['user_id']!='')
        {

          $userInfo= array('name'=> $userDetails['name'],'email'=>$userDetails['email'],'mobile'=>$userDetails['mobile'],'imagename'  =>$userDetails['image'] ,'plan_name'  => ucwords($plan_name),'type' => $type );
          $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> $userInfo);
        }
        else{$data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Invalid User");}
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
      }
      echo json_encode($data);
      break;
    }
    case 'saveProfileAx':
    {
      $error='';


      if($error=='')
      {

        $aryData=$request['dbData'];
        $flgIn=$db->updateAry("users",$aryData,"where user_id='".$request['pInfo']['user_id']."'");
        if(!is_null($flgIn))
        {
          $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> "Details Saved Successfully");
        }
        else
        {
          $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "Error");
        }
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
      }
      echo json_encode($data);
      break;


    }
    case 'saveKycAx':{
      if ($request['appData']['contract_id']!='' ){
        // aggiorno sempre dati contratto
        $contract = $db->getRow("SELECT * FROM contract where id=". $request['appData']['contract_id']);
        error_log("kyc".print_r($contract,1));
        $request['appData']['contract_data']=json_encode($contract,JSON_UNESCAPED_SLASHES);

        if ($request['final']){
          $request['dbData']['kyc_status']=1;
        }
        if ($request['agg']>0){
          $db->updateAry("kyc_log", $request['dbData'], "where id=". $request['agg']);

        }else {
          $db->updateAry("kyc", $request['dbData'], "where contract_id=". $request['appData']['contract_id']);

        }

        $contractor=json_decode($request['dbData']['contractor_data'],true);
        //aggiornamento word
        foreach ($contractor as $key => $word){
          $date = date_parse($word);
          if (!($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))) {
              if (! is_numeric($word) && strlen($word)>3 && $key!="sign" ){
                $sql="select * from word_tag_kyc where agency_id=" .$request['pInfo']['agency_id'] ."
                and  kyc_id='" .$request['appData']['contract_id'] . "' and  id_tag='" .$key ."'";
                $w=$db->getRow($sql);
                if (count($w)>0){
                  $w['agency_id']=$request['pInfo']['agency_id'] ;
                  $w['kyc_id']=$request['appData']['contract_id'] ;
                  $w['id_tag']=$key;
                  $w['word']=$word;
                  $db->updateAry('word_tag_kyc',$w," where agency_id=" .$request['pInfo']['agency_id'] ."
                  and  kyc_id='" .$request['appData']['contract_id'] . "' and  id_tag='" .$key ."'");

                }
                else{
                  $w['agency_id']=$request['pInfo']['agency_id'] ;
                  $w['kyc_id']=$request['appData']['contract_id'] ;
                  $w['id_tag']=$key;
                  $w['word']=$word;
                  $db->insertAry('word_tag_kyc',$w);

                }

              }
            }
          }
          $Docs=json_decode($request['dbData']['Docs'],true);
          //aggiornamento word per i documenti
          $i=0;
          foreach ($Docs as $key => $Doc){

                  $sql="select * from word_tag_kyc where agency_id=" .$request['pInfo']['agency_id'] ."
                  and  kyc_id='" .$request['appData']['contract_id'] . "' and  id_tag='doc_type' and tag_key=".$i;
                  $w=$db->getRow($sql);
                  if (count($w)>0){
                    $w['agency_id']=$request['pInfo']['agency_id'] ;
                    $w['kyc_id']=$request['appData']['contract_id'] ;
                    $w['tag_key']=$i;
                    $w['id_tag']='doc_type';
                    $w['word']=$Doc['doc_type'];
                    $db->updateAry('word_tag_kyc',$w," where agency_id=" .$request['pInfo']['agency_id'] ."
                    and  kyc_id='" .$request['appData']['contract_id'] . "' and  id_tag='doc_type' and tag_key=".$i);

                  }
                  else{
                    $w['agency_id']=$request['pInfo']['agency_id'] ;
                    $w['kyc_id']=$request['appData']['contract_id'] ;
                    $w['tag_key']=$i;
                    $w['id_tag']='doc_type';
                    $w['word']=$Doc['doc_type'];
                    $db->insertAry('word_tag_kyc',$w);

                  }
                  $i++;
                }


        //aggiornamento dati persone
        if (strlen($contractor['fiscal_number'])>0){
          $sqlperson="select * from kyc_person where agency_id=" .$request['pInfo']['agency_id'] ." and  fiscal_id='" .$contractor['fiscal_number'] ."'";
          $person=$db->getRow($sqlperson);
          if (count($person)>0){
            error_log("bcontractor_date".print_r($contractor,1).$request['dbData']['contractor_data']);
            $person['fullname']=$contractor['name'] ." " .$contractor['surname'];
            $person['fiscal_id']=$contractor['fiscal_number'];
            $person['kyc_data']=$request['dbData']['contractor_data'];
            $person['agency_id']=$request['pInfo']['agency_id'] ;
            $db->updateAry('kyc_person',$person,"where agency_id=" .$request['pInfo']['agency_id'] ." and  fiscal_id='" .$contractor['fiscal_number'] ."'");

          }
          else{
            error_log("acontractor_date".print_r($contractor,1).$request['dbData']['contractor_data']);
            $person=array();
            $person['fullname']=$contractor['name'] ." " .$contractor['surname'];
            $person['fiscal_id']=$contractor['fiscal_number'];
            $person['kyc_data']=$request['dbData']['contractor_data'];
            $person['agency_id']=$request['pInfo']['agency_id'] ;
            $db->insertAry('kyc_person',$person);

          }
        }
        //aggiorno owners
        $owners=json_decode($request['dbData']['owner_data'],true);
        if (is_array($owners) && count($owners)>0)
        foreach ($owners as $contractor) {
          $sqlperson="select * from kyc_person where agency_id=" .$request['pInfo']['agency_id'] ." and  fiscal_id='" .$contractor['fiscal_number'] ."'";
          $person=$db->getRow($sqlperson);
          if (count($person)>0){
            error_log("bcontractor_date".print_r($contractor,1).$request['dbData']['contractor_data']);
            $person['fullname']=$contractor['name'] ." " .$contractor['surname'];
            $person['fiscal_id']=$contractor['fiscal_number'];
            $person['kyc_data']=$request['dbData']['contractor_data'];
            $person['agency_id']=$request['pInfo']['agency_id'] ;
            //$db->updateAry('kyc_person',$person,"where agency_id=" .$request['pInfo']['agency_id'] ." and  fiscal_id='" .$contractor['fiscal_number'] ."'");

          }
          else{
            error_log("acontractor_date".print_r($contractor,1).$request['dbData']['contractor_data']);
            $person=array();
            $person['fullname']=$contractor['name'] ." " .$contractor['surname'];
            $person['fiscal_id']=$contractor['fiscal_number'];
            $person['kyc_data']=$request['dbData']['contractor_data'];
            $person['agency_id']=$request['pInfo']['agency_id'] ;
            $db->insertAry('kyc_person',$person);

          }

        }
        //aggiorno owners
        $company=json_decode($request['dbData']['company_data'],true);
        if (strlen($company['fiscal_id'])>0){
          $sqlperson="select * from kyc_company where agency_id=" .$request['pInfo']['agency_id'] ." and  fiscal_id='" .$company['fiscal_id'] ."'";
          $person=$db->getRow($sqlperson);
          if (count($person)>0){
            error_log("bcontractor_date".print_r($company,1).$request['dbData']['contractor_data']);
            $person['fullname']=$company['name'];
            $person['fiscal_id']=$company['fiscal_id'];
            $person['kyc_data']=$request['dbData']['company_data'];
            $person['agency_id']=$request['pInfo']['agency_id'] ;
            $db->updateAry('kyc_company',$person,"where agency_id=" .$request['pInfo']['agency_id'] ." and  fiscal_id='" .$company['fiscal_id'] ."'");

          }
          else{
            error_log("acontractor_date".print_r($company,1).$request['dbData']['contractor_data']);
            $person=array();
            $person['fullname']=$company['name'];
            $person['fiscal_id']=$company['fiscal_id'];
            $person['kyc_data']=$request['dbData']['company_data'];
            $person['agency_id']=$request['pInfo']['agency_id'] ;
            $db->insertAry('kyc_company',$person);

          }
        }


        $data = array('RESPONSECODE'=> 1 ,'RESPONSE'=> "Information updated successfully");
        echo json_encode($data);
        if ($request['final']){
          $request['appData']['kyc_status']=1;
          $flgIn=$db->updateAry("contract", $request['appData'], "where id=". $request['appData']['contract_id']);
        }
        break;
      }
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
      echo json_encode($data);
      break;

    }

    case 'kycAx':{
      if ($request['appData']['contract_id']!='' ){
        if ($request['agg']>0){
          $SQL="SELECT * FROM kyc_log WHERE id=".  $request['agg'];
          $kyc=$db->getRow($SQL);

        }else {
          $SQL="SELECT * FROM kyc WHERE contract_id=".  $request['appData']['contract_id'];
          $kyc=$db->getRow($SQL);

        }

        if (count($kyc)==0){
          $aryData=$request['appData'];
          $Kyc=$request['appData'];
          $new=true;
        }
        $aryData2=$request['appData'];
        $aryData2['name']=$aryData['name1'];
        $contract = $db->getRow("SELECT * FROM contract where id=".$aryData2['contract_id']);
        error_log("kyc".print_r($kyc,1));
        $kyc['contract_data']=json_encode($contract,JSON_UNESCAPED_SLASHES);
        // aggiorno gli owner
        if ($aryData2['act_for_other']==2 && !$request['agg']>0){
          $owner=json_decode($kyc['owner_data'],true);
          $present=false;
          error_log("ciaox1".print_r($owner,1));

          foreach ($owner as $key => $value) {
              if ($value['user_id']==$request['appData']['other_id'])
                $present=true;
          }

          if (! $present || $kyc['owner_data']=='false' ||  count($owner)==0) {
            $owners = $db->getRows("SELECT percentuale, us.* FROM  company_owners as co join users as us on us.user_id=co.user_id where  contract_id=".$aryData2['contract_id']);
            $kyc['owner_data']=json_encode($owners,JSON_UNESCAPED_SLASHES);
          }
        }
        $docs=json_decode($kyc['Docs'],true);
          if (is_array($docs) && count($docs)>0){
            error_log("metto a posto il loaded". print_r($docs,1));
            foreach ($docs as $key=>$doc) {
              if (!$doc['loaded']){
                error_log('metto a posto');
                $SQL="SELECT loaded FROM tmp_image WHERE imagename='".$doc['doc_image']."'"  ;
                $exist=$db->getRow($SQL);
                if (count($exist)>0){
                  $docs[$key]['loaded']=true;
                }
              }
            }
            $kyc['Docs']=json_encode($docs,JSON_UNESCAPED_SLASHES);
          }
        ;
        if ($new && !$request['agg']>0){
          //insert onnly if no kyc data are present
          $kyc['contract_id']=$request['appData']['contract_id'];
          $kyc['agent_id']=$request['pInfo']['agent_id'];
          $kyc['agency_id']=$request['pInfo']['agency_id'];
          $db->insertAry("kyc", $kyc);
          //$kyc=$aryData;
        }
        else {
          if ($request['agg']>0){
            $db->updateAry("kyc_log", $kyc,"WHERE id=".    $request['agg']);

          }else {
            $db->updateAry("kyc", $kyc,"WHERE contract_id=".  $request['appData']['contract_id']);

          }

        }

        $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> $kyc);
        echo json_encode($data);

        break;
      }
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Invalid Contract");
      echo json_encode($data);
      break;
    }
    case 'saveRiskAx':{
      if ($request['appData']['contract_id']!='' ){
        //  $request['dbData']['risk_data']=stripslashes($request['dbData']['risk_data']);
        $request['dbData']['agent_id']=$request['pInfo']['agent_id'];
        $request['dbData']['contract_id']=$request['appData']['contract_id'];
        $request['dbData']['CPU']=$request['appData']['CPU'];
        if ($request['final']){
          $request['dbData']['risk_status']=1;
          $request['dbData']['risk_date']=date('Y-m-d H:i:s');
          $request['dbData']['riskAssigned']=1;
          $risk=json_decode($request['dbData']['risk_data'],true);
          $request['dbData']['riskCalculated']=$risk['riskCalculated'];
          $request['dbData']['riskAssigned']=$risk['riskAssigned'];
        }
        if ($request['agg']>0){
          $flgIn=$db->updateAry("risk_log", $request['dbData'], "where id=". $request['agg']);

        }else {
          $flgIn=$db->updateAry("risk", $request['dbData'], "where risk_id=". $request['dbData']['risk_id']);

        }
        //error_log("flgin".$flgIn);
        if ($request['final'] && !$request['agg']>0){
          $dec=json_decode($request['dbData']['risk_data'],true);
          //error_log("dec".print_r($dec,1));
          $request['appData']['risk_defined']=$dec['riskAssigned'];
          $request['appData']['status']=1;
          $request['appData']['date_of_analisys']=date('Y-m-d H:i:s');
          error_log("data analisi".$request['appData']['date_of_analisys']);

            $flgIn=$db->updateAry("contract", $request['appData'], "where id=". $request['appData']['contract_id']);



        }


        $data = array('RESPONSECODE'=> 1 ,'RESPONSE'=> "Information updated successfully");
        echo json_encode($data,JSON_UNESCAPED_SLASHES);
        break;
      }
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
      echo json_encode($data);
      break;

    }
    case 'riskAx':{
      if ($request['appData']['contract_id']>0 ){
        if ($request['agg']>0){
          $SQL="SELECT * FROM risk_log WHERE id='".  $request['agg']."'" ;
          $risk=$db->getRow($SQL);

        }else {
          $SQL="SELECT * FROM risk WHERE contract_id='".  $request['appData']['contract_id']."'" ;
          $risk=$db->getRow($SQL);

        }
        if ($request['kyc']){
          $SQL="SELECT * FROM kyc WHERE contract_id=".  $request['appData']['contract_id'];
          $Kyc=$db->getRow($SQL);
        }
        if (count($risk)==0 && !$request['agg']>0){
          error_log("xxrisk" . print_r($risk,1));
          //insert onnly if no kyc data are present
          $risk['CPU']=$request['appData']['CPU'];
          $risk['agency_id']=$request['pInfo']['agency_id'];
          $risk['contract_id']=$request['appData']['contract_id'];
          $db->insertAry("risk", $risk);
          $SQL="SELECT * FROM risk WHERE contract_id='".  $request['appData']['contract_id']."'" ;
          $risk=$db->getRow($SQL);
          //$kyc=$aryData;
        }
        $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> $risk,'kyc'=>$Kyc);
        echo json_encode($data);
        break;
      }
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Invalid Contract");
      echo json_encode($data);
      break;
    }


    case 'Password' :
    {
      if($request['currentPassword']!='' )
      {
        $chkuser=$db->getRow("SELECT password FROM `users` WHERE user_id='".$request['id']."'");
        if(($chkuser['password'])!=$request['currentPassword'])
        {

          $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Current password you entered is incorrect");
        }
        else
        {
          $aryData=array('password'=>$request['newPassword']);
          $flgIn=$db->updateAry("users",$aryData,"where user_id='".$request['id']."'");
          if(!is_null($flgIn))
          {
            $data = array('RESPONSECODE'=> 1 ,'RESPONSE'=> "Password Changed Successfully");
          }
        }
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
      }
      echo json_encode($data);
      break;
    }
    ///////////////////////Login User //////////////////////////////////////
    case 'Forgot' :
    {
      if($request['email']!='')
      {
        $chkuser=$db->getRow("SELECT status,user_id,name,password,email,username FROM users WHERE email='".$request['email']."' AND (user_type ='2' OR user_type = '1' ) ");

        if($chkuser['user_id']=='')
        {
          $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Email ID does not exist");
        }
        elseif($chkuser['user_id']!='' && $chkuser['status']=='0')
        {
          $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Please activate your account");
        }
        elseif($chkuser['user_id']!='' && $chkuser['status']=='1')
        {
          $random_passw = rand(1000,1500000);
          $aryData =array(  'password'  => $random_passw );

          $flgIn=$db->updateAry("users",$aryData,"where user_id='".$chkuser['user_id']."'");
          $vars = array(

            'user_email' => $chkuser['email'],
            'passwords' => $random_passw
          );
          mail_template($chkuser['email'],'forgot_front',$vars);
          $data = array('RESPONSECODE'=>  1 ,'RESPONSE'=> "A new password has been sent to your e-mail address.");
          unset($_POST);

        }
        else
        {
          $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Email ID does not exist");
        }
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Please enter Email ID");
      }
      echo json_encode($data);
      break;
    }
    ///////////////SignUp//////////////////////////////////////////

    case 'CustomerList' :
    {
      $sql="SELECT concat(us.name,' ',us.surname) as fullname ,us.email,us.mobile,us.image,us.user_id
      FROM users us   ";
      $where="";
      if (strlen($request['last'])>0){
        $where = " and us.user_id <  " .$request['last'];

      }
      if($request['pInfo']['user_type'] =='2')
      {
        if($request['pInfo']['priviledge'] == 2 )
        {
          $sql.="  WHERE us.agent_id ='".$request['pInfo']['agent_id']."'  AND us.status <> 2 and us.user_type='3' ".$where." ORDER BY us.user_id DESC limit 5 ";

        }
        else if($request['pInfo']['priviledge'] == 1)
        {
          $sql.=" WHERE us.agency_id ='".$request['pInfo']['agency_id']."'  AND us.status <> 2 and us.user_type='3' ".$where." ORDER BY us.user_id DESC limit 5 ";

        }
      }
      else if($request['pInfo']['user_type'] =='1')
      {

        $sql.=" WHERE us.agency_id ='".$request['pInfo']['agency_id']."'  AND us.status <> 2 and us.user_type='3' ".$where." ORDER BY us.user_id DESC limit 5 ";
      }

      if ($request['pInfo']['user_type'] =='-1') {
        $sql.="where us.status <> 2 and us.user_type='3' ".$where." ORDER BY us.user_id DESC limit 5 ";
      }
      //////error_log($request['action']."1-".$request['id'] .$sql.  PHP_EOL);
      if($request['pInfo']['user_type'] =='3')
      {
        if (strlen($request['last'])>0){
          $where = " and us.user_id <  " .$request['last'];

        }
        $sql="SELECT  distinct concat(us.name,' ',us.surname) as fullname ,us.email,us.mobile,us.image,us.user_id
                      from users as us

                      where
                            us.user_id='".$request['pInfo']['user_id']."' and us.status <> 2 ".$where." ORDER BY user_id DESC
                                           ";

      }
      $getcustomerlist = $db->getRows($sql);
      if(count($getcustomerlist) > 0 && is_array($getcustomerlist) )
      {
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getcustomerlist);
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> '');

      }
      echo json_encode($data);
      break;
    }
    case 'ContractList' :
    {
      $limit=" limit 10";
      if (strlen($request['last'])>0){
        $last = " and co.id <  " .$request['last'];

      }
      if (strlen($request['search'])>0){
        $search="and (1=0  ";
        if ($request['searchThings']['fullname']){
          $search.=" or concat(us.name,' ',us.surname) like '%".$request['search']  ."%' or cmy.name like '%".$request['search']  ."%' or co.nometemp like '%".$request['search']  ."%'";
        }
        if ($request['searchThings']['CPU']){
          $search.=" or co.CPU like '%".$request['search']  ."%' ";
        }
        if ($request['searchThings']['email']){
          $search.=" or us.email like '%".$request['search']  ."%'";
        }
        if ($request['searchThings']['rischio']){
          $search.=" or r.riskAssigned like '%".$request['search']  ."%'";
        }
        if ($request['searchThings']['scaduti']){
          $search.=" or co.contract_eov < '".date('Y-m-d')  ."'";
        }
        if ($request['searchThings']['numcontratto']){
          $search.="or co.number like '%".$request['search']  ."%'";
        }
        if ($request['searchThings']['scopo']){
          $search.="or co.scope_contract like '%".$request['search']  ."%'";
        }
        if ($request['searchThings']['natura']){
          $search.="or co.nature_contract like '%".$request['search']  ."%'";
        }
        if ($request['searchThings']['agente']){
          $sql="select a.agent_id from agent a join users us on us.user_id=a.user_id  where concat(us.name,' ',us.surname) like '%".$request['search']  ."%' and a.agency_id=".$request['pInfo']['agency_id'] ;
          $agents = $db->getRows($sql);
          if (count($agents)>0){
                  foreach ($agents as $key => $value){
                    $search.=" or co.agent_id = '".$value['agent_id']  ."'";
                    error_log("agenti".$search);
                  }
          }
        }
        $search.=" ) ";
/*
        $search=" and (concat(us.name,' ',us.surname) like '%".$request['search']  ."%' or  concat(op.name,' ',op.surname) like '%".$request['search']  ."%'
                  or us.email like '%".$request['search']  ."%' or co.CPU like '%".$request['search']  ."%' or co.number like '%".$request['search']  ."%'
                  or cmy.name like '%".$request['search']  ."%' or co.nometemp like '%".$request['search']  ."%' ) ";
*/
      }
      else {
        $search="";
      }
      $sql= "SELECT co.agent_id,co.agency_id,co.id as contract_id, co.nometemp, co.nature_contract,co.scope_contract,co.number,co.CPU,co.contract_date,
      co.contract_value,co.contractor_id,co.role_for_other,co.activity_country,co.tipo_contratto,
      co.contract_eov,co.act_for_other,co.Docs,co.other_id,r.riskAssigned,co.status,co.value_det,
      concat(us.name,' ',us.surname) as fullname, concat(us.name,' ',us.surname) as contractor_name,us.surname,us.name as name1, us.email,us.mobile,us.image,cmy.company_id,
      cmy.name,op.image as owner_image,cmy.image as company_image,
      concat(op.name,' ',op.surname) as other_name,sh.user_id as shared,
      k.kyc_status, k.kyc_date,k.kyc_update, r.risk_status, r.risk_date,r.risk_update
      FROM contract co
      left JOIN users us ON co.contractor_id = us.user_id
      LEFT JOIN company cmy ON co.other_id =cmy.company_id and co.act_for_other=1
      LEFT JOIN users op ON co.other_id =op.user_id  and co.act_for_other=2
      LEFT JOIN  share sh ON co.id=sh.object_id AND sh.object='contract'
                and sh.user_id=co.contractor_id and sh.agency_id=co.agency_id
                left join kyc as k on k.contract_id=co.id
                left join risk as r on r.contract_id=co.id
      ";


      if($request['pInfo']['user_type'] =='2')
      {

        if($request['pInfo']['priviledge'] == 2 )
        {
          $sql.="  WHERE co.agent_id ='".$request['pInfo']['user_id']."' AND co.status <> 2 ".$where.$search.$last." ORDER BY co.id DESC " . $limit;

        }
        else if($request['pInfo']['priviledge'] == 1)
        {

          $sql.=" WHERE co.agency_id ='".$request['pInfo']['agency_id'] ."' AND co.status <> 2 ".$where.$search.$last." ORDER BY co.id DESC   ". $limit;


        }
      }
      else if($request['pInfo']['user_type'] =='1')
      {
        $sql.=" WHERE co.agency_id ='".$request['pInfo']['agency_id'] ."' AND co.status <> 2  ".$where.$search. $last." ORDER BY co.id DESC " . $limit;


      }
      if ($request['pInfo']['user_type'] =='-1'){
        $sql.=" WHERE  co.status <> 2  ".$where. $last.$search. " ORDER BY co.id DESC " . $limit;
      }

      if ($request['pInfo']['user_type'] =='3'){
        $sql= "SELECT  co.id as contract_id, co.nature_contract,co.scope_contract,co.number,co.CPU,co.contract_date,co.kyc_status,co.contract_value,co.status,co.risk_defined,
        co.act_for_other,co.value_det,co.role_for_other,co.activity_country,co.tipo_contratto,co.agency_id,
        us.user_id, concat(us.name,' ',us.surname) as contractor_name, concat(agyd.name,' ',agyd.surname) as agency_name,
        us.email,us.mobile,agyd.image ,us.user_id,
        cmy.name,
        concat(op.name,' ',op.surname) as other_name,
        op.image as owner_image,cmy.image as company_image, sh.user_id as share
        FROM contract co JOIN users us ON co.contractor_id = us.user_id
        join agency agy on co.agency_id=agy.agency_id
        join users as agyd on agy.user_id=agyd.user_id
        left JOIN risk rk ON rk.cpu = co.cpu
        LEFT JOIN company cmy ON co.other_id =cmy.company_id and co.act_for_other=1
        LEFT JOIN users op ON co.other_id =op.user_id  and co.act_for_other=2
        LEFT JOIN  share sh ON co.id=sh.object_id AND sh.object='contract'
        and sh.user_id=co.contractor_id and sh.agency_id=co.agency_id
        ";

        $sql.=" WHERE co.contractor_id ='".$request['pInfo']['user_id'] ."' AND co.status <> 2  ".$where.$search .$last." ORDER BY co.contract_date DESC " . $limit;
      }
      //error_log("usertype ". $request['usertype'] ."last".$last.PHP_EOL);

      $getcustomerlist = $db->getRows($sql);

      if(count($getcustomerlist) > 0 && is_array($getcustomerlist) )
      {
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getcustomerlist);
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> '');

      }
      echo json_encode($data);
      break;
    }
    case 'addShare':{
      $ary_data=$request['appData'];
      $ary_data['object']='contract';
      $ary_data['object_id']=$ary_data['contract_id'];
      $ary_data['user_id']=$request['pInfo']['user_id'];
      $flgIn=$db->insertAry('share',$ary_data);
      if ($flgIn>0){
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> "share  aggiunto");
        echo json_encode($data);
      }
      else {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "share non aggiunto");
        echo json_encode($data);

      }
      break;
    }
    case 'removeShare':{
      $ary_data=$request['appData'];
      $ary_data['object']='contract';
      $ary_data['object_id']=$ary_data['contract_id'];
      $ary_data['user_id']=$request['pInfo']['user_id'];
      $where="where object_id=".$ary_data['object_id']." and user_id=".$ary_data['user_id']." and object='contract' and agency_id=" . $ary_data['agency_id'];
      $flgIn=$db->delete('share',$where );
      if ($flgIn>0){
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> "share  cancellato");
        echo json_encode($data);
      }
      else {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "share non aggiunto");
        echo json_encode($data);

      }
      break;
    }

    case 'ListObjs' :
    {
      error_log("lista oggetti");
      $settings=$request['settings'];
      if (!(strlen($settings['table'])>0 && strlen($settings['id'])>0 )){
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "parametri oggetto errati");
        echo json_encode($data);
        break;

      }

      $SQL=getSQL($settings,$db);
      $getOb = $db->getRows($SQL);

      if(count($getOb) > 0 && is_array($getOb) )
      {
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getOb);
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $sql);
      }
      echo json_encode($data);
      break;

    }
    case 'getObj' :
    {
      error_log("lista oggetti");
      $settings=$request['settings'];
      if (!(strlen($settings['table'])>0 && strlen($settings['id'])>0 )){
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "parametri oggetto errati");
        echo json_encode($data);
        break;

      }

      $SQL=getSQL($settings,$db);
      $getOb = $db->getRow($SQL);

      if( is_array($getOb) )
      {
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getOb);
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $sql);
      }
      echo json_encode($data);
      break;

    }
    case 'saveOb':{
      error_log("salva  oggetti");
      $settings=$request['settings'];
      $aryData=$request['dbData'];
      if (!$request['pInfo']['agency_id']==-1)
      $aryData['agency_id']=$request['pInfo']['agency_id'];
      if (!(strlen($settings['table'])>0 && strlen($settings['id'])>0 )){
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "parametri oggetto errati");
        echo json_encode($data);
        break;
      }
      if ($settings['action']=='add'){

        foreach ($settings['other_table'] as $key => $value) {
          error_log("other". print_r($value,1).print_r($aryData,1)."id:".$aryData[$value['id']] );
          if ($aryData[$value['id']]>0){
            $where="where " . $value['id'] ."=".$aryData[$value['id']];
            $flgIn=$db->updateAry($value['table'],$aryData,$where);
            if ($flgIn==0)$flgIn=1;
          }else {
            $flgIn=$db->insertAry($value['table'],$aryData);
            $aryData[$value['id']]=$flgIn;
          }
          if (! $flgIn){
            $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "Spicenti, qualcosa è andato storto.");
            echo json_encode($data);
            return $data;
          }
        }
        $flgIn=$db->insertAry($settings['table'],$aryData);
        if ($flgIn)
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> "Riga inserita",'lastid'=>$flgIn);
          else
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "Spiacente qualcosa è andato storto.");
        echo json_encode($data);
        return $data;
      }
      error_log("salva  oggetti prima di update");
      foreach ($settings['other_table'] as $key => $value) {
        $db->updateAry($value['table'],$aryData, "where ".$value['id']."=". $request['dbData'][$value['id']]);
      }
      $db->updateAry( $settings['table'],$aryData, "where ".$settings['id']."=". $request['dbData'][$settings['id']]);
      $data = array('RESPONSECODE'=> 1 ,'RESPONSE'=> "Information updated successfully");
      echo json_encode($data);

      break;

    }
    case 'ACCustomerList' :
    { $getcustomerlist=array();

      if ($request['kyc']==1){
        $sql="SELECT id as user_id,  fullname, fiscal_id as email from  kyc_person   WHERE
         (fiscal_id  like '%".$request['name']. "%' or fullname  like '%".$request['name']."%' ) and agency_id='".$request['pInfo']['agency_id']."'  ORDER BY fullname ASC limit 5 ";
        $getcustomerlist = $db->getRows($sql);

      } else if (strlen($request['name'])>0){
        $sql="SELECT us.user_id, concat(us.name,' ',us.surname) fullname, us.email from  users as us  WHERE
         (us.email  like '%".$request['name']. "%' or concat(us.name,' ',us.surname)  like '%".$request['name']."%' )
         and agency_id='".$request['pInfo']['agency_id']."'
         and user_type='3'  ORDER BY concat(us.name,us.surname) ASC limit 5 ";
        $getcustomerlist = $db->getRows($sql);

      }
      if(count($getcustomerlist) > 0 && is_array($getcustomerlist) )
      {
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getcustomerlist);
      }
      else
      {
        $data = array('RESPONSECODE'=> $sql , 'RESPONSE'=> '');

      }
      echo json_encode($data);
      break;

    }
    case 'ACCompanyList' :
    { $getcustomerlist=array();

      if (strlen($request['last'])>0){
        $where = " and co.company_id <  " .$request['last'];

      }
      if (strlen($request['name'])>0){
        $sql="SELECT co.company_id  ,co.name, co.fiscal_id from  company as co
        WHERE (co.name  like '%".$request['name']. "%' or co.fiscal_id  like '%".$request['name']."%' ) ".$where."
          and agency_id='".$request['pInfo']['agency_id']."'
         ORDER BY co.company_id ASC limit 5 ";
        $getcustomerlist = $db->getRows($sql);

      }
      if(count($getcustomerlist) > 0 && is_array($getcustomerlist) )
      {
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getcustomerlist);
      }
      else
      {
        $data = array('RESPONSECODE'=> $sql , 'RESPONSE'=> '');

      }
      echo json_encode($data);
      break;

    }
    case 'ACWord' :
    {
      //////error_log($request['usertype']);

      $order="";
      if ($request['order']==1)
      $order= " tag_key asc, ";
      if ($request['order']==-1)
      $order= " tag_key desc, ";
      if (!is_null($request['settings'])){
        $request['zero']=$request['settings']['zero'];
        $request['countries']=$request['settings']['countries'];
        $request['order']=$request['settings']['order'];
        $request['ob']=$request['settings']['ob'];
      }
      if (strlen($request['search'])>0 || $request['word']=="countries" || $request['zero'] || $request['countries']){
        if ($request['word']!="countries" || !  $request['zero'] || ! $request['countries'] ){
          if ( is_array($request['ob'])){
            $sql=getSQL($request['ob']['settings'],$db);
          }else
          {

          $sql="SELECT distinct " . $request['word']."  as word from  ". $request['table'] ."
          WHERE " . $request['word']."   like '%".addslashes($request['search']). "%'  AND agency_id=".$request['pInfo']['agency_id']."
          AND ".$request['word']."<> ''
           ORDER BY ". $request['word']. "  ASC limit 5 ";
          //error_log("sql1:". $sql.PHP_EOL);
          }
          $getword = $db->getRows($sql);
//          error_log("concat1:". print_r($getword,1).PHP_EOL);
        }
        if ( ($request['word']=="countries"  || $request['zero'] || $request['countries'])){
          if ($request['countries'])
          $word="countries";
          else
          $word=$request['word'];
          if (strlen($request['search'])>0 )
          $sql="SELECT distinct  word from  word_tag    WHERE word  like '%".addslashes($request['search']). "%'  AND  '". $word. "'=id_tag order by ".$order. "  field(word, 'Italia','San Marino') DESC, word ASC ";
          else
          $sql="SELECT distinct  word from  word_tag    WHERE  '". $word. "'=id_tag order by ".$order. "  field(word, 'Italia','San Marino') DESC, word ASC ";

        }
        else{
          $sql="SELECT distinct  word from  word_tag    WHERE word  like '%".addslashes($request['search']). "%'  AND  '". $request['word']. "'=id_tag order by ".$order. " word ASC limit 5 ";
        }
        if (!is_array($request['ob']) ){
        $getword1 = $db->getRows($sql);
        }
        $sql="SELECT distinct  word from  word_tag_kyc    WHERE word  like '%".addslashes($request['search']). "%'  AND  '". $request['word']. "'=id_tag
              and agency_id=".$request['pInfo']['agency_id'] ." order by ".$order. " word ASC limit 5 ";
        $getword2 = $db->getRows($sql);

//        error_log("concat2:". print_r($getword1,1).PHP_EOL);
        if (is_array($getword) && count($getword)>0 && is_array($getword1) && count($getword1)>0 ){
          $x=array_merge($getword,$getword1);
          error_log("xxxxcat1:".print_r($x,1));
          $getword=array_unique($x,SORT_REGULAR);
          if ( is_array($getword2) && count($getword2)>0){
            $x=array_merge($getword,$getword2);
            error_log("xxxxcat1:".print_r($x,1));
            $getword=array_unique($x,SORT_REGULAR);

          }
          //error_log("xxxxcat2:".print_r($getword,1));

        }
        elseif (is_array($getword1) && count($getword1)>0 ){
          error_log("xxxncat2:");
          $getword=$getword1;
          if ( is_array($getword2) && count($getword2)>0){
            $x=array_merge($getword,$getword2);
            error_log("xxxxcat1:".print_r($x,1));
            $getword=array_unique($x,SORT_REGULAR);

          }

        }elseif (is_array($getword2) && count($getword2)>0){
          $getword=$getword2;

        }

        error_log("concat3:". print_r($getword,1).print_r($getword2,1).PHP_EOL);
      }
      if(is_array($getword) && count($getword)>0)
      {
        $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getword);
      }
      else
      {
        $data = array('RESPONSECODE'=> $sql , 'RESPONSE'=> '');

      }
      echo json_encode($data);
      break;

    }
    case 'addcustomer' :
    {
      //////error_log($request['action']."prima insert -".$request['id'] .print_r($request,1). print_r($request['dbData'],1).  PHP_EOL);
      $error='';
/*
      if(!strlen($request['dbData']['email'])>0){
        $error="Si prega di immettere una mail di riferimento";
      }

      if(strlen($request['dbData']['email'])>0)
      {
        $sql="select email  from users where email='".trim($request['dbData']['email'])."'";
        $aryCheckEmail=$db->getVal($sql);
        //////error_log($request['action']."dopo controllo email  -".$request['id'] .$sql.  PHP_EOL);
      }

      if($aryCheckEmail!='')
      {
        $error=" Email Id  already exists";
      }
*/
      $random_passw = rand(1000,1500000);
      if($error=='')
      {

        $aryData=$request['dbData'];
        $aryData['agency_id']=$request['pInfo']['agency_id'];
        $aryData['agent_id']=$request['pInfo']['agent_id'];
        $aryData['user_type']=3;
        if ($request['agent']){
          $aryData['user_type']=2;
        }
        $aryData['status']=1;
        $aryData['password']=$random_passw;

        $flgIn=$db->insertAry("users",$aryData);
        if(!is_null($flgIn))
        {
          $lastid=$db->getVal("SELECT LAST_INSERT_ID()");
          if ($request['agent']){
            $aryData2=array();
            $aryData2['user_id']=$lastid;
            $aryData2['agency_id']=$request['pInfo']['agency_id'];
            $aryData2['agent_previledge']=$request['dbData']['agent_previledge'];
            $flgIn=$db->insertAry("agent",$aryData2);
          }


          //////error_log($request['action']."dopo insert -".print_r($request['dbData'],1). print_r($agency,1). print_r($aryData,1) .$sql.  PHP_EOL);
          $vars = array(
            'email' => $request['dbData']['email'],
            'password' => $random_passw,
            'agency_name' => $agency['agency_name']
          );
          if (strlen($request['dbData']['email'])>0)
            mail_template($request['dbData']['email'],'add_customer',$vars, $request['lang']);
          //////error_log($request['action']."dopo-".$request['id'] .$sql.  PHP_EOL);
          $data = array('ID'=>$flgIn,'RESPONSECODE'=>1 ,'RESPONSE'=> "Customer Added successfully","lastid"=>$lastid);
        }
        else
        {
          $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "Error");
        }
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
      }
      echo json_encode($data);
      break;

    }
    case 'mail_template':
    {
      $vars = $request['vars'];
      error_log("data in mail tamplate".print_r($data,1));
      $vars['id']=$data['lastid'];
      if (! isset($request['lang']) || strlen($request['lang'])!=2)
        $request['lang']='it';
      mail_template($request['email'],$request['template'],$vars, $request['lang']);
      break;
    }
    case 'addcontract' :
    {

      //////error_log("addContract1 ". print_r($request,1).PHP_EOL .$request['appData']['usertype'] .PHP_EOL);
      if ($request['appData']['usertype'] >=3 &&  $request['appData']['usertype'] <1){
        $error="Non hai i privilegi per salvare un contratto";
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=>$error);
        echo json_encode($data);
        break;
      }
      $error='';
      //  $request['dbData']['CPU']=$request['appData']['CPU'];
      $aryData=$request['dbData'];


      $aryData['agency_id']=$request['pinfo']['agency_id'];
      if (!$aryData['agent_id']>0)
      $aryData['agent_id']=$request['pinfo']['agent_id'];
      //////error_log("EDIT::". $request['edit'] .PHP_EOL);
      //file_put_contents('/var/www/html/tmp/php-error.log',"...". $request['edit'] );
      if ($request['edit']=="edit"){
        $flgIn=$db->updateAry("contract",$aryData, "where id='".$request['dbData']['contract_id']."'");
        $ok="Contratto Aggiornato Correttamente";
        if(!is_null($flgIn))  {
          // inserisco riga valutazione rischio.
          //$db->insertAry("risk", $arydata2);
          $kyc=$db->getRow("select contract_data from kyc where contract_id='" . $request['dbData']['contract_id'] ."'");

          //error_log("contractor data".$kyc['contract_data'].PHP_EOL);
          $con=json_decode($kyc['contract_data'],true);
          //error_log("contractor data".print_r($con,1).PHP_EOL);
          $con=array_merge($con,$aryData);
          foreach ($aryData as $key => $value) {
            $con[$key]=$value;
          }
          //error_log("nuovo contractor data".print_r($con,1).PHP_EOL);
          $con=array('contract_data'=>json_encode($con,JSON_UNESCAPED_SLASHES));
          //error_log("nuovo contractor data".print_r($con,1).PHP_EOL);

          $flgIn=$db->updateAry("kyc",$con, "where contract_id='".$request['dbData']['contract_id']."'");


          if ($aryData['act_for_other']==2){
            $owners=$db->getRows("select * from company_owners where contract_id='" . $request['dbData']['contract_id']."' and user_id=".$request['dbData']['other_id'] );
            if (count($owners)>0){
              $xx=1;
            } else {
              $aryData3['agency_id']=  $aryData['agency_id'];
              $aryData3['agent_id']=  $aryData['agent_id'];
              $aryData3['user_id']=  $aryData['other_id'];
              $aryData3['contract_id']=$request['dbData']['contract_id'];
              $aryData3['percentuale']=$aryData['role_for_other'];
              $db->insertAry("company_owners",$aryData3);

            }

          }
          //error_log("aggKyc".$request['aggKyc'].PHP_EOL );
          if ($request['aggKyc']==1){
            $kyc=array();
            $contractor = $db->getRow("SELECT * FROM users where user_id=".$aryData['contractor_id']);
            $contr=json_decode($kyc['contractor_data'],true);
            $kyc['contractor_data']=json_encode($contractor,JSON_UNESCAPED_SLASHES);
            if ($aryData['act_for_other']==1){
              $company = $db->getRow("SELECT * FROM company where company_id=".$aryData['other_id']);
              ////error_log("xx".print_r($company,1));
              $kyc['company_data']=json_encode($company,JSON_UNESCAPED_SLASHES);
              //inserisco i dati della società
              $owners = $db->getRows("SELECT percentuale ,us.* FROM  company_owners as co join users as us on us.user_id=co.user_id where company_id=".$aryData['other_id']);
              $kyc['owner_data']=json_encode($owners,JSON_UNESCAPED_SLASHES);
              ////error_log("owner_null".print_r($owners,1));
            }
            if ($aryData['act_for_other']==2){
              //inserisco i dati della società
              $owners = $db->getRows("SELECT percentuale, us.* FROM  company_owners as co join users as us on us.user_id=co.user_id where  contract_id=".$aryData['contract_id']);
              $kyc['owner_data']=json_encode($owners,JSON_UNESCAPED_SLASHES);
              ////error_log("owner_null".print_r($owners,1));
            }
            //error_log("Kyc da agg".print_r($kyc,1));
            //$flgIn=$db->updateAry("kyc",$kyc, "where contract_id='".$request['dbData']['contract_id']."'");
          }

          $data = array('ID'=>$flgIn,'RESPONSECODE'=>1 ,'RESPONSE'=> $ok);
          echo json_encode($data);
          break;
        }


      }
      else {
        //GEstione CPU per insert
        $sql="SELECT  CPU  FROM  `contract`  where  agency_id = ". $agency_id ." order by id desc limit 1";
        $CPU =$db->getVal($sql);
        $anno_corr=date('Y');
        if (is_null($CPU)){
          $CPU="0/".date("Y");
        };
        list($num, $anno) = split("/", $CPU,2);
        if (!strlen($anno)>0){
          $anno=$anno_corr;
        }
        $num=intval($num);
        $num++;
        $CPU=$num ."/" .$anno;
        $aryData['CPU']=$CPU;

        $flgIn=$db->insertAry("contract",$aryData);
        $contract=$flgIn;
        $ok="Contratto Inserito Correttamente";
        if(!is_null($flgIn))  {
          // inserisco riga valutazione rischio.
          $lastid=$db->getVal("SELECT LAST_INSERT_ID()");
          $aryData['contractor_id']=$lastid;
          $flgIn=$db->insertAry("customer",$aryData);

          $arydata2 = array(
            'CPU'          => $aryData['CPU'],
            'agent_id'     => $aryData['agent_id'],
            'agency_id'    => $agency_id,


          );
          if ($aryData['act_for_other']==2){
            $aryData3['agency_id']=  $aryData['agency_id'];
            $aryData3['agent_id']=  $aryData['agent_id'];
            $aryData3['user_id']=  $aryData['other_id'];
            $aryData3['contract_id']=$lastid;
            $aryData3['percentuale']=$aryData['role_for_other'];
            $db->insertAry("company_owners",$aryData3);

          }
          foreach ($request['Docs'] as $doc){
            $ary=array("per_id"=>$lastid);
            $db->updateAry($ary,"documents","where id=".$docs['id']);
          }
          $db->insertAry("risk", $arydata2);
          $data = array('ID'=>$flgIn,'RESPONSECODE'=>1 ,'RESPONSE'=> $ok, "lastid"=>$lastid);
          echo json_encode($data);
          break;
          //mail_template($request['customer_email'],'add_customer',$vars);

        }
      }
      //$flgIn=$db->insertAry("contract",$aryData);

      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "Error");
      echo json_encode($data);
      break;

    }
    case 'addagent' :
    {
      $error='';
      if($request['agent_email']!='')
      {
        $aryCheckEmail=$db->getVal("select email  from users where email='".trim($request['agent_email'])."'");
      }

      if($aryCheckEmail!='')
      {
        $error=" Email Id  already exists";
      }

      $random_passw = rand(1000,1500000);
      if($error=='')
      {

        $aryData=array(	            'name'			=>	trim($request['agent_name']),
        'email'			=>	trim($request['agent_email']),
        'password'		         =>	$random_passw,
        'mobile'		=>	trim($request['agent_mobile']),
        'user_type'                 =>   2,
        'status'		=>  1
      );
      $flgIn=$db->insertAry("users",$aryData);
      if(!is_null($flgIn))
      {

        $agent_last_id = $flgIn;
        $arydata1 = array(
          'user_id'                       => $flgIn,
          'agency_id'                     => $request['id'],


        );
        $flgIn=$db->insertAry("agent",$arydata1);



        $vars = array(

          'user_email' => $request['agent_email'],
          'user_password' => $random_passw
        );
        mail_template($request['agent_email'],'add_agent',$vars);


        $data = array('ID'=>$agent_last_id,'RESPONSECODE'=>1 ,'RESPONSE'=> "Agent Added successfully");
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "Error");
      }
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
    }
    echo json_encode($data);
    break;

  }
  case 'OwnersList' :
  {
    if (strlen($request['last'])>0){
      $last = " and co.id <  " .$request['last'];

    }
    if ($request['appData']['act_for_other']==2)
    $other= " co.contract_id ='".$request['appData']['contract_id']." and co.agency_id=".$request['pInfo']['agency_id'];
    else
    $other= " co.company_id ='".$request['company_id'] ." and co.agency_id=".$request['pInfo']['agency_id'];

    //   $getcustomerlist = $db->getRows("SELECT us.name,us.email,us.mobile,us.image,us.user_id FROM users us JOIN customer cs ON cs.user_id = us.user_id JOIN risk rk rk.user_id =us.user_id  WHERE cs.agency_id ='".$request['id']."' AND us.status = '1' AND us.normal_or_company_owner ='1' ORDER BY us.user_id DESC ");
    // $getcustomerlist = $db->getRows("SELECT us.name,us.email,us.mobile,us.image,us.user_id,rk.status,cs.kyc_status FROM users us JOIN customer cs ON cs.user_id = us.user_id  JOIN risk rk ON rk.user_id = us.user_id JOIN  WHERE cs.agency_id ='".$request['id']."' AND us.normal_or_company_owner ='1' ORDER BY us.user_id DESC ");
    $sql="SELECT co.id,concat(us.name,' ',us.surname) as fullname, us.email,us.mobile,us.image,us.user_id,
    co.company_id,co.agency_id, co.percentuale, co.contract_id
    FROM company_owners co
    JOIN users us  ON  co.user_id = us.user_id
    WHERE " .$other ."' ".$last ."  AND us.status <> 2 ORDER BY co.id DESC  limit 5 ";
    //////error_log($request['action']."1-".$request['id'] .$sql.  PHP_EOL);
    $getcustomerlist = $db->getRows($sql);


    if(count($getcustomerlist) > 0 && is_array($getcustomerlist) )
    {
      $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getcustomerlist);
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> '');

    }
    echo json_encode($data);
    break;
  }

  case 'AgentList' :
  {
    $where="";
    if (strlen($request['last'])>0){
      $where = " and us.user_id <  " .$request['last'];

    }

    $sql="SELECT us.* , concat(us.name, ' ' , us.surname) fullname, cs.agent_previledge as priviledge
    FROM users us JOIN agent cs ON cs.user_id = us.user_id
    WHERE cs.agency_id ='".$request['pInfo']['agency_id']."' AND us.status = '1' ".$where." ORDER BY us.user_id DESC ";
    if ($request['usertype'] =='-1'){
      $sql="SELECT us.* , concat(us.name, ' ' , us.surname) fullname, cs.agent_previledge as priviledge
      FROM users us JOIN agent cs ON cs.user_id = us.user_id
      WHERE  us.status = '1' ".$where." ORDER BY us.user_id DESC ";
    }

    $getcustomerlist = $db->getRows($sql);



    if(count($getcustomerlist) > 0 && is_array($getcustomerlist) )
    {
      $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getcustomerlist);
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> '');

    }
    echo json_encode($data);
    break;
  }
  case 'view_Agent_Profile_info' :
  {
    if($request['agent_id']!='' && $request['agent_id']!='')
    {
      $userDetails=$db->getRow("SELECT users.*, concat(users.name,' ', users.surname) as fullname,ag.agent_previledge FROM  users JOIN agent ag ON ag.user_id = users.user_id WHERE users.user_id='".trim($request['agent_id'])."' ");

      if($userDetails['user_id']!='')
      {

        $userInfo= array('surname'=>$userDetails['name'],'fullname'=>$userDetails['fullname'],'name'=> $userDetails['name'],'email'=>$userDetails['email'],'mobile'=>$userDetails['mobile'],'imagename'  =>$userDetails['image'],'agent_previledge'  => $userDetails['agent_previledge'] );
        $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> $userInfo);
      }
      else{$data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Invalid User");}
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
    }
    echo json_encode($data);
    break;
  }
  case 'view_Customer_Profile_info' :
  {
    if($request['customer_id']!='' )
    {      // print_r($request['customer_id']);
      if ($request['kyc']==1){
        $sql="SELECT kyc_data from  kyc_person   WHERE id='".trim($request['customer_id'])."' ";
        $kyc = $db->getRow($sql);
        $userDetails=json_decode($kyc['kyc_data'],true);
        error_log(print_r($kyc,1).print_r($userDetails,1));

      } else {
        $sql="SELECT us.* ,concat(us.name, ' ',us.surname) as fullname, a.agent_previledge,us.settings FROM  users us left join agent as a on us.user_id=a.user_id and a.agency_id=".$request['pInfo']['agency_id'] ."
        WHERE us.user_id='".trim($request['customer_id'])."' ";
        //////error_log($request['action']."1-".$request['customer_id'] .$sql.  PHP_EOL);
        $userDetails=$db->getRow($sql);

      }

      if(count($userDetails)>0)
      {

        $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> $userDetails);
      }
      else{$data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Invalid User");}
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
    }
    echo json_encode($data);
    break;
  }
  case 'view_Contract_info' :
  {
    //////error_log($request['action']."1-".$request['contract_id'] . PHP_EOL .$request['appData']['usertype'] .PHP_EOL);

    if($request['contract_id']!='' )
    {      // print_r($request['customer_id']);
      if (strlen($request['search'])>0){
        $search=" and (fullname like '%".$request['search']  ."%' or contractor_name like '%".$request['search']  ."%' or email like '%".$request['search']  ."%' or CPU like '%".$request['search']  ."%' or number='".$request['search']  ."%' ) ";
      }
      else {
        $search="";
      }

      $sql= "SELECT co.agent_id,co.nometemp, co.agency_id,co.id as contract_id, co.nature_contract,co.scope_contract,co.number,co.CPU,co.contract_date,
      co.contract_value,co.contractor_id,co.role_for_other,co.activity_country,co.tipo_contratto,co.end_det,
      co.contract_eov,co.act_for_other,co.Docs,co.other_id,r.riskAssigned,co.status,co.value_det,co.procura,
      concat(us.name,' ',us.surname) as fullname,concat(us.name,' ',us.surname) as contractor_name, us.surname,us.name as name1, us.email,us.mobile,us.image,cmy.company_id,
      cmy.name,concat(op.name,' ',op.surname) as other_name, sh.user_id as shared, k.kyc_status, k.kyc_date,k.kyc_update, r.risk_status, r.risk_date,r.risk_update

      FROM contract co
      left JOIN users us ON co.contractor_id = us.user_id
      left JOIN risk rk ON rk.cpu = co.cpu
      LEFT JOIN company cmy ON co.other_id =cmy.company_id and co.act_for_other=1
      LEFT JOIN users op ON co.other_id =op.user_id  and co.act_for_other=2
      LEFT JOIN  share sh ON co.id=sh.object_id AND sh.object='contract'
                and sh.user_id=co.contractor_id and sh.agency_id=co.agency_id
      left join kyc as k on k.contract_id=co.id
      left join risk as r on r.contract_id=co.id

      WHERE co.id ='".$request['contract_id']."' " . $search;
      //////error_log($request['action']."xx". $sql .PHP_EOL .$request['appData']['usertype'] .PHP_EOL);

      $ContractDetails=$db->getRow($sql);
      //////error_log($request['action']."x2-". print_r($ContractDetails,1) ."-".$ContractDetails['id'] .PHP_EOL .$request['appData']['usertype'] .PHP_EOL);

      if($ContractDetails['contract_id']!='')
      {

        $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> $ContractDetails);
      }
      else{$data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Invalid User");}
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
    }
    echo json_encode($data);
    break;
  }

  case 'close_account' :
  {
    $aryData =array(  'status'  => 0 );
    $flgIn=$db->updateAry("users",$aryData,"where user_id='".$request['agent_id']."'");
    if($flgIn !='')
    {
      $data = array('RESPONSECODE'=> 1 ,'RESPONSE'=> "You Successfully Close the account");
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
    }
    echo json_encode($data);
    break;
  }





  case 'view_Owners_Profile_info' :
  {
    if($request['customer_id']!='' )
    {
      $userDetails=$db->getRow("SELECT us.*,cy.name,cs.customer_dob  FROM  users us JOIN company_owners co ON co.user_id = us.user_id JOIN company cy ON co.company_id = cy.company_id JOIN customer cs ON cs.user_id = us.user_id  WHERE us.user_id='".trim($request['customer_id'])."' ");
      //echo $db->getLastQuery(); exit;
      //  $userDetails=$db->getRow("SELECT us.*,cs.customer_dob FROM  users us JOIN customer cs ON cs.user_id = us.user_id WHERE us.user_id='".trim($request['customer_id'])."' ");

      if($userDetails['user_id']!='')
      {

        $userInfo= array('name'=> $userDetails['name'],'email'=>$userDetails['email'],'mobile'=>$userDetails['mobile'],'imagename'  =>$userDetails['image'],'company_name' =>  $userDetails['company_name'],'dob' => $userDetails['customer_dob']  );
        $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> $userInfo);
      }
      else{$data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Invalid User");}
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 ,'RESPONSE'=> "Error");
    }
    echo json_encode($data);
    break;
    break;
  }
  case 'CompanyList' :
  {
    if (strlen($request['last'])>0){
      $where = " and company_id <  " .$request['last'];

    }
    $sql="SELECT  * FROM company ";
    if($request['pInfo']['user_type'] =='2')
    {
      if($request['pInfo']['priviledge'] == 2 )
      {
        $sql.="  WHERE  agent_id ='".$request['id']."' AND status <> 2 ".$where." ORDER BY company_id DESC ";

      }
      else if($request['pInfo']['priviledge'] == 1)
      {
        $agencyvalue = $db->getVal("SELECT agency_id FROM agent WHERE user_id = '".$request['id']."'  ");
        $sql.=" WHERE agency_id ='".$agencyvalue."' AND status <> 2 ".$where." ORDER BY company_id DESC";

      }
    }
    else if($request['pInfo']['user_type'] =='1')
    {

      $agencyvalue = $db->getVal("SELECT agency_id FROM agency WHERE user_id = '".$request['id']."'  ");
      $sql.=" WHERE agency_id ='".$agencyvalue."' AND status <> 2 ".$where." ORDER BY company_id DESC";
    }
    if($request['pInfo']['user_type'] =='-1')
    {
      $sql.=" WHERE  status <> 2 ".$where." ORDER BY company_id DESC";
    }

    if($request['pInfo']['user_type'] =='3')
    {
      $sql="SELECT  * FROM company as co join contract as cn on cn.other_id=co.company_id and cn.act_for_other=1 ";
      $sql.=" WHERE cn.contractor_id ='".$request['id']."' AND co.status <> 2 ".$where." ORDER BY company_id DESC";
    }
    //////error_log($request['action']."1-".$request['id'] .$sql.  PHP_EOL);

    // $getcompanylist = $db->getRows("SELECT  * FROM company WHERE agency_id ='".$request['id']."' And status <> 2 ORDER BY  company_id DESC ");
    $getcompanylist = $db->getRows($sql);

    if(count($getcompanylist) > 0 && is_array($getcompanylist) )
    {
      $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getcompanylist);
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> '');

    }
    echo json_encode($data);
    break;
  }
  case 'add_company' :
  {
    if($request['company_name']!='')
    {
      $aryCheckCompName=$db->getVal("select name  from company where fiscal_id='".trim($request['dbData']['fiscal_id'])."'");
    }
    if($aryCheckCompName !='')
    {
      $error=" Partita Iva già esistente";
    }
    $currdate = strtotime(date("y-m-d"));

    /*$company_authorisation_date  = strtotime($request['company_authorisation_date']);

    if($company_authorisation_date >= $currdate )
    {
    $error=" Invalid  Authorization  date";
  }
  */
  //////error_log($request['action']."1-".$request['id'] .$sql.  PHP_EOL);

  if($error=='')
  {
    $aryData=$request['dbData'];
    $aryData['status']=1;

    $aryData['agency_id']=$request['pInfo']['agency_id'];
    $aryData['agent_id']=$request['pInfo'];

    $flgIn=$db->insertAry("company",$aryData);
    if (!is_null($flgIn)){
      $lastid=$db->getVal("SELECT LAST_INSERT_ID()");
      $data = array('ID'=>$flgIn,'RESPONSECODE'=>1 ,'RESPONSE'=> "Società inserita correttamente", 'lastid'=> $lastid);
      echo json_encode($data);
      break;
    }

  }
  $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
  echo json_encode($data);
  break;
  }
  case 'upload_compnay_doc_image' :
  {
    $newfile=md5(microtime()).".jpg";
    $user_id = $request['custid'];
    if(move_uploaded_file($_FILES['file']['tmp_name'],"uploads/company/".$newfile))
    {
      copy("uploads/company/" . $newfile, "uploads/company/resize/" . $newfile);
      smart_resize_image("uploads/company/" . "resize/$newfile", 300, 0,true);
      $aryData = array("imagename" =>	$newfile,"cust_id"  => $user_id);
      $flgIn = $db->insertAry("tmp_image",$aryData);


    }





    $data = array('id' => $flgIn, 'response' => $newfile,'file_name' => $_FILES["file"]["tmp_name"] );
    echo json_encode($data);
    break;

  }
  case 'get_compnay_doc_image_name' :
  {
    $imagename=$db->getVal("select imagename from tmp_image where id='".$request['id']."' ");
    $data = array(
      'RESPONSECODE'	=>  1,
      'RESPONSE'	=> $imagename,
    );
    echo json_encode($data);
    break;
  }
  case 'upload_company_lisence_image' :
  {
    $newfile=md5(microtime()).".jpg";
    $user_id = $request['custid'];
    if(move_uploaded_file($_FILES['file']['tmp_name'],"uploads/company/".$newfile))
    {
      copy("uploads/company/" . $newfile, "uploads/company/resize/" . $newfile);
      smart_resize_image("uploads/company/" . "resize/$newfile", 300, 0,true);
      $aryData = array("imagename" =>	$newfile,"cust_id"  => $user_id);
      $flgIn = $db->insertAry("tmp_image",$aryData);


    }





    $data = array('id' => $flgIn, 'response' => $newfile,'file_name' => $_FILES["file"]["tmp_name"] );
    echo json_encode($data);
    break;

  }
  case 'get_company_lisence_image_name' :
  {
    $imagename=$db->getVal("select imagename from tmp_image where id='".$request['id']."' ");
    $data = array(
      'RESPONSECODE'	=>  1,
      'RESPONSE'	=> $imagename,
    );
    echo json_encode($data);
    break;
  }
  case 'edit_company' :
  {
    if($request['company_name']!='')
    {
      $aryCheckCompName=$db->getVal("select name  from company where fiscal_id='".trim($request['dbData']['fiscal_id'])."' and company_id !='".$request['dbData']['company_id']."' ");
    }
    if($aryCheckCompName !='')
    {
      $error=" Partita Iva già Esistente";
    }
    /*$currdate = strtotime(date("y-m-d"));
    $company_authorisation_date  = strtotime($request['company_authorisation_date']);

    if($company_authorisation_date >= $currdate )
    {
    $error=" Invalid Authorization  date";
  }
  */
  if($error=='')
  {
    $aryData=$request['dbData'];
    $flgIn=$db->updateAry("company",$aryData,"where company_id='".$request['dbData']['company_id']."'");
    if (!is_null($flgIn)){
      $data = array('ID'=>$flgIn,'RESPONSECODE'=>1 ,'RESPONSE'=> "Società aggiornata correttamente");
      echo json_encode($data);
      break;

    }

  }
  $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
  echo json_encode($data);
  break;
  }
  case 'show_edit_company' :
  {
    $userDetails=$db->getRow("SELECT * FROM   company   WHERE company_id='".trim($request['company_id'])."'");

    $data = array('RESPONSECODE'=> 1 ,'RESPONSE'=> $userDetails,  );
    echo json_encode($data);
    break;
  }
  case 'add_owners' :
  {
    //error_log("appdata".print_r($request['appData'],1).PHP_EOL);

    if($request['appData']['act_for_other'] >'0'){
      $aryData=$request['dbData'];
      $aryData['agency_id']=$request['pInfo']['agency_id'];
      $aryData['agent_id']=$request['pInfo']['agent_id'];
      if ($request['appData']['act_for_other']=='1')
        $aryData['company_id']=$request['appData']['company_id'];
      else
        $aryData['contract_id']=$request['appData']['contract_id'];

      $flgIn=$db->insertAry("company_owners",$aryData);
      $lastid=$flgIn;
      //error_log("ultimo inserito".$lastid.PHP_EOL);
      if(!is_null($flgIn))
      {
        if ($request['appData']['act_for_other']=='1')
        $sql="SELECT percentuale, us.* FROM   company_owners as co join users as us on company_id='".$aryData['company_id']."'  and co.user_id=us.user_id  where us.user_id='". $aryData['user_id']."'";
        else
        $sql="SELECT percentuale, us.* FROM   company_owners as co join users as us on contract_id = '".$aryData['contract_id']."' and co.user_id=us.user_id  where us.user_id='". $aryData['user_id']."'";

        $owner=$db->getRow($sql);
        $data = array('ID'=>$flgIn,'RESPONSECODE'=>1 ,'RESPONSE'=> "Titolare Effettivo inserito correttamente","owner"=>$owner, "lastid"=>$lastid);
        //error_log("owners".print_r($owner,1).PHP_EOL);
        echo json_encode($data);
        break;
      }
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
      echo json_encode($data);
      break;



    }
    //////error_log('add owners: ' . print_r($request,1) .print_r($request['dbData'],1) . PHP_EOL);
    if($request['dbData']['user_id']!='')
    {
      $sql="select user_id  from company_owners where user_id='".trim($request['dbData']['user_id'])."'";
      //////error_log('add owenrs: ' . $sql . PHP_EOL);
      $aryCheckEmail=$db->getVal();
    }

    if($aryCheckEmail!='')
    {
      $error="Cliente già presente fra i titolari";
    }

    if($error=='')
    {
      $aryData=$request['dbData'];
      $aryData['agency_id']=$request['pInfo']['agency_id'];
      $aryData['agent_id']=$request['pInfo']['agency_id'];

      $flgIn=$db->insertAry("company_owners",$aryData);
      if(!is_null($flgIn))
      {
        $data = array('ID'=>$flgIn,'RESPONSECODE'=>1 ,'RESPONSE'=> "Titolare Effettivo inserito correttamente");
        echo json_encode($data);
        break;
      }
    }
    $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
    echo json_encode($data);
    break;

  }
  case 'edit_owners' :
  {
    if($request['appData']['usertype'] =='1')
    {
      $agencyvalue = $db->getVal("SELECT agency_id FROM agency WHERE user_id = '".$request['appData']['id']."'  ");

    }else{
      $agencyvalue = $db->getVal("SELECT agency_id FROM agent WHERE user_id = '".$request['appData']['id']."'  ");

    }
    $error='';
    //////error_log('add owners: ' . print_r($request,1) .print_r($request['dbData'],1) . PHP_EOL);
    if($request['dbData']['user_id']!='')
    {
      $sql="select user_id  from company_owners where user_id='".trim($request['dbData']['user_id'])."'";
      //////error_log('add owenrs: ' . $sql . PHP_EOL);
      $aryCheckEmail=$db->getVal();
    }

    if($aryCheckEmail!='')
    {
      $error="Cliente già presente fra i titolari";
    }

    if($error=='')
    {
      $aryData=$request['dbData'];
      $aryData['agency_id']=$agencyvalue;
      $aryData['agent_id']=$request['appData']['id'];

      $flgIn=$db->updateAry("company_owners",$aryData, "where id=" . $request['dbData']['id']  );
      if(!is_null($flgIn))
      {
        $data = array('ID'=>$flgIn,'RESPONSECODE'=>1 ,'RESPONSE'=> "Titolare Effettivo inserito correttamente");
        echo json_encode($data);
        break;
      }
    }
    $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
    echo json_encode($data);
    break;

  }


  case 'saveProfileCustomer':
  {
    $error='';

    if($error=='')
    {

      $aryData=$request['dbData'];

      $flgIn=$db->updateAry("users",$aryData,"where user_id='".$request['dbData']['user_id']."'");
      if ($request['agent']){
        $aryData2['agent_previledge']=$request['dbData']['agent_previledge'];
        $flgIn=$db->updateAry("agent",$aryData2,"where user_id='".$request['dbData']['user_id']."'" );
      }

      //                $flgIIn2=$db->updateAry("customer",$aryData2,"where contractor_id='".$request['dbData']['id']."'");
      if(!is_null($flgIn))
      {
        $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> "Cliente Aggiornato con Successo");
      }
      else
      {
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "Error");
      }
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
    }
    echo json_encode($data);
    break;


  }
  case 'saveProfileAgent':
  {
    $error='';


    if($error=='')
    {

      $aryData=array(	'name'			=>	trim($request['name']),
      'mobile'     =>	trim($request['mobile']),


    );
    $flgIn=$db->updateAry("users",$aryData,"where user_id='".$request['id']."'");

    $aryData2 = array( 'agent_previledge'      =>      trim($request['agent_previledge']) );
    $flgIn1=$db->updateAry("agent",$aryData2,"where user_id='".$request['id']."'");
    if(!is_null($flgIn))
    {


      $data = array('RESPONSECODE'=>1 ,'RESPONSE'=> "Details Saved Successfully");
    }
    else
    {
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> "Error");
    }
  }
  else
  {
    $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> $error);
  }
  echo json_encode($data);
  break;


  }
  case 'documentList' :
  {
    $getimaglist=array();
    if (strlen($request['last'])>0){
      $where = " and id <  " .$request['last'];

    }


    $getimaglist = $db->getRows("SELECT  * FROM documents WHERE per_id ='".$request['dbData']['per_id']."' AND per ='".$request['dbData']['per']."' " .$requestE['where'].$where." ORDER BY  id DESC limit 5 ");

    if(count($getimaglist) > 0 && is_array($getimaglist) )
    {
      $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getimaglist);
    }
    else if (count($getimaglist) == 0 ){
      $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $getimaglist);

    } else
    {
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> '');

    }
    echo json_encode($data);
    break;
  }
  case 'deletecustimage' :
  {
    $imagename=$db->getVal("select doc_image from customer_documents where id='".$request['id']."' ");
    @unlink("uploads/document/user_" . $request['cust_id'] . '/' . $imagename);
    @unlink("uploads/document/user_" . $request['cust_id'] . "/resize/" . $imagename);
    $res = $db->delete('customer_documents', "where id='" . $request['id'] . "'");
    $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> $res );
    echo json_encode($data);
    break;
  }

  case 'upload_document_ax' :
  {
    error_log("file:" .print_r($files,1));
// decido se da upload web o da file
    if (strlen($files['file']['tmp_name'])>0){
      error_log('è arivato un _FILES');
      $file_content =file_get_contents($files['file']['tmp_name']);
      $ext=".".pathinfo($request['filename'], PATHINFO_EXTENSION);
      $newfile=$request['filename'];
      error_log('passatoxx'."ext".$ext."filename:".$newfile);

    }
    else{
      error_log('è arrivato in una variabile');
      $file_content = $request['f']['data'];
      $file_content = substr($file_content, strlen('data:' . mime_content_type_ax($request['f']['name']).';base64,'));
      $file_content = base64_decode($file_content);
      $ext=".".pathinfo($request['filename'], PATHINFO_EXTENSION);
      $newfile=$request['filename'];
      error_log('passatoxx'."ext".$ext."filename:".$newfile);

    }
    // gestisco firma
    $entity=$request['entity'];
    $entity_key=$request['entity_key'];
    if (strlen($request['entity'])==0) {
      $entity='users';
      $entity_key='user_id';
    }
    if ($request['firma']==1){
      error_log('sto facendo firma'.PATH_UPLOADS ."document" .DS. $entity ."_" . $entity_key .DS. 'firma'. DS);
      if (!file_exists(PATH_UPLOADS ."document" .DS. $entity ."_" . $entity_key .DS. 'firma')) {
        mkdir(PATH_UPLOADS ."document" .DS. $entity ."_" . $entity_key .DS. 'firma', 0755, true);
      }
      if(file_put_contents ( PATH_UPLOADS ."document" .DS. $entity ."_" . $entity_key .DS. 'firma'. DS . $newfile,  $file_content ))
      {
        error_log("errore copia file" .$esit);

      }
      $oldImage=$db->getRow("select imagename from tmp_image where tipo='firma' and per='".$entity."' and per_id=".$entity_key  );
      if (count($oldImage)>0){
        $flgDel=$db->delete("tmp_image", "where imagename='".$oldImage['imagename']."' ");
        @unlink( PATH_UPLOADS ."document" .DS. $entity ."_" . $entity_key .DS. 'firma'. DS .$oldImage['imagename']);

      }
      $aryData = array("imagename" =>	$newfile,"tipo"=>"firma" , "per"  =>$entity,"per_id"=>$entity_key, "file_type"=>$ext,"loaded"=>1);
      $flgIn = $db->insertAry("tmp_image",$aryData);
            $data = array("RESPONSECODE"=>"1",'image' => $newfile);
      //$data = array('review_id' => $flgIn, 'response' => $newfile,'file_name' => $_FILES["file"]["tmp_name"] );
      echo json_encode($data);
      break;
      return;

    }
    //gestisco profilo
    if ($request['type']=="profile"){


      if(file_put_contents ( PATH_UPLOADS .$entity.DS. $newfile,  $file_content ))
      {
        if (!file_exists(PATH_UPLOADS.DS.$entity.DS.'small')) {
          mkdir(PATH_UPLOADS.DS.$entity.DS.'small', 0755, true);
        }
        $esit=copy(PATH_UPLOADS. $entity.DS.$newfile, PATH_UPLOADS.DS.$entity.DS.'small'.DS.$newfile);
        error_log("DOPOCOPIA" .$esit.PATH_UPLOADS. $entity.DS.$newfile);
        if(!$esit) {
          error_log("errore copia file" .$esit);
        }

        smart_resize_image(PATH_UPLOADS.$entity.DS.'small'.DS.$newfile, 300, 0,true);
        if (!file_exists(PATH_UPLOADS.DS.$entity.DS.'medium')) {
          mkdir(PATH_UPLOADS.DS.$entity.DS.'medium', 0755, true);
        }

        $esit=copy(PATH_UPLOADS. $entity.DS.$newfile, PATH_UPLOADS.DS.$entity.DS.'medium'.DS.$newfile);
        smart_resize_image(PATH_UPLOADS. $entity.DS.$newfile, PATH_UPLOADS.DS.$entity.DS.'medium'.DS.$newfile, 1000, 0,true);

      }
      $oldImage=$db->getRow("select imagename from tmp_image where tipo='profilo' and per='".$entity."' and per_id=".$entity_key  );
      if (count($oldImage)>0){
        $flgDel=$db->delete("tmp_image", "where imagename='".$oldImage['imagename']."' ");
        @unlink(PATH_UPLOAD. $entity.DS.$oldImage['imagename']);
        @unlink(PATH_UPLOAD. $entity.DS.'small'.DS. $oldImage['imagename']);
        @unlink(PATH_UPLOAD. $entity.DS.'medium'.DS. $oldImage['imagename']);

      }
      $aryData = array("imagename" =>	$newfile,"tipo" =>'profilo', "per"  =>$entity,"per_id"=>$entity_key, "file_type"=>$ext,"loaded"=>1);
      $flgIn = $db->insertAry("tmp_image",$aryData);
      $data = array("RESPONSECODE"=>"1",'image' => $newfile);
      //$data = array('review_id' => $flgIn, 'response' => $newfile,'file_name' => $_FILES["file"]["tmp_name"] );
      echo json_encode($data);
      break;
      return;
    }


    $for=$request['for'] ;
    $user_id = $request['userid'];
    $image_ext=array('.jpeg','.jpg',".png",".gif",".tif",".bmp",".tiff");
    //  //error_log("file caricato" .$_FILES['file']['tmp_name'] . "<br>tipo:".mime_content_type($_FILES['file']['tmp_name']). "<br>valore:". $file_content .PHP_EOL);
    //////error_log("controllo" .PATH_UPLOAD . "document" . DS . $for ."_".  $user_id .PHP_EOL);
    if (!file_exists(PATH_UPLOAD . "document" . DS . $for ."_" . $user_id  )) {
      //////error_log("MKDIR:". PATH_UPLOAD . "document" . DS . $for ."_". $user_id .PHP_EOL);

      mkdir(PATH_UPLOAD . "document" . DS . $for ."_" . $user_id , 0755, true);
    }
    error_log("estensione".$ext);
    if (in_array($ext,$image_ext))
    if (!file_exists(PATH_UPLOAD . 'document' . DS . $for .'_' . $user_id . DS. 'resize')) {
      mkdir(PATH_UPLOAD . 'document' . DS . $for .'_' . $user_id  .DS. 'resize', 0755, true);
    }
    if (!file_exists(PATH_UPLOAD . 'document' . DS . $for .'_' . $user_id . DS. 'medium')) {
      mkdir(PATH_UPLOAD . 'document' . DS . $for .'_' . $user_id  .DS. 'medium', 0755, true);
    }
    //////error_log("MOVE:". $_FILES['file']['tmp_name']. "uploads/document/".$for ."_". $user_id . DS . $newfile .PHP_EOL);

    if (file_put_contents ( PATH_UPLOAD ."document" .DS. $for."_" . $user_id  .DS. $newfile,  $file_content)) {
      //////error_log("COPY:". "uploads/document/".$for ."_" . $user_id . DS . $newfile ."---" . "uploads/document/".$for ."_". $user_id . DS . "resize/" . $newfile.PHP_EOL);
      if ( in_array($ext,$image_ext)){
        error_log("crea immagine piccola"."uploads/document/".$for ."_" . $user_id . DS . $newfile . PHP_EOL);
        copy(PATH_UPLOAD. "document" .DS. $for ."_" . $user_id . DS . $newfile, PATH_UPLOAD. "document".DS. $for ."_" . $user_id . DS . "resize".DS . $newfile);
        smart_resize_image(PATH_UPLOAD."document".DS.$for."_" . $user_id  . DS . "resize".DS. $newfile, 300, 0,true);
        copy(PATH_UPLOAD. "document" .DS. $for ."_" . $user_id . DS . $newfile, PATH_UPLOAD. "document".DS. $for ."_" . $user_id . DS . "medium".DS . $newfile);
        smart_resize_image(PATH_UPLOAD."document".DS.$for."_" . $user_id  . DS . "medium".DS. $newfile, 1000, 0,true);

      }
    }
    //$indice=$db->getVal("select count(imagename) from tmp_image where per='".$for."' and per_id=".$user_id  );

    $indice=$request['indice'];

    $oldImage=$db->getRow("select imagename from tmp_image where per='".$for."' and per_id=".$user_id ." and indice=" . $indice  );
    if (count($oldImage)>0){
      $flgDel=$db->delete("tmp_image", "where imagename='".$oldImage['imagename']."' ");
      @unlink(PATH_UPLOAD."document".DS.$for."_" .$user_id.DS.$oldImage['imagename']);
      @unlink(PATH_UPLOAD. "document".DS.$for."_" .$user_id.DS."resize".DS.$oldImage['imagename']);
      @unlink(PATH_UPLOAD. "document".DS.$for."_" .$user_id.DS."medium".DS.$oldImage['imagename']);

    }
    $aryData = array("imagename" =>	$newfile,"per"  =>$for,"per_id"=>$user_id, "file_type"=>$ext,"loaded"=>1,"indice"=>$indice);
    $flgIn = $db->insertAry("tmp_image",$aryData);
    $data = array('RESPONSECODE'=>1,'id' => $flgIn, 'response' => $newfile,"file_type" => $ext);
    echo json_encode($data);
    break;

  }
  case 'get_document_image_name_multi' :
  {
    $imagename=$db->getVal("select imagename from tmp_image where id='".$request['id']."' ");
    $data = array(
      'RESPONSECODE'	=>  1,
      'RESPONSE'	=> $imagename,
    );
    if ($request['DocId']>0){
      $aryData=array("image_name"=>$imagename);
      $db->updateAry('documents',$aryData,"where id=".$request['DocId']);

    }
    echo json_encode($data);
    break;
  }
  case 'savedocument' :
  {
    $aryData = $request['dbData'];
    if ($request['type']=='add')
    $flgIn = $db->insertAry("documents",$aryData);
    if ($request['type']=='edit'){
      $doc=$db->getRow("select * from documents where id =" .$aryData['id']);
      //error_log('dati' .print_r($doc,1).print_r($aryData,1).PHP_EOL);
      if ($doc['doc_image']!= $aryData['doc_image']){
        $flgIn=$db->delete('tmp_image',"where imagename='".$doc['doc_image']."'");
        //error_log("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
        @unlink("uploads/document/".$doc['per']."_" .$doc['per_id']."/".$doc['doc_image']);
        @unlink("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);

      }

      $flgIn = $db->updateAry("documents",$aryData,"where id=".$aryData['id']);

    }

    $data = array(
      'RESPONSECODE'	=>  1,
      'RESPONSE'	=> $imagename,
      'lastid'=> $flgIn
    );
    echo json_encode($data);
    break;
  }
  case 'fetchuserimage' :
  {
    $imagename=$db->getRow("select image,name from users where user_id='".$request['id']."' ");
    $data = array(
      'RESPONSECODE'	=>  1,
      'RESPONSE'	=> $imagename['image'],
      'NAME'      => $imagename['name']
    );
    echo json_encode($data);
    break;
  }
  case 'addAggKyc' :
  {
    $kyc=$db->getRow("select * from kyc where contract_id='".$request['contract_id']."' ");
    if (count($kyc)>0){
      $kyc['kyc_id']=$kyc['id'];
      $kyc['id']='';


      $db->insertAry("kyc_log", $kyc);
      $kyc=array();
      $kyc['kyc_update']='{
        "state" : "aggiornamento"
      }';
      $kyc['kyc_status']=0;
      $kyc['kyc_date']=NULL;

      $db->updateAry('kyc',$kyc,"where contract_id=".$request['contract_id']);
      $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> 'Aggiornamento Effettuato');
      echo json_encode($data);

    }else{
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> 'Errore');
      echo json_encode($data);

    }
    break;
  }
  case 'addAggRisk' :
  {
    $kyc=$db->getRow("select * from risk where contract_id='".$request['contract_id']."' ");
    if (count($kyc)>0){
      $kyc['kyc_id']=$kyc['id'];
      $kyc['id']='';


      $db->insertAry("risk_log", $kyc);
      $kyc=array();
      $kyc['risk_update']='{
        "state" : "aggiornamento"
      }';
      $kyc['risk_status']=0;
      $kyc['risk_date']=NULL;

      $db->updateAry('risk',$kyc,"where contract_id=".$request['contract_id']);
      $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> 'Aggiornamento Effettuato');
      echo json_encode($data);

    }else{
      $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> 'Errore');
      echo json_encode($data);

    }
    break;
  }
  case 'copyContract':
  {
      if (!$request['id']>0){
        $data = array('RESPONSECODE'=> 0 , 'RESPONSE'=> 'Errore manca ID');
        echo json_encode($data);
        break;
        return;

      }
      $sql="select * from contract where id=".$request['id'];
      $contract=$db->getRow($sql);
//determino prossimo CPU
      $sql="SELECT  CPU  FROM  `contract`  where  agency_id = ". $request['pInfo']['agency_id'] ." order by id desc limit 1";
      $CPU =$db->getVal($sql);
      $anno_corr=date('Y');
      if (is_null($CPU)){
        $CPU="0/".date("Y");
      };
      list($num, $anno) = split("/", $CPU,2);
      if (!strlen($anno)>0){
        $anno=$anno_corr;
      }
      $num=intval($num);
      $num++;
      $CPU=$num ."/" .$anno;
      $contract['CPU']=$CPU;

      unset($contract['id']);
      $sql="select * from kyc  contract where contract_id=".$request['id'];
      $kyc=$db->getRow($sql);
      $kyc['CPU']=$CPU;
      $kyc['kyc_status']=0;
      unset($kyc['id']);
      unset($kyc['kyc_date']);
// copio i due record
      $flgIn=$db->insertAry('contract',$contract);
      $kyc['contract_id']=$flgIn;
      $flgIn=$db->insertAry('kyc',$kyc);

      $data = array('RESPONSECODE'=> 1 , 'RESPONSE'=> 'Duplicazione effettuata');
      echo json_encode($data);
      break;


  }

  case 'delete':
  {
    foreach ($request['other_table'] as $key => $value) {
      $flgIn=$db->delete($value['table'],"where ".$value['id']. "='".$value['value']."'");
    }
    $where = " where " . $request['primary'] ."='". $request['id'] ."'";
    error_log("other_table" .print_r($request['other_table'],1).PHP_EOL);
    //error_log("_$request di table" .$request['table'].PHP_EOL);
    if ($request['table']=='documents'){
      $doc=$db->getRow("select * from documents where id =" .$request['id']);
      $flgIn=$db->delete('tmp_image',"where imagename='".$doc['doc_image']."'");
      //error_log("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);

      @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/".$doc['doc_image']);
      @unlink(PATH_UPLOADS."uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
    }
    if ($request['table']=='contract'){
      $flgIn=$db->delete('kyc',"where contract_id='".$request['id'] ."' and  agency_id=".$request['pInfo']['agency_id']);
      $flgIn=$db->delete('kyc_log',"where contract_id='".$request['id'] ."' and  agency_id=".$request['pInfo']['agency_id']);
      $flgIn=$db->delete('risk',"where contract_id='".$request['id'] ."' and  agency_id=".$request['pInfo']['agency_id']);
      $flgIn=$db->delete('risk_log',"where contract_id='".$request['id'] ."' and  agency_id=".$request['pInfo']['agency_id']);
      $docs=$db->getRow("select * from documents where per='kyc' and  per_id =" .$request['id']);
      foreach ($docs as $doc){
        $doc=$db->getRow("select * from documents where id =" .$request['id']);
        $flgIn=$db->delete('tmp_image',"where imagename='".$doc['doc_image']."'");
        //error_log("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);

        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/".$doc['doc_image']);
        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/medium/".$doc['doc_image']);

      }

    }
    if ($request['table']=='users'){
      $profile=$db->getVal("select image from users where user_id =" .$request['id']);
      $flgIn=$db->delete('tmp_image',"where imagename='".$profile."'");
      //error_log("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
      @unlink(PATH_UPLOADS."users".DS.$profile);
      @unlink(PATH_UPLOADS."users".DS."small".$profile);
      $doc=$db->getRow("select * from documents where per='customer' and  per_id =" .$request['id']);
      foreach ($docs as $doc){
        $doc=$db->getRow("select * from documents where id =" .$request['id']);
        $flgIn=$db->delete('tmp_image',"where imagename='".$doc['doc_image']."'");
        //error_log("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);

        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/".$doc['doc_image']);
        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/medium/".$doc['doc_image']);

      }

    }

    if ($request['table']=='company'){
      $profile=$db->getVal("select image from company where user_id =" .$request['id']);
      $flgIn=$db->delete('tmp_image',"where imagename='".$profile."'");
      //error_log("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
      @unlink(PATH_UPLOADS."users".DS.$profile);
      @unlink(PATH_UPLOADS."users".DS."small".$profile);

      $docs=$db->getRow("select * from documents where per='company' and  per_id =" .$request['id']);
      foreach ($docs as $doc){
        $doc=$db->getRow("select * from documents where id =" .$request['id']);
        $flgIn=$db->delete('tmp_image',"where imagename='".$doc['doc_image']."'");
        //error_log("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/".$doc['doc_image']);
        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
        @unlink(PATH_UPLOADS."document/".$doc['per']."_" .$doc['per_id']."/medium/".$doc['doc_image']);

      }

    }
    error_log($request['table']);
    if ($request['table']=='tmp_image'){
      $image=$db->getRow("select * from tmp_image ". $where);
      error_log('image'.print_r($image,1).PATH_UPLOADS."document/".$image['per']."_" .$image['per_id']."/".$image['imagename']);
        @unlink(PATH_UPLOADS."document/".$image['per']."_" .$image['per_id']."/".$image['imagename']);
        @unlink(PATH_UPLOADS."document/".$image['per']."_" .$image['per_id']."/resize/".$image['imagename']);
        @unlink(PATH_UPLOADS."document/".$image['per']."_" .$image['per_id']."/medium/".$image['imagename']);


    }
    if ($request['agent']){
      $flgIn=$db->delete('agent',"where user_id='".$request['id'] ."' and  agency_id=".$request['pInfo']['agency_id']);
      //////error_log("uploads/document/".$doc['per']."_" .$doc['per_id']."/resize/".$doc['doc_image']);
    }
    $flgIn=$db->delete($request['table'],$where);
    if ($flgIn>0)
    $data=array(  'RESPONSECODE'	=>  1,   'RESPONSE'	=> "cancellato");
    else
    $data=array(  'RESPONSECODE'	=>  0,   'RESPONSE'	=> "errore");
    echo json_encode($data);
    break;
  }
  case 'print_kyc':
  {
    include('pdfgeneration/kyc.php');
    break;
  }

}
return $data;
}
ob_start();
$data=doAction($_REQUEST,$_FILES,$db,$data);
error_log("data dopo first doAction :".print_r($data,1));
if ($data['RESPONSECODE']==1 && is_array($_REQUEST['other_actions']) ){
    $res=$data;
    foreach($_REQUEST['other_actions'] as $key => $val) {
      error_log(print_r($val,1));
      $val['pInfo']=$_REQUEST['pInfo'];
      $res=doAction($val,'',$db,$res,$_REQUEST['action']);
      if ($res['RESPONSECODE']!=1){
        $data=array(  'RESPONSECODE'	=>  0,   'RESPONSE'	=> "Errore nella Azione precedente");
        ob_end_clean();
        echo json_encode($data);
        die();
      }
    }
    ob_end_clean();
    $data=array(  'RESPONSECODE'	=>  1,   'RESPONSE'	=> "Tutte le azioni sono state completate correttamente");
    echo json_encode($data);

}
ob_end_clean();
echo json_encode($data);

?>
