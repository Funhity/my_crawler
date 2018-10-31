<?php

/**
* 用于回复视频消息类型
*/
class Video_response extends Wechat_response {

	protected $mediaId;
	protected $title;
	protected $description;

	protected $template = <<<XML
		<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[video]]></MsgType>
			<Video>
				<MediaId><![CDATA[%s]]></MediaId>
				<Title><![CDATA[%s]]></Title>
				<Description><![CDATA[%s]]></Description>
			</Video>
		</xml>
XML;

	public function __construct($toUserName, $fromUserName, $mediaId, $title, $description) {
		parent::__construct($toUserName, $fromUserName, $funcFlag);
		$this->mediaId = $mediaId;
		$this->title = $title;
		$this->description = $description;
	}

	public function __toString() {
		return sprintf($this->template,
			$this->toUserName,
			$this->fromUserName,
			time(),
			$this->mediaId,
			$this->title,
			$this->description
		);
	}

}


?>