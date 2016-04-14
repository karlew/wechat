
<?php

/*
*snsapi_base静默授权获取openid  
*by karlew
*/

$appid = "wx9b4b97f6ce6647ef";  //替换为所用公众号的appid
$appsecret = "b1be97744f78714a5de17f4ca2823211";  //替换为所用公众号的appsecret

//用是否含有授权返回的code判断是否从授权url跳转过来,若未通过授权链接进入则重定向到授权链接
if(is_weixin()){
	//用是否含有授权返回的code判断是否从授权url跳转过来
	if(!$_GET['code']){
		$url = urlencode("http://wx.karlew.com/oauth2/base.php");
		$askurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$url."&response_type=code&scope=snsapi_base#wechat_redirect";
		header("location:".$askurl);exit();
	}else{
		//通过返回的code获取openid
		$res = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code");
		$res = json_decode($res);
		$openid = $res['openid'];
	}
}

?>

<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>snsapi_base授权测试</title>
<style type="text/css">
html,body{ margin: 0; padding: 0; }
.content{ width:100%; height:100%; position:fixed; left:0; top:0; width:100%;text-align:center }
</style>
</head>

<body>
<div class="content">
	<div style="margin:0 auto;margin-top:5%"><?php echo $openid; ?></div>
</div>
</body>
</html>