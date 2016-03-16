<?php
/**
 * 微信公众平台-自定义菜单 创建菜单
 */

header('Content-Type: text/html; charset=UTF-8');

//替换为自己的appid和secret
$appid="wx9b4b97f6ce6647ef";
$secret="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

//获取access_token
$token_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
$json=file_get_contents($token_url);
$res=json_decode($json);
$access_token=$res->access_token;

//菜单数据，最多三个一级菜单button，一级菜单下面最多五个二级菜单sub_button
$data='{
"button":[
	{
		"name":"事件菜单",
		"sub_button":[
			{
				"type":"click",
				"name":"测试菜单1",
				"key":"test1"
			},
			{
				"type":"click",
				"name":"测试菜单2",
				"key":"test2"
			},
			{
				"type":"click",
				"name":"测试菜单3",
				"key":"test3"
			}]
	},
	{
		"name":"链接菜单",
		"sub_button":[
			{
				"type":"view",
				"name":"移动网页开发",
				"url":"http://www.karlew.com/?cat=16"
			},
			{
				"type":"view",
				"name":"公众平台开发",
				"url":"http://www.karlew.com/?cat=5"
			},
			{
				"type":"view",
				"name":"前端性能优化",
				"url":"http://www.karlew.com/?cat=39"
			}]
	},
	{
		"type":"view",
		"name":"开发笔记",
		"url":"http://www.karlew.com"
	}]
}';

//POST方式请求
$menu_url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$ch = curl_init($menu_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
$msg = json_decode(curl_exec($ch),true);

if($msg['errcode'] == "0"){
	echo "创建成功";
}else{
	echo "创建失败";
}

?>