<?php
namespace app\socket\controller;

use think\Loader;

class Business implements interfaces\BussinessInterface {
    /**
     * ------------------------------------
     * 连接验证
     *
     * @param obj    $worker workerman对象
     * @param obj    $conn   当前连接对象
     * @param string $msg    客户端发送的信息
     * ------------------------------------
     */
	public function checkConnection($worker, $conn, $msg) {
        // 如果不是连接请求，直接返回错误
        if (!$msg['type'] == 'conn') {
            return false;
        }

        // 验证token
        $c_mytoken = Loader::controller('common/MyToken');
        if (!$c_mytoken->checkToken($msg['data'], $msg['timestamp'], $msg['token'])) {
            return false;
        }

        // 生成uid返回
        return password_hash($msg['token'], PASSWORD_DEFAULT);
	}



    /**
     * ------------------------------------
     * workerman业务类入口
     *
     * @param obj    $worker workerman对象
     * @param obj    $conn   当前连接对象
     * @param string $msg    客户端发送的信息
     * ------------------------------------
     */
    public function doBussiness($worker, $conn, $msg) {
        if (is_callable([$this, $msg['type']])) {
            call_user_func([$this, $msg['type']], $worker, $conn, $msg);
        }
    }



    /**
     * ------------------------------------
     * 根据情景拼装数组并返回
     *
     * @param string $msg 调试情景
     * ------------------------------------
     */
    public function setMsg($msg) {
        switch ($msg) {
            case 'checked':
                return [
                    'type'      => 'checked',
                    'msg'       => 'websocket验证成功',
                ];
            
            default:
                break;
        }
    }



    /**
     * ------------------------------------
     * 发送信息给当前连接的客户端
     *
     * @param obj   $connection 指定连接
     * @param array $msg        要发送的信息
     * ------------------------------------
     */
    public function send($conn, $msg) {
        $conn->send(json_encode($this->setMsg($msg)));
    }



    /**
     * ------------------------------------
     * 连接
     *
     * @param obj    $worker workerman对象
     * @param obj    $conn   当前连接对象
     * @param string $msg    客户端发送来的信息
     * ------------------------------------
     */
    protected function conn($worker, $conn, $msg) {
        // 返回验证成功回执
        $this->send($conn, 'checked');
    }



    /**
     * ------------------------------------
     * 注销
     *
     * @param obj    $worker workerman对象
     * @param obj    $conn   当前连接对象
     * @param string $msg    客户端发送来的信息
     * ------------------------------------
     */
    protected function logout($worker, $conn, $msg) {
        return $conn->close();
    }



    /**
     * ------------------------------------
     * 获取gps客户端状态
     *
     * @param obj   $worker workerman对象
     * @param array $gps    指定要查找的gps客户端编号
     * ------------------------------------
     */
    private function getGPSStatus($worker, $gps = []) {
        $status = [];

        // 遍历当前workerman对象的所有连接，如果存在指定的gps客户端则连接状态为在线，否则为离线
        foreach ($worker->connections as $connection) {

        }
    }
}