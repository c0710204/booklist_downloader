<?php
namespace booklist_downloader;
include "adapter/sfacg.php";

use booklist_downloader\adapter;
function trans($obj)
{
$rtn=array();
$rf = new ReflectionObject($obj);
foreach ($rf->getProperties() as $value) {
	$name=$value->name;
	if (!is_null($obj->$name)) 	$rtn[$name]=$obj->$name;
}
return $rtn; 
}





$len=30;

$adp=new adapter\sfacg();
//print_r(
$channel=$adp->get_channel();
foreach ($channel as $key => $value) 
{
	
	$adp->set_channel($key);
	
	//$adp->set_pagecount(2);
	echo ' | '.$value." |\n";
	echo $adp->get_pagecount_min()."-";
	echo $adp->get_pagecount_max()."\n";
	for ($i=$adp->get_pagecount_min(); $i<=$adp->get_pagecount_max() ; $i++) 
	{ 
		file_put_contents("php://stdout", $i."\t|");

		 $p=($i*$len/$adp->get_pagecount_max());
		for ($i1=0; $i1 <$p-1 ; $i1++) { file_put_contents("php://stdout",'=');}	
		file_put_contents("php://stdout",'>');
		for ($i1=$p; $i1 <$len ; $i1++) { file_put_contents("php://stdout",' ');}	
		file_put_contents("php://stdout","|\n");
		//echo $i."\n";
		$adp->set_pagecount($i);
		$books=$adp->get_book();
		foreach ($books as $book) 
		{
			//file_put_contents("php://stdout", $book);
			file_put_contents("output.txt", $book,FILE_APPEND);
			//$book->toString();
		}
	}

}//$adp->get_book());

