<?php
require_once("../../config.php");
if( !isset( $_REQUEST['file'] ) ) {
  error_log("file non specificato");
  die( 'no file specified' );

}

$entity=$_REQUEST['entity'];
$entity_key=$_REQUEST['entity_key'];
if (strlen($_REQUEST['entity'])==0) {
  $entity='users';
  $entity_key=$_REQUEST['user_id'];
}
error_log("entity". $entity.PHP_EOL);
if ($_REQUEST['file']==null){
  $file=PATH_ROOTAPP.DS.'img/customer-listing1.png';

}
switch ($_REQUEST['tipo']){
  case "profilo":
      if ($_REQUEST['resize']=='m'){
        $file=PATH_UPLOADS.$entity.DS.'medium'.DS.$_REQUEST['file'];
        $fp=PATH_UPLOADS.$entity.DS.'medium'.DS;
      }else {
        $file=PATH_UPLOADS.$entity.DS.'small'.DS.$_REQUEST['file'];
        $fp=PATH_UPLOADS.$entity.DS.'small'.DS;

      }

      if( !file_exists( $file )    ) {
        $file=PATH_UPLOADS.$entity.DS.'small'.DS.$_REQUEST['file'];
        $fp=PATH_UPLOADS.$entity.DS.'small'.DS;
      }
    break;
  case "firma":
    $file=PATH_UPLOADS."document" .DS. $entity."_". $entity_key .DS.'firma'.DS.$_REQUEST['file'];
    $fp=PATH_UPLOADS."document" . DS. $entity."_". $entity_key .DS.'firma'.DS;
    break;

  default:
  if ($_REQUEST['resize']==1){
    $file=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS."resize" .DS.$_REQUEST['file'];
    $fp=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS."resize" .Ds;
    if( !file_exists( $file ) ) {
      $file=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS.$_REQUEST['file'];
      $fp=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS;
    }
  }
  elseif ($_REQUEST['resize']=='m'){
    $file=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS."medium" .DS.$_REQUEST['file'];
    $fp=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS."medium" .Ds;
    if( !file_exists( $file ) ) {
      $file=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS.$_REQUEST['file'];
      $fp=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS;
    }
  }
  else {
    $file=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS.$_REQUEST['file'];
    $fp=PATH_UPLOADS."document".DS.$_REQUEST['doc_per']."_". $_REQUEST['per_id'].DS;

  }
}

error_log("resize:". print_r($_REQUEST,1). "file:".$file. "tipo". mime_content_type_ax($file));

//echo mime_content_type_ax($file);

if( !file_exists( $file ) ) {
  error_log("file non esiste");
  die( 'xxfile doesnt exist'. $file);
}

set_time_limit(0); // disable the time limit for this script

$dl_file=$_REQUEST['file'];
//$dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).]|[\.]{2,})", '', $_GET['file']); // simple file name validation
//$dl_file = filter_var($dl_file, FILTER_SANITIZE_URL); // Remove (more) invalid characters
$fullPath = $fp.$dl_file;
$image_ext=array('jpg',"png","gif","jpeg");

if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
    header('Content-type: '.mime_content_type_ax($file));
    if (in_array($ext,$image_ext)){
      header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");

    }
    else{
      header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download

    }
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    while(!feof($fd)) {
        $buffer = fread($fd, 2048);
//        echo $buffer;
    }
}
error_log("file".$fullPath."mim".mime_content_type_ax($file));
fclose ($fd);
echo file_get_contents($file);
?>
