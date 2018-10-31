<?php

/**
* 用于回复视频消息类型, 这个消息封装是个轻量级的，不包含标题和描述
*/
class Video_response_lite extends Wechat_response {

	protected $mediaId;

	protected $template = <<<XML
		<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[video]]></MsgType>
			<Video>
				<MediaId><![CDATA[%s]]></MediaId>
			</Video>
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
			$this->mediaId
		);
	}

}


?>