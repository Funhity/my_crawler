<?php

/**
* 用于回复的音乐消息类型
*/
class Music_response extends Wechat_response {

	protected $title;
	protected $description;
	protected $musicUrl;
	protected $hqMusicUrl;

	protected $template = <<<XML
		<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[music]]></MsgType>
			<Music>
				<Title><![CDATA[%s]]></Title>
				<Description><![CDATA[%s]]></Description>
				<MusicUrl><![CDATA[%s]]></MusicUrl>
				<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
			</Music>
			<FuncFlag>%s</FuncFlag>
		</xml>
XML;

	public function __construct($toUserName, $fromUserName, $title, $description, $musicUrl, $hqMusicUrl, $funcFlag) {
		parent::__construct($toUserName, $fromUserName, $funcFlag);
		$this->title = $title;
		$this->description = $description;
		$this->musicUrl = $musicUrl;
		$this->hqMusicUrl = $hqMusicUrl;
	}

	public function __toString() {
		return sprintf($this->template,
			$this->toUserName,
			$this->fromUserName,
			time(),
			$this->title,
			$this->description,
			$this->musicUrl,
			$this->hqMusicUrl,
			$this->funcFlag
		);
	}
}

