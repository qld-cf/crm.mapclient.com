<?php
namespace app\common\controller;

use app\common\model\User as ModelUser;
use app\common\model\Admin as ModelAdmin;

class MyLogin {
	// 加密字符串
	private $_secret_str = 'ltjpassword';



	/**
     * ------------------------------------
     * 登录入口
     *
     * @param array  $filter 检索条件
     * @param string $client 登录端 [admin : 后台管理； user : 客户端管理平台]
     * ------------------------------------
     */
	public function setIn($filter = [], $client = 'admin') {
		// 根据参数来判断要访问的方法
		$func = $client . 'Login';
		// 设置password
		$filter['password'] = $this->getPassword($filter['password']);

		if (!is_callable([$this, $func])) return false;

		// 如果获取到登录信息，则设置对应session并返回true
		if ($info = call_user_func([$this, $func], $filter)) {
			session($client . '_info', $info);
			return true;
		}

		return false;
	}



	/**
     * ------------------------------------
     * 登出
     * ------------------------------------
     */
	public function setOut() {

	}



	/**
     * ------------------------------------
     * 生成密码并返回
     *
     * @param string $pw 明文密码
     * ------------------------------------
     */
	private function getPassword($pw) {
		return md5($pw . $this->_secret_str);
	}



	/**
     * ------------------------------------
     * 后台管理登录
     *
     * @param array  $filter 检索条件
     * ------------------------------------
     */
	private function adminLogin($filter = []) {
		// 获取登录信息
        return ModelAdmin::get($filter);
	}



	/**
     * ------------------------------------
     * 客户端管理平台登录
     *
     * @param array  $filter 检索条件
     * ------------------------------------
     */
	private function userLogin($filter = []) {
		// 获取登录信息
		return ModelUser::get($filter);
	}
}