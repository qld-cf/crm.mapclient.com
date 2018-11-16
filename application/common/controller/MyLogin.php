<?php
namespace app\common\controller;

class MyLogin {
	// 加密字符串
	private $_secret_str = 'ltjpassword';



	/**
     * ------------------------------------
     * 生成密码并返回
     *
     * @param string $pw 明文密码
     * ------------------------------------
     */
	public function getPassword($pw) {
		return md5($pw . $this->_secret_str);
	}
}