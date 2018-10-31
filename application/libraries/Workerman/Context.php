<?php

/**
 * 上下文 包含当前用户uid， 内部通信local_ip local_port socket_id ，以及客户端client_ip client_port
 */
class Context
{
    /**
     * 内部通讯id
     * @var string
     */
    public static $local_ip;
    /**
     * 内部通讯端口
     * @var int
     */
    public static $local_port;
    /**
     * 客户端ip
     * @var string
     */
    public static $client_ip;
    /**
     * 客户端端口
     * @var int
     */
    public static $client_port;
    /**
     * client_id
     * @var string
     */
    public static $client_id;
    /**
     * 连接connection->id
     * @var int
     */
    public static $connection_id;

    /**
     * 编码session
     * @param mixed $session_data
     * @return string
     */
    public static function sessionEncode($session_data = '')
    {
        if($session_data !== '')
        {
            return serialize($session_data);
        }
        return '';
    }
    
    /**
     * 解码session
     * @param string $session_buffer
     * @return mixed
     */
    public static function sessionDecode($session_buffer)
    {
        return unserialize($session_buffer);
    }
    
    /**
     * 清除上下文
     * @return void
     */
    public static function clear()
    {
        self::$local_ip = self::$local_port  = self::$client_ip = self::$client_port = self::$client_id  = self::$connection_id = null;
    }
 
    /**
     * 通讯地址到client_id的转换
     * @return string
     */
    public static function addressToClientId($local_ip, $local_port, $connection_id)
    {
        return bin2hex(pack('NnN', $local_ip, $local_port, $connection_id));
    }

    /**
     * client_id到通讯地址的转换
     * @return array
     */
    public static function clientIdToAddress($client_id)
    {
        if(strlen($client_id) !== 20)
        {
            throw new \Exception("client_id $client_id is invalid");
        }
        return unpack('Nlocal_ip/nlocal_port/Nconnection_id' ,pack('H*', $client_id));
    }

}

/**
 * Gateway与Worker间通讯的二进制协议
 * 
 * struct GatewayProtocol
 * {
 *     unsigned int        pack_len,
 *     unsigned char       cmd,//命令字
 *     unsigned int        local_ip,
 *     unsigned short      local_port,
 *     unsigned int        client_ip,
 *     unsigned short      client_port,
 *     unsigned int        connection_id,
 *     unsigned char       flag,
 *     unsigned short      gateway_port,
 *     unsigned int        ext_len,
 *     char[ext_len]       ext_data,
 *     char[pack_length-HEAD_LEN] body//包体
 * }
 * NCNnNnNCnN
 */

?>