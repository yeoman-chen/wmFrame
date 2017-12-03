<?php
/**
 * 测试
 * @author yeoman
 * @since 2017.07.21
 */
namespace App\Controllers;

use \App\Components\ApiError;


class TestController extends Controller
{
	/**
	 * 注册用户
	 * @param $params 请求参数
	 */
	public static function index($params){
		
        $data = ["status" => 1,"data" => "测试数据"];
        return self::rspSuccess("注册成功",$data);
	}
}