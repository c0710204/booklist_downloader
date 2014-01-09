<?php
function f2i($f,$l=2)
{
	for ($i=0;$i<$l;$i++) $f=$f*10;
	$in=(int)$f;
	for ($i=0;$i<$l;$i++) $in=$in/(10.0);
	return $in;

}
$site=array(
	"itemNum"=>100,
	"list_start"=>'<!--[if !IE]> 结果列表 开始 <![endif]-->',
	"item_start"=>'<div class="swa">',
	"items"=>array(
		array('/Book/','.asp',"id"),
		array('"_blank">','</a>',"title"),
		array('<div class="swc">','</div>',"length"),
		array('class="hui2">','</a>',"type"),
		array('"   class="hui2">','</a>]',"intro"),
		array('<div class="swe">','</div>',"update")
	),
	"item_end"=>'        </div><div class="sw',
	"list_end"=>'<!--[if !IE]> 结果列表 结束 <![endif]-->',
	"page_start"=>'initPageCount',
	"page_end"=>"'",
	);
function getdateandlength($str,$data1)
	{
	$info=explode("/",$str);
	$data1['update']=$info[0];
	$data1['length']=preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$info[1]);
	return $data1;
	}
function getBtype($str,$data1)
	{
		$info=explode(">",$str);
		$data1['type']=$info[1];
		return $data1;
	}
function FUNCnil($str,$data1)
{
}
function cliout($str)
{
		$STDOUT = fopen('php://stdout', 'w');fwrite($STDOUT,$str); fclose($STDOUT);
}
function cliout_back($len)
{
		$STDOUT = fopen('php://stdout', 'w');
		for($i=0;$i<$len;$i++)
		fwrite($STDOUT,'\b');
		fclose($STDOUT);
}
function loadhtml($sql,$cid,$index,$site)
	{
		//cliout_back(54);
		cliout('start download:'.$index);
		$url="http://all.qidian.com/book/bookstore.aspx?ChannelId=$cid&Tag=all&OrderId=5&P=all&PageIndex=$index";
		//$l->writelog($index,'api-QUERY');
		$html=file_get_contents($url);
		cliout("..........................OK\n");
		//		cliout_back(54);
		cliout('start load data from:'.$index);
		$s=strpos($html,$site['page_start'],0)+strlen($site['page_start']);
		$e=strpos($html,$site['page_end'],$s)-$s;
		$max=substr($html,$s,$e);
		
		$s=strpos($html,$site['list_start'],0)+strlen($site['list_start']);
		$e=strpos($html,$site['list_end'],$s)-$s;
		$list=substr($html,$s,$e);
		$s=0;
		$e=0;
		for ($i = 0; $i < $site['itemNum']; $i++) 
		{
			//	cliout('load'.$i);
			$s=strpos($list,$site['item_start'],$e)+strlen($site['item_start']);
			$e=strpos($list,$site['item_end'],$s);
			if (($e===false)&&($i==$site['itemNum']-1))
			{
				$e=strlen($list);
			}

			$info=substr($list,$s,$e-$s);
			$zse=0;
			foreach($site['items'] as $item )
			{
				$zss=strpos($info,$item[0],$zse)+strlen($item[0]);
				$zse=strpos($info,$item[1],$zss)-$zss;				
				if ($item[2]!='__func__')
				{
					$sql->I_data[$item[2]]=substr($info,$zss,$zse);
				}
				else
				{
					$sql->I_data=$item[3](substr($info,$zss,$zse),$sql->I_data);
				}
			}
			
		$sql->insert();
		if ($i%($site['itemNum']/20)==0)cliout(".");
		}
		cliout("OK\n");
	}
//define("__CFG_document_place__",'/framework-1' );
define("__CFG_document_place__",'E:\webproject\framework-1' );
//set_time_limit(18000) ;


//$booklist_game="7";
$booklist_kehuan="13";
$booklist_qidian="-1";
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/logger.php';
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log']['apilog']);
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
$sql=new SQL();
//sql::$debug=true;
$sql->table='booklist_qidian';
$sql->delete();
$start=1;
$max=6933;
set_time_limit(1000000);
$cid=$sql->table;
$cid=$$cid;
$sumtime_s=microtime(true);
for ($index=$start;$index<=$max;$index+=1)
{
	$starttime=microtime(true) ;
	loadhtml($sql,$cid,$index,$site);
	$endtime=microtime(true) -$starttime;
	$avgtime=f2i((microtime(true)-$sumtime_s)/($index));
	$needtime=$avgtime*($max-$index);
	$min=(ceil($needtime/60))-1;
	cliout("AVG Speed:".number_format($avgtime,2,'.','').'s ETA:'.$min.'M '.number_format(($needtime-$min*60),2,'.','').'S'."\n");
}

 ?>