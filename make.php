<?php
header("Content-type: text/html; charset=gbk"); 
ini_set('memory_limit', '1028M');//��������ʱ�ڴ��С,���������ļ�ʱ�����ڴ治��
//��Դ����
$urls = "www.zoudihuang008.pw";

 
//����ҳ
$geturl = GetURL();
if($_GET["dirnums"] != '')
{
    //Ŀ¼��
    $dirnums = $_GET["dirnums"];
    //������
    $arcnums = $_GET["arcnums"];
    echo "<h1>��ӭʹ�ã��������ɣ������ĵȴ�������</h1>"; 
    //��ҳ
    $index = $_GET["index"];
    for($i=0; $i < $dirnums;$i++)
    {
       ToFileArc($urls, $arcnums,$index);
    }
    echo "<script>setTimeout('self.close()',1000)</script>";
}
else if($_GET["index"]  != '')
{
	if($_GET["index"] == "del")
	{
		$url = $_SERVER['PHP_SELF'];
		$filename = end(explode('/',$url)); 
	    unlink($filename);
	    exit;	
	}
	//��ȡ����
    $nowtexts = file_get_contents("index.txt");
    $index_arc = file_get_contents("index_arc.txt");
    $sitemap_arc = file_get_contents("sitemap.txt");
    //��ҳ
    $index = $_GET["index"];
    $listurl = "http://".$urls."/list.php?index=".$index."&geturl=".$geturl;
    $indextexts = GetHttpData($listurl);
    $indextexts = str_replace("{index}", $nowtexts,$indextexts);
    $indextexts = str_replace("{index_arc}", $index_arc,$indextexts);
    $index_arr = explode( "@@@@@@",$indextexts);
    WriteFile($index,$index_arr[0]);
    $sitetexts = '<?xml version="1.0"?>\r\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\r\n{site}\r\n</urlset>';
    $sitetexts = str_replace("{site}", $sitemap_arc,$sitetexts);
    WriteFile("sitemap.xml", $sitetexts);
    unlink("sitemap.txt");
    unlink("index.txt");
    unlink("index_arc.txt");
	$url = $_SERVER['PHP_SELF'];
	$filename = end(explode('/',$url)); 
    unlink($filename);
    echo "<h1>������ҳ�ɹ���</h1>";
    echo "<script>setTimeout('self.close()',1000)</script>";
}
else
{
    echo "<h1>��ӭʹ�ã�Ŀ¼���ɰ棡����</h1>";
}
function WriteFile($filename,$txt)
{
	$dir = dirname($filename);
	if (!file_exists($dir)) mkdir($dir,0777,true);
	//chmod($dir,0777);
	return file_put_contents($filename , $txt."\n",FILE_APPEND);
} 
function GetHttpData($durl)
{
	$r= file_get_contents($durl);
	return $r;
}
function CreateFolder($filename)
{
	$dir = dirname($filename);
	if (!file_exists($dir)) mkdir($dir,0777,true);
}
function GetURL()
{
	
    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }
    else
    {
        $pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    }
   $pageURL = dirname($pageURL)."/";
    return $pageURL;
}
//��������   
function  ToFileArc($urls,$arcnums,$index)
{
	$geturl = GetURL();
    //�б�  ����
    $list = "list";
    $listtext = "";
    $listurl = "";
    $arc = "arc";
    //����
    $arctitle = "";
    $arcurl = "";
    $arctext = "";
    //��ȡ��ǰurl
    $nowurl = "";
    //��ȡĿ¼��
    $dirs = "";
    //��������Ŀ¼  �б�ҳ  ����ҳ
    //��ȡ�б�ҳ�� �б�ҳ���⣨�����⣩
    $listurl = "http://".$urls."/list.php?list=".$list."&dirname=".$dirs."&geturl=".$geturl;
    $listtext = GetHttpData($listurl);
    $list_arr = explode( "@@@@@@",$listtext);
    $arctexts = "";
    //��ȡĿ¼��
    $dirs = trim($list_arr[2].rand(1,10000));
    CreateFolder($dirs);
    //��ȡ���±���
    $arcurl = "http://".$urls."/list.php?dirname=".$dirs."&geturl=".$geturl."&gettnums=".$arcnums;
    $arctitle = GetHttpData($arcurl);
    $title_arr =  explode( "|",$arctitle); 
    $flink = "{flink}";
    $flink_url = "";
    $index_arc = "";
    //ѭ����������ҳ �б����
    for($i = 0; $i< $arcnums; $i++)
    {  
        $arcurl = "http://".$urls."/list.php?dirname=".$dirs."&geturl=".$geturl."&getarc=".$arc."&gettitle=".$title_arr[$i];
        $arctext = GetHttpData($arcurl);
        //���⡢�ؼ���
        $arctext = str_replace("{title}", $list_arr[1],$arctext);
        $arctext = str_replace("{keywords}", $title_arr[$i],$arctext);
        //�������
		$link_zz = "/{flink}/iS";
		preg_match_all($link_zz,$arctext,$link_arr);
		$link_count = count($link_arr[0]);
		for($j=0;$j< $link_count;$j++)
		{
			$rand_arc = mt_rand(1,$arcnums);
	        $flink_url = "<a href='http://".$geturl."/".$dirs."/".$rand_arc.".html' target='_blank' >".$title_arr[$rand_arc-1]."</a>";
	        $arctext = preg_replace($link_zz, $flink_url,$arctext,1);
		}   
        //�б����
        $arctexts = $arctexts."<li><a href='http://".$geturl.$dirs."/".($i+1).".html' target='_blank' >".$title_arr[$i]."</a></li>";
        $archtml = $dirs."/".($i + 1).".html";
        WriteFile($archtml,$arctext);
	 }
	 //���2ƪ���µ���ҳ
	 for($z = 0; $z < 2; $z++)
	 { 
	     $rand_arc = mt_rand(0,$arcnums)+1;
	 	 $index_arc = $index_arc."<dd><a href='http://".$geturl.$dirs."/".$rand_arc.".html' target='_blank' >".$title_arr[($rand_arc - 1)]."</a></dd>";
	     $sitemap_arc = $sitemap_arc."<url>\r\n<loc>http://".$geturl.$dirs."/".$rand_arc.".html</loc>\r\n<changefreq>hourly</changefreq>\r\n</url>";
	 }
     $sitemap_arc = $sitemap_arc."<url>\r\n<loc>http://".$geturl.$dirs."/index.html</loc>\r\n<changefreq>hourly</changefreq>\r\n<priority>0.8</priority>\r\n</url>";
     //�����б�ҳ
     $listtext = str_replace("{list}", $arctexts,$list_arr[0]);
     $listtext = str_replace("{host}", "http://".$geturl.$dirs."/index.html",$listtext);
     WriteFile($dirs."/index.html", $listtext);
     //������ҳindex.txt����Ҫ���б�
     $nowurl = "http://".$geturl.$dirs."/index.html";
     $nowurl = "<li><a href='".$nowurl."' target='_blank' >".$list_arr[1]."</a></li>";
     WriteFile("sitemap.txt", $sitemap_arc);
     WriteFile("index.txt", $nowurl);
     WriteFile("index_arc.txt", $index_arc);
}
?>

