<?php
//根据域名查找IP:
//$dm=array('www.purui.cn','www.p0931.com',);	
function findIP($dm){		
	foreach($dm as $v){
		echo $v.'  IP IS:   <font color=\'red\'>'.gethostbyname($v).'</font><br /><hr>';
	}
}
//findIP($dm);

