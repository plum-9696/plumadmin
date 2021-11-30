<?php

namespace plum\helper;

class Arr extends \think\helper\Arr
{
    /**
     * 二维数组转树状结构
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月27日 20:13
     */
    public static function tree($data): array
    {
        $data = array_column($data, null, 'id');
        $tree = [];
        foreach ($data as &$v) {
            if (isset($data[$v['parent_id']])) {
                //父类存在进入父类的children下
                $data[$v['parent_id']]['children'][] = &$v;
            } else {
                //父类不存在进入tree数组
                $tree[] = &$v;
            }
        }
        return $tree;
    }

    /**
     * 多维数组合并,由于数组相加,索引数组不会累加
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月27日 20:13
     */
    public static function mergeMultiple($array1, $array2): array
    {
        $merge = $array1 + $array2;
        $data = [];
        foreach ($merge as $key => $val) {
            if (
                isset($array1[$key])
                && is_array($array1[$key])
                && isset($array2[$key])
                && is_array($array2[$key])
            ) {
                $data[$key] = self::isAssoc($array1[$key]) ? self::mergeMultiple($array1[$key], $array2[$key]) : $array2[$key];
            } else {
                $data[$key] = $array2[$key] ?? $array1[$key];
            }
        }
        return $data;
    }

    /**
     * 二维数组排序
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月27日 20:13
     */
    public static function columnSort($array, $field, $sort = SORT_ASC)
    {
        $sortArray = array_column($array, $field);
        array_multisort($sortArray, $sort, SORT_NUMERIC, $array);
        return $array;
    }

    /**
     * 指定获取数组,且设置默认值
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 14:08
     */
    public static function onlyDefault($array, $name, $default = '')
    {
        $item = [];
        foreach ($name as $key => $val) {

            if (is_int($key)) {
                $key = $val;
            } else {
                $default = $val;
            }

            $item[$key] = $array[$key] ?? $default;
        }
        return $item;
    }
}
