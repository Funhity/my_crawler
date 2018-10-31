<?php
/**
* Redis 数据库访问类
**/

class RedisConnect {
	
	protected $host;
	protected $port;
	protected $auth;
    
    protected $linkID		=	array();	// 数据库连接ID 支持多个连接
    protected $_linkID		=	null;		// 当前连接ID
	protected $_redis		=	null;		// Redis Object
    protected $_keyname		=	null;		// Redis Key
    protected $_dbIndex		=	'';			// dbIndex	// 使用哪个数据库
    protected $_cursor		=	null;		// Reids Cursor Object
	
	// 构造函数：
    function __construct($cfg = null){
		if($cfg == null) {
			$CI =& get_instance();
			$cfg = $CI->config->item('redis');
		}

		$this->host = $cfg['host'];
		$this->port = $cfg['port'];
		$this->auth = $cfg['password'];
	}
	
	// 执行数据库连接，这个方法一般不直接调用，由本类的其它方法调用;
	// 这个$linkNum的作用是一个连接标识，这个类可以同时创建多个连接，以数组形式存放在$linkID中
	// 执行这个方法的时候是因为需要创建一个新的连接，与当前有没有连接没有多大关系，也不会影响连接
	// 执行这个方法就相当于需要往连接池里添加一个连接，但怎么用，不关这个方法的事；
    public function connect($linkNum=0) {
        if ( !isset($this->linkID[$linkNum]) ) {
            $redis = new Redis();
			$result=$redis->connect($this->host, $this->port,30);
			if($result!==true){
				return false;
			}
			if($this->auth){
				$result=$redis->auth($this->auth);
				if($result!==true){
					return false;
				}
			}
			$this->linkID[$linkNum] = $redis;
			/*
			$info=$redis->info();
            // 标记连接成功
            if (!empty($info["redis_version"]) || !empty($info["process_id"])){
            	$this->linkID[$linkNum] = $redis;
            	$this->connected    =   true;
            }
			*/
			
        }
		// 返回当前创建的连接
        return $this->linkID[$linkNum];
    }
	
	// 该方法用于设置当前使用的数据库连接，可以传入数据库ID来切换Redis数据库，
	// 默认Redis启动的时候启动了16个数据库，可以通过下面这个方法选择操作哪个数据库
    public function switchKey($db_index=0){
        // 当前连接属性为空， 则首先进行数据库连接
        if ( !$this->_linkID ){
			$this->_linkID = $this->connect();
		}
		
		if(empty($this->_linkID)){
			return false;
		}
		
        try{
              // 设置当前操作的数据库
              $this->_dbIndex  =  $db_index;
              $this->_redis = $this->_linkID->select($db_index);
        }catch (Exception $e){
			return false;
            //throw_exception($e->getMessage());
        }
        
		// 如果直接调用这个方法，会直接返回连接名柄，然后可以直接使用phpredis模块的方法操作redis
        return $this->_linkID;
    }
	// ==========================================================




	// ##########################################################
	// ################## Public API ############################
	/**
	 * 通用api: 删除元素
	 * @param $key
	 * @return int 返回移除变量的数量; key不存在时返回0
	 */
	public function del($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->del($key);
	}

	/**
	 * 通用api: 查找符合给定模式的key, key可使用正则表达式
	 * @param $key
	 * @return array 返回符合模式的key列表;
	 */
	public function keys($key){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->keys($key);
	}

	/**
	 * 通用api: 返回指定key的生成时间
	 * @param $key
	 * @return int 未指定生存时间时返回-1
	 */
	public function ttl($key){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->ttl($key);
	}

	/**
	 * 通用api: 判断传入的key是否存在
	 * @param $key
	 * @return int 若存在返回1，不存在返回0
	 */
	public function exists($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->exists($key);
	}

	/**
	 * 通用api: 返回指定key的类型
	 * @param $key
	 * @return int 返回类型状态码
	 * 	none(key不存在) int(0)
		string(字符串) int(1)
		list(列表) int(3)
		set(集合) int(2)
		zset(有序集) int(4)
		hash(哈希表) int(5)
	 */
	public function type($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->type($key);
	}

