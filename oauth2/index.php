
<?php

/*
url为  https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx9b4b97f6ce6647ef&redirect_uri=http%3a%2f%2fwx.karlew.com%2foauth2_test%2findex.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
*/

//第一步，获取授权页面的scope为snsapi_userinfo时返回的code
$code = $_GET['code'];
$appid = "wx9b4b97f6ce6647ef";  //替换为所用公众号的appid
$appsecret = "b1be97744f78714a5de17f4ca2823201";  //替换为所用公众号的appsecret
$res1 = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code");
$res1 = json_decode($res1);
//第二步，通过返回的code获取授权接口用到的access_token（跟基础接口的access_token不同）和openid
$token = $res1['access_token'];
$openid = $res1['openid'];
//第三步，通过access_token和openid获取用户信息，详细可获取的内容请参考微信公众平台开发者文档
$res2 = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$openid."&lang=zh_CN");
$res2 = json_decode($res2);
$nickname = $res2['nickname'];
$sex = $res2['sex'];
$headimgurl = $res2['headimgurl'];

?>

<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>授权测试</title>
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