<?php
/**
 * 微信公众平台-自定义菜单 查询菜单
 */

header('Content-Type: text/html; charset=UTF-8');

//替换为自己的appid和secret
$appid="wx9b4b97f6ce6647ef";
$secret="b1be97744f78714a5de17f4ca2823201";

//获取access_token
$token_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
$json=file_get_contents($token_url);
$res=json_decode($json);
$access_token=$res->access_token;

//GET方式请求
$menu_url="https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$access_token;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $menu_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$menu_json = json_decode(curl_exec($ch),true);
curl_close($ch);

echo "<pre>";
print_r($menu_json);
echo "</pre>";

?>