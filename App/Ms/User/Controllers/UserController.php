<?php
/**
 * 用户控制器
 * @author yeoman
 * @since 2017.07.21
 */
namespace App\Ms\User\Controllers;

use \App\Components\ApiError;
use \App\Constants\UserErr;
use \App\Controllers\BController;
use \App\Models\UserModel;
use \App\Ms\User\Services\UserService;

class UserController extends BController
{
    /**
     * 注册用户
     * @param array $params 请求参数
     * @return array $res
     */
    public static function register($params)
    {
        if (!isset($params['userName']) || !isset($params['pwd']) || !isset($params['rePwd'])) {
            return self::rspError(UserErr::PARAM_ERROR, UserErr::MSG[UserErr::PARAM_ERROR]);
        }
        $userName = $params['userName'];
        $pwd      = $params['pwd'];
        $rePwd    = $params['rePwd'];

        $userService = new UserService();
        try {
            //注册用户
            $res = $userService->register($userName, $pwd, $rePwd);
        } catch (ApiError $e) {
            $err = $e->_return();
            return self::rspError($err->errCode, $err->errMsg);
        }

        return isset($res["errCode"]) ? self::rspError($res["errCode"], $res["errMsg"]) : self::rspSuccess($res['data']);
    }
    /**
     * 用户登录
     * @param array $params 请求参数
     * @return array $res
     */
    public static function login($params)
    {
        if (!isset($params['userName']) || !isset($params['pwd'])) {
            return self::rspError(UserErr::PARAM_ERROR, UserErr::MSG[UserErr::PARAM_ERROR]);
        }
        $userName    = $params['userName'];
        $pwd         = $params['pwd'];
        $userService = new UserService();
        try {
            $res = $userService->login($userName, $pwd);
        } catch (ApiError $e) {
            $err = $e->_return();
            return self::rspError($err->errCode, $err->errMsg);
        }

        return self::rspSuccess($res);
    }
    /**
     * 用户信息
     * @param array $params 请求参数
     * @return array $res
     */
    public static function uinfo($params)
    {
        if (!isset($params["userId"])) {
            return self::rspError(UserErr::PARAM_ERROR, UserErr::MSG[UserErr::PARAM_ERROR]);
        }
        $userId = $params["userId"];
        $select = "userId,userName,phone,type,sex,levelId,cash,gem,exp";
        $model  = new UserModel();

        $res = $model->getOne($userId, $select);
        return self::rspSuccess($res);
    }
}
