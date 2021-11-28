<?php

namespace plum\core;

use plum\helper\Str;
use think\Paginator;

class Query extends \think\db\Query
{
    /**
     * 扩展分页,占用size
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 19:53
     */
    public function paginate($listRows = null, $simple = false): Paginator
    {
        //重写每页页数
        if (!$listRows) {
            $listRows = input('size', 15);
        }
        return parent::paginate($listRows, $simple);
    }

    /**
     * 根据post,get参数进行自动排序
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 20:53
     */
    public function autoOrder(...$args)
    {
        $orderBy = input('order_by');
        $orderSort = input('order_sort');
        $tableFields = array_keys($this->getFields());
        //允许排序的字段
        if (count($args) > 0) {
            $allowFields = $args;
        } else {
            $allowFields = $tableFields;
        }

        if ($orderBy && $orderSort && in_array($orderBy, $allowFields) && in_array($orderSort, ['asc', 'desc'])) {
            //自主排序
            $this->order($orderBy, $orderSort);
        } else {
            //默认排序,sort升序,时间降序,id降序
            if (in_array('sort', $tableFields)) {
                $this->order("sort", 'asc');
            } elseif (in_array('create_time', $tableFields)) {
                $this->order("create_time", 'desc');
            } else {
                $this->order("id", 'desc');
            }
        }
        return $this;
    }

    /**
     * 自动搜索
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 20:54
     */
    public function autoSearch()
    {
        $params = empty($params) ? request()->param() : $params;

        if (empty($params)) {
            return $this;
        }

        foreach ($params as $field => $value) {
            $method = 'search' . Str::studly($field) . 'Attr';
            //只要不是null,空字符,空数组,就可以进行搜索
            if ($value !== null && $value !== '' && $value !== [] && method_exists($this->model, $method)) {
                $this->model->$method($this, $value, $params);
            }
        }
        return $this;
    }
}
