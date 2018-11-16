<?php
namespace app\common\controller;

use app\common\model\Catalog as ModelCatalog;

class MyCatalog {
	/**
     * ------------------------------------
     * 按排序获取所有未分类的目录
     *
     * @param array $filter 筛选条件
     * ------------------------------------
     */
	public function getCatalog($filter = []) {
		return ModelCatalog::all(function($query) use ($filter) {
			$query->where($filter)->order('priority', 'desc');
		});
	}



	/**
     * ------------------------------------
     * 获取后台管理客户端左侧列表
     * ------------------------------------
     */
	public function getShowCatalog() {
		// 按排序读取所有目录
		$catalog = $this->getCatalog(['show' => 1]);
		// 将读取的目录分类
		$result = $this->classifyCatalog($catalog);

		return $result;
	}



	/**
     * ------------------------------------
     * 目录分类
     *
     * @param obj $catalog 目录列表
     * ------------------------------------
     */
	public function classifyCatalog($catalog) {
		$result = [];

		// 抓取目录表中parent_id为0的目录，设为总目录
		foreach ($catalog as $c => $arr) {
			if ($arr->parent_id == 0) {
				$result[] = $arr;
				unset($catalog[$c]);
			}
		}

		// 目录分类
		foreach ($result as $r => $res) {
			// 定义一个中间变量
			$childs = [];

			foreach ($catalog as $c => $arr) {
				if ($res->id == $arr->parent_id) {
					$childs[] = $arr;
				}
			}

			// 将装载的下级目录赋予当前父目录的childs属性中
			$result[$r]->childs = $childs;
		}

		return $result;
	}
}