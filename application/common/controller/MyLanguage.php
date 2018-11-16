<?php
namespace app\common\controller;

class MyLanguage {
    // 请求不合法
    public $_no_permit_request = '请求不合法';

    // 获取token失败
    public $_token_error = '获取token失败';

    // 登录失败
    public $_login_error = '用户名或密码错误';

    // 成功提示信息
    public $_success_res = [
    	'res'		=> true,
    	'msg'		=> '',
    ];

    // 失败提示信息
    public $_error_res = [
    	'res'		=> false,
    	'msg'		=> '',
    ];



    /**
     * ------------------------------------
     * 设置返回数组
     *
     * @param mixed   $msg        返回提示值
     * @param boolean $is_success 是否返回成功提示 [ture：成功；false：失败]
     * ------------------------------------
     */
    public function setReturn($msg = [], $is_success = false) {
    	$res = $is_success ? $this->_success_res : $this->_error_res;
    	$res['msg'] = $msg;

    	return json_encode($res);
    }
}
