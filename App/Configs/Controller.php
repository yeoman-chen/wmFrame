<?php
/**
 * 控制器的配置
 *
 */
namespace App\Configs;

class Controller
{
    public static $ctrInfo = [
        "user"          => "\\App\\Ms\\User\\Controllers\\UserController",
        "equipment"     => "\\App\\Ms\\Equip\\Controllers\\EquipmentController",
        "userEquipment" => "\\App\\Ms\\User\\Controllers\\UserEquipmentController",
        //用户
        "100101" => ["\\App\\Ms\\User\\Controllers\\UserController","register"],
        "100102" => ["\\App\\Ms\\User\\Controllers\\UserController","login"],
        "100103" => ["\\App\\Ms\\User\\Controllers\\UserController","uinfo"],
        //用户设备
        "100201" => ["\\App\\Ms\\User\\Controllers\\UserEquipmentController","getList"],
        "100202" => ["\\App\\Ms\\User\\Controllers\\UserEquipmentController","buy"],
        "100203" => ["\\App\\Ms\\User\\Controllers\\UserEquipmentController","upgrade"],

        //设备
        "110101" => ["\\App\\Ms\\Equip\\Controllers\\EquipmentController","getList"],
        "110102" => ["\\App\\Ms\\Equip\\Controllers\\EquipmentController","getDetail"],
    ];
}