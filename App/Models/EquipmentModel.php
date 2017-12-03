<?php
/**
 * 设备Model
 */
namespace App\Models;

class EquipmentModel extends Model
{
	//数据表名称
    protected $tName = "equipment";
    
    //默认主键id,如果主键id不同,请在自己的模型类重定义该属性
    protected $primaryKey = 'id'; 
} 