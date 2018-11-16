<?php
namespace app\socket\controller;

use Workerman\Worker;
use Workerman\Lib\Timer;
use think\Loader;
use app\socket\controller\Business;

class Server extends Business {
    // workerman对象
    private $_worker;

    // 保存读取的环境配置项
    private $_envs = [];

    // 要读取环境配置项及默认值
    private $_envs_param = [
        'workerman.heartbeat_time'  => 50,
        'workerman.outtime_timer'   => 30,
        'workerman.server_port'     => '',
        'ssl.is_ssl'                => '',
        'ssl.pem_path'              => '',
        'ssl.key_path'              => '',
    ];



    /**
     * ------------------------------------
     * 客户端与socket服务器建立连接时
     *
     * @param obj $connection 当前连接对象
     * ------------------------------------
     */
    public function onConnect($connection) {
        // 定时规定时间内关闭连接，需要客户端在规定时间内发送验证删除定时器
        $connection->auth_timer_id = Timer::add($this->_envs['workerman.outtime_timer'], function() use ($connection) {
            $connection->close();
        }, null, false);
    }



    /**
     * ------------------------------------
     * socket服务器接收到客户端信息时
     *
     * @param obj    $connection 当前连接对象
     * @param string $msg        客户端发送的信息
     * ------------------------------------
     */
    public function onMessage($connection, $msg) {
        // 更新当前连接最新通信时间
        $connection->lastMessageTime = time();

        // 将客户端发送信息转成数组
        $msg = json_decode($msg, true);

        // 当前连接没有验证过，则执行验证
        if (!isset($connection->uid)) {
            // 验证成功
            if ($uid = $this->checkConnection($this->_worker, $connection, $msg)) {
                // 设置uid
                $connection->uid = $uid;
                // 保存uid到connection的映射
                $this->_worker->uidconnections[$connection->uid] = $connection;
                // 删除验证定时器
                Timer::del($connection->auth_timer_id);

            // 验证失败
            } else {
                return $connection->close();
            }
        }

        // 执行信息处理
        $this->doBussiness($this->_worker, $connection, $msg);
    }



    /**
     * ------------------------------------
     * 客户端与socket服务器关闭连接时
     *
     * @param obj $connection 当前连接对象
     * ------------------------------------
     */
    public function onClose($connection) {
        if (isset($connection->uid)) {
            // 连接断开时删除映射
            unset($this->_worker->uidconnections[$connection->uid]);
        }
    }



    /**
     * ------------------------------------
     * 根据是否开启ssl认证设置workerman对象
     * ------------------------------------
     */
    private function setWorker() {
        // 通过是否开放ssl来选择相对应的协议生成workerman对象
        // 当开放ssl时选择wss协议
        if ($this->_envs['ssl.is_ssl']) {
            $context = [
                'ssl' => [
                    'local_cert'    => $this->_envs['ssl.pem_path'],    // ssl pem文件路径
                    'local_pk'      => $this->_envs['ssl.key_path'],    // ssl key文件路径
                    'verify_peer'   => false,                           // 是否验证证书
                ],
            ];

            $this->_worker = new Worker('websocket://0.0.0.0:' . $this->_envs['workerman.server_port'], $context);
            $this->_worker->transport = 'ssl';

        // 不开放ssl时选择ws协议
        } else {
            $this->_worker = new Worker('websocket://0.0.0.0:' . $this->_envs['workerman.server_port']); 
        }

        // 设置进程数为1
        $this->_worker->count = 1;
        // 新增加一个属性，用来保存uid到connection的映射（uid是用户id或者客户端唯一标识）
        $this->_worker->uidconnections = [];
    }



    /**
     * ------------------------------------
     * workerman单入口，用于启动socket监听
     * ------------------------------------
     */
    public function index() {
        // 读取需要的环境配置项
        $c_myenv = Loader::controller('common/MyEnv');
        $this->_envs = $c_myenv->getEnvs($this->_envs_param);

        // 生成workerman对象
        $this->setWorker();

        // 在onWorkerStart里初始化类
        $this->_worker->onWorkerStart = function($worker) {
            // 进程启动后设置一个每秒运行一次的定时器
            Timer::add(1, function() use ($worker) {
                // 获取当前时间
                $time_now = time();

                // 遍历当前在线的所有连接
                foreach($worker->connections as $connection) {
                    // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                    if (empty($connection->lastMessageTime)) {
                        $connection->lastMessageTime = $time_now;
                        continue;
                    }

                    // 上次通讯时间间隔大于心跳间隔，则控制客户端已经下线
                    if ($time_now - $connection->lastMessageTime > $this->_envs['workerman.heartbeat_time']) {
                    }
                }
            });

            $this->_worker->onConnect    = array($this, 'onConnect');    // 建立连接时(TCP三次握手完成后)触发的回调函数
            $this->_worker->onMessage    = array($this, 'onMessage');    // 收到数据时触发的回调函数
            $this->_worker->onClose      = array($this, 'onClose');      // 断开连接时触发的回调函数
        };

        Worker::runAll();
    }
}
