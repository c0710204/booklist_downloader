<?php
namespace booklist_downloader\model;
class book
{
	public $title;
	public $count;
	public $updatetime;
	public $author;
	public $intro;
	public $ID;
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
}
