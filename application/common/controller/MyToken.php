<?php
namespace app\common\controller;

class MyToken {
	// 加密字符串
	private $_secret_str = 'ltjmaptoken';

	// 参数1
	private $_param1;

	// 参数2
	private $_param2;



	/**
     * ------------------------------------
     * 设置参数
     *
     * @param array|string $param1 参数1
     * @param string       $param2 参数2
     * ------------------------------------
     */
    private function setParams($param1, $param2) {
    	if (!$param1 || !$param2) return false;

    	if (is_array($param1)) $param1 = json_encode($param1);

        $this->_param1 = $param1;
        $this->_param2 = $param2;

        return true;
    }



	/**
     * ------------------------------------
     * 生成token
     *
     * @param array|string $param1 参数1
     * @param string       $param2 参数2
     * ------------------------------------
     */
    private function setToken() {
    	return md5($this->_param2 . sha1($this->_secret_str . $this->_param1));
    }



	/**
     * ------------------------------------
     * 获取token
     *
     * @param array|string $param1 参数1
     * @param string       $param2 参数2
     * ------------------------------------
     */
	public function getToken($param1 = '', $param2 = '') {
		if (!$this->setParams($param1, $param2)) {
			return false;
		}

		return $this->setToken();
	}



	/**
     * ------------------------------------
     * 检查token
     *
     * @param array|string $param1 参数1
     * @param string       $param2 参数2
     * @param string       $token  token
     * ------------------------------------
     */
	public function checkToken($param1 = '', $param2 = '', $token = '') {
		if ($token == $this->getToken($param1, $param2)) {
			return true;
		} else {
			return false;
		}
	}
}
