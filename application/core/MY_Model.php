<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 数据模型
 */
class MY_Model extends CI_Model {
    protected function loadModel($name) {
        $this->load->model($name);
    }
    
    /**
     * 布尔，是
     */
    const IS_YES = 1;
    /**
     * 布尔：否
     */
    const IS_NO =0;
    
    

    /**
     * 加载数据库管理
     */
    protected function initCreateTables(){
        //加载数据库管理, http://codeigniter.org.cn/user_guide/database/forge.html#id8
        $this->load->dbforge();
    }
    
    
    /**
     * 创建表格
     */
    protected function createMyTable($fields) {
        $this->initCreateTables();
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table($this->getMyTbName(), TRUE, $attributes);
    }
    
    
    public function __construct() {
        parent::__construct();
        //加载随机库
        $this->load->library('randomtool');
        //加载时间工具
        $this->load->library('timetool');
        //加载价格工具
        $this->load->library('pricetool');
        
        //加载字符串工具
        $this->load->library('stringtool');
    }

    
    /**
     * 查询符合条件的第一条记录
     * @param type $colname
     * @param type $val
     * @return type
     */
    public function getOneByCol($colname, $val) {
        $this->db->select('*');
        $this->db->where($colname, $val);
        $this->db->limit(1, 0);
        $this->db->from($this->getMyTbName());
        $query = $this->db->get();
        $list = $query->result();
        if (count($list) > 0) {
            return $list[0];
        } else {
            return null;
        }
    }
    
    
    /**
     * 尝试更新记录
     */
    public function tryUpdate($data) {
        try{
            $this->db->update($this->getMyTbName(), $data);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    
    /**
     * 尝试删除记录，返回是否操作成功
     */
    public function tryDelete() {
        try{
            $this->db->delete($this->getMyTbName());
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    
    /**
     * 查询第一条符合条件的记录
     * @return type
     */
    public function getFirstOne() {
        $this->db->limit(1, 0);
        $this->db->from($this->getMyTbName());
        $query = $this->db->get();
        $list = $query->result();
        if (count($list) > 0) {
            return $list[0];
        } else {
            return null;
        }
    }
    
    /**
     * 查询出所有符合条件的记录
     */
    public function getAll() {
        $this->db->from($this->getMyTbName());
        $query = $this->db->get();
        $list = $query->result();
        return $list;
    }
    
      
    /**
     * 根据编号获取信息
     */
    public function getById($id) {
        $this->db->select('*');
        $this->db->where($this->getIdName(), $id);
        $this->db->limit(1, 0);
        $this->db->from($this->getMyTbName());
        $query = $this->db->get();
        $list = $query->result();
        if (count($list) > 0) {
            return $list[0];
        } else {
            return null;
        }
    }
    
    
    /**
     * 插入数据，并且不返回数据
     */
    protected function insertNoReturn($data) {
        
        $this->db->insert($this->getMyTbName(), $data);
    }
    
    
    /**
     * 插入数据，返回是否成功
     */
    protected function tryInsert($data) {
        try{
            $this->db->insert($this->getMyTbName(), $data);
            $affectedRows = $this->db->affected_rows();
            if($affectedRows == 1) {
                return true;
            }
            return false;
        }catch(Exception $e) {
            return false;
        }
    }
    
    
    /**
     * 获取ID名称
     * @return string
     */
    private function getIdName() {
        return 'id';
    }
    
    /**
     * 获取表名
     */
    protected function getMyTbName() {
        $name = get_class($this).''; 
        $name = strtolower($name);
        return $name;
    }


    /**
     * 把一个数组映射到数据库表中的每一个字段，如果传入的数组的字段不存在，那么忽略掉;
     *  该方法只支持只有一个主键的表, 并且必须传入主键;
     * @return int: 影响的行数
     */
    protected  function updateWithArray($data) {
        $tableName = $this->getMyTbName();
        $fields = $this->db->field_data($tableName);
        $saveData = array();
        $primaryKey = null;         // 表的主键
//        pushlog('fields:', $fields);
        foreach ($fields as $field) {
            $fieldName = $field->name;
            if(array_key_exists($fieldName, $data)) {
                if($field->primary_key == 1 && $primaryKey == null) {        // 只检查第一个主键
                    $primaryKey = $fieldName;
                } else {
                    $saveData[$fieldName] = $data[$fieldName];
                }
            }
        }
        if($primaryKey == null) {
            pushlog('未传入主键，无法更新', $data);
            return 0;
        }
        $this->db->where($primaryKey, $data[$primaryKey]);
        $this->db->update($tableName, $saveData);
        return $this->db->affected_rows();
    }

}
