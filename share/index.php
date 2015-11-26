
<?php

//获取access_token
function wx_get_token(){
	global $appid;
	global $appsecret;
	$res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);
	$res = json_decode($res,true);
	$token = $res['access_token'];
	return $token;
}

//获取ticket
function wx_get_jsapi_ticket(){
	if(time()-filemtime('ticket.txt')>3600){    //每一个小时获取一次新的ticket
		//通过access_token获取签名时用到的ticket
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".wx_get_token()."&type=jsapi";
		$res = file_get_contents($url);
		$res = json_decode($res,true);
		$ticket = $res['ticket'];
		$fileupdata = fopen("ticket.txt",'w');
		fwrite($fileupdata,$ticket);  //将ticket保存，由于access_token一天只能获取2000次，所以不建议每次都获取新的access_token
		fclose($fileupdata);
	}else{
		$ticket = file_get_contents('ticket.txt');
	}
	return $ticket;
}

$appid = "wx9b4b97f6ce6647ef";  //替换为所用公众号的appid
$appsecret = "b1be97744f78714a5de17f4ca2823201";  //替换为所用公众号的appsecret
$weburl = "http://wx.karlew.com/share_test";
$timestamp = time();  //生成签名的时间戳
$nonceStr = "wm3wzytpz0wzccnw";  //随机字符串
$wxticket  = wx_get_jsapi_ticket();  //获取ticket
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];  //当前页面url
$wxOri = sprintf("jsapi_ticket=".$wxticket."&noncestr=".$nonceStr."&timestamp=".$timestamp."&url=".$url);
$signature = sha1($wxOri);  //生成签名

?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta content="text/html; charset=utf-8">
<title>分享测试</title>	
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
wx.config({
	//debug: true, 
	appId: '<?php echo $appid; ?>', 
	timestamp: <?php echo $timestamp; ?>,
	nonceStr: '<?php echo $nonceStr; ?>',
	signature: '<?php echo $signature; ?>',
	jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] 
});
wx.ready(function(){
	var share_title = '分享接口调用成功！';
	var share_imgurl = '<?php echo $weburl; ?>/img/share.jpg';
	var share_link = '<?php echo $weburl; ?>';
	wx.onMenuShareTimeline({
		title: share_title, // 分享标题
		link: share_link, // 分享链接
		imgUrl: share_imgurl, // 分享图标
		success: function () { 
			
		},
		cancel: function () { 
			
		}
	});
	wx.onMenuShareAppMessage({
		//title: share_title, // 分享标题
		desc: share_title, // 分享描述
		link: share_link, // 分享链接
		imgUrl: share_imgurl, // 分享图标
		type: '', // 分享类型,music、video或link，不填默认为link
		dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		success: function () { 
			
		},
		cancel: function () { 
			
		}
	});
});
</script> 
<style>
html, body { height: 100%; }
body { margin: 0; padding: 0; width: 100%; display: table; font-weight: 100; }
.container { text-align: center; display: table-cell; vertical-align: middle; }
.title { font-size: 96px; }
</style>
</head>
<body>
<div class="container">
	<div class="title">分享测试</div>
</div>
</body>
</html>