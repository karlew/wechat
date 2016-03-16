<?php
/**
 * 微信公众平台-自定义菜单 删除菜单
 */

header('Content-Type: text/html; charset=UTF-8');

$APPID="wx9b4b97f6ce6647ef";
$APPSECRET="b1be97744f78714a5de17f4ca2823201";

$TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;

$json=file_get_contents($TOKEN_URL);
$result=json_decode($json);

$ACC_TOKEN=$result->access_token;

$MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$ACC_TOKEN;

$cu = curl_init();
curl_setopt($cu, CURLOPT_URL, $MENU_URL);
curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
$info = curl_exec($cu);
$res = json_decode($info);
curl_close($cu);

if($res->errcode == "0"){
	echo "菜单删除成功";
}else{
	echo "菜单删除失败";
}

?>