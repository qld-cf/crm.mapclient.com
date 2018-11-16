<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Request;
use app\common\model\Admin as ModelAdmin;

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
     * 检查登录
     *
     * @param string $_POST['uid']
     * @param string $_POST['password']
     * ------------------------------------
     */
    public function checkAdmin() {
        $c_mylogin = Loader::controller('common/MyLogin');

        // 拼装筛选条件
        $filter = [
            'uid' => $this->_data['uid'],
            'password' => $c_mylogin->getPassword($this->_data['password']),
        ];

        // 尝试获取登录信息
        $admin_info = ModelAdmin::get($filter);

        // 检查密码是否正确
        if ($admin_info) {
            // 生成session
            session('admin_info', $admin_info);
            $res = $this->_c_mylanguage->setReturn('', true);
        } else {
            $res = $this->_c_mylanguage->setReturn($this->_c_mylanguage->_login_error);
        }

        return $res;
    }
}
