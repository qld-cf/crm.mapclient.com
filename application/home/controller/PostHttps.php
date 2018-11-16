<?php
namespace app\home\controller;

use think\Controller;
use think\Loader;
use think\Request;

class PostHttps extends Controller {
    // 提示信息类对象
    private $_c_mylanguage;

    // 客户端传来的参数
    private $_data;



    /**
     * ------------------------------------
     * 初始化方法
     * ------------------------------------
     */
    public function _initialize() {
        $this->_c_mylanguage = Loader::controller('common/MyLanguage');

        // 如果不为post请求，统一返回错误页面
        if (!Request::instance()->isPost()) {
            return $this->error($this->_c_mylanguage->_no_permit_request);
        }

        $this->_data = Request::instance()->post();
    }



    /**
     * ------------------------------------
     * 生成token并返回
     *
     * @param array|string $_POST['data']
     * @param string       $_POST['timestamp']
     * ------------------------------------
     */
    public function getToken() {
        $c_mytoken = Loader::controller('common/MyToken');

        // 获取token
        if ($token = $c_mytoken->getToken($this->_data['data'], $this->_data['timestamp'])) {
            $res = $this->_c_mylanguage->setReturn($token, true);
        } else {
            $res = $this->_c_mylanguage->setReturn($this->_c_mylanguage->_token_error);
        }

        return $res;
    }



    /**
     * ------------------------------------
     * 检查登录
     *
     * @param string $_POST['uid']
     * @param string $_POST['password']
     * ------------------------------------
     */
    public function checkLogin() {
        $c_mylogin = Loader::controller('common/MyLogin');

        // 拼装筛选条件
        $filter = [
            'uid' => $this->_data['uid'],
            'password' => $this->_data['password'],
        ];

        if ($c_mylogin->setIn($filter, 'user')) {
            $res = $this->_c_mylanguage->setReturn('', true);
        } else {
            $res = $this->_c_mylanguage->setReturn($this->_c_mylanguage->_login_error);
        }

        return $res;
    }
}
