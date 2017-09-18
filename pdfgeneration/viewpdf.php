
<!-- Customer pdf-->
<?php
require_once("../config.php");
$user_company = 0;
?>

<table cellspacing="3" cellpadding="2">
    <tr>
        <th width="35%"></th><th width="30%"><h3>Amlapp  - Kyc Form</h3></th>
    </tr>
</table>  
<?php
/* $user_id = $_GET['id'];
  $sqlLimit = $db->getRows("SELECT u.*,ag.*,cd.* FROM users u
  JOIN customer ag ON u.user_id = ag.user_id
  JOIN customer_documents cd ON u.user_id = cd.user_id
  WHERE u.user_type = 3 AND  u.normal_or_company_owner = 0 AND u.user_id ='".(int)$user_id."'");

  //$result = $db->getRows($sqlLimit);

  echo '<pre>';
  print_r($sqlLimit);
  echo '</pre>'; */
?>
<?php
/*
$countryList = $db->getRows("SELECT * FROM countries ORDER BY country_name ASC");
$agencyList = $db->getRows("SELECT us.name,us.user_id FROM users us JOIN agency ag ON us.user_id = ag.user_id ");
$user_id = 0;
$company_id = 0;
if (isset($_GET['id'])) {
    if(isset($_GET['cid'])){
        $user_company = 1;
        $company_id = (int)$_GET['cid'];
        $user_id = (int)$_GET['id'];
        $user = $db->getRow("SELECT u.*,ag.* FROM users u JOIN customer ag ON u.user_id = ag.user_id  WHERE u.user_id='" . $user_id. "' AND ag.company_id='" . $company_id. "'  AND u.normal_or_company_owner = 1 ");

        /* $user_id = $_GET['id'];
          $user= $db->getRow("SELECT u.*,cu.* FROM users u
          JOIN customer cu ON u.user_id = cu.user_id
          WHERE u.user_type = 3 AND  u.normal_or_company_owner = 0 AND u.user_id ='".(int)$user_id."'");
         */
/*
		 } else {
        $user_id = $_GET['id'];
        $user = $db->getRow("SELECT u.*,cu.* FROM users u  
                    JOIN customer cu ON u.user_id = cu.user_id 
                    WHERE u.user_type = 3 AND  u.normal_or_company_owner = 0 AND u.user_id ='" . (int) $user_id . "'");
    }
} else {
    exit();
}
*/
?>


<hr>
<table cellspacing="3" cellpadding="4" width="100%" >
    <tr>
        <th width="80%" style="padding-top:20px;" colspan="2"></th>
        <th width="70%" style="padding-top:20px;" colspan="2" ><span style="opacity:3;">Date : <?php echo date("d-m-Y");?></span></th>
