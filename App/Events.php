<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \App\Components\XUtils;
use \App\Configs\Controller as ctrConfig;
use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 新建一个类的静态成员，用来保存数据库实例
     */
    //public static $db = null;

    /**
     * 进程启动后初始化数据库连接
     */
    public static function onWorkerStart($worker)
    {
        //global $db;
        //$db = new Workerman\MySQL\Connection('127.0.0.1', '3306', 'root', '123456', 'users');
    }

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        $data = ["status" => 1, "data" => "Hello $client_id"];
        // 向当前client_id发送数据
        Gateway::sendToClient($client_id, $data);
        // 向所有人发送
        //Gateway::sendToAll("$client_id login\n");
    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($clientId, $message)
    {
        $_SESSION["userId"] = 18;
        $res = ["code" => null, "status" => 0, "message" => null];
        //gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}
        echo "[" . date("Y-m-d H:i:s") . "] client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} client_id:$clientId session:" . json_encode($_SESSION) . " onMessage:" . json_encode($message) . "\n";
        $data = $message;
        //$data = json_decode($message, true);
        isset($data['code']) && $data['code'];
        if (!$data || !isset($data['code']) || !isset($data['data'])) {

            $res["message"] = "data in wrong format";
            Gateway::sendToClient($clientId, $res);
            return;
        }

        if (!isset($data["code"]) || !isset($data["data"])) {
            $res["message"] = "params is wrong";
            Gateway::sendToClient($clientId, $res);
            return;
        } else {

            if (!isset(ctrConfig::$ctrInfo[$data["code"]])) {
                $res["message"] = "class not exist";
                Gateway::sendToClient($clientId, $res);
                return;
            }
            $ctrInfo = ctrConfig::$ctrInfo[$data["code"]];
            $ctrName = $ctrInfo[0];
            $actName = $ctrInfo[1];
            if (class_exists($ctrName) && method_exists($ctrName, $actName)) {
                //调用相关控制器和方法处理业务
                $res = call_user_func([new $ctrName($data["code"]), $actName], $data["data"]);
                //登录绑定session
                if ($data["code"] == '100102' && $_SESSION["userId"]) {
                    Gateway::bindUid($clientId, $_SESSION["userId"]);
                }
                Gateway::sendToClient($clientId, $res);
            } else {
                $res["message"] = "class not exist";
                Gateway::sendToClient($clientId, $res);
            }
        }

    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($clientId)
    {
        $data = ["status" => 1, "data" => "Hello $clientId\n"];
        // 向所有人发送
        GateWay::sendToClient($clientId, $data);
    }
}