	/**
	 * 通用api: 设置变量的过期时间
	 * @param $key
	 * @param expire
	 * @return int 若存在返回1，不存在返回0
	 */
	public function expire($key, $expire){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->expire($key, $expire);
	}

	/**
	 * 通用api: 设置变量的过期时间: 传入unix时间戳
	 * @param $key
	 * @param $timestamp
	 * @return int 若存在返回1，不存在返回0
	 */
	public function expireat($key, $timestamp){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->expireat($key, $timestamp);
	}
	// ==========================================================



	
	// ##########################################################
	// ################## String API ############################
	/**
	 * String: 设置或更新key：如果key已经持有其他值，SET就覆写旧值，无视类型
	 * @param $key
	 * @param $value
	 * @return bool 永远返回true，因为这个操作不可能失败;
	 */
	public function set($key, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->set($key, $value);
	}

	/**
	 * String: 将key的值设为value，当且仅当key不存在
	 * @param $key
	 * @param $value
	 * @return bool 设置成功返回true，设置失败返回false
	 */
	public function setnx($key, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->setnx($key, $value);
	}


	public function setex($key, $second, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->setex($key, $second, $value);
	}
	
	/**
	 * String: SETBIT key offset value
	 *
	 * 对 key 所储存的字符串值，设置或清除指定偏移量上的位(bit)。
	 * 位的设置或清除取决于 value 参数，可以是 0 也可以是 1 。
	 * 当 key 不存在时，自动生成一个新的字符串值。
	 * 字符串会进行伸展(grown)以确保它可以将 value 保存在指定的偏移量上。当字符串值进行伸展时，空白位置以 0 填充。
	 * offset 参数必须大于或等于 0 ，小于 2^32 (bit 映射被限制在 512 MB 之内)。
	 *
	 * @param $key
	 * @param $offset
	 * @param $value
	 * @return bool
	 */
	public function setbit($key, $offset, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->setbit($key, $offset, $value);
	}
	
