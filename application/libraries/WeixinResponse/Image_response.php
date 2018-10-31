<?php

/**
* 用于回复语音消息类型
*/

require_once 'Wechat_response.php';

class Image_response extends Wechat_response {

	protected $mediaId;

	protected $template = <<<XML
		<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[image]]></MsgType>
			<Image>
				<MediaId><![CDATA[%s]]></MediaId>
			</Image>
			<FuncFlag>%s</FuncFlag>
		</xml>
XML;

	public function __construct($toUserName, $fromUserName, $mediaId, $funcFlag = 0) {
		parent::__construct($toUserName, $fromUserName, $funcFlag);
		$this->mediaId = $mediaId;
	}

	public function __toString() {
		return sprintf($this->template,
			$this->toUserName,
			$this->fromUserName,
			time(),
			$this->mediaId,
			$this->funcFlag
		);
	}
}
