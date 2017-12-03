<?php

/**
 * 自定义错误类
 */
namespace App\Components;

class ApiError extends \Exception
{
    /**
     * 返回
     * @return object
     */
    public function _return(){
        return (object)['errCode'=>$this->getCode(),'errMsg'=>$this->getMessage()];
    }
}