	/**
	 * String: BITCOUNT key [start] [end]
	 *
	 * 计算给定字符串中，被设置为 1 的比特位的数量。
	 * 一般情况下，给定的整个字符串都会被进行计数，通过指定额外的 start 或 end 参数，可以让计数只在特定的位上进行。
	 * start 和 end 参数的设置和 GETRANGE 命令类似，都可以使用负数值： 比如 -1 表示最后一个字节， -2 表示倒数第二个字节，以此类推。
	 * 不存在的 key 被当成是空字符串来处理，因此对一个不存在的 key 进行 BITCOUNT 操作，结果为 0 。
	 *
	 * @param $key
	 * @param $start
	 * @param $end
	 * @return bool
	 */
	public function bitcount($key, $start=0, $end=-1) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->bitcount($key, $start, $end);
	}

	/**
	 * String: 以数组的形式设置多个key的值, 强制覆盖，永远返回true
	 * @param array $data
	 * @return bool 永远返回true
	 */
	public function mset($data){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->mset($data);
	}

	/**
	 * String: 以数组的形式设置多个key的值, 只有所传入的key都不存在时，才返回true,
	 * 只要有一个已经存在的key存在，返回false, 因为是原子操作，此时所有元素不插入；
	 * @param array $data
	 * @return bool 永远返回true
	 * MSETNX是原子性的，因此它可以用作设置多个不同key表示不同字段(field)的
	 * 唯一性逻辑对象(unique logic object)，所有字段要么全被设置，要么全不被设置。
	 */
	public function msetnx($data){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->msetnx($data);
	}


	/**
	 * String: 把传入的值附加到key值的后面，如果key不存在，相当于简单的set操作;
	 * @param $key
	 * @param $append
	 * @return bool 设置成功返回true，设置失败返回false
	 */
	public function append($key, $append) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->append($key, $append);
	}


	/**
	 * String: 查询key值
	 * @param string $key
	 * @return bool 如果key不存在或者说类型不是string，返回false;
	 */
	public function get($key){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->get($key);
	}
	
	/**
	 * String: GETBIT key offset
	 * 
	 * 对 key 所储存的字符串值，获取指定偏移量上的位(bit)。
	 * 
	 * @param string $key
	 * @return bool 当 offset 比字符串值的长度大，或者 key 不存在时，返回 0 。
	 */
	public function getbit($key, $offset){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->getbit($key, $offset);
	}

	/**
	 * 返回所有给定Key值的数组;
	 * @param $keyArray
	 * @return array 返回值永远是数组，如果某个指定的key不存在，那么数据中的该item值为false，不影响其它字段
	 * 如果给一个string类型的key设置一个bool值，那么redis对于false存储的是空字符串，对true存储的是1;
	 */
	public function mget($keyArray) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->mget($keyArray);
	}

	/**
	 * 将给定key的值设为value，并返回key的旧值
	 * @param $key
	 * @param $value
	 * @return bool 当key存在但不是字符串类型时，返回false
	 */
	public function getset($key, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->getset($key, $value);
	}

	/**
	 * 将key中存储的数字值加1，值限制64位长
	 * @param $keyArray
	 * @return int 如果key不存在，那么设置key为0然后执行加1，返回1; 如果key不是值为数字字符串类型，返回false;
	 */
	public function incr($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->incr($key);
	}

	/**
	 * 将key中存储的数字值加n，值限制64位长
	 * @param $keyArray
	 * @return int 如果key不存在，那么设置key为0然后执行加n，返回n; 如果key不是值为数字字符串类型，返回false;
	 */
	public function incrby($key, $num) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->incrby($key, $num);
	}

	/**
	 * 將key中存儲的數字值減1
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function decr($key){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->decr($key);
	}

	/**
	 * 將key中存儲的數字值減量num
	 * @param  [type] $key [description]
	 * @param  [type] $num [description]
	 * @return [type]      [description]
	 */
	public function decrBy($key, $num){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->decrby($key, $num);
	}
	// ==========================================================




	// ##########################################################
	// ################## Hash API ##############################
	/**
	 * Hash: 设置一个hash的某个字段的值，如果key不存在，则新建一个，如果field已存在，则覆盖
	 * @param $key
	 * @param $field
	 * @param $value
	 * @return int 注意：这里只有对应的field不存在时，设置成功才返回1；如果key已存在，设置成功那么也是返回0的；
	 */
	public function hset($key, $field, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->hset($key, $field, $value);
	}

	/**
	 * Hash: 设置一个hash的某个字段的值，如果key不存在，则新建一个，如果field已存在，操作无效
	 * @param $key
	 * @param $field
	 * @param $value
	 * @return int 操作成功返回1, 操作失败返回false；
	 */
	public function hsetnx($key, $field, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->hsetnx($key, $field, $value);
	}

	/**
	 * Hash: 将一个数组设置到一个hash表中
	 * 如果key已经存在，该操作传入已有的field会执行覆盖，未传入的field会保持原样
	 * 也就是说这里执行的不是删除后重新写入，而是针对每个field进行写入的;
	 * @param $key string
	 * @param $dataArray array
	 * @return bool 如果key已存在并且不是hash类型，返回false，其它情况返回true；
	 */
	public function hmset($key, $dataArray) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->hmset($key, $dataArray);
	}

	/**
	 * Hash: 获取hash中某个field的值
	 * @param $key
	 * @param $field
	 * @return bool 当给定域不存在或是给定key不存在时，返回false
	 */
	public function hget($key, $field) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->hget($key, $field);
	}

	/**
	 * Hash: 返回哈希表key中，一个或多个给定域的值
	 * @param $key
	 * @param $fieldArray
	 * @return array|bool 返回传入field的数组数据，如果field不存在，那么该field返回值为false
	 * 					 （key不存在，也会返回一个所有field值为false的数组）
	 * 					  但如果key不是hash类型，那么会返回false；
	 */
	public function hmget($key, $fieldArray) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->hmget($key, $fieldArray);
	}

	/**
	 * Hash: 返回哈希表key中，所有的域和值
	 * @param $key
	 * @return array|bool 如果key不存在，那么返回空列表
	 * 				如果key不是hash类型，那么返回false
	 */
	public function hgetall($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->hgetall($key);
	}
	
	/**
	 * Hash: 用来检查哈希字段是否存在。
	 * @param $key
	 * @param $field 字段
	 * @return 整数，1或0。
	 */
	public function hexists($key,$field) {
	    $dblink = $this->switchKey();
	    if($dblink == false){
	        return false;
	    }
	    return $dblink->hExists($key,$field);
	}

	/**
	 * Hash: 为哈希表key中的域field的值加上增量increment
	 * @param $key
	 * @return bool|int key或field类型错误将返回false, 其它情况返回incr后的值
	 * 增量也可以为负数，相当于对给定域进行减法操作。
	 * 如果key不存在，一个新的哈希表被创建并执行HINCRBY命令。
	 * 如果域field不存在，那么在执行命令前，域的值被初始化为0，然后执行hincrby命令。
	 * 对一个储存字符串值的域field执行HINCRBY命令将造成一个错误，返回false。
	 */
	public function hincrby($key, $field, $inc) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->hincrby($key, $field, $inc);
	}

	public function hdel($key, $field){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->hdel($key, $field);
	}
	// ==========================================================




	// ##########################################################
	// ################## List API ##############################
	/**
	 * 将一个数组的值插入到列表key的表头：数组item值必须是一个字符串，不能是多维数组，否则插入的就是一个'Array'字符串
	 * @param $key
	 * @param $value string
	 * @return bool|int 如果key存在且不是一个列表类型，那么返回false, 其它情况返回完成后列表的长度
	 */
	public function lpush($key, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->lpush($key, $value);
		return $ret;
	}

	/**
	 * 将值value插入到列表key的表头，当且仅当key存在并且是一个列表
	 * @param $key
	 * @param $value
	 * @return int|null 如果key存在且不是一个列表类型，那么返回false
	 * 					如果key已存在，那么不执行操作，返回0,
	 * 					如果key不存在，那么返回列表的长度;
	 */
	public function lpushx($key, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->lpushx($key, $value);
		return $ret;
	}

	/**
	 * 将一个数组的值插入到列表key的表尾：数组item值必须是一个字符串，不能是多维数组，否则插入的就是一个'Array'字符串
	 * @param $key
	 * @param $value string 数组前面的值会先插入，也就是说完成后数组最后一个元素在表尾
	 * @return bool|int 如果key存在且不是一个列表类型，那么返回false, 其它情况返回完成后列表的长度
	 */
	public function rpush($key, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->rpush($key, $value);
		return $ret;
	}

	/**
	 * 将值value插入到列表key的表尾，当且仅当key存在并且是一个列表
	 * @param $key
	 * @param $value
	 * @return int|null 如果key存在且不是一个列表类型，那么返回false
	 * 					如果key已存在，那么不执行操作，返回0,
	 * 					如果key不存在，那么返回列表的长度;
	 */
	public function rpushx($key, $value) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->rpushx($key, $value);
		return $ret;
	}

	/**
	 * 移除并返回列表key的表头元素
	 * @param $key
	 * @return bool|string 如果key不存在或不是list类型，返回false; 成功时返回元素值;
	 */
	public function lpop($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->lpop($key);
	}

	/**
	 * 移除并返回列表key的表尾元素
	 * @param $key
	 * @return bool|string 如果key不存在或不是list类型，返回false; 成功时返回元素值;
	 */
	public function rpop($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->rpop($key);
	}

	/**
	 * 阻塞版本的移除并返回列表key的表头元素
	 * @param $key
	 * @return bool|array 如果key不是list类型，返回false;
	 * 				如果key不存在或空列表（list为空后实际上就是删除了该key），那么会阻塞等待，直到超时或有插入的元素可弹出
	 * 				成功弹出时返回值为一个2个item的数组，数组中第一个元素为key, 第二个元素为弹出的值。
	 * 				如果设置了超时，超时后还没有可弹出的元素，那么返回一个空数组；
	 */
	public function blpop($key, $timeout=0) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->blpop($key, $timeout);
	}

	/**
	 * 阻塞版本的移除并返回列表key的表尾元素
	 * @param $key
	 * @return bool|array 如果key不是list类型，返回false;
	 * 				如果key不存在或空列表（list为空后实际上就是删除了该key），那么会阻塞等待，直到超时或有插入的元素可弹出
	 * 				成功弹出时返回值为一个2个item的数组，数组中第一个元素为key, 第二个元素为弹出的值。
	 * 				如果设置了超时，超时后还没有可弹出的元素，那么返回一个空数组；
	 */
	public function brpop($key, $timeout=0) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->blpop($key, $timeout);
	}

	/**
	 * 返回list的长度
	 * @param $key
	 * @return bool|int key不存在为解释为一个空列表，返回0，key类型错误时返回false;
	 */
	public function llen($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->llen($key);
	}


	/**
	 * List: 返回列表key中指定区间内的元素，区间以偏移量start和stop指定(不会执行移除)
	 * 下标(index)参数start和stop都以0为底，也就是说，以0表示列表的第一个元素，以1表示列表的第二个元素
	 * 要注意区分stop和start都是从0开始的, 并且是闭合区间[start, stop], [10, 20]代表第10到第20个元素共11个元素;
	 * @param $key
	 * @param $start int 从下标0开始
	 * @param $stop int 从下标0开始
	 * @return array 类型错误将返回false, key不存在，区间超范围时都返回空列表;
	 */
	public function lrange($key, $start, $stop) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->lrange($key, $start, $stop);
	}

	/**
	 * List: 对一个列表进行修剪(trim)，就是说，让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除
	 * @param $key
	 * @param $start int 也可以使用负数下标，以-1表示列表的最后一个元素，-2表示列表的倒数第二个元素
	 * @param $stop int
	 * @return bool 类型错误返回false, 执行成功时返回true;
	 * 这个命令可以与lpush/rpush命令，实现保存最近n条记录的功能，如实时日志输出
	 */
	public function ltrim($key, $start, $stop){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->ltrim($key, $start, $stop);
		return $ret;
	}

	/**
	 * List: 返回指定下标的元素：
	 * @param $key
	 * @param $index int index是从0开始的下标值
	 * @return bool|string 如果下标元素不存在，那么返回false;
	 */
	public function lindex($key, $index){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->lindex($key, $index);
		return $ret;
	}

	/**
	 * 将值value插入到列表key当中，位于值pivot之前或之后
	 * 当pivot不存在于列表key时，不执行任何操作
	 * 当key不存在时，key被视为空列表，不执行任何操作
	 * @param $key
	 * @param $position
	 * @param $pivot
	 * @param $value
	 * @return bool|int 执行成功返回列表的长度，如果key不是列表类型，返回一个错误
	 * 使用这个函数，可用来实现拖动排序（先移除，然后再插入到指定元素之前或之后）
	 */
	public function linsert($key, $position, $pivot, $value){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		if($pivot != 'BEFORE' && $pivot != 'AFTER') return false;
		$ret = $dblink->linsert($key, $position, $pivot, $value);
		return $ret;
	}


	/**
	 * List: 在一个原子时间内，执行以下两个动作：
	 * 将列表source中的最后一个元素(尾元素)弹出，并返回给客户端。
	 * 将source弹出的元素插入到列表destination，作为destination列表的的头元素。
	 * @param $source string 如果source不存在或类型错误，那么返回false；
	 * @param $destination string 如果destination不存在则创建一个，如果类型错误，那么返回false；
	 * @return bool|string: 执行成功时返回被转移的元素;
	 * 如果source和destination为同一个列表，就可以实现列表的旋转(rotation)操作
	 * 这个逻辑还可以用来实现生产者与消费者的逻辑; source相当于任务队列, destination相当于处理中队列（备份）
	 */
	public function rpoplpush($source, $destination){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->rpoplpush($source, $destination);
	}

	/**
	 * rpoplpush操作的阻塞版本
	 */
	public function brpoplpush($source, $destination, $timeout){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->brpoplpush($source, $destination, $timeout);
	}
	// ==========================================================



	// ##########################################################
	// ################## Sets API ############################
	// Sets: 为集合中添加元素：集合不存在则创建新的集合
	/**
	 * Set: 往集合中添加元素
	 * @param $key
	 * @param $member
	 * @return bool|int 类型错误返回false, 其它情况返回成功添加member的个数，member已存在的返回0
	 */
	public function sadd($key, $member){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->sadd($key, $member);
		return $ret;
	}

	/**
	 * Set: 移除集合key中的一个或多个member元素，不存在的member元素会被忽略
	 * @param $key
	 * @param $member string 目前接口仅支持一个member
	 * @return bool 类型错误时返回false, 执行成功返回移除member的数量，不存在的member直接忽略;
	 */
	public function srem($key, $member) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->sRem($key, $member);
		return $ret;
	}
	
	/**
	 * Set: 返回集合中的一个随机元素
	 * @param $key
	 * @param $count  返回几个member，默认1个
	 */
	public function srandmember($key, $count=1) {
	    $dblink = $this->switchKey();
	    if($dblink == false){
	        return false;
	    }
	    if ($count>1){
	        //返回数组
	        return $dblink->sRandMember($key, $count);
	    }else{
	        //直接返回元素
	        return $dblink->sRandMember($key);
	    }
	}

	/**
	 * Set: 返回集合中所有的成员:
	 * @param $key
	 * @return array
	 */
	public function smembers($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->smembers($key);
		return $ret;
	}

	/**
	 * Sets: 判断某个元素是否在某个集合内:
	 * @param $set
	 * @param $member
	 * @return bool 返回true/false
	 */
	public function sismember($set, $member) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->sismember($set, $member);
		return $ret;
	}

	/**
	 * Sets: 返回集合中成员数量:
	 * @param $key
	 * @return int|bool
	 */
	public function scard($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->scard($key);
		return $ret;
	}

	/**
	 * Sets: 随机弹出集合中的一个元素:
	 * @param $key
	 * @return int|bool
	 */
	public function spop($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->spop($key);
		return $ret;
	}


	/**
	 * 返回多个集合的并集:
	 * @param $set1
	 * @param $set2
	 * @param null $set3
	 * @param null $set4
	 * @return bool
	 */
	public function sets_sUnion($set1, $set2, $set3=null, $set4 = null) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		if($set3 == null) {
			return $dblink->sUnion($set1, $set2);
		} else if($set4 == null) {
			return $dblink->sUnion($set1, $set2, $set3);
		} else {
			return $dblink->sUnion($set1, $set2, $set3, $set4);
		}
	}

	/**
	 * 返回多个集合的交集:
	 * @param $set1
	 * @param $set2
	 * @param null $set3
	 * @param null $set4
	 * @return bool
	 */
	public function sets_sDiff($set1, $set2, $set3=null, $set4 = null) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		if($set3 == null) {
			return $dblink->sDiff($set1, $set2);
		} else if($set4 == null) {
			return $dblink->sDiff($set1, $set2, $set3);
		} else {
			return $dblink->sDiff($set1, $set2, $set3, $set4);
		}
	}
	// ==========================================================


	
	// ##########################################################
	// ################## ZSet API ############################
	/**
	 * Zset: 为集合中添加元素：集合不存在则创建新的集合
	 * @param $key
	 * @param $score string zset是按score排序的, 这个值必须是可数字化的，如果传入非数字字符，那么将设置值为0
	 * @param $value string 这个相当于是名字, member
	 * @return bool|int 返回添加成功成员的数量，更新成员时返回0;
	 */
	public function zadd($key, $score, $value){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zadd($key, $score, $value);
		return $ret;
	}

	/**
	 * Zset: 移除集合中的元素:
	 * @param $key
	 * @param $member
	 * @return bool
	 */
	public function zrem($key, $member) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zrem($key, $member);
		return $ret;
	}

	/**
	 * Zset: 返回有序集key中，成员member的score值。
	 * @param $key
	 * @param $member
	 * @return bool
	 */
	public function zscore($key, $member) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zscore($key, $member);
		return $ret;
	}

	/**
	 * Zset: 返回集合元素数量
	 * @param $key
	 * @return bool|int
	 */
	public function zcard($key) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zcard($key);
		return $ret;
	}


	/**
	 * Zset: 为有序集key的成员member的score值加上增量increment
	 * @param $key
	 * @return bool|int 执行成功，返回操作完成后的新score值;
	 */
	public function zincrby($key, $incr, $member) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zincrby($key, $incr, $member);
		return $ret;
	}


	/**
	 * Zset: 返回有序集key中，指定区间内的成员, 按score从小到大排
	 * @param $key
	 * @return bool|int 执行成功，返回操作完成后的新score值;
	 */
	public function zrange($key, $start, $stop, $withScore=true) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zrange($key, $start, $stop, $withScore);
		return $ret;
	}


	/**
	 * Zset: 返回有序集key中，指定区间内的成员：按score从大到小排
	 * @param $key
	 * @return bool|int 执行成功，返回操作完成后的新score值;
	 */
	public function zrevrange($key, $start, $stop, $withScore=true) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zrevrange($key, $start, $stop, $withScore);
		return $ret;
	}
	
	/**
	 * Zset: 返回有序集key中，指定区间内的成员：按score从大到小排
	 * @param $key
	 * @return bool|int 执行成功，返回操作完成后的新score值;
	 */
	public function zrevrangebyscore($key, $max, $min, $otherArr=array()) {
	    $dblink = $this->switchKey();
	    if($dblink == false){
	        return false;
	    }
	    return $dblink->zrevrangebyscore($key, $max, $min, $otherArr);
	}
	
	/**
	 * Zset: 返回有序集key中，指定区间内的成员：按score从小到大排
	 * @param $key
	 * @return bool|int 执行成功，返回操作完成后的新score值;
	 */
	public function zrangebyscore($key, $min,$max, $otherArr=array()) {
	    $dblink = $this->switchKey();
	    if($dblink == false){
	        return false;
	    }
	    return $dblink->zrangebyscore($key, $min,$max , $otherArr);
	}


	/**
	 * Zset:返回有序集 key 中成员 member 的排名。其中有序集成员按 score 值递减(从大到小)排序。
	 * 排名以 0 为底，也就是说， score 值最大的成员排名为 0 。
	 * 使用 ZRANK 命令可以获得成员按 score 值递增(从小到大)排列的排名。
	 * 
	 * @param string $key
	 * @param string $member
	 * @return mixed 如果 member 是有序集 key 的成员，返回 member 的排名。如果 member 不是有序集 key 的成员，返回 nil 。
	 */
	public function zrevrank($key, $member) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zrevrank($key, $member);
		return $ret;
	}
	
	/**
	 * Zset:移除有序集 key 中，所有 score 值介于 min 和 max 之间(包括等于 min 或 max )的成员
	 *
	 * @param string $key
	 * @param int $min
	 * @param int $max
	 * @return
	 */
	public function zremrangebyscore($key, $min, $max) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zremrangebyscore($key, $min, $max);
		return $ret;
	}
	
	/**
	 * Zset:返回有序集 key 中， score 值在 min 和 max 之间(默认包括 score 值等于 min 或 max )的成员的数量
	 *
	 *	ZCOUNT key min max
	 *
	 * @param string $key
	 * @param int $min
	 * @param int $max
	 * @return
	 */
	public function zcount($key, $min, $max) {
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->zcount($key, $min, $max);
		return $ret;
	}
	
	// ==========================================================
	

	// ##########################################################
	// ################## Transaction API ############################
	// Sets: 为集合中添加元素：集合不存在则创建新的集合
	/**
	 * Set: 监控一个或多个键
	 * @param $key
	 * @return string 返回OK
	 */
	 public function watch($key){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->watch($key);
		return $ret;
	}

	/**
	 * Set: 开启事务
	 * @param $type 默认是 Redis::MULTI，其他值如 Redis::PIPELINE
	 * @return string 返回OK
	 */
	public function multi($type=''){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		empty($type) && $type = Redis::MULTI;
		$ret = $dblink->multi($type);
		return $ret;
	}

	/**
	 * Set: 执行事务语句
	 * @return 操作失败返回空值(false)
	 */
	public function exec(){
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		$ret = $dblink->exec();
		return $ret;
	}

	 

	// ==========================================================
	
	
	// 释放查询结果
    public function free() {
        $this->_cursor = null;
    }

    // 关闭数据库
    public function close() {
        if($this->_linkID) {
            $this->_linkID->close();
            $this->_linkID = null;
			$this->linkID = null;
            $this->_redis = null;
            $this->_keyname =  null;
            $this->_cursor = null;
        }
    }

	/**
	 * @param $key
	 * @param $member
	 * @return bool
	 */
	public function zrank($key, $member) {
		if(empty($key)||empty($member)){
			return false;
		}
		$dblink = $this->switchKey();
		if($dblink == false){
			return false;
		}
		return $dblink->zrank($key, $member);
	}

}
?>
