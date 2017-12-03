<?php
/**
 * 用户设备控制器
 * @author yeoman
 * @since 2017.07.21
 */
namespace App\Ms\User\Controllers;

use \App\Components\ApiError;
use \App\Constants\UserErr;
use \App\Controllers\BController;
use \App\Models\UserEquipmentModel;
use \App\Ms\User\Services\EquipmentService;

class UserEquipmentController extends BController
{
    /**
     * 购买设备
     * @param array $params 请求参数
     * @return array $res
     */
    public static function buy($params)
    {
        if (!isset($params["emId"]) || !isset($params["userId"]) || !isset($params["typeId"]) || !isset($params["levelId"])) {
            return self::rspError(UserErr::PARAM_ERROR, UserErr::MSG[UserErr::PARAM_ERROR]);
        }
        //购买设备
        //$emService = new EquipmentService();
        try {
            $res = EquipmentService::buy($params);
        } catch (ApiError $e) {
            $err = $e->_return();
            return self::rspError($err->errCode, $err->errMsg);
        }

        return isset($res["errCode"]) ? self::rspError($res["errCode"], $res["errMsg"]) : self::rspSuccess($res);
    }
    /**
     * 用户设备列表
     * @param array $params 请求参数
     * @return array $res
     */
    public static function getList($params)
    {
        if (!isset($params["userId"])) {
            return self::rspError(UserErr::PARAM_ERROR, UserErr::MSG[UserErr::PARAM_ERROR]);
        }
        $tbl           = "dq_user_equipment";
        $data["page"]  = 1;
        $data["limit"] = 100;
        $data["where"] = "{$tbl}.userId = :userId";
        $data["bind"]  = ["userId" => $params["userId"]];
        $data["join"]  = [["leftJoin", "dq_equipment as e", "{$tbl}.emId = e.id"]];

        $select = "{$tbl}.id,{$tbl}.emId,e.name,{$tbl}.levelId,{$tbl}.typeId,{$tbl}.coordinate,{$tbl}.statusIs,e.capacity,e.productRate";
        $model  = new UserEquipmentModel();

        $res = $model->getList($data, $select);

        return self::rspSuccess($res);
    }
    /**
     * 升级设备
     * @param array $params 请求参数
     * @return array $res
     */
    public static function upgrade($params)
    {
        if(!isset($params["id"])){
            return self::rspError(UserErr::PARAM_ERROR, UserErr::MSG[UserErr::PARAM_ERROR]);
        }
        try {
            $res = EquipmentService::upgrade($params["id"]);
        } catch (ApiError $e) {
            $err = $e->_return();
            return self::rspError($err->errCode, $err->errMsg);
        }

        return isset($res["errCode"]) ? self::rspError($res["errCode"], $res["errMsg"]) : self::rspSuccess($res);
    }
}
