<?php
/**
 * 基础Model
 */
namespace App\Models;

use \GatewayWorker\Lib\Db;

class Model
{
    //数据库实例
    protected $db;
    //数据表全称
    protected $tblName;
    //数据库配置实例名称
    protected $dbConf = "user";
    //数据表前缀
    protected $tblPrefix = "dq_";
    //数据表名称
    protected $tName = "user";

    //默认主键id,如果主键id不同,请在自己的模型类重定义该属性
    protected $primaryKey = 'id';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->db      = Db::instance("{$this->dbConf}");
        $this->tblName = $this->getTblName();
    }
    /**
     * 获取数据库实例
     */
    public function getDb()
    {
        return $this->db;
    }
    /**
     * 获取数据表名称
     */
    public function getTblName()
    {
        return $this->tblPrefix . $this->tName;
    }
    /**
     * 根据主键id获取一行信息
     * @param int $id 主键id
     * @param string $select 获取的字段
     * @return mixed $row 数组或false结果
     */
    public function getOne($id, $select = "*")
    {
        $row = Db::instance("{$this->dbConf}")->select("{$select}")->from("{$this->tblName}")->where("{$this->primaryKey} = :id")->bindValues(['id' => $id])->row();
        return $row;
    }
    /**
     * 根据查询条件获取一行
     * @param array $params 查询条件
     * @param string $select 获取的字段
     * @return mixed $row 数组或false结果
     */
    public function getRow($params = [],$select = '*')
    {
        $join   = (isset($params["join"]) && $params["join"]) ? $params["join"] : [];
        $where  = (isset($params["where"]) && $params["where"]) ? $params["where"] : null;
        $bind   = (isset($params["bind"]) && $params["bind"]) ? $params["bind"] : [];
        $query = $this->db->select("{$select}")->from("{$this->tblName}");

        if($join){
            foreach ($join as $value) {
                if($value[0] == 'leftJoin'){
                    $query->leftJoin($value[1], $value[2]);
                } elseif ($value[0] == "rightJoin") {
                    $query->rightJoin($value[1], $value[2]);
                } elseif ($value[0] == "innerJoin") {
                    $query->rightJoin($value[1], $value[2]);
                }
            }
        }
        $where && $query->where($where);
        $bind && $query->bindValues($bind);

        $row = $query->row();
        return $row;

    }
    
    /**
     * 获取列表
     * @param array $params 查询相关的数组
     * @param string $select 获取的字段
     * @return mixed $row 数组或false结果
     */
    public function getList($params = [], $select = '*')
    {
        $page   = isset($params["page"]) ? intval($params["page"]) : 1;
        $limit  = isset($params["limit"]) ? intval($params["limit"]) : 10;
        $offset = ($page - 1) * $limit;
        $order  = (isset($params["order"]) && $params["order"]) ? $params["order"] : [];
        $group  = (isset($params["group"]) && $params["group"]) ? $params["group"] : [];
        $join   = (isset($params["join"]) && $params["join"]) ? $params["join"] : [];
        $where  = (isset($params["where"]) && $params["where"]) ? $params["where"] : null;
        $bind   = (isset($params["bind"]) && $params["bind"]) ? $params["bind"] : [];
        $query  = $this->db->select("{$select}")->from("{$this->tblName}");
        //关联查询
        if ($join) {
            foreach ($join as $key => $value) {
                if ($value[0] == "leftJoin") {
                    $query->leftJoin($value[1], $value[2]);
                } elseif ($value[0] == "rightJoin") {
                    $query->rightJoin($value[1], $value[2]);
                } elseif ($value[0] == "innerJoin") {
                    $query->rightJoin($value[1], $value[2]);
                }
            }
        }
        $where && $query->where($where);
        $group && $query->groupBy($group);
        //排序
        if ($order) {
            foreach ($order as $key => $value) {
                strtolower($value[0]) == 'asc' ? $query->orderByASC($value[1]) : $query->orderByDESC($value[1]);
            }
        }
        $query->limit($limit)->offset($offset);

        $bind && $query->bindValues($bind);

        $list = $query->query();

        return $list;
    }
    /**
     * 获取列表
     * @param int $id 主键id
     * @param string $select 获取的字段
     * @return mixed $row 数组或false结果
     */
    public function getTotal($params = [], $select = 'count(*) as total')
    {
        $page   = isset($params["page"]) ? intval($params["page"]) : 1;
        $limit  = isset($params["limit"]) ? intval($params["limit"]) : 10;
        $offset = ($page - 1) * $limit;
        $order  = (isset($params["order"]) && !$params["order"]) ? $params["order"] : [];
        $group  = (isset($params["group"]) && !$params["group"]) ? $params["group"] : [];
        $join   = (isset($params["join"]) && !$params["join"]) ? $params["join"] : [];
        $where  = (isset($params["where"]) && !$params["where"]) ? $params["where"] : null;
        $bind   = (isset($params["bind"]) && !$params["bind"]) ? $params["bind"] : [];
        $query  = $this->db->select("{$select}")->from("{$this->tblName}");
        //关联查询
        if ($join) {
            foreach ($join as $key => $value) {
                if ($value[0] == "leftJoin") {
                    $query->leftJoin($value[1], $value[2]);
                } elseif ($value[0] == "rightJoin") {
                    $query->rightJoin($value[1], $value[2]);
                } elseif ($value[0] == "innerJoin") {
                    $query->rightJoin($value[1], $value[2]);
                }
            }
        }
        $where && $query->where($where);
        $group && $query->groupBy($group);

        $bind && $query->bindValues($bind);

        $res = $query->row();

        return $res["total"];
    }
    /**
     * 插入数据
     * @param array $data 插入的数据字段
     * @return mixed $lastId 插入的结果
     */
    public function addData($data)
    {
        return $this->db->insert("{$this->tblName}")->cols($data)->query();
    }
    /**
     * 更新数据todo
     * @param array $data 更新的数据数组
     * @param string $where 条件
     * @return mixed $res 删除结果
     */
    public function updateData($data, $where)
    {
        $sql = "update {$this->tblName} set ";
        foreach ($data as $key => $value) {
            $key != 0 && $sql .= ',';
            switch ($value[1]) {
                case '+':
                    $sql .= " {$value[0]} = {$value[0]} + {$value[2]}";
                    break;
                case '-':
                    $sql .= " {$value[0]} = {$value[0]} - {$value[2]}";
                    break;
                case '*':
                    $sql .= " {$value[0]} = {$value[0]} * {$value[2]}";
                    break;
                case '/':
                    $sql .= " {$value[0]} = {$value[0]} / {$value[2]}";
                    break;
                default:
                    $sql .= " {$value[0]} = {$value[2]} ";
                    break;
            }

         } 
        $sql .= " where ". $where;
        return $this->db->query($sql);

    }

    /**
     * 删除数据
     * @param int $id 主键key
     * @param string $where 条件
     * @return mixed $res 删除结果
     */
    public function delData($id)
    {
        return $this->db->delete("{$this->tblName}")->where("{$this->primaryKey} = :id")->bindValues(['id' => $id])->query();
    }
}
