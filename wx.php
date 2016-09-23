<?php
//引用jssdk.php 该文件微信公众平台提供
require_once "jssdk.php";
$jssdk = new JSSDK("wx9c48792ef7f4c6b7", "2da59c3bdd03ce4636f24a5ea172f2e5");

//获取微信公众号的相关消息
$signPackage = $jssdk->GetSignPackage();//appID  timestamp nonceStr signature



//接收事件并回复
    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }

?>
<html>
<title>微信开发公众平台</title>

<body>
//引用JS文件
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>


  <script type="text/javascript">
  /* wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '', // 必填，公众号的唯一标识
    timestamp: , // 必填，生成签名的时间戳
    nonceStr: '', // 必填，生成签名的随机串
    signature: '',// 必填，签名，见附录1
    jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
}); */

//通过config接口注入权限验证配置
	wx.config({
		debug:false,
		appId:'<?php echo $signPackage['appId']?>',
		timestamp:'<?php echo $signPackage["timestamp"]?>',
		nonceStr:'<?php echo $signPackage["nonceStr"]?>',
		signature:'<?php echo $signPackage["signature"]?>,
		jsApiList:[
			'onMenuShareTimeline',
		
		
		]		
	});
	
	//通过ready接口处理成功验证
	wx.ready(function(){

    // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
	
	//获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
	/* wx.onMenuShareTimeline({
		title: '', // 分享标题
		link: '', // 分享链接
		imgUrl: '', // 分享图标
		success: function () { 
			// 用户确认分享后执行的回调函数
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
	}); */
		wx.onMenuShareTimeline({
			title:'分享到朋友圈',
			link:'http://www.baidu.com',
			imgUrl:'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/logo_white_fe6da1ec.png',
			type:'link',
				success:function(){
					var request = new XMLHttpRequest();
					rqeuest.open("GET","<?php echo="$sn/s.php?tid=$tid&f=$f&hbid=$hbid&share=pyq"?>");
					request.send();
					alert('分享到朋友圈成功请看底部提示');
					window.location.reload();
				},
				cancel:function(){
					var request = new XMLHttpRequest();
					request.open("GET","<?php echo "$sn/s.php?tid=$tid&f=$f&share=pyqno";?>");
					request.send();
					alert('no share');
				}
		});
});




  </script>
  </body>
</html>