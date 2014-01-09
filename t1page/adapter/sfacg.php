<?php
namespace booklist_downloader\adapter;
use booklist_downloader\model as model;
include "adapter.php";
class sfacg extends adapter
{
	public $site_host="http://3g.sfacg.com";
	public $channel_host="http://3g.sfacg.com/Catalog/";
	public $list_host="http://book.sfacg.com/List/?tid=|channel|&PageIndex=|pagecount|";
	public $site_name="SF轻小说";
	protected $point=0;
	protected $pagecount_now=1;
	protected $pagecount_max=-1;
	protected $pagecount_min=-1;
	protected $channel_now=1;
	protected $url_now="";
	protected $downloadcache=array("cahce"=>"","uri"=>"");
	protected $channelcache=array();
	protected function bookinsert($array)
	{
		$book=new model\book();
		$book->site=$this->site_name;
		//
		$book->title=$array[2];
		//
		$book->author=$array[3];
		//
		$book->ID=$array[1];
		//
		$book->updatetime=$array[5];
		//
		$book->count=$array[6];
		//
		$book->type=$array[4];
		//
		return $book;
	}
	public function get_pagecount_max(){
		if ($this->pagecount_max>0)return $this->pagecount_max;
		$pagecount_min_preg='|>(\d*)</a></li><li class="pagebarNext">|U';
		$this->url_now=str_replace("|channel|", $this->channel_now, $this->list_host);
		$this->url_now=str_replace("|pagecount|", $this->pagecount_now, $this->url_now);
		$ans=array();
		preg_match_all($pagecount_min_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		if(isset($ans[0][1]))$this->pagecount_max=$ans[0][1]; else $this->pagecount_max=$this->get_pagecount_now();
		return $this->pagecount_max;
	}
	public function get_pagecount_min(){
		if ($this->pagecount_min>0)return $this->pagecount_min;
		$pagecount_min_preg='|<li class="pagebarPrv"><a href=".*PageIndex=(\d*)">1</a>|U';
		$this->url_now=str_replace("|channel|", $this->channel_now, $this->list_host);
		$this->url_now=str_replace("|pagecount|", $this->pagecount_now, $this->url_now);
		$ans=array();
		preg_match_all($pagecount_min_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		if(isset($ans[0][1]))$this->pagecount_min=$ans[0][1]; else $this->pagecount_min=$this->get_pagecount_now();
		return $this->pagecount_min;
	}
	public function get_pagecount_now(){
		$pagecount_now_preg='|<li class="pagebarCurrent">(\d*)</li>|U';
		$this->url_now=str_replace("|channel|", $this->channel_now, $this->list_host);
		$this->url_now=str_replace("|pagecount|", $this->pagecount_now, $this->url_now);
		$ans=array();
		preg_match_all($pagecount_now_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		return $ans[0][1];
	}
	public function get_book(){
		//$list_preg='|<li><a href="3g\.sfacg\.com/Novel/(\d*)" id=".*">(.*)</a>(.*)</li>|U';
		$list_preg='|<li><strong><a href="/Novel/(\d*)/" target.*>(.*)</a></strong><br />\s*作.*<a .*>(.*)</a><br />
\s*.*<span .*</span> / <a .*>(.*)</a> / (\d*-\d*-\d*) / (\d*)字<br />|U';//<span .*</span> / <a.*</a> / (\d*)-(\d*)-(\d*) / (\d*)字<br />(\s*)</li>
		$this->url_now=str_replace("|channel|", $this->channel_now, $this->list_host);
		$this->url_now=str_replace("|pagecount|", $this->pagecount_now, $this->url_now);
		$ans=array();
		preg_match_all($list_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		//return $ans;
		$ansarray=array();
		foreach ($ans as $key ) {
			//var_dump($this->bookinsert($key));
			array_push($ansarray, $this->bookinsert($key));
		}
		$this->channelcache=$ansarray;
		return $ansarray;		
	}
	public function get_channel(){
		if (count($this->channelcache)>0)return $this->channelcache;
		$channel_preg='|<a href="http://book\.sfacg\.com/3gList/\?tid=(\d*)\">(.*)</a>|U';
		$ans=array();
		$this->url_now=$this->channel_host;
		preg_match_all($channel_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		$ansarray=array();
		foreach ($ans as $key ) {
			$ansarray[$key[1]]=$key[2];
		}
		$this->channelcache=$ansarray;
		return $ansarray;
	}
	public function set_pagecount($pagecount){
		$this->pagecount_now=$pagecount;
	}
	public function set_channel($channel){
		$this->channel_now=$channel;
		$this->pagecount_now=1;
		$this->pagecount_min=-1;
		$this->pagecount_max=-1;
	}
}