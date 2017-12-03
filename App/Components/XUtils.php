<?php
/**
 * 工具类函数
 *
 */
namespace App\Components;

class XUtils {

    
    /**
     * 格式化单位
     * @param string $filesize 字节大小
     * @return string 返回大小
     */
    public static function byteFormat($filesize,$dec = 2)
    {
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $pos = 0;
        while ( $filesize >= 1024) {
            $filesize /= 1024;
            ++$pos;
        }
        return round($filesize,$dec).$unit[$pos];
    }
    /**
     * 打印函数
     * @param array $arr 需要输出的数组
     */
    public static function p($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
    /**
     * 循环创建目录
     * @param string $dir 需要输出的数组
     * @param string $mode 读写权限
     * @return boolean true|false
     */
    public static function mkdir($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) {
            return true;
        }

        if (!mk_dir(dirname($dir), $mode)) {
            return false;
        }

        return @mkdir($dir, $mode);
    }
    /**
     * 验证邮箱
     * @param string $email 邮箱地址
     * @return boolean true|false
     */
    public static function email($email)
    {
        if (empty($email)) {
            return true;
        }

        $chars = '/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}$/i';
        if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
            if (preg_match($chars, $email)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 验证手机号码
     * @param string $phone 手机号码
     * @return boolean true|false
     */
    public static function mobile($phone)
    {
        if (empty($phone)) {
            return false;
        }
        return preg_match('#^1[3-9][\d]{9}$#', $phone);
    }

    /**
     * 验证固定电话
     * @param string $telphone 电话号码
     * @return boolean true|false
     */
    public static function tel($telphone)
    {
        if (empty($str)) {
            return false;
        }
        return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/', trim($str));
    }

    /**
     * 验证qq号码
     * @param string $qq qq号码
     * @return boolean true|false
     */
    public static function qq($qq)
    {
        if (empty($qq)) {
            return false;
        }

        return preg_match('/^[1-9]\d{4,12}$/', trim($str));
    }

    /**
     * 验证邮政编码
     * @param string $code 邮政编码
     * @return boolean true|false
     */
    public static function zipCode($code)
    {
        if (empty($code)) {
            return false;
        }

        return preg_match('/^[1-9]\d{5}$/', trim($code));
    }

    /**
     * 验证ip
     * @param string $ip ip地址
     * @return boolean true|false
     */
    public static function ip($ip)
    {
        if (empty($ip)) {
            return false;
        }

        if (!preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $ip)) {
            return false;
        }

        $ip_array = explode('.', $ip);

        //真实的ip地址每个数字不能大于255（0-255）
        return ($ip_array[0] <= 255 && $ip_array[1] <= 255 && $ip_array[2] <= 255 && $ip_array[3] <= 255) ? true : false;
    }

    /**
     * 验证身份证(中国)
     * @param string $cardNo 身份证号码
     * @return boolean true|false
     */
    public static function idCard($cardNo)
    {
        $str = trim($cardNo);
        if (empty($str)) {
            return false;
        }

        if (preg_match('/^([0-9]{15}|[0-9]{17}[0-9a-z])$/i', $str)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证网址
     * @param string $url 网址
     * @return boolean true|false
     */
    public static function url($url)
    {
        if (empty($url)) {
            return true;
        }

        return preg_match('#(http|https|ftp|ftps)://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?#i', $url) ? true : false;
    }
    /**
     * 检测是否为英文或英文数字的组合
     * @param string $param 待验证参数
     * @return boolean true|false
     */
    public static function isEnglist($param)
    {
        if (!eregi('^[A-Z0-9]{1,26}$', $param)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 将自动判断网址是否加http://
     *
     * @param string $url 网址
     * @return  string 
     */
    public static function convertHttp($url)
    {
        if ($url == 'http://' || $url == '') {
            return '';
        }

        if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {
            $str = 'http://' . $url;
        } else {
            $str = $url;
        }

        return $str;
    }

    /**
     * 自动转换字符集 支持数组转换
     *
     * @param string $string 字符串
     * @param string $from 源格式
     * @param string $to 目标格式
     * @param string $string 返回数据
     */
    public static function autoCharset($string, $from = 'gbk', $to = 'utf-8')
    {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to   = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($string) || (is_scalar($string) && !is_string($string))) {
            //如果编码相同或者非字符串标量则不转换
            return $string;
        }
        if (is_string($string)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($string, $to, $from);
            } elseif (function_exists('iconv')) {
                return iconv($from, $to, $string);
            } else {
                return $string;
            }
        } elseif (is_array($string)) {
            foreach ($string as $key => $val) {
                $_key          = self::autoCharset($key, $from, $to);
                $string[$_key] = self::autoCharset($val, $from, $to);
                if ($key != $_key) {
                    unset($string[$key]);
                }
            }
            return $string;
        } else {
            return $string;
        }
    }

    /**
     * 获取微妙时间戳
     *
     * @param boolean $inms 
     */
    public static function utime($inms)
    {
        $utime = preg_match('/^(.*?) (.*?)$/', microtime(), $match);
        $utime = $match[2] + $match[1];
        if ($inms) {
            $utime *= 10000;
        }
        return substr($utime, 0, 14);
    }
    /**
     * 生成json字符串
     *
     * @param array arr 待转换的数组
     * @param null option 操作选项
     * @return  string 
     */
    public static function jsonEncode($arr,$option = null)
    {
        return $arr;
        return json_encode($arr,$option);
    }

}