<?php
/**
 * 用户设备相关业务逻辑
 * @author yeoman
 * @since 2017.07.26
 */
namespace App\Ms\User\Services;

use \App\Components\ApiError;
use \App\Components\XSecurity;
use \App\Models\EquipmentUpgradeModel;
use \App\Models\UserBackpackModel;
use \App\Models\UserEquipmentModel;
use \App\Models\UserModel;
use \App\Ms\User\Services\UserService;
use \App\Constants\EquipErr;
use \App\Constants\UserErr;

class EquipmentService
{
	/**
     * 用户购买设备
     * @param array $params 请求参数
     * @return boolean true|false
     */
	public static function buy($params)
	{
		$upInfo = self::getUpgradeInfo($params['emId'],1);
		if(!$upInfo){
			throw new ApiError(EquipErr::MSG[EquipErr::INFO_NOT_EXIST], EquipErr::INFO_NOT_EXIST);
		}

		$userId = $_SESSION["userId"];
		
		$bpInfo = UserService::getAssets($userId);
		$userModel = new UserModel();
		
		//余额不足
		if($bpInfo['cash'] < $upInfo['cash'] || $bpInfo['steelTotal'] < $upInfo['steel'] || $bpInfo['bullionTotal'] < $upInfo['bullion'] || $bpInfo['gemTotal'] < $upInfo['gem'] || $bpInfo['jadeTotal'] < $upInfo['jade'] ){
			throw new ApiError(UserErr::MSG[UserErr::UBP_BALANCE_INSUFFICIENT], UserErr::UBP_BALANCE_INSUFFICIENT);
		}
		//购买设备事务todo
		$bpModel = new UserBackpackModel();
		$emModel = new UserEquipmentModel();

		$bpModel->getDb()->beginTrans();
		//减去现金
		$data1 = [["cash","-","{$upInfo['cash']}"]];
		$where = "userId = {$userId} and cash >= {$upInfo['cash']}";
		$res1 = $userModel->updateData($data1,$where);
		
		//减去背包需要的物品数量
		$data2[] = ["steelTotal","-","{$upInfo['steel']}"];
		$data2[] = ["bullionTotal","-","{$upInfo['bullion']}"];
		$data2[] = ["gemTotal","-","{$upInfo['gem']}"];
		$data2[] = ["jadeTotal","-","{$upInfo['jade']}"];
		$where = "userId = {$userId} and steelTotal >= {$upInfo['steel']} and bullionTotal >= {$upInfo['bullion']} and gemTotal >= {$upInfo['gem']} and jadeTotal >= {$upInfo['jade']}";
		$res2 = $bpModel->updateData($data2,$where);

		//插入设备数据
		$data["emId"]        = $params["emId"];
        $data["userId"]     = $params["userId"];
        $data["typeId"]       = $params["typeId"];
        $data["levelId"]    = $params["levelId"];
        $data["coordinate"] = json_encode($params["coordinate"]);
        $data["createTime"] = time();
        $res3 = $emModel->addData($data);
        
		if($res1 && $res2 && $res3) {
			$bpModel->getDb()->commitTrans();
			return true;
		}else{
			$bpModel->getDb()->rollBackTrans();
			return $res = ["errMsg" => UserErr::MSG[UserErr::UEQUIP_CREATE_FAIL], "errCode" => UserErr::UEQUIP_CREATE_FAIL];
		}

	}
	/**
     * 设备升级
     * @param int $id 设备id
     * @return array|false $info 返回数据
     */
	public static function upgrade($id)
	{
		$userId = $_SESSION["userId"];
		//用户设备信息
		$info = self::getUserEquipmentInfo($id);
		if(!$info || $info['userId'] != $userId){
			throw new ApiError(UserErr::MSG[UserErr::UEQUIP_NOT_EXIST], UserErr::UEQUIP_NOT_EXIST);
		}
		//设备升级信息
		$upInfo = self::getUpgradeInfo($info['emId'],$info['levelId'] + 1);
		if(!$upInfo){
			throw new ApiError(EquipErr::MSG[EquipErr::INFO_NOT_EXIST], EquipErr::INFO_NOT_EXIST);
		}
		//用户资产
		$assets = UserService::getAssets($userId);
		//余额不足
		if($assets['cash'] < $upInfo['cash'] || $assets['steelTotal'] < $upInfo['steel'] || $assets['bullionTotal'] < $upInfo['bullion'] || $assets['gemTotal'] < $upInfo['gem'] || $assets['jadeTotal'] < $upInfo['jade'] ){
			throw new ApiError(UserErr::MSG[UserErr::UBP_BALANCE_INSUFFICIENT], UserErr::UBP_BALANCE_INSUFFICIENT);
		}
		//升级设备事务todo
		$bpModel = new UserBackpackModel();
		$emModel = new UserEquipmentModel();
		$userModel = new UserModel();

		$bpModel->getDb()->beginTrans();
		//减去现金
		$data1 = [["cash","-","{$upInfo['cash']}"]];
		$where = "userId = {$userId} and cash >= {$upInfo['cash']}";
		$res1 = $userModel->updateData($data1,$where);
		
		//减去背包需要的物品数量
		$data2[] = ["steelTotal","-","{$upInfo['steel']}"];
		$data2[] = ["bullionTotal","-","{$upInfo['bullion']}"];
		$data2[] = ["gemTotal","-","{$upInfo['gem']}"];
		$data2[] = ["jadeTotal","-","{$upInfo['jade']}"];
		$where = "userId = {$userId} and steelTotal >= {$upInfo['steel']} and bullionTotal >= {$upInfo['bullion']} and gemTotal >= {$upInfo['gem']} and jadeTotal >= {$upInfo['jade']}";
		$res2 = $bpModel->updateData($data2,$where);

		//升级设备
		$data3 = [["levelId","+",1],["statusIs","=",2],["upTime","=",time() + $upInfo['takeTime']]];

		$where = "userId = {$userId} and id = {$info['id']}";
		$res3 = $emModel->updateData($data3,$where);
        
		if($res1 && $res2 && $res3) {
			$bpModel->getDb()->commitTrans();
			return true;
		}else{
			$bpModel->getDb()->rollBackTrans();
			return $res = ["errMsg" => UserErr::MSG[UserErr::UEQUIP_CREATE_FAIL], "errCode" => UserErr::UEQUIP_CREATE_FAIL];
		}

	}
	/**
     * 获取设备升级的信息
     * @param int $epId 设备id
     * @param int $levelId 等级id
     * @return array|false $info 返回数据
     */
	public static function getUpgradeInfo($epId,$levelId)
	{
		$model = new EquipmentUpgradeModel();
		$select = 'id,emId,levelId,cash,steel,bullion,gem,jade,takeTime';
		$param['where'] = "emId =:emId and levelId=:levelId";
		$param['bind'] = ["emId" => $epId,"levelId" => $levelId];
		$info = $model->getRow($param,$select);
		return $info;
	}
	/**
     * 获取用户背包信息
     * @param int $params 请求参数
     * @return array|false $info 返回数据
     */
	public static function getBackpackInfo($userId)
	{
		$select = 'steelTotal,bullionTotal,gemTotal,jadeTotal';
		$model = new UserBackpackModel();
		$param['where'] = "userId=:userId";
		$param['bind'] = ["userId" => $userId];
		$info = $model->getRow($param,$select);
		return $info;
	}
	/**
	 * 获取用户单个设备
	 * @param int $id 用户设备id
	 * @return mixed $info 结果
	 */
	public static function getUserEquipmentInfo($id)
	{
		$select = 'id,emId,userId,typeId,levelId,statusIs';
		$model = new UserEquipmentModel();
		return $model->getOne($id);
	}
}