</tr>
</table>
<table cellspacing="3" cellpadding="4" width="100%" >
    <tr>
        <th width="30%" style="padding-top:20px;" colspan="2"></th>
        <th width="70%" style="padding-top:20px;" colspan="2" ></th>
    </tr>
    <?php 
    if($user_company==1){
        $company_data = $db->getRow("SELECT u.company_name,ag.user_role_with_company FROM company u JOIN company_owners ag ON u.company_id = ag.company_id  WHERE ag.user_id='" . $user_id. "' AND ag.company_id='" .$company_id. "'");
        
        ?>
        
    <tr>
        <th width="30%" style="padding-top:20px;" colspan="2">Company Name</th>
        <th width="70%" style="padding-top:20px;" colspan="2" ><?php 
        
        if ($company_data['company_name'] != '') {
                                echo ucfirst($company_data['company_name']);
                            }
                            
                            
        ?></th>
    </tr>
    <tr>
        <th width="30%" style="padding-top:20px;" colspan="2">Your role in company</th>
        <th width="70%" style="padding-top:20px;" colspan="2" ><?php 
        
        if ($company_data['company_name'] != '') {
                                echo ucfirst($company_data['user_role_with_company']);
                            }
                            
                            
        ?></th>
    </tr>
    
    
    
    
        
   <?php } ?>
    
    
    <tr>
        <th width="30%" style="padding-top:20px;" colspan="2">Name</th>
        <th width="70%" style="padding-top:20px;" colspan="2" ><?php echo ucfirst($user['name']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Surname</th>
        <th width="70%" colspan="2"  ><?php echo ucfirst($user['surname']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Username</th>
        <th width="70%" colspan="2" ><?php echo $user['username']; ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Email</th>
        <th width="70%" colspan="2" ><?php echo $user['email']; ?></th>
    </tr>

    <tr>
        <th width="30%" colspan="2">Image </th>
        <th width="70%" colspan="2"><?php
if ($user['image'] != '') {
    ?>
            <img src="<?php echo URL_ROOT; ?>/uploads/user/<?php echo $user['image']; ?>" height="100" width="auto" />
    <?php
}
?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Mobile Number</th>
        <th width="70%" colspan="2" ><?php echo $user['mobile_number']; ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Residence Country</th>
        <th width="70%" colspan="2" ><?php
            foreach ($countryList as $countryVal) {
                if ($countryVal['country_id'] == $user['customer_domecile_country']) {
                    echo ucfirst($countryVal['country_name']);
                }
            }
?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Address of residence</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_address_resi']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Domicile Country</th>
        <th width="70%" colspan="2" ><?php
            foreach ($countryList as $countryVal) {
                if ($countryVal['country_id'] == $user['customer_domecile_country']) {
                    echo ucfirst($countryVal['country_name']);
                }
            }
?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Domicile Address</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_domecile_address_residence']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Nationality</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_main_nationality']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Fiscal Number</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_fiscal_number']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Date Of Birth</th>
        <th width="70%" colspan="2" ><?php 
        
             if($user['customer_dob'] != "0000-00-00"){
                 
                 $date_of_birth = date("d-m-Y", strtotime($user['customer_dob'])); 
   
        if (($date_of_birth != "01-01-1970") && ($date_of_birth != "31-12-1969")&& ($date_of_birth != "31-12-2069") ) {
                                                    echo $date_of_birth;
        }
             }
        
        
        ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Country where birth</th>
        <th width="70%" colspan="2" ><?php
            foreach ($countryList as $countryVal) {
                if ($countryVal['country_id'] == $user['customer_birth_country']) {
                    echo ucfirst($countryVal['country_name']);
                }
            }
?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Documnet of Identity</th>
        <th width="70%" colspan="2" ><?php
            if (($user['customer_id_type']) != '') {
                if ($user['customer_id_type'] == 0) {
                    echo 'Passport';
                }
                if ($user['customer_id_type'] == 1) {
                    echo 'Identity Card';
                }
                if ($user['customer_id_type'] == 2) {
                    echo 'Guide License';
                }
            }
            ?> </th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Identity Image </th>
        <th width="70%" colspan="2"><?php if ($user['customer_id_image'] != '') { ?>
                <img src="<?php echo URL_ROOT; ?>/uploads/document/user_<?php
                echo $user['user_id'];
                echo '/';
                echo $user['customer_id_image'];
                ?>"  height="100" style="width:auto;"/><?php } ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Authority name that relesed Idnetity Document</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_id_authority_name']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Release Date of Identity</th>
        <th width="70%" colspan="2" ><?php
        
        
        if($user['customer_id_release_date'] != "0000-00-00"){
            $date = date("d-m-Y", strtotime($user['customer_id_release_date'])); 
        
        if(($date != "01-01-1970") && ($date != "31-12-1969") && ($date != "31-12-2069")){
            echo $date;
        }
            
        }
        
        
        
        
        ?></th>
    </tr>

    <tr>
        <th width="30%" colspan="2">Validity Date</th>
        <th width="70%" colspan="2" ><?php
        
         if($user['customer_id_validity'] != "0000-00-00"){
             $date =  date("d-m-Y", strtotime($user['customer_id_validity'])); 
        
        if(($date != "01-01-1970") && ($date != "31-121969") && ($date != "31-12-2069")){
            echo $date;
        }             
         }
        
        
        
        ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Profession</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_profession']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Telephone</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_tel']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Fax</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_fax']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Annual Income</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['customer_annual_income']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Declaration</th>
        <th width="70%" colspan="2" ><?php
            if ($aryForm['customer_check_pep'] == 1) {
                echo "Pep";
            } else {
                echo "Not Pep";
            }
            ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Economic value of service</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['economic_value_of_service']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Nature of service</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['nature_of_service']); ?></th>
    </tr>
    <tr>
        <th width="30%" colspan="2">Scope of service</th>
        <th width="70%" colspan="2" ><?php echo ucfirst($user['scope_of_service']); ?></th>
    </tr>

</table>
<hr>

