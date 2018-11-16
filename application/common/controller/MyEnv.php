<?php
namespace app\common\controller;

use think\Env;

class MyEnv {
    /**
     * ------------------------------------
     * 批量获取环境配置项并返回配置项结果
     *
     * @param array $envs 指定要查找的配置项 [键名：配置项键名；键值：该键名默认设置的值]
     * @return array 配置项结果集
     * ------------------------------------
     */
    public function getEnvs($envs = []) {
    	// 声明要返回的结果
    	$result = [];

    	// 获取要查询的配置项的键名
    	$env_keys = array_keys($envs);

    	// 遍历要查询的键名，如果配置文件中存在键名与键值，则返回键值；若不存在键名或键值为空则返回默认值
    	foreach ($env_keys as $key) {
    		$result[$key] = Env::get($key, $envs[$key]) ? Env::get($key, $envs[$key]) : $envs[$key];
    	}

    	return $result;
    }
}
