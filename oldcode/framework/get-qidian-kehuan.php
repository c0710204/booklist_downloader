
<?php
/*
define("__CFG_document_place__",'/framework' );
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
$start=time();
$index=$_GET['long'];

$sql=new SQL();
$sql->table='booklist';
for ($i = 0; $i < $index; $i++) 
{
	$bookid=$_GET['bookids']+$i;

$sql->I_data['id']=$bookid;

$url='http://www.qidian.com/Book/'.$bookid.'.aspx';
$html=file_get_contents($url);
$title=strpos($html,'<div class="title">',0);
$titles=strpos($html,'<h1>',$title)+4;
$titlee=strpos($html,'</h1>',$titles)-$titles;
$sql->I_data['name']=substr($html,$titles,$titlee)."\n";
$s=strpos($html,'<div class="info_box">',0);
//echo $s.'<br>';
$zt=strpos($html,'<b>写作进程：</b>',$s)+22;
//echo $zt.'<br>';
$ztend=strpos($html,'</td>',$zt)-$zt;
$zs=strpos($html,'<b>完成字数：</b>',$s)+22;
$zsend=strpos($html,'</td>',$zs)-$zs;
$lx=strpos($html,'<b>小说类别：</b>',$s)+22;
$lx1=strpos($html,'blank">',$lx)+7;
$lxend=strpos($html,'</a>',$lx1)-$lx1;
$sql->I_data['status']=substr($html,$zt,$ztend)."\n";
$sql->I_data['length']= substr($html,$zs,$zsend)."\n";
$sql->I_data['type']= substr($html,$lx1,$lxend)."\n";


@$sql->insert();
}
echo time()-$start;





*/
define("__CFG_document_place__",'/framework-1' );
set_time_limit(18000) ;
$starttime=time();

$booklist_game="7";
$booklist_kehuan="9";
$booklist_tongren="12";
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/log/logger.php';
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/settings/files.php';
$l=new logger($_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.$cfg['file']['log']['apilog']);
include $_SERVER['DOCUMENT_ROOT'].__CFG_document_place__.'/includes/database/sql.php';
$sql=new SQL();

//sql::$debug=true;
$sql->table='booklist_kehuan';
if (isset($_GET['target'])) $sql->table='booklist_'.$_GET['target'];
$k=1;
//$max=269;

if(isset($_GET['k']))
$k=$_GET['k'];
for ($index=$k;$index<$k+1;$index++)
{

	
$cid=$sql->table;
$url='http://all.qidian.com/book/bookStore.aspx?ChannelId='.$$cid.'&SubCategoryId=-1&Tag=all&Size=-1&Action=-1&OrderId=6&P=all&PageIndex='.$index.'&update=-1&Vip=-1&Boutique=-1&SignStatus=-1';

//$l->writelog($index,'api-QUERY');
$html=file_get_contents($url);
$s=strpos($html,'initPageCount',0)+15;

$e=strpos($html,"'",$s)-$s;
$max=substr($html,$s,$e);

$s=strpos($html,'<!--[if !IE]> 结果列表 开始 <![endif]-->',0)+46;
$e=strpos($html,' <!--[if !IE]> 结果列表 结束 <![endif]-->',$s)-$s;
$list=substr($html,$s,$e);
$e=0;
$arr=array();
for ($i = 0; $i < 100; $i++) {
	$s=strpos($list,'<div class="swa">',$e)+26;
	$e=strpos($list,'<div class="swe">',$s);
	$info=substr($list,$s,$e-$s);
	//id
	$ids=strpos($info,'/Book/',0)+6;
	$ide=strpos($info,'.asp',$ids)-$ids;
	$sql->I_data['id']=substr($info,$ids,$ide);
	//name
	$ns=strpos($info,'"_blank">',$ide)+9;
	$ne=strpos($info,'</a>',$ns)-$ns;
	$sql->I_data['name']=substr($info,$ns,$ne);
	//zs
	$zss=strpos($info,'<div class="swc">',$ns)+17;
	$zse=strpos($info,'</div>',$zss)-$zss;
	$sql->I_data['length']=substr($info,$zss,$zse);

	/*
	$ids=strpos($list,'/Book/',0)+6;
	$ide=strpos($list,'.asp',$ids)-$ids;
	$sql->I_data['id']=substr($list,$ids,$ide);*/
	array_push($arr, $sql->I_data);
	$sql->insert();
}
}
/*
 <div class="swc">226793</div><div class="swd"><a href="http://me.qidian.com/authorIndex.aspx?id=2964724" target="_blank" class="black">村长万岁</a></div>            <div class="swe">13-02-17 21:56</div>        </div><div class="sw2"><div class='swz'>27</div><div class="swa">[<a href="http://all.qidian.com/book/bookstore.aspx?ChannelId=12" class="hui2">同人</a>/<a href="http://all.qidian.com/book/bookstore.aspx?ChannelId=12&SubCategoryId=66"   class="hui2">小说同人</a>]</div><div class="swb"><span class="swbt"><a href="/Book/2564944.aspx" target="_blank">哈利波特与狮心王</a> </span><a href="/BookReader/2564944,43748458.aspx" target="_blank" class="hui2">第二卷 雄狮之醒　054. 狮心之</a>  </div>     
*/
//echo json_encode($arr);
/*
 * 
 * http://all.qidian.com/book/bookstore.aspx?
 * ChannelId=12&
 * SubCategoryId=-1&
 * Tag=all&Size=-1
 * &Action=-1&
 * OrderId=6&
 * P=all&
 * PageIndex=2
 * &update=-1&
 * Vip=-1&
 * Boutique=-1&
 * SignStatus=-1
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * /
 */

 ?>
 <head>
 <?php

if ($k+1<=$max)
{
if (isset($_GET['target'])) 
echo' <meta http-equiv ="refresh" content = "1;http://127.0.0.1:8099/framework-1/get-qidian-all.php?target='.$_GET['target'].'&k='.($k+1).'">';
else
echo' <meta http-equiv ="refresh" content = "1;http://127.0.0.1:8099/framework-1/get-qidian-all.php?k='.($k+1).'">';
} ?>
 </head>
 <body>
<a href="http://127.0.0.1:8099/framework-1/get-qidian-all.php?k=<?php echo ($k+1);?>">next</a>k=<?php echo ($k+1);?>
<div>
<div style="width:400px;background-color:black">
	<div style="width:<?php echo ($k*400/$max)?>px;background-color:yellow;text-align:right">
	 |
	</div>
</div>
<?php echo ($k)?>/<?php echo $max;?> <?php echo ((ceil($k*10000/$max))/100)?>%
<?php  
	$endtime=time()-$starttime;
	$needtime=($endtime+1)*($max-$k);
	$min=(ceil($needtime/60))-1;
	echo '<br>ETA:'.$min.'m '.($needtime-$min*60).'s';
?>
</div>

</body>