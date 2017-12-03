<?php
/**
 * 用户Model
 */
namespace App\Models;

class UserModel extends Model
{
	//数据表名称
    protected $tName = "user";
    
    //默认主键id,如果主键id不同,请在自己的模型类重定义该属性
    protected $primaryKey = 'userId';

    /**
     * 密码进行加密
     * @param $username
     * @param $password 密码明文
     * @return string
     */
    public function hashPassword($userName, $password)
    {
        return md5(md5($userName) . md5($password) . "@dq123");
    }
} 