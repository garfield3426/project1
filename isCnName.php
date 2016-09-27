<?php
/* $str = "112是"; 
if (preg_match("/[\x7f-\xff]/", $str)) { 
echo "含有中文"; 
}else{ 
echo "没有中文"; 
} 

//查找目录下中文命名的文件 */
tree('/pkg/web/web.prykweb.com');
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
	} 
	echo "</ul>\n"; 
	$mydir->close(); 
} 