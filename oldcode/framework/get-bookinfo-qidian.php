
<?php
//define("__CFG_document_place__",'/framework-1' );
//set_time_limit(18000) ;
//include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
//include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/logger.php';
//include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
//$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log']['apilog']);
//include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
//$sql=new SQL();

$url='http://h5.qidian.com/Book/BookInfo.ashx?ajaxMethod=getbookinfo&bookid='.$_GET['id'];

$json=file_get_contents($url);
$info=json_decode($json,true);
echo json_encode($info['ReturnObject'][0]['ReturnObject']);
?>
