<?php
namespace booklist_downloader\model;
class book
{
	public $title;
	public $count;
	public $updatetime;
	public $author;
	public $intro;
	public $I;
	public $site;
	public $type;
	/*
	public function __toString()
	{
		$str="";
		$str=$str." $this->title "."\r\n";
		$str=$str." $this->type / $this->count / $this->updatetime"."\r\n";
		//echo ""."\n";
		//echo ""."\n";
		//echo ""."\n";
		//echo ""."\n";
		return $str;
	}
	*/
	public function __toString()
	{
		$str="";
		$str=$str."$this->title ".",";
		$str=$str."$this->type,$this->count,$this->updatetime"."\n";
		//echo ""."\n";
		//echo ""."\n";
		//echo ""."\n";
		//echo ""."\n";
		return $str;
	}
	public function cvs($lfgf=",",$nrfgf="\"")
	{
		$str="";
		$str=$str.$nrfgf.str_replace($nrfgf, '\\'.$nrfgf,$this->title).$nrfgf.$lfgf;
		$str=$str.$nrfgf.str_replace($nrfgf, '\\'.$nrfgf,$this->type).$nrfgf.$lfgf;
		$str=$str.$nrfgf.str_replace($nrfgf, '\\'.$nrfgf,$this->count).$nrfgf.$lfgf;
		$str=$str.$nrfgf.str_replace($nrfgf, '\\'.$nrfgf,$this->updatetime).$nrfgf."\n";
		//echo ""."\n";
		//echo ""."\n";
		//echo ""."\n";
		//echo ""."\n";
		return $str;
	}	
}
