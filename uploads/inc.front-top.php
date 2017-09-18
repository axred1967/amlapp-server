<?php  
// included on 1st-July-2011
include_once("config.php"); 
$aryMetaTemp=fetchSetting(array('general_meta_title','general_meta_desc','general_meta_tags','general_site_status','general_site_offline'));
//print_r($aryMetaTemp);
if(isset($aryMetaTemp['general_site_status']) && strtolower($aryMetaTemp['general_site_status'])=="yes") die($aryMetaTemp['general_site_offline']);
$aryMeta=array(
'title'=>$aryMetaTemp['general_meta_title'],
'keywords'=>$aryMetaTemp['general_meta_tags'],
'description'=>$aryMetaTemp['general_meta_desc']
);
?>