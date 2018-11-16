<?php
namespace app\home\controller;

use think\Controller;
use think\Loader;

class Index extends Controller {
    // 保存读取的环境配置项
    private $_envs = [];

    // 要读取环境配置项及默认值
    private $_envs_param = [
        'workerman.server_url'      => '',
        'workerman.server_port'     => '',
        'workerman.heartbeat_time'  => '',
        'ssl.is_ssl'                => '',
        'ssl.pem_path'              => '',
        'ssl.key_path'              => '',
        'map.map_api'               => 'baidu',
        'map.baidu_api_ak'          => '',
    ];



    /**
     * ------------------------------------
     * 初始化方法
     * ------------------------------------
     */
	public function _initialize() {
        // 获取配置文件中的配置项
		$c_myenv = Loader::controller('common/MyEnv');
		$this->_envs = $c_myenv->getEnvs($this->_envs_param);
    }



    /**
     * ------------------------------------
     * 首页
     * ------------------------------------
     */
    public function index() {
        if (!check_login()) {
            $this->redirect('login');
        }

    	$data = [
    		'envs' => $this->_envs,
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
        if (check_login()) {
            $this->redirect('/');
        }
        
        return $this->fetch('index/login');
    }
}