<html xmlns="http:'www.w3.org/1999/xhtml">
<head><title>
	��ӭʹ�ã�Ŀ¼���ɰ棡����
</title></head>
<style type="text/css">
*{margin:0px;padding:0px;}
h1{width:600px;height:50px;margin:0 auto;}
.open_web{width:600px;height:300px;border:1px #cccccc solid;margin:0 auto;}
.open_web dl dt{height:30px;line-height:30px;color:#000000; font-size:14px; background-color:#eeeeee;}
.open_web dl dd{height:28px;line-height:28px;color:#00cccc; font-size:13px;text-indent:10px;}
.open_web h5{color:#ff0000;}
</style>
<body>
 <div class="open_web">
	 <dl>
	 	 <dt>1������Ŀ¼���£�</dt>
	 	  <dd> Ĭ���ĵ���<span id="default_txt">index.html,default.html,index.php,Default.htm,index.htm</span></dd>
  <dd> <span id="Label7">Ĭ����ҳ��</span>
 <input name="default_index" type="text" value="index.html" id="default_index" />Ĭ�ϻ����ֶ�����,��ҳ���б�����ҳ�����ơ�</dd>
		 <dd> <span id="Label1">����ҳ������</span>
		 <input name="TextBox1" type="text" value="20" id="TextBox1" />����ҳ�棬�Ƽ�30-100����</dd>
		 <dd> <span id="Label2">Ŀ¼��</span>
		 <input name="TextBox3" type="text" value="1" id="TextBox3" />ÿ��ҳ���Ŀ¼�����Ƽ�1����</dd>
		 <dd> <span id="Label3">���£�</span>
		 <input name="TextBox4" type="text" value="50" id="TextBox4" />ÿ��Ŀ¼�����������Ƽ�100-300ƪ��</dd>
		 <dd> <input type="button" name="Button1" value="ȷ������Ŀ¼����" id="Button1" onclick="OpenArc()"  /> <input type="button" name="Button3" value="����������" id="Button3" onclick="CountArc()" /></dd>
		 <dd><h5>1) ����ҳ���� �� Ŀ¼�� �� ������ + Ŀ¼�� + 1 =  <span id="Label4">10051</span></h5></dd>
		 <dd><h5>2) �����Ҫ�������£����Լ�������������£�������������ҳ��</h5></dd>
		   <dd><h5>3) Ĭ��10051ƪ���£�����Ŀ¼��1����Ҫ���ģ��������ڲ����ƣ�ÿ��Ŀ¼�����Ƽ�200ƪ��</h5></dd>
	 </dl>
 </div>
 
 <div class="open_web">
	<dl>
		 <dt>2��������ҳ��</dt>
		 <dd><h5>1) �����ĵȴ���ִ���������ɵ�ҳ����Զ��رա�</h5></dd>
		 <dd><h5>2) ������������ϣ��ٵ��������ҳ������˳��ߵ���ִ�д���</h5></dd>
		 <dd>�������ӣ�<input name="TextBox2" type="text" value="20" id="TextBox2" />
		 <input type="button" name="Button2" value="ȷ��������ҳ" id="Button2" onclick="OpenIndex()"  /></dd>
	  <dd> <div style="float:left;">�ƹ�����1 ��</div>
  <div style="float:left;" id="Label6"></div> </dd>
    <dd> <div style="float:left;">�ƹ�����2 ��</div>
  <div style="float:left;"  id="Label5"></div> </dd>
  <dd><h5>3) �����ˣ�������ɾ�����ҳ��(default.aspx)�� <input type="button" name="Buttondel" value="ɾ��MAKEҳ��" id="Buttondel" onclick="DELIndex()"  /></h5></dd>
  <dd><h5>4) ���Ҫ���������������Ŀ¼��</h5></dd>

	</dl>
 </div>
 
