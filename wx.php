<?php
//����jssdk.php ���ļ�΢�Ź���ƽ̨�ṩ
require_once "jssdk.php";
$jssdk = new JSSDK("wx9c48792ef7f4c6b7", "2da59c3bdd03ce4636f24a5ea172f2e5");

//��ȡ΢�Ź��ںŵ������Ϣ
$signPackage = $jssdk->GetSignPackage();//appID  timestamp nonceStr signature



//�����¼����ظ�
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
<title>΢�ſ�������ƽ̨</title>

<body>
//����JS�ļ�
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>


  <script type="text/javascript">
  /* wx.config({
    debug: true, // ��������ģʽ,���õ�����api�ķ���ֵ���ڿͻ���alert��������Ҫ�鿴����Ĳ�����������pc�˴򿪣�������Ϣ��ͨ��log���������pc��ʱ�Ż��ӡ��
    appId: '', // ������ںŵ�Ψһ��ʶ
    timestamp: , // �������ǩ����ʱ���
    nonceStr: '', // �������ǩ���������
    signature: '',// ���ǩ��������¼1
    jsApiList: [] // �����Ҫʹ�õ�JS�ӿ��б�����JS�ӿ��б����¼2
}); */

//ͨ��config�ӿ�ע��Ȩ����֤����
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
	
	//ͨ��ready�ӿڴ���ɹ���֤
	wx.ready(function(){

    // config��Ϣ��֤���ִ��ready���������нӿڵ��ö�������config�ӿڻ�ý��֮��config��һ���ͻ��˵��첽���������������Ҫ��ҳ�����ʱ�͵�����ؽӿڣ��������ؽӿڷ���ready�����е�����ȷ����ȷִ�С������û�����ʱ�ŵ��õĽӿڣ������ֱ�ӵ��ã�����Ҫ����ready�����С�
	
	//��ȡ����������Ȧ����ť���״̬���Զ���������ݽӿ�
	/* wx.onMenuShareTimeline({
		title: '', // �������
		link: '', // ��������
		imgUrl: '', // ����ͼ��
		success: function () { 
			// �û�ȷ�Ϸ����ִ�еĻص�����
		},
		cancel: function () { 
			// �û�ȡ�������ִ�еĻص�����
		}
	}); */
		wx.onMenuShareTimeline({
			title:'��������Ȧ',
			link:'http://www.baidu.com',
			imgUrl:'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/logo_white_fe6da1ec.png',
			type:'link',
				success:function(){
					var request = new XMLHttpRequest();
					rqeuest.open("GET","<?php echo="$sn/s.php?tid=$tid&f=$f&hbid=$hbid&share=pyq"?>");
					request.send();
					alert('��������Ȧ�ɹ��뿴�ײ���ʾ');
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