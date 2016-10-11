<?php
/* $str = "112是"; 
if (preg_match("/[\x7f-\xff]/", $str)) { 
echo "含有中文"; 
}else{ 
echo "没有中文"; 
} 

//查找目录下中文命名的文件 */
//tree('/pkg/web/www.prykweb.com/hospital/html/p0371');
function tree($directory) 
{ 
	$mydir=dir($directory); 
	echo "<ul>\n"; 
	while($file=$mydir->read()){ 
		if((is_dir("$directory/$file")) AND ($file!=".") AND ($file!="..")) 
		{
		//	echo "<li><font color=\"#ff00cc\"><b>$file</b></font></li>\n"; 
			tree("$directory/$file"); 
		} elseif(preg_match("/[\x7f-\xff]/", $file)){
			echo "<li>$directory/$file</li>\n"; 
		}	
		 elseif(preg_match("/[\x7f-\xff]/", $directory)){
			echo "<li>$directory</li>\n"; 
		}	
	} 
	echo "</ul>\n"; 
	$mydir->close(); 
} 

//根据域名显示IP
$dm=array(
				'www.purui.cn',
				'www.p0931.com',
				' ',
);	
//showIp($dm);
function showIp($dm){
	foreach($dm as $v){
		echo $v.'  IP IS:   <font color=\'red\'>'.gethostbyname($v).'</font><br /><hr>';
	}
}				

