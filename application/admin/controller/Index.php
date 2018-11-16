<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;

class Index extends Controller {
    /**
     * ------------------------------------
     * 首页
     * ------------------------------------
     */
    public function index() {
        if (!check_admin()) {
            $this->redirect('login');
        }

        $c_mycatalog = Loader::controller('common/MyCatalog');
        // 获取目录
        $catalog = $c_mycatalog->getShowCatalog();

        $data = [
            'catalog' => $catalog,
        ];

        $this->assign('data', $data);
    	return $this->fetch('index/index');
    }



    /**
     * ------------------------------------
     * 登录页
     * ------------------------------------
     */
    public function login() {
        // 如果通过登录状态检查，则跳转到首页
        if (check_admin()) {
            $this->redirect('index');
        }
        
        return $this->fetch('index/login');
    }
}
