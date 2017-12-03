<?php
/**
 * 用户背包model
 */
namespace App\Models;

class UserBackpackModel extends Model
{
	//数据表名称
    protected $tName = "user_backpack";
    
    //默认主键id,如果主键id不同,请在自己的模型类重定义该属性
    protected $primaryKey = 'id'; 
} 