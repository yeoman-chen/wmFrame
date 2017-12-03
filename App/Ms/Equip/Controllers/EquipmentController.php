<?php 

/**
 * 设备控制器
 */
namespace App\Ms\Equip\Controllers;
use \App\Controllers\BController;
use \App\Models\EquipmentModel;
use \App\Constants\EquipErr;

class EquipmentController extends BController
{
	/**
	 * 获取设备列表
	 * @param array $params
	 */
	public static function getList($params)
	{
		$data["page"] = isset($params["page"]) ? $params["page"] : 1;
		$data["psize"] = isset($params["psize"]) ? $params["psize"] : 10;

		if(isset($params["type"])){
			$data["where"] = "`type` = :type";
			$data["bind"] = ["type" => intval($params["type"])];
		}
		$select = "id,name,price,levelId,typeId,size,lifeTime,capacity,productRate";
		$model = new EquipmentModel();

		$res["list"] = $model->getList($data,$select);
		$res["total"] = $model->getTotal($data);

		return self::rspSuccess($res);
	}
	/**
	 * 获取设备详情
	 * @param array $params
	 */
	public static function getDetail($params)
	{
		if(!isset($params["id"])){
			return self::rspError(EquipErr::PARAM_ERROR,EquipErr::MSG[EquipErr::PARAM_ERROR]);
		}
		$id = (int)$params["id"];
		$select = "id,name,price,levelId,typeId,size,lifeTime,capacity,productRate";
		$model = new EquipmentModel();
		$res = $model->getOne($id,$select);
		return self::rspSuccess($res);
	}
}
