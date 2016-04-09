<?php
/**
  * 微信被动回复消息PHP后台url
  * 2016.4.9  by karlew
  */

//将weixin替换为公众号后台设置的token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapi();

//判断是token验证还是消息或事件推送
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapi
{
    //token验证
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
        	exit;
        }
    }
    //签名验证
    private function checkSignature()
    {
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];   
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // 按官方文档要求，需字典排序
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    //消息或事件回复
    public function responseMsg()
    {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		if (!empty($postStr)){
          	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $msgType = $postObj->MsgType;
            $time = time();
            
            //通过MsgType判断消息类型
            //文本消息             
			if($msgType=="text"){
                require_once('keyword.php');
                $keyword = trim($postObj->Content);
                $responseArr = keywordResponse($keyword,$fromUsername);
                //回复文本
                if($responseArr['msgType']=="text"){
                    if($responseArr['content']!="noreply"){
                        textResponse($fromUsername, $toUsername, $time, $responseArr['content'])
                    }
                    //无匹配关键字的情况
                    else{
                        echo "";
                        exit;
                    }
                }
                //图文消息回复
                elseif($responseArr['msgType']=="news"){
                    $articleCount = count($responseArr['articles']);
                    newsResponse($fromUsername, $toUsername, $time, $articleCount, $responseArr['articles'])
                }
            }
            //语音消息
            elseif($msgType=="voice"){
                $content = "语音消息我们是听不到的哦。\n所以无法回复语音消息，给您造成的不便敬请谅解。"; 
                textResponse($fromUsername, $toUsername, $time, $content)
            }
            //事件推送
            elseif($msgType=="event"){
                $event = trim($postObj->Event);
                $eventKey = trim($postObj->EventKey);
                //用户关注事件
                if($event=="subscribe"){
                    $content = "感谢关注XXXX。";
                    textResponse($fromUsername, $toUsername, $time, $content)
                }
                //自定义菜单点击事件
                elseif($event=="CLICK"){
                    require_once('click.php');
                    $responseArr = eventResponse($eventKey,$fromUsername);
                    //文字消息回复
                    if($responseArr['msgType']=="text"){
                        textResponse($fromUsername, $toUsername, $time, $responseArr['content'])
                    }
                    //图文消息回复
                    elseif($responseArr['msgType']=="news"){
                        $articleCount = count($responseArr['articles']);
                        newsResponse($fromUsername, $toUsername, $time, $articleCount, $responseArr['articles'])
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
    //文本消息回复
    public function textResponse($fromUsername, $toUsername, $time, $content){
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";  
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $content);
        echo $resultStr;
    }
    //回复图文消息
    public function newsResponse($fromUsername, $toUsername, $time, $articleCount, $articlesArr){
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <ArticleCount>%s</ArticleCount>
            <Articles>%s</Articles>
            </xml>"; 
        $articlesStr = "";
        for ($i=0; $i < $articleCount; $i++) { 
             $articlesStr .= "<item><Title><![CDATA[".$articlesArr[$i]['Title']."]]></Title>";
             $articlesStr .= "<Description><![CDATA[".$articlesArr[$i]['Description']."]]></Description>";
             $articlesStr .= "<PicUrl><![CDATA[".$articlesArr[$i]['PicUrl']."]]></PicUrl>";
             $articlesStr .= "<Url><![CDATA[".$articlesArr[$i]['Url']."]]></Url></item>";
         } 
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $articleCount, $articlesStr);
        echo $resultStr;
    }	
}

?>