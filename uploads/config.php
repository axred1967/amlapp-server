<?php
ini_set('session.gc_maxlifetime', '7200');
error_reporting(0);
@session_start();
$varAdminFolder="cp_new";
$varCoachFolder="coach_new";
$varClientFolder="client_new";

//echo "HTTP_HOST : ".$_SERVER['HTTP_HOST'];

define("DS",DIRECTORY_SEPARATOR);
define("PATH_ROOT",dirname(__FILE__));
define("PATH_LIB",PATH_ROOT.DS."library".DS);
define("PATH_CLASS",PATH_ROOT.DS."classes".DS);
define("PATH_ADMIN",PATH_ROOT.DS.$varAdminFolder.DS);
define("PATH_ADMIN_INC",PATH_ROOT.DS."admin_includes".DS);

define("PATH_COACH",PATH_ROOT.DS.$varCoachFolder.DS);
define("PATH_CLIENT",PATH_ROOT.DS.$varClientFolder.DS);
define("PATH_UPLOAD",PATH_ROOT.DS."uploads".DS);
define("PATH_UPLOAD_EDU",PATH_UPLOAD."edu_content".DS);
define("PATH_USERS_IMG",PATH_UPLOAD."userImg".DS);
define("PATH_CLIENT_IMG",PATH_UPLOAD."client_images".DS);
define("PATH_EVENTS_IMG",PATH_UPLOAD."event_images".DS);

/*define("PATH_IMAGES",PATH_ROOT.DS.'images'.DS);
define("PATH_VIDEO",PATH_ROOT.DS.'videos'.DS);
define("PATH_SWF",PATH_ROOT.DS.'swf'.DS);*/

/*define("PATH_UPLOAD",PATH_ROOT.DS."uploads".DS);
define("PATH_UPLOAD_CMS",PATH_UPLOAD."admin".DS);*/

if($_SERVER['HTTP_HOST'] == "192.168.0.5" or $_SERVER['HTTP_HOST'] == "111.93.90.230:82")
{
	define("URL_ROOT","http://".$_SERVER['HTTP_HOST']."/deve17/tsp/"); 
	define("DB_HOST","localhost");
	define("DB_USERNAME","root");
	define("DB_PASSWORD","Smo1!9(3#");
	define("DB_NAME","tsp");
}
elseif($_SERVER['HTTP_HOST'] == "www.omsoftware.us" || $_SERVER['HTTP_HOST'] == "omsoftware.us")
{
	define("URL_ROOT","http://".$_SERVER['HTTP_HOST']."/theplayers/tsp/"); 
	define("DB_HOST","localhost");
	define("DB_USERNAME","omsus1_plathfp2");
	define("DB_PASSWORD","Xxd~1wDs3DqT");
	define("DB_NAME","omsus1_tspnew");
}
/*elseif($_SERVER['HTTP_HOST'] == "www.transtrategypartners.com" || $_SERVER['HTTP_HOST'] == "transtrategypartners.com")
{
	define("URL_ROOT","http://".$_SERVER['HTTP_HOST']."/"); 
	define("DB_HOST","transtrategypartners.ipagemysql.com");
	define("DB_USERNAME","omslive_55");
	define("DB_PASSWORD","rajcorls_7");
	define("DB_NAME","my_new_tspdb");
}*/
elseif($_SERVER['HTTP_HOST'] == "www.transtrategypartners.com" || $_SERVER['HTTP_HOST'] == "transtrategypartners.com")
{
	define("URL_ROOT","http://".$_SERVER['HTTP_HOST']."/"); 
	define("DB_HOST","localhost");
	define("DB_USERNAME","trnstrte_tspUser");
	define("DB_PASSWORD","0!1]7o+VfRl#");
	define("DB_NAME","trnstrte_tsp");
}
//elseif($_SERVER['HTTP_HOST'] == "www.transtrategypartners.com" || $_SERVER['HTTP_HOST'] == "transtrategypartners.com")
else
{
	define("URL_ROOT","http://".$_SERVER['HTTP_HOST']."/~trnstrtegpt146/"); 
	define("DB_HOST","localhost");
	define("DB_USERNAME","trnstrte_tspUser");
	define("DB_PASSWORD","0!1]7o+VfRl#");
	define("DB_NAME","trnstrte_tsp");
}

define("URL_CSS",URL_ROOT."css/");
define("URL_JS",URL_ROOT."js/");
define("URL_IMG",URL_ROOT."images/");

define("URL_ADMIN",URL_ROOT.$varAdminFolder."/");
define("URL_COACH",URL_ROOT.$varCoachFolder."/");
define("URL_CLIENT",URL_ROOT.$varClientFolder."/");

/*define("URL_ADMIN_HOME",URL_ADMIN."index.php");*/
define("URL_ADMIN_CSS",URL_CSS."admin/");
define("URL_ADMIN_JS",URL_JS."admin/");
define("URL_ADMIN_IMG",URL_IMG."admin/");

define("DATE_FORMAT","m-d-Y");

//global variables
$_pswd_len=array(
'min'=>6,
'max'=>30 //put 0 for unlimited
);

//Notification Events
/*$_notification_event=array(
"coach_new"				=>	'New Coach',
"edit_coach"			=>	"Edit 'Client'",
"inactive_coach"		=>	"Inactive 'Coach'",
"new_client"			=>	'New Client',
"client_edit"	    	=>	"Edit Client",
"coach_calender_client"	=>	"Coach Calender 'Client'",
"coach_calender_coach"	=>	"Coach Calender 'Coach'",
"invoice"				=>	"Invoice 'Client'",
"payment_details"		=>	"Payment Details 'Coach/Client/Admin'",
"pswd_change"			=>	"Change password 'Coach/Client'"
);*/

$_notification_event=array(
'coach_new'			=>	"New Coach",
'coach_edit'		=>	"Edit Coach",
'coach_inactive'	=>	"Inactivate Coach",
'client_new'		=>	"New Client",
'client_edit'	    =>	"Edit Client",
'calender_client'	=>	"Coach Calendar 'Client'",
'calender_coach'	=>	"Coach Calendar 'Coach'",
'invoice'			=>	"Invoice",
'session_created'	=>	"Session Created - Coach Mail",
'client_sess_created' => "Session Created - Client Mail",
'sess_coachreminder'  =>	"Session Reminder - Coach Mail",
'sess_clientreminder' =>   "Session Reminder - Client Mail",
'payment_details'	=>	"Payment Details",
'pswd_change'		=>	"Change Password",
'newsletter_subscriber'		=>	"Newsletter Subscriber"
);
//constant for session variable name
define("LOGIN_ADMIN","tsp_admin");
define("LOGIN_USER","tsp_user");

//define RegX expressions
define("REGX_MAIL","/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_-]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/");
define("REGX_URL","/^(http(s)?\:\/\/(?:www\.)?[a-zA-Z0-9]+(?:(?:\-|_)[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:(?:\-|_)[a-zA-Z0-9]+)*)*\.[a-zA-Z]{2,4}(?:\/)?)$/i");
define("REGX_PHONE","/^[0-9\+][0-9\-\(\)\s]+[0-9]$/");
define("REGX_DATE","/^(\d{4})-(\d{2})-(\d{2})$/");

//$recPg=20; //records per page

require_once(PATH_LIB."class.database.php");

//$db=new MySqlDb("localhost",DB_USERNAME,DB_PASSWORD,DB_NAME);

require_once(PATH_LIB."functions.php");
//require_once(PATH_LIB."class.mailer.php");

//set time zone
date_default_timezone_set("America/New_York");
?>