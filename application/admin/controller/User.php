<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\User as ModelUser;

class User extends Controller {
    // 后台管理用户信息
    private $admin_info;



    /**
     * ------------------------------------
     * 初始化方法
     * ------------------------------------
     */
    public function _initialize() {
        // 验证登录
        if (!check_admin()) {
            $this->redirect('index/login');
        }

        $this->admin_info = check_admin();
    }



    /**
     * ------------------------------------
     * 用户列表
     * ------------------------------------
     */
    public function index() {
       
    }



    /**
     * ------------------------------------
     * 用户绑定
     * ------------------------------------
     */
    public function bind() {
        $data = [
            'gps'       => ModelUser::all(['src' => 'gps']),     // gps用户列表
            'admins'    => ModelUser::all(['src' => 'admin']),   // 平台管理员用户列表
        ];
        
        $this->assign('data', $data);
        return $this->fetch('user/bind');
    }
}
