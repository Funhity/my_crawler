<?php

/**
* 用于回复微信服务器XML消息封装的基本消息类型
*/
abstract class Wechat_response {

	protected $toUserName;
	protected $fromUserName;
	protected $funcFlag;

	public function __construct($toUserName, $fromUserName, $funcFlag) {
	  $this->toUserName = $toUserName;
	  $this->fromUserName = $fromUserName;
	  $this->funcFlag = $funcFlag;
	}

	abstract public function __toString();

}
