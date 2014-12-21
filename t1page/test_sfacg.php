<?php
//namespace booklist_downloader;
include "adapter/qidian.php";

use booklist_downloader\adapter;

//db
define("__CFG_document_place__",'./' );

include __CFG_document_place__.'/settings/setting.php';
include_once __CFG_document_place__.'/includes/log/logger.php';
include __CFG_document_place__.'/settings/files.php';
$l=new logger(__CFG_document_place__.$cfg['file']['log']['apilog']);
include __CFG_document_place__.'/includes/database/sql.php';
$sql=new SQL();
$sql->table='books';


echo "start run!\n";
function showprogressbar($persent,$len=40)
{
	$p=$persent*$len;
	$bar="|";
	for ($i1=0; $i1 <=$p-1 ; $i1++) { $bar=$bar.'=';}	
	$bar=$bar.'>';
	for ($i1=$p; $i1 <$len ; $i1++) { $bar=$bar.' ';}	
	$bar=$bar.'|';
	return $bar;
}
function trans($obj)
{
	$rtn=array();
	$rf = new ReflectionObject($obj);
	foreach ($rf->getProperties() as $value) 
	{
		$name=$value->name;
		if (!is_null($obj->$name)) 	$rtn[$name]=$obj->$name;
	}
	return $rtn; 
}
function cleanline()
{
	$str="";
	for ($i=0; $i < 255; $i++) { 
		$str=sprintf("%s\x08",$str);
	}
	for ($i=0; $i < 255; $i++) { 
		$str=sprintf("%s ",$str);
	}
	for ($i=0; $i < 255; $i++) { 
		$str=sprintf("%s\x08",$str);
	}	
	return $str;
}
ini_set("max_execution_time","99999999");
$adp=new adapter\qidian();
//print_r(
$channel=$adp->get_channel();
//var_dump($channel);
$pagesum=0;
if ($argc==2)
{
	if ($argv[1]=="channel")
	{
		$channel=$adp->get_channel();
		foreach ($channel as $key => $value) 
		{
			$adp->set_channel($key);
			//$adp->set_pagecount(2);
			echo ' |  '.$key.'  ->  '.$value."  |\r\n";
		}
	}
	exit(0);

}
/*
|  21  ->  玄幻  |
|  1  ->  奇幻  |
|  2  ->  武侠  |
|  22  ->  仙侠  |
|  4  ->  都市  |
|  5  ->  历史  |
|  6  ->  军事  |
|  7  ->  游戏  |
|  8  ->  竞技  |
|  9  ->  科幻  |
|  10  ->  灵异  |
|  12  ->  同人  |
 */
$channel=array(
	//9=>"科幻",
	//12=>"同人",
	7=>"游戏",
	//1=>"奇幻",
	);


foreach ($channel as $key => $value) 
{
	$adp->set_channel($key);
	$pagesum+=$adp->get_pagecount_max()-$adp->get_pagecount_min();
}



echo "  共有  ".$pagesum."  页  \n";

$len=40;
$pagenow=0;
$starttime=0;
$endtime=1;
$min=0;
foreach ($channel as $key => $value) 
{
	$adp->set_channel($key);

	for ($i=$adp->get_pagecount_min(); $i<=$adp->get_pagecount_max() ; $i++) 
	{ 
		$pagenow++;
		file_put_contents("php://stdout",cleanline());
		file_put_contents("php://stdout", $i.'/'.$pagenow.'/'.$pagesum."\t");

		 $p=($pagenow/$pagesum);

		 file_put_contents("php://stdout",showprogressbar($p));
		//echo $i."\n";
		$begin=
		$adp->set_pagecount($i);
		$starttime=time();
		$books=$adp->get_book();

		foreach ($books as $book) 
		{//var_dump($book);
			//file_put_contents("php://stdout", $book);
			file_put_contents("output.txt", $book->cvs(),FILE_APPEND);
			$sql->I_data=trans($book);
			$sql->insert();
			//$book->toString();
		}

		$endtime=time()-$starttime;
		$needtime=$endtime*($pagesum-$pagenow);
		$min=(ceil($needtime/60))-1;
		file_put_contents("php://stdout",'ETA:'.$min.'m '.($needtime-$min*60).'s');
	}
}