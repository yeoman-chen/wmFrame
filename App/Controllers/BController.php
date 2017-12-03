<?php
/**
 * 基类控制器
 *
 */
namespace App\Controllers;

class BController
{
    public static $code;
    public function __construct($code)
    {
        self::$code = $code;
    }
    /**
     * 返回成功
     * @param string $msg 提示信息
     * @param array $data 数据
     * @return array 返回数组
     */
    protected static function rspSuccess($data = null, $msg = "ok")
    {
        $res                  = ['code' => self::$code, 'status' => 1, 'message' => $msg];
        $data && $res['data'] = $data;
        return $res;
    }
    /**
     * 返回错误
     * @param string $errCode 错误code
     * @param string $errMsg 提示信息
     * @return array 返回数组
     */
    protected static function rspError($errCode, $errMsg = null)
    {        
        $res = ['code' => self::$code, 'status' => $errCode, 'message' => $errMsg];
        return $res;
    }
}
