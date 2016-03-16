<?php
/**
 * 微信公众平台-自定义菜单 添加菜单
 */

header('Content-Type: text/html; charset=UTF-8');

//更换成自己的APPID和APPSECRET
$APPID="wx9b4b97f6ce6647ef";
$APPSECRET="b1be97744f78714a5de17f4ca2823201";

$TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;

$json=file_get_contents($TOKEN_URL);
$result=json_decode($json);

$ACC_TOKEN=$result->access_token;

$data='{
		 "button":[
		 {
			   "name":"测试菜单",
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
				},
				{
				   "type":"click",
				   "name":"测试菜单4",
				   "key":"test4"
				}]
		  },
		  {
			   "name":"开发笔记",
			   "sub_button":[
				{
				   "type":"view",
				   "name":"移动网页开发",
				   "url":"http://www.karlew.com/?cat=16"
				},
				{
				   "type":"view",
				   "name":"html5-canvas",
				   "url":"http://www.karlew.com/?cat=11"
				},
				{
				   "type":"view",
				   "name":"CSS/CSS3",
				   "url":"http://www.karlew.com/?cat=32"
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
			   "name":"技术博客",
			   "url":"http://www.karlew.com"
		   }]
       }';

$MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACC_TOKEN;

$ch = curl_init($MENU_URL);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
$info = curl_exec($ch);
$menu = json_decode($info);
print_r($info);		//创建成功返回：{"errcode":0,"errmsg":"ok"}

if($menu->errcode == "0"){
	echo "菜单创建成功";
}else{
	echo "菜单创建失败";
}

?>