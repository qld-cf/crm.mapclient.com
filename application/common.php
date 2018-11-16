<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件



/**
 * ------------------------------------
 * 判断用户登录状态，如果是已登录状态，按要求返回用户信息；否则返回错误
 *
 * @param boolean $get_id 是否只返回id
 * ------------------------------------
 */
function check_login($get_id = false) {
	if (!session('?user_info')) {
		return false;
	}

	// 如果只需要当前用户主键
	if ($get_id) {
		$user_info = session('user_info');
		return $user_info['id'];
	}

	return session('user_info');
}



/**
 * ------------------------------------
 * 判断后台管理用户登录状态，如果是已登录状态，按要求返回用户信息；否则返回错误
 *
 * @param boolean $get_id 是否只返回id
 * ------------------------------------
 */
function check_admin($get_id = false) {
	if (!session('?admin_info')) {
		return false;
	}

	// 如果只需要当前用户主键
	if ($get_id) {
		$admin_info = session('admin_info');
		return $admin_info['id'];
	}

	return session('admin_info');
}
