<?php
namespace booklist_downloader;
include "adapter/sfacg.php";

use booklist_downloader\adapter;
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




$len=40;
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

function cleanline()
{
	$str="";
	for ($i=0; $i < 255; $i++) { 
		$str=sprintf("%s\x08",$str);
	}
	return $str;
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
	echo $adp->get_pagecount_min()."-".$adp->get_pagecount_max()."\n";
	for ($i=$adp->get_pagecount_min(); $i<=$adp->get_pagecount_max() ; $i++) 
	{ 
		file_put_contents("php://stdout",cleanline());
		file_put_contents("php://stdout", $i."\t");
		
		 $p=($i/$adp->get_pagecount_max());

		 file_put_contents("php://stdout",showprogressbar($p));
		//echo $i."\n";
		$adp->set_pagecount($i);
		$books=$adp->get_book();
		foreach ($books as $book) 
		{
			//file_put_contents("php://stdout", $book);
			file_put_contents("output.txt", $book->cvs(),FILE_APPEND);
			//$book->toString();
		}
	}

}//$adp->get_book());

