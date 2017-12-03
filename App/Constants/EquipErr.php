<?php
/**
 * 设备模块相关错误提示码
 *
 */
namespace App\Constants;

class EquipErr
{
    /**
     * 系统
     */
    const SYSTEM_BUSY = -1;
    const NOT_LOGIN   = -2;
    const PARAM_ERROR = -3;
    //用户
    const INFO_EXIST       = -100101;
    const INFO_NOT_EXIST   = -100102;
    const INFO_CREATE_FAIL = -100103;
    const INFO_UPDATE_FAIL = -100104;
    const INFO_DELETE_FAIL = -100105;

 

    /**
     * 错误消息
     */
    const MSG = [
        self::SYSTEM_BUSY         => '系统繁忙',
        self::NOT_LOGIN           => '没有登录',
        self::PARAM_ERROR         => '参数错误',

        self::INFO_EXIST          => '设备已存在',
        self::INFO_NOT_EXIST      => '设备不存在',
        self::INFO_CREATE_FAIL    => '设备创建失败',
        self::INFO_UPDATE_FAIL    => '设备信息更新失败',
        self::INFO_DELETE_FAIL    => '设备删除失败',

    ];
}
