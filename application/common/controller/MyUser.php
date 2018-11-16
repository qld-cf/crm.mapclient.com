<?php
namespace app\common\controller;

use app\common\model\User as ModelUser;
use app\common\model\Bind as ModelBind;

class MyUser {
	/**
     * ------------------------------------
     * 按条件搜索所有用户
     *
     * @param array $filter 筛选条件
     * ------------------------------------
     */
	public function getUser($filter = []) {
		return User::all(function($query) use ($filter) {
			$query->where($filter)->order('src', 'desc');
		});
	}
}
