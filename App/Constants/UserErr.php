<?php
/**
 * 用户相关错误提示码
 *
 */
namespace App\Constants;

class UserErr
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

    const UNAME_PWD_NOT_EMPTY = -100111;
    const UNAME_FORMAT_WRONG  = -100112;
    const PWD_FORMAT_WRONG    = -100113;
    const TWICE_PWD_DIFF      = -100114;
    const UNAME_PWD_WRONG     = -100115;

    //用户设备
    const UEQUIP_EXIST       = -100201;
    const UEQUIP_NOT_EXIST   = -100202;
    const UEQUIP_CREATE_FAIL = -100203;
    const UEQUIP_UPDATE_FAIL = -100204;
    const UEQUIP_DELETE_FAIL = -100205;

    //用户背包
    const UBP_INFO_EXIST = -100301;
    const UBP_INFO_NOT_EXIST = -100302;
    const UBP_INFO_CREATE_FAIL = -100303;
    const UBP_INFO_UPDATE_FAIL = -100304;
    const UBP_INFO_DELETE_FAIL = -100305;

    const UBP_BALANCE_INSUFFICIENT =  -100310;


    /**
     * 错误消息
     */
    const MSG = [
        self::SYSTEM_BUSY         => '系统繁忙',
        self::NOT_LOGIN           => '没有登录',
        self::PARAM_ERROR         => '参数错误',

        self::INFO_EXIST          => '用户已存在',
        self::INFO_NOT_EXIST      => '用户不存在',
        self::INFO_CREATE_FAIL    => '用户注册失败',
        self::INFO_UPDATE_FAIL    => '用户信息更新失败',
        self::INFO_DELETE_FAIL    => '用户删除失败',

        self::UNAME_PWD_NOT_EMPTY => '账号或密码不能为空',
        self::UNAME_FORMAT_WRONG  => '账号格式不符',
        self::PWD_FORMAT_WRONG    => '密码格式不符',
        self::TWICE_PWD_DIFF      => '两次密码不一致',
        self::UNAME_PWD_WRONG      => '账号或密码错误',

        //用户设备
        self::UEQUIP_EXIST          => '用户设备已存在',
        self::UEQUIP_NOT_EXIST      => '用户设备不存在',
        self::UEQUIP_CREATE_FAIL    => '用户设备购买失败',
        self::UEQUIP_UPDATE_FAIL    => '用户设备信息更新失败',
        self::UEQUIP_DELETE_FAIL    => '用户设备删除失败',

        //用户背包
        self::UBP_INFO_EXIST          => '用户背包已存在',
        self::UBP_INFO_NOT_EXIST      => '用户背包不存在',
        self::UBP_INFO_CREATE_FAIL    => '用户背包创建失败',
        self::UBP_INFO_UPDATE_FAIL    => '用户背包信息更新失败',
        self::UBP_INFO_DELETE_FAIL    => '用户背包删除失败',

        self::UBP_BALANCE_INSUFFICIENT    => '余额不足',

    ];
}
