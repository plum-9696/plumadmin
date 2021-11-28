<?php

namespace plum\helper;

class Helper
{
    /**
     * 获取子ID
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月04日 17:26
     */
    public static function getChildrenIds($model, $ids)
    {
        $childrenIds = $model->whereIn('parent_id', $ids)->column('id');
        if (!empty($childrenIds)) {
            $childrenIds = array_merge($childrenIds, self::getChildrenIds($model, $childrenIds));
        }
        return $childrenIds;
    }

    /**
     * 获取当前文件夹下某个扩展的文件
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月06日 19:02
     */
    public static function getAllFile($path, $extension)
    {
        $result = [];
        $pattern = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*';
        $files = glob($pattern);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $result = array_merge($result, self::getAllFile($file, $extension));
            } elseif (strcasecmp(pathinfo($file, PATHINFO_EXTENSION), $extension) === 0) {
                array_push($result, $file);
            }
        }
        return $result;
    }

    /**
     * 获取文档注释
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 15:55
     */
    public static function getNotes(...$arg): array
    {
        try {
            $notes = (new \ReflectionMethod(...$arg))->getDocComment();
            $pattern = "/@([a-zA-Z]+)\s+(\S+)\s+/";
            preg_match_all($pattern, $notes, $matches);
            $data = [];
            foreach ($matches[1] as $k => $v) {
                $data[$v] = $matches[2][$k];
            }
            return $data;
        } catch (\ReflectionException $e) {
            return [];
        }
    }

    /**
     * 判断是不是linux系统
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月26日 22:40
     */
    public static function isLinux(): bool
    {
        return DIRECTORY_SEPARATOR !== '\\';
    }
}
