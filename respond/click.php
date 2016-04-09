<?php

include('conn.php');

//新增关注用户
function eventResponse($openid,$key){

	//判断user表中是否有该用户数据 若无则增加记录
	$count = mysql_num_rows(mysql_query("SELECT * from mfsj_user where openid='".$openid."'"));
	if($count<=0){
		mysql_query("INSERT into mfsj_user (openid,first_subtime,last_subtime) value ('".$openid."',".time().",".time().")");
	}

	$msg = array();
	//签到
	if($key=="signIn"){
		$today=date("Y-m-d",time());
		$tomorrow=date("Y-m-d",strtotime('+1 day'));
		$count = mysql_num_rows(mysql_query("SELECT * from mfsj_signin where openid='$openid' and time >='$today' and time <'$tomorrow'"));
		$msg['msgType'] = 'text';
		if($count>0){
			$msg['content'] = "您今天已经签到了，请明天再来";
			return $msg;
		}else{
			//用户表添加积分
			mysql_query("UPDATE mfsj_user set point=point+10 where openid='".$openid."'");
			//记录表添加记录
			mysql_query("INSERT into mfsj_signin (openid,getpoint,time) value ('".$openid."',10,'".date("Y-m-d H:i:s",time())."')");
			//获取当前积分
			$row  = mysql_fetch_array(mysql_query("SELECT * from mfsj_user where openid='".$openid."'"));
			$point = $row['point'];
			$msg['content'] = "签到成功！加10积分，您目前的积分为：".$point;
			return $msg;
		}
	}
	//查询积分
	elseif($key=="checkpoint"){
		$row  = mysql_fetch_array(mysql_query("SELECT * from mfsj_user where openid='".$openid."'"));
		$point = $row['point'];
		$msg['msgType'] = 'text';
		$msg['content'] = "您当前的积分为：".$point;
		return $msg;
	}
	//积分说明
	elseif($key=="pointRule"){
		$msg = array();
		$msg['msgType'] = 'text';
		$msg['content'] = "1.300积分可以兑换一个绿豆清肌中样三件套;\n2.600积分可以兑换一瓶绿豆小黄瓜蜜汁;\n3.1000积分可以兑换一盒绿豆泥浆面膜;\n兑换方式：截取兑换成功页面，发给膜法世家，并回复验证码即可兑换。\n积分有效期至2016年12月31日；\n本活动最终解析权归膜法世家所有";
		return $msg;
	}
	//300兑换
	elseif($key=="exchange300"){
		$row  = mysql_fetch_array(mysql_query("SELECT * from mfsj_user where openid='".$openid."'"));
		$point = $row['point'];
		$msg['msgType'] = 'text';
		if($point>300){
			$key = getExchangeKey();
			mysql_query("UPDATE mfsj_user set point=point-300 where openid='".$openid."'");
			mysql_query("INSERT INTO mfsj_exechange (openid,point,item,code,time) VALUE('$openid',300,'绿豆清肌中样三件套','$key','".date("Y-m-d H:i:s",time())."')");
			$msg['content'] = "兑换绿豆清肌中样三件套成功！领取码为：".$key." 提供截图给客服即可安排发放哦！";
			return $msg;
		}else{
			$msg['content'] = "您的积分不够，努力做任务吧~";
			return $msg;
		}
	}
	//600兑换
	elseif($key=="exchange600"){
		$row  = mysql_fetch_array(mysql_query("SELECT * from mfsj_user where openid='".$openid."'"));
		$point = $row['point'];
		$msg['msgType'] = 'text';
		if($point>600){
			$key = getExchangeKey();
			mysql_query("UPDATE mfsj_user set point=point-600 where openid='".$openid."'");
			mysql_query("INSERT INTO mfsj_exechange (openid,point,item,code,time) VALUE('$openid',600,'绿豆小黄瓜蜜汁','$key','".date("Y-m-d H:i:s",time())."')");
			$msg['content'] = "兑换绿豆小黄瓜蜜汁成功！领取码为：".$key." 提供截图给客服即可安排发放哦！";
			return $msg;
		}else{
			$msg['content'] = "您的积分不够，努力做任务吧~";
			return $msg;
		}
	}
	//1000兑换
	elseif($key=="exchange1000"){
		$row  = mysql_fetch_array(mysql_query("SELECT * from mfsj_user where openid='".$openid."'"));
		$point = $row['point'];
		$msg['msgType'] = 'text';
		if($point>1000){
			$key = getExchangeKey();
			mysql_query("UPDATE mfsj_user set point=point-1000 where openid='".$openid."'");
			mysql_query("INSERT INTO mfsj_exechange (openid,point,item,code,time) VALUE('$openid',1000,'绿豆泥浆面膜','$key','".date("Y-m-d H:i:s",time())."')");
			$msg['content'] = "兑换绿豆泥浆面膜成功！领取码为：".$key." 提供截图给客服即可安排发放哦！";
			return $msg;
		}else{
			$msg['content'] = "您的积分不够，努力做任务吧~";
			return $msg;
		}
	}
	//老虎机抽奖
	elseif($key=="raffle"){
		$msg['msgType'] = 'news';
		$msg['articles'][0]['Title'] = '幸运抽奖';
		$msg['articles'][0]['Description'] = '幸运抽奖';
		$msg['articles'][0]['PicUrl'] = 'http://www.mfsj1908.com/outer/game/mfsj_raffle/img/title.jpg';
		$msg['articles'][0]['Url'] = 'http://www.mfsj1908.com/outer/game/mfsj_raffle/index.php?openid='.$openid.'&sign=1';
		return $msg;
	}
	else{
		$msg['msgType'] = 'text';
		$msg['content'] = "暂未开放";
		return $msg;
	}
}

//获取兑换码
function getExchangeKey() {
list($usec, $sec) = explode(" ",microtime());
$timeStr = substr($sec, 2, 8).substr($usec, 2, 4);
return base_convert($timeStr,10,32);
}