<?php

$site=array(
	"list_start"=>'<div class="comic_cover Blue_link3" style="line-height: 22px; color: #666666">',
	"item_start"=>'<ul class="Comic_Pic_List">',
	"items"=>array(
		array('<a href="/Novel/','/',"id"),
		array('color: #FF6600;">','</a>',"title"),
		array('<a href="/List/?tid=','</a>',"__func__","getBtype"),
		array('</a> /','字',"__func__","getdateandlength"),
		array('字<br />','</li>',"intro")
	),
	"item_end"=>'</ul>',
	"list_end"=>'</div>',
	"page_start"=>'pagebarCurrent">',
	"page_end"=>'</li>',
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
		$url="http://book.sfacg.com/List/Default.aspx?ud=-1&tid=$cid&PageIndex=$index";
		//$l->writelog($index,'api-QUERY');
		$html=file_get_contents($url);
		cliout("..................................OK\n");
		//		cliout_back(54);
		cliout('start load data from:'.$index);
		$s=strpos($html,$site['page_start'],0)+strlen($site['page_start']);
		$e=strpos($html,$site['page_end'],$s)-$s;
		$max=substr($html,$s,$e);
		
		$s=strpos($html,$site['list_start'],0)+strlen($site['list_start']);
		$e=strpos($html,$site['list_end'],$s)-$s;
		$list=substr($html,$s,$e);
	
		$e=0;
		for ($i = 0; $i < 20; $i++) 
		{
			//	cliout('load'.$i);
			$s=strpos($list,$site['item_start'],$e)+strlen($site['item_start']);
			$e=strpos($list,$site['item_end'],$s);
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
		}
		cliout("............................OK\n");
	}
//define("__CFG_document_place__",'/framework-1' );
define("__CFG_document_place__",'E:\webproject\framework-1' );
//set_time_limit(18000) ;


//$booklist_game="7";
$booklist_kehuan="13";
$booklist_sfacg="-1";
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/logger.php';
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log']['apilog']);
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
$sql=new SQL();
//sql::$debug=true;
$sql->table='booklist_sfacg';
$sql->delete();
$start=1;
$max=878;
set_time_limit(1000000);
$cid=$sql->table;
$cid=$$cid;
$sumtime_s=microtime(true);
for ($index=$start;$index<=$max;$index+=1)
{
	$starttime=microtime(true) ;
	loadhtml($sql,$cid,$index,$site);
	$endtime=microtime(true) -$starttime;
	$avgtime=(microtime(true)-$sumtime_s)/($index);
	$needtime=$avgtime*($max-$index);
	$min=(ceil($needtime/60))-1;
	cliout("AVG Speed:".$avgtime.'s ETA:'.$min.'M '.($needtime-$min*60).'S'."\n");
}

 ?>