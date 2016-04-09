<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixinkarlew");
$wechatObj = new wechatCallbackapiTest();

//判断是否为token验证
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            // libxml_disable_entity_loader(true);
          	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $msgType = $postObj->MsgType;
            $time = time();
            
            //通过MsgType判断消息类型
            //文本消息             
			if($msgType=="text"){
                require_once('text.php');
                $keyword = trim($postObj->Content);
                $contentArr = textResponse($keyword,$fromUsername);
                //回复文本
                if($contentArr['msgType']=="text"){
                    if($contentArr['content']!="noreply"){
                        $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";  
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentArr['content']);
                        echo $resultStr;
                    }
                    //无匹配关键字的情况
                    else{
                        echo "";
                        exit;
                    }
                }
                //图文消息回复
                elseif($contentArr['msgType']=="news"){
                    $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>%s</Articles>
                        </xml>"; 
                    $articleCount = count($contentArr['articles']);
                    $articlesStr = "";
                    for ($i=0; $i < $articleCount; $i++) { 
                         $articlesStr .= "<item><Title><![CDATA[".$contentArr['articles'][$i]['Title']."]]></Title>";
                         $articlesStr .= "<Description><![CDATA[".$contentArr['articles'][$i]['Description']."]]></Description>";
                         $articlesStr .= "<PicUrl><![CDATA[".$contentArr['articles'][$i]['PicUrl']."]]></PicUrl>";
                         $articlesStr .= "<Url><![CDATA[".$contentArr['articles'][$i]['Url']."]]></Url></item>";
                     } 
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $articleCount, $articlesStr);
                    echo $resultStr;
                }
            }
            //语音消息
            elseif($msgType=="voice"){
                $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>"; 
                $content = "语音消息我们是听不到的哦。\n所以无法回复语音消息，给您造成的不便敬请谅解。"; 
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $content);
                echo $resultStr;
            }
            //事件推送
            elseif($msgType=="event"){
                $event = trim($postObj->Event);
                $eventKey = trim($postObj->EventKey);
                //用户关注事件
                if($event=="subscribe"){
                    $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>";  
                    $content = "感谢关注膜法世家服务号。马上签到即可获得更多积分哦~\n回复【1】即可签到\n回复【2】进入【幸运抽奖】\n回复【3】进入微社区晒单，抽奶皮面膜！";
                    require_once('user.php');
                    adduser($fromUsername);
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $content);
                    echo $resultStr;
                }
                //自定义菜单点击事件
                elseif($event=="CLICK"){
                    require_once('click.php');
                    $contentArr = eventResponse($fromUsername,$eventKey);
                    //文字消息回复
                    if($contentArr['msgType']=="text"){
                        $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";  
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentArr['content']);
                        echo $resultStr;
                    }
                    //图文消息回复
                    elseif($contentArr['msgType']=="news"){
                        $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[news]]></MsgType>
                            <ArticleCount>%s</ArticleCount>
                            <Articles>%s</Articles>
                            </xml>"; 
                        $articleCount = count($contentArr['articles']);
                        $articlesStr = "";
                        for ($i=0; $i < $articleCount; $i++) { 
                             $articlesStr .= "<item><Title><![CDATA[".$contentArr['articles'][$i]['Title']."]]></Title>";
                             $articlesStr .= "<Description><![CDATA[".$contentArr['articles'][$i]['Description']."]]></Description>";
                             $articlesStr .= "<PicUrl><![CDATA[".$contentArr['articles'][$i]['PicUrl']."]]></PicUrl>";
                             $articlesStr .= "<Url><![CDATA[".$contentArr['articles'][$i]['Url']."]]></Url></item>";
                         } 
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $articleCount, $articlesStr);
                        echo $resultStr;
                    }
                }
            	//其他事件
                else{
                    echo "";
                    exit;
                }
            }
            //其他消息
            else{
                echo "";
                exit;
            }
        }
        //如果没有接收到消息
        else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>