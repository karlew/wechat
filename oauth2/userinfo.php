
<?php

/*
*snsapi_userinfo获取用户信息授权  
*by karlew
*/

$appid = "wx9b4b97f6ce6647ef";  //替换为所用公众号的appid
$appsecret = "b1be97744f78714a5de17f4ca2823211";  //替换为所用公众号的appsecret

//用是否含有授权返回的code判断是否从授权url跳转过来,若未通过授权链接进入则重定向到授权链接
$url = urlencode("http://wx.karlew.com/oauth2/userinfo.php");
$askurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$url."&response_type=code&scope=snsapi_userinfo#wechat_redirect";
if(!$_GET['code']){
	header("location:".$askurl);exit();
}else{
	//通过返回的code获取授权接口用到的access_token（跟基础接口的access_token不同）和openid
	$tokenres = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code");
	$tokenres = json_decode($tokenres,true);
	if(isset($tokenres['errmsg'])){
		header("location:".$askurl);exit();
	}
	$token = $tokenres['access_token'];
	$openid = $tokenres['openid'];
	//通过access_token和openid获取用户信息，详细可获取的内容请参考微信公众平台开发者文档
	$infoRes = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$openid."&lang=zh_CN");
	$infoRes = json_decode($infoRes,true);
	//用户昵称
	$nickname = $infoRes['nickname'];
	//用户性别
	$sex = $infoRes['sex'];
	//用户头像url
	$headimgurl = $infoRes['headimgurl'];
}

?>

<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>snsapi_userinfo授权测试</title>
<style type="text/css">
html,body{ margin: 0; padding: 0; }
.content{ width:100%; height:100%; position:fixed; left:0; top:0; width:100%;text-align:center }
</style>
</head>

<body>
<div class="content">
	<div style="margin:0 auto;margin-top:5%"><img width="40%" src='<?php echo $headimgurl; ?>'></div>
	<div style="margin:0 auto;margin-top:5%"><?php echo $nickname; ?></div>
	<div style="margin:0 auto;margin-top:5%"><?php echo $openid; ?></div>
</div>
</body>
</html>