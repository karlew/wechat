<?php

//文本自动回复，把openid传进来，用于需要记录用户id的业务逻辑
function keywordResponse($keyword,$openid){

	$msg = array();
	//文本示例 你好
	elseif(strstr($keyword,"你好")){
		$msg['msgType'] = 'text';
		$msg['content'] = "你好，有什么可以帮忙的吗？";
		return $msg;
	}
	//文本示例 谢谢
	elseif(strstr($keyword,"谢谢")){
		$msg['msgType'] = 'text';
		$msg['content'] = "不客气哟~";
		return $msg;
	}
	//图文示例 优惠
	elseif(strstr($keyword,"优惠")||strstr($keyword,"活动")){
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
	//其他关键字
	else{
		//设置时区
		ini_set('date.timezone','PRC');
		$week = date("w",time());
		$hour = date("H",time());
		//周末
		if($week==0||$week==6){
			$msg['msgType'] = 'text';
			$msg['content'] = "亲亲，周末愉快，今天是休息哒，希望亲有个愉快的周末~\n如果有问题咨询，可以XXXXXX";
		}
		//工作日下班时间
		elseif($hour<9||$hour>=18){
			$msg['msgType'] = 'text';
			$msg['content'] = "今天已经下班咯~如果有问题，请在工作日9点-18点咨询哦~\n如果亲的问题非常紧急，不用担心，可XXXXXXXXX";
		}
		//其他情况不作自动回复
		else{
			$msg['msgType'] = 'text';
			$msg['content'] = "noreply";
		}
		return $msg;
	}
}