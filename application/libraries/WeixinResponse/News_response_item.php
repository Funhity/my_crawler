<?php

/**
* 单条图文消息类型
*/
class News_response_item {

	protected $title;
	protected $description;
	protected $picUrl;
	protected $url;

	protected $template = <<<XML
		<item>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
		</item>
XML;

	public function __construct($title, $description, $picUrl, $url) {
		$this->title = $title;
		$this->description = $description;
		$this->picUrl = $picUrl;
		$this->url = $url;
	}

	public function __toString() {
		return sprintf($this->template,
			$this->title,
			$this->description,
			$this->picUrl,
			$this->url
		);
	}
}


?>