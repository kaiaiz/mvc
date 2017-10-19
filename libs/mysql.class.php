<?php
class Mysql
{
    protected $db;//数据库连接资源
    protected $sql;//sql语句
    protected $option=array();//sql语句选项
    function __construct($config = array())
    {
        $host=isset($config['host'])?$config['host']:'localhost';
        $port=isset($config['port'])?$config['port']:'3306';
        $username=isset($config['username'])?$config['username']:'root';
        $username=isset($config['password'])?$config['password']:'root';
        $username=isset($config['dbname'])?$config['dbname']:'mysql';
        $this->option['table']=$this->option['where']=$this->option['order']=$this->option['limit']='';
        $this->option['field']=' * ';

        $this->connect($config);
        $this->setChar($charset = '');//数据库编码
    }
    protected function connect($config = array())
    {
          // 创建连接
        $this->db=new mysqli($config['database']['host'], $config['database']['username'], $config['database']['password'], $config['database']['dbname'], $config['database']['port']);
        //检测连接
        if ($this->db->connect_error) {
              die('数据库连接失败'.$this->error().'<br />');
        } else {
            echo '连接数据库成功<br />';
        }
    }
    protected function setChar($charset = 'utf8')
    {
        $this->db->set_charset($charset);
    }
    /**
     * 初始化sql语句
     * @access public
    */
    public function initSql()
    {
        $this->sql="select {$this->option['field']} from {$this->option['table']} {$this->option['where']} {$this->option['order']} {$this->option['limit']}";
    }
    /**
     * 判断sql语句是否为空时的操作
    */
    public function setSql($sql)
    {
        if (empty($sql)) {
            $this->initSql();
        } else {
            $this->sql=$sql;
        }
    }
    /**
     * 判断表名为空时的操作
    */
    public function setTbale($table)
    {
        if (empty($table)) {
              die('错误：表名不能为空!');
        }
    }
    /**
     * 释放结果集
     * @access public
    */
    public function free()
    {
        mysqli_free_result($this->db);
    }
    /**
     * 关闭数据库连接
    */
    protected function close()
    {
        mysqli_close($this->db);
    }
    /**
     * 获取错误信息
     * @access public
     * @return mysql错误信息
    */
    protected function error()
    {
        return mysqli_error($this->db);
    }
    
    /**
     * 设置表名
    */
    public function table($table = '')
    {
        if (empty($table)) {
            die('表名不能为空');
        } else {
            $this->option['table']=$table;
        }
            return $this;
    }
    /**
     * 指定查询字段
    */
    public function field($field = '*')
    {
        $this->option['field']=$field;
    }
    /**
     * 指定where
    */
    public function where($where = '')
    {
        $this->option['where']='where '.$where;
        return $this;
    }
    /**
     * 指定order
    */
    public function order($order = '')
    {
        $this->option['order']='order by '.$order;
        return this;
    }
    /**
     * 指定limit
    */
    public function limit($limit = '')
    {
        $this->option['limit']='limit '.$limit;
        return this;
    }
    /**
     * 执行sql语句
     * @access public
     * @param $sql string sql语句
     * @return $result object(statment) 查询返回结果集
    */
    public function query($sql = '')
    {
        $this->setSql($sql);
        $result=$this->db->query($this->sql);
        if (!$result) {
            die('执行查询语句错误'.$this->db->error.'<br />'.'出错语句为:'.$sql.'<br />');
        }
        return $result;
    }
    /**
     * 获取第一条记录
     * @access public
     * @param $sql string sql查询语句
     * @retuen $row array 返回第一行数据
    */
    public function getRow($sql = '')
    {
        $this->setSql($sql);
        $result=$this->query($this->sql);
        $row=$result->fetch_assoc();
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }
    /**
     * 获取第一条记录的第一个字段
     * @access public
     * @param $sql sql查询语句
     * @retuen $row string sql返回第一条记录的第一个字段
    */
    public function getOne($sql = '')
    {
        $this->setSql($sql);
        if ($result=$this->getRow($this->sql)) {
            $row=$result[$field];
            return $row;
        }
    }
    /**
     * 获取所有记录
     * @access public
     * @param $sql string sql查询语句
     * @return $rows array 关联数组
    */
    public function getAll($sql = '')
    {
        $this->setSql($sql);
        static $rows;
        if ($result=$this->query($this->sql)) {
            //获取数据
            $rows=mysqli_fetch_all($result, MYSQLI_ASSOC);
            //释放结果集
            mysqli_free_result($result);
        }
        return $rows;
    }
    /**
     * 返回查询的列数
     * @access public
     * @param $sql sql查询语句
     * @return int 返回查询结果的列数
    */
    public function getFieldCount($sql)
    {
        $this->setSql($sql);
        if ($result=$this->query($this->sql)) {
            return mysqli_field_count($this->db);
        }
    }
    /**
     * 返回表中的所有字段
     * @access public
     * @param string 传入的表名
     * @return $arr array 关联数组，返回所有字段
    */
    public function getField($table)
    {
        $this->setTbale($table);
        $this->sql='desc '.$table;
        global $arr;
        $arr=array();
        if ($result=$this->getAll($this->sql)) {
            foreach ($result as $key => $value) {
                if($value['Extra']=='auto_increment'){
                    continue;
                }
                $arr[]=$value['Field'];
            }
        }
        return $arr;
    }
    /**
     * 插入数据
    */
    public function insert($insertObj)
    {
        $insertObj=explode(',',$insertObj);
        $arr=$this->getField($this->option['table']);
        static $strValue;
        static $strField='';
        foreach ($arr as $key=>$value) {
            $strField.=' '.$value.',';
            $insertObj[$key]=empty($insertObj[$key])?'':$insertObj[$key];
        }
        $strField=substr($strField, 0, strlen($strField)-1);
        foreach ($insertObj as $key => $value) {
            $strValue.=' '."'$value',";
        }
        $strValue=substr($strValue, 0, strlen($strValue)-1);
        $this->sql="insert into {$this->option['table']} ({$strField}) values ({$strValue})";

        $this->query($this->sql);
    }
    /**
     * 删除数据
    */
}
