<?php
namespace booklist_downloader\adapter;
use booklist_downloader\model as model;
include "../string_render.php";
include "adapter.php";
class qidian extends adapter
{
	public $site_host="http://qidian.com";
	public $channel_host="http://h5.qidian.com/recommend.html";
	public $list_host="http://all.qidian.com/book/bookStore.aspx?ChannelId={channel}&PageIndex={pagecount}";
	public $site_name="起点中文网";
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
		$book->title=$array[4];
		//
		$book->author=$array[7];
		//
		$book->ID=$array[3];
		//
		$book->updatetime=$array[8];
		//
		$book->intro=$array[5];
		//
		$book->count=$array[6];
		//
		$book->type=$array[1].'-'.$array[2];
		//
		return $book;
	}
	public function get_pagecount_max(){
		if ($this->pagecount_max>0)return $this->pagecount_max;
		$pagecount_min_preg='|&PageIndex=(\d*)&update=-1&Vip=-1&Boutique=-1&SignStatus=-1\'>末页</a>|U';

		$this->url_now=str_replace("{channel}", $this->channel_now, $this->list_host);
		$this->url_now=str_replace("{pagecount}", $this->pagecount_now, $this->url_now);
		$ans=array();
		preg_match_all($pagecount_min_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		if(isset($ans[0][1]))$this->pagecount_max=$ans[0][1]; else $this->pagecount_max=$this->get_pagecount_now();
		return $this->pagecount_max;
	}
	public function get_pagecount_min(){
		if ($this->pagecount_min>0)return $this->pagecount_min;
		$pagecount_min_preg='|&PageIndex=(\d*)&update=-1&Vip=-1&Boutique=-1&SignStatus=-1\'>首页</a>|U';
		$this->url_now=str_replace("{channel}", $this->channel_now, $this->list_host);
		$this->url_now=str_replace("{pagecount}", $this->pagecount_now, $this->url_now);
		$ans=array();
		preg_match_all($pagecount_min_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		if(isset($ans[0][1]))$this->pagecount_min=$ans[0][1]; else $this->pagecount_min=$this->get_pagecount_now();
		return $this->pagecount_min;
	}
	public function get_pagecount_now(){
		$pagecount_now_preg='|<a class=\'f_s\' href=\'.*\'>(\d*)</a>|U';
		$this->url_now=str_replace("{channel}", $this->channel_now, $this->list_host);
		$this->url_now=str_replace("{pagecount}", $this->pagecount_now, $this->url_now);
		$ans=array();
		preg_match_all($pagecount_now_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		return $ans[0][1];
	}
	public function get_book(){
		//$list_preg='|<li><a href="3g\.sfacg\.com/Novel/(\d*)" id=".*">(.*)</a>(.*)</li>|U';
		$list_preg='|<div class=\'swz\'>.*</div>.*<div class="swa">\[<a .*>(.*)</a>/<a .*>(.*)</a>\]</div>.*<div class="swb">.*<a href="/Book/(\d*).aspx" .*>(.*)</a> </span><a .*>(.*)</a>.*</div>.*<div class="swc">(\d*)</div>.*<div class="swd"><a .*>(.*)</a>.*<div class="swe">(.*)</div>|U';
		$this->url_now=str_replace("{channel}", $this->channel_now, $this->list_host);
		$this->url_now=str_replace("{pagecount}", $this->pagecount_now, $this->url_now);
		$ans=array();
		preg_match_all($list_preg,$this->loaduri($this->url_now),$ans,PREG_SET_ORDER);
		//echo $this->loaduri($this->url_now);
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
		$channel_preg='|objTop.ParamChange\((\d*),0\);">(.*)<span|U';
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