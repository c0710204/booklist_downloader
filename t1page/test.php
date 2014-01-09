<?php

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
	public function toString()
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
}
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
 var_dump (trans(new book()));