</body>
</html>
<SCRIPT   language="javascript">   
   //=====�������壬����Ŀ¼���¡�===== 
  function   OpenArc()
  {   
		//ˢ�´���
        var nums = document.getElementById("TextBox1").value;
        //Ŀ¼��
        var dirnums = document.getElementById("TextBox3").value;
        //������
        var arcnums = document.getElementById("TextBox4").value;
        //��ҳ��
        var index = document.getElementById("default_index").value;
        
        for (var j = 0; j < nums; j++)
        {
            window.open(window.location.href + "?index=" + index + "&dirnums=" + dirnums + "&arcnums=" + arcnums, '_blank'); 
        }
    } 
    //====������������=====
    function CountArc()
    {
    	//ˢ�´���
        var nums = document.getElementById("TextBox1").value;
        //Ŀ¼��
        var dirnums = document.getElementById("TextBox3").value;
        //������
        var arcnums = document.getElementById("TextBox4").value;
        //������
        document.getElementById("Label4").innerHTML = (nums*dirnums*arcnums) + (nums*dirnums) + 1;
    } 
   //=====�������壬������ҳ��===== 
  function   OpenIndex()
  {   
  	    //��ҳ��
        var index = document.getElementById("default_index").value;
   		document.getElementById("Label6").innerHTML = "<a href='http://<?php echo GetURL(); ?>' target='_blank' >http://<?php echo GetURL(); ?></a>";
   		document.getElementById("Label5").innerHTML = "<a href='http://<?php echo GetURL(); ?>sitemap.xml' target='_blank' >http://<?php echo GetURL(); ?>sitemap.xml</a>";
        window.open(window.location.href + "?index="+ index +"&ilink=" + document.getElementById("TextBox2").value, '_blank'); 
    }
  //ɾ����ҳ
 function DELIndex()
 {
 	window.open(window.location.href + "?index=del", '_blank'); 
 }                                                                     
  </SCRIPT>
