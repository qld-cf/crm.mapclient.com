<?php
namespace app\socket\controller\interfaces;

interface BussinessInterface {
	// 连接验证
	public function checkConnection($worker, $conn, $msg);

	// 业务处理
	public function doBussiness($worker, $conn, $msg);

	// 根据不同情景拼装不同的消息发送给客户端
	public function setMsg($msg);

	// 发送信息给客户端
	public function send($conn, $msg);
}