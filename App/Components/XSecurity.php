<?php
/**
 * 安全加密解密类
 */

namespace App\Components;

use App\Libs\STD3Des;
use App\Configs\Env;

class XSecurity {

	/**
     * 使用STD3Des类加密字符串
     * @param string $str 加密字符串
     * @param string $keyPrefix 密钥类型
     * @return string
     */
    public static function apiSTD3Encrypt($str, $keyPrefix = 'keyConfig')
    {
        $key         = Env::${$keyPrefix}["key"];
        $iv          = Env::${$keyPrefix}["iv"];
        $des         = new STD3Des(base64_encode($key), base64_encode($iv));
        $securityStr = urlencode($des->encrypt($str));
        return $securityStr;
    }

    /**
     * 解密STD3Des类加密字符串
     * @param string $securityStr 加密字符串
     * @param string $keyPrefix 密钥类型
     * @return string
     */
    public static function apiSTD3Decrypt($securityStr, $keyPrefix = 'keyConfig')
    {
        if (base64_decode($securityStr)) {
            $key         = Env::${$keyPrefix}["key"];
            $iv          = Env::${$keyPrefix}["iv"];
            $des      = new STD3Des(base64_encode($key), base64_encode($iv));
            $realyStr = $des->decrypt($securityStr);
            return $realyStr;
        } else {
            return '';
        }
    }

}