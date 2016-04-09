<?php

//文本自动回复
function textResponse($keyword,$openid){

	$msg = array();
	//微商
	if(strstr($keyword,"代理")||strstr($keyword,"加盟")||strstr($keyword,"微商")||strstr($keyword,"微店")){
		$msg['msgType'] = 'text';
		$msg['content'] = "亲亲，由于官微的咨询量较大，微商加盟请联系：\n电话：020-28941382\n或者添加微信公众号：mfsj1908wyx\n（直接公众号对话框内输入，会有在线客服接待解答）";
		return $msg;
	}
	//招聘
	elseif(strstr($keyword,"招聘")||strstr($keyword,"应聘")||strstr($keyword,"招人")){
		$msg['msgType'] = 'text';
		$msg['content'] = "亲 这个是我们招聘信息，亲有意向的话可以先把简历发给我们过目下的哦，谢谢！\n联系人：周先生  联系电话：020-29040619、29040624\n公司微信号：mfsj1908qj 电子邮箱：mfsjrocky@163.com  公司网址：http://www.mfsj1908.com\n公司地址：广州市白云区黄边南路47号永力商贸楼A区A栋4楼。";
		return $msg;
	}
	//最膜粉
	elseif(strstr($keyword,"最膜粉")){
		$msg['msgType'] = 'text';
		$msg['content'] = "马上点击【膜法盒】>>>【微社区】晒出你购买过的膜法世家产品，即可参与【最膜粉】抽奖活动，每周五微社区会公布本周获奖者，敬请关注！\nps：【最膜粉晒单】活动仅在微社区举行，私信晒单不参与评奖。";
		return $msg;
	}
	//京东
	elseif(strstr($keyword,"京东")){
		$msg['msgType'] = 'text';
		$msg['content'] = "亲，我们是京东自营旗舰店，百分百正品保证哦。每款宝贝均加贴膜法世家防伪标志，印刷膜法世家专属条形码，亲可以通过扫描二维码或条形码查询真伪，亲放心购买使用哈！";
		return $msg;
	}
	//唯品会
	elseif(strstr($keyword,"唯品会")){
		$msg['msgType'] = 'text';
		$msg['content'] = "亲，唯品会出售的膜法世家产品均属正品。每款宝贝均加贴膜法世家防伪标志，印刷膜法世家专属条形码，亲可以通过扫描二维码或条形码查询真伪，亲放心购买使用哈！";
		return $msg;
	}
	//蘑菇街
	elseif(strstr($keyword,"蘑菇街")){
		$msg['msgType'] = 'text';
		$msg['content'] = "蘑菇街请点击：http://mfsj1908.mogujie.com/";
		return $msg;
	}
	//防伪码
	elseif(strstr($keyword,"防伪码")||strstr($keyword,"正品码")||strstr($keyword,"正品")||strstr($keyword,"防伪")){
		$msg['msgType'] = 'text';
		$msg['content'] = "亲 我们是天猫旗舰店  保证正品，所有“膜法世家1908”系列正装产品均加贴膜法世家防伪标志，印刷膜法世家专属条形码。\n老的防伪标（标上无二维码的），拨打电话查询：4006781315，登录网址查询：www.61131115.com，也可登录膜法世家官网查询：http://www.mfsj1908.com/checkSC.php查询；\n新的防伪标（标上有二维码的），刮开密码涂层后用扫码软件扫描二维码查询，也可拨打电话查询：4006378315，登录膜法世家官网查询：http://www.mfsj1908.com/checkSC.php\n条形码查询（以69开头的13位数字）：亲可以登录“中国物品编码中心”的官网http://www.ancc.org.cn/，输入13位数字进行查询，中国物品编码中心是“国家质量监督检验检疫总局”的下属单位 信息权威可信";
		return $msg;
	}
	//门店/网点/实体店/直营店
	elseif(strstr($keyword,"门店")||strstr($keyword,"网点")||strstr($keyword,"实体店")||strstr($keyword,"直营店")||strstr($keyword,"直营")){
		$msg['msgType'] = 'text';
		$msg['content'] = "亲亲，点击网址，可以查询膜法世家实体店网点哦：http://www.mfsj1908.com/outer/zylocation/zylocation.php";
		return $msg;
	}
	//签到
	elseif(strstr($keyword,"签到")||$keyword=="1"){
		require_once('click.php');
		$msg['msgType'] = 'text';
		$content = eventResponse($openid,"signIn");
		$msg['content'] = $content['content'];
		return $msg;
	}
	//幸运抽奖
	elseif(strstr($keyword,"幸运抽奖")||strstr($keyword,"抽奖")||$keyword=="2"){
		$msg['msgType'] = 'news';
		$msg['articles'][0]['Title'] = '幸运抽奖';
		$msg['articles'][0]['Description'] = '幸运抽奖';
		$msg['articles'][0]['PicUrl'] = 'http://www.mfsj1908.com/outer/game/mfsj_raffle/img/title.jpg';
		$msg['articles'][0]['Url'] = 'http://www.mfsj1908.com/outer/game/mfsj_raffle/index.php?openid='.$openid.'&sign=1';
		return $msg;
	}
	//微社区
	elseif(strstr($keyword,"微社区")||strstr($keyword,"晒单")||$keyword=="3"){
		$msg['msgType'] = 'text';
		$msg['content'] = "进入微社区晒单，抽奶皮面膜 http://s.p.qq.com/pub/jump?d=AAAS6ioc";
		return $msg;
	}
	//怀孕/孕妇/坐月子/哺乳
	elseif(strstr($keyword,"怀孕")||strstr($keyword,"孕妇")||strstr($keyword,"坐月子")||strstr($keyword,"哺乳")){
		$msg['msgType'] = 'text';
		$msg['content'] = "亲，我们家的产品都是比较天然的， 但毕竟不是专业的孕妇产品哦 ，建议亲怀孕、哺乳期间最主要是营养好，休息好，保持好的心情，等过了这个阶段就可以针对肤质选用我们家的产品了。";
		return $msg;
	}
	//过敏
	elseif(strstr($keyword,"过敏")){
		$msg['msgType'] = 'text';
		$msg['content'] = "非常理解您现在的心情，因过敏源有很多种，导致皮肤敏感的因素也很复杂（比如可是有些人对这类东西就过敏:（海鲜、牛奶、芦荟、等，在生活中这些都是对身体有益的营养物质）。亲亲，如果有过敏症状，请马上停用产品，并联系膜法世家官方旗舰店售后客服，我们会妥善给亲亲解决的哦~";
		return $msg;
	}
	//你好
	elseif(strstr($keyword,"你好")){
		$msg['msgType'] = 'text';
		$msg['content'] = "你好，有什么可以帮忙的吗？";
		return $msg;
	}
	//谢谢
	elseif(strstr($keyword,"谢谢")){
		$msg['msgType'] = 'text';
		$msg['content'] = "不客气哟~";
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
			$msg['content'] = "亲亲，周末愉快，今天膜膜家是休息哒，希望亲亲有个愉快的周末~\n如果有问题咨询，可以联系膜法世家官方旗舰店客服，或是工作日9点-18点私信膜膜哦~";
		}
		//工作日下班时间
		elseif($hour<9||$hour>=18){
			$msg['msgType'] = 'text';
			$msg['content'] = "膜膜今天已经下班咯~亲亲如果有问题，请在工作日9点-18点咨询哦~\n如果亲亲的问题非常紧急，不用担心，可以咨询膜法世家官方旗舰店的客服哦~他们会更详细解答亲亲的问题哒~";
		}
		//其他情况不作自动回复
		else{
			$msg['msgType'] = 'text';
			$msg['content'] = "noreply";
		}
		return $msg;
	}
}