<?php
function render($template,$args){
	$ret=$template;
	foreach($args as $key=>$value)
	{
		$ret=str_replace("{$key}",$value,$ret);
	}
	return $ret;
}