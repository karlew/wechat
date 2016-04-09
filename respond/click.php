<?php

//菜单点击事件回复，把openid传进来，用于需要记录用户id的业务逻辑
function eventResponse($key,$openid){

	$msg = array();
	//文本回复示例 查看规则
	if($key=="rule"){
		$msg['msgType'] = 'text';
		$msg['content'] = "XXXXX的用户，可兑换我们准备的XXXXX，有效期至XXXXXXXXXX";
		return $msg;
	}
	//文本回复示例 礼品兑换
	elseif($key=="exchange"){
		...
		//业务逻辑代码
		if(兑换条件判断){
			...
			//业务逻辑代码
			$msg['msgType'] = 'text';
			$msg['content'] = "兑换成功！领取码为：XXXX，可通过XXXX领取！";
			return $msg;
		}else{
			$msg['msgType'] = 'text';
			$msg['content'] = "您未满足兑换条件，继续XXXXXX吧~";
			return $msg;
		}
	}
	//图文示例 优惠活动
	elseif($key=="raffle"){
		$msg['msgType'] = 'news';
		//有多少条图文，就按格式写多少个数组元素，官方文档规定最多十条图文
		$msg['articles'][0]['Title'] = '优惠活动1';
		$msg['articles'][0]['Description'] = 'XXXXXXXXXXXX大放送';
		$msg['articles'][0]['PicUrl'] = '这里替换为图文消息1图片的url';
		$msg['articles'][0]['Url'] = '替换为用户点击图文1后跳转的url';
		$msg['articles'][1]['Title'] = '优惠活动2';
		$msg['articles'][1]['Description'] = 'XXXXXXXXXXXX回馈用户';
		$msg['articles'][1]['PicUrl'] = '这里替换为图文消息2图片的url';
		$msg['articles'][1]['Url'] = '替换为用户点击图文2后跳转的url';
		return $msg;
	}
	//其它情况不作回复
	else{
		$msg['msgType'] = 'text';
		$msg['content'] = "noreply";
		return $msg;
	}
}