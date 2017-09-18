<?php
require_once("./config.php");
if( !isset( $_REQUEST['file'] ) ) {
  error_log("file non specificato");
  die( 'no file specified' );

}

$entity=$_REQUEST['entity'];
if (strlen($_REQUEST['entity'])==0) {
  $entity='users';
  $entity_key='user_id';
}
error_log("entity". $entity.PHP_EOL);
if ($_REQUEST['file']==null){
  $file='../img/customer-listing1.png';

}

elseif ($_REQUEST['profile']==1){
  $file="uploads/$entity/small/" .$_REQUEST['file'];

}

elseif ($_REQUEST['resize']==true){
  $file="uploads/document/".$_REQUEST['doc_per']."_". $_REQUEST['per_id']."/resize/" .$_REQUEST['file'];
}
else {
  $file="uploads/document/".$_REQUEST['doc_per']."_". $_REQUEST['per_id']."/" .$_REQUEST['file'];

}
error_log("file:".$file.mime_content_type_ax($file).file_get_contents($file));

//echo mime_content_type_ax($file);

if( !file_exists( $file ) ) {
  error_log("file non esiste");
  die( 'xxfile doesnt exist'. $file);
}

header('Content-type: '.mime_content_type_ax($file));

echo file_get_contents($file);
?>
