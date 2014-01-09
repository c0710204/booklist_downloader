<?php
namespace booklist_downloader\adapter;
use booklist_downloader\model as model;
include "model/book.php";
class adapter
{
	public $site_host="";
	public $channel_host="";
	public $list_host="";
	public $site_name="";
	protected $point=0;
	protected $pagecount_now=1;
	protected $channel_now=1;
	protected $url_now="";
	protected $downloadcache=array("cahce"=>"","uri"=>"");
	protected $channelcache=array();
	protected function loaduri($uri)
	{
		if ($this->downloadcache['uri']==$uri)return $this->downloadcache['cache'];
		$this->downloadcache['uri']=$uri;
		$this->downloadcache['cache']=file_get_contents($this->downloadcache['uri']);
		return $this->downloadcache['cache'];
	}
	protected function bookinsert($array)
	{
		$book=new model\book();
		return $book;
	}
	public function get_pagecount_max(){return 1;}
	public function get_pagecount_min(){return 1;}
	public function get_pagecount_now(){return 1;}
	public function get_book(){return array();}
	public function get_channel(){return array();}
	public function set_channel(){}
}