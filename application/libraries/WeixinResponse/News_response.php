<?php

/**
* 用于回复的图文消息类型
*/
class News_response extends Wechat_response {

	protected $items = array();

	protected $template = <<<XML
		<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>%s</ArticleCount>
			<Articles>
				%s
			</Articles>
			<FuncFlag>%s</FuncFlag>
		</xml>
XML;

	public function __construct($toUserName, $fromUserName, $items, $funcFlag) {
		parent::__construct($toUserName, $fromUserName, $funcFlag);
		$this->items = $items;
	}

	public function __toString() {
		return sprintf($this->template,
			$this->toUserName,
			$this->fromUserName,
			time(),
			count($this->items),
			implode($this->items),
			$this->funcFlag
		);
	}
}


?>