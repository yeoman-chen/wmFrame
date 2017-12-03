<?php
/**
 * 用户相关业务逻辑
 * @author yeoman
 * @since 2017.07.26
 */
namespace App\Ms\User\Services;

use \App\Components\ApiError;
use \App\Components\XSecurity;
use \App\Models\UserModel;
use \App\Constants\UserErr;
use \App\Models\UserBackpackModel;

class UserService
{
    
    /**
     * 检查用户名是否能使用
     * @param $groupName
     * @param int $groupId
     * @return bool
     */
    public static function canUserNameUsed($userName)
    {
        $fields = "userId";
        $where  = "`userName` = :userName";
        $params = ["userName" => $userName];

        $model = new UserModel();
        $res   = $model->getRowByWhere($where, $params, $fields);
        if (!$res) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 注册用户
     * @param $userName
     * @param $pwd
     * @return bool
     */
    public static function register($userName, $pwd, $rePwd, $channel = '系统')
    {
        if (empty($userName) || empty($pwd) || empty($rePwd)) {
            throw new ApiError(UserErr::MSG[UserErr::UNAME_PWD_NOT_EMPTY],UserErr::UNAME_PWD_NOT_EMPTY);
        }
        if (strlen($userName) < 4 || strlen($userName) > 24) {
            throw new ApiError(UserErr::MSG[UserErr::UNAME_FORMAT_WRONG], UserErr::UNAME_FORMAT_WRONG);
        }
        if (strlen($pwd) < 6 || strlen($pwd) > 24) {
            throw new ApiError(UserErr::MSG[UserErr::PWD_FORMAT_WRONG], UserErr::PWD_FORMAT_WRONG);
        }
        //校验密码
        if ($pwd !== $rePwd) {
            throw new ApiError(UserErr::MSG[UserErr::TWICE_PWD_DIFF], UserErr::TWICE_PWD_DIFF);
        }

        $res = self::canUserNameUsed($userName);
        if (!$res) {
            throw new ApiError(UserErr::MSG[UserErr::INFO_EXIST], UserErr::INFO_EXIST);
        }

        $pwd  = self::hashPassword($userName, $pwd);
        $data = ['userName' => $userName, 'password' => $pwd, 'channel' => $channel];

        $model = new UserModel();
        $res   = $model->addData($data);

        if ($res) {
            $data["status"] = 1;
            $data["data"]   = self::getUserToken($res);
        } else {
            $data["errCode"] = UserErr::INFO_CREATE_FAIL;
            $data["errMsg"]  = UserErr::MSG[UserErr::INFO_CREATE_FAIL];
        }
        return $data;
    }
    /**
     * 密码进行加密
     * @param $username
     * @param $password 密码明文
     * @return string
     */
    public static function hashPassword($userName, $password)
    {
        return md5(md5($userName) . md5($password) . "@dq123");
    }
    /**
     * 登录
     * @param $username 用户名称
     * @param $password 密码明文
     * @return string
     */
    public static function login($userName, $password)
    {
        if(!$userName || !$password){
            throw new ApiError(UserErr::MSG[UserErr::PARAM_ERROR], UserErr::PARAM_ERROR);
        }
        $model  = new UserModel();
        $fields = 'userId,userName,password,phone';
        $where  = "`userName` = :userName";
        $params = ["userName" => $userName];

        $info   = $model->getRowByWhere($where, $params, $fields);
        
        if (!$info || (self::hashPassword($userName, $password) != $info["password"])) {
            throw new ApiError(UserErr::MSG[UserErr::UNAME_PWD_WRONG], UserErr::UNAME_PWD_WRONG);
        }
        $_SESSION["userId"] = $info["userId"];
        
        unset($info["password"]);
        $data["userId"] = $info["userId"];
        $data["userName"] = $info["userName"];
        $data["token"]  = self::getUserToken($info["userId"], $info);
        return $data;

    }
    /**
     * 获取用户的token
     * @param $userId 用户ID
     * @return string
     */
    public static function getUserToken($userId, $uinfo = [])
    {
        if (empty($uinfo)) {
            $fields = 'userId,userName,phone';
            $model  = new UserModel();
            $uinfo  = $model->getOne($userId, $fields);
        }

        $uinfo = $uinfo["userId"] . "##" . $uinfo["userName"] . "##" . $uinfo["phone"] . "##" . json_encode($uinfo);

        return XSecurity::apiSTD3Encrypt(rawurlencode($uinfo));
    }
    /**
     * 获取用户资产
     * @param int $userId
     * @return array $res
     */
    public static function getAssets($userId)
    {
        $userModel = new UserModel();
        $ubpModel = new UserBackpackModel();
        $tbl = $userModel->getTblName();
        $joinTbl = $ubpModel->getTblName();
        $select = "{$tbl}.userId,{$tbl}.cash,{$tbl}.gem,{$tbl}.exp,bp.steelTotal,bp.bullionTotal,bp.gemTotal,bp.jadeTotal";

        $param["where"] = "{$tbl}.userId=:uid";
        $param["bind"] = ["uid" => $userId];
        $param["join"] = [["leftJoin","{$joinTbl} as bp","{$tbl}.userId = bp.userId"]];
        $res = $userModel->getRow($param,$select);
        return $res;
    }
}
