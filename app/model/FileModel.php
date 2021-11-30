<?php

namespace app\model;

use plum\core\base\Model;
use think\facade\Config;
use think\facade\Filesystem;
use think\model\concern\SoftDelete;

class FileModel extends Model
{
    use SoftDelete;

    protected $name = 'file';
    //上传文件的字段
    private $uploadField = 'file';
    //配置
    protected $defaultConfig = [
        'valid' => [
            ['name' => '图片', 'ext' => 'png', 'size' => 1024 * 300],
            ['name' => '视频', 'ext' => 'mp4', 'size' => 0],
            ['name' => '文件', 'ext' => 'txt', 'size' => 0],
        ]
    ];

    /**
     * 文件大小
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 17:44
     */
    public function getSizeTextAttr($value, $data)
    {
        $size = $data['size'];
        if ($size < 1024) {
            return $size . 'B';
        } elseif ($size < 1024 * 1024) {
            return round(bcdiv($size, 1024, 2), 2) . 'KB';
        } elseif ($size < 1024 * 1024 * 1024) {
            return round(bcdiv($size, 1024 * 1024, 2), 2) . 'MB';
        } else {
            return round(bcdiv($size, 1024 * 1024 * 1024, 2), 2) . 'GB';
        }
    }

    /**
     * 设置上传文件的field
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 16:01
     */
    public function setUploadField($field)
    {
        $this->uploadField = $field;
    }

    /**
     * 获取上传文件的field
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 16:01
     */
    public function getUploadField()
    {
        return $this->uploadField;
    }

    /**
     * 加载配置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 16:27
     */
    private function loadConfig()
    {
        //TODO::加载cache,database数据
        // Arr::mergeMultiple($this->defaultConfig,);
        Config::set($this->defaultConfig, 'filesystem');
    }

    /**
     * 校验上传文件
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 16:27
     */
    public function valid()
    {
        $field = $this->getUploadField();
        $file = request()->file($field);
        if (!$file)
            error("请上传文件");
        //如果有限制校验规则
        $rules = config('filesystem.valid');
        $limitExt = [];
        $rules = array_map(function ($item) use ($limitExt) {
            $item['ext'] = strtolower($item['ext']);
            $limitExt = array_merge($limitExt, explode(',', $item['ext']));
            return $item;
        }, $rules);
        if (!empty($limitExt)) {
            foreach ($rules as $rule) {
                //存在当前的扩展
                if (in_array(strtolower($file->getOriginalExtension()), explode(',', $rule['ext']))) {
                    $fileExt = !$rule['ext'] ?: "fileExt:{$rule['ext']}";
                    $fileSize = !$rule['size'] ?: "fileSize:{$rule['size']}";
                    validate([
                        $field => implode('|', [$fileExt, $fileSize])
                    ], [
                        "{$field}.fileExt"  => "不支持此格式,请上传{$rule['ext']}的格式",
                        "{$field}.fileSize" => "上传{$rule['name']}不能超过" . round($rule['size'] / 1024 / 1024, 2) . 'MB',
                    ])->check([$field => $file]);
                }
            }
        }
    }

    /**
     * 上传
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 16:27
     */
    public function upload()
    {
        $this->loadConfig();
        $file = request()->file('file');
        $this->valid();
        $path = Filesystem::putFile('', $file);
        $data = [
            'name'        => $file->getOriginalName(),
            'path'        => $path,
            'url'         => str_replace('\\', '/', Filesystem::getUrl($path)),
            'driver'      => config('filesystem.default'),
            'mime'        => $file->getOriginalMime(),
            'size'        => $file->getSize(),
            'module'      => app('http')->getName(),
            'uploader_id' => get_user_id(),
        ];
        $fileModel = self::create($data);
        $fileModel->append(['size_text'])->visible(['name', 'url']);
        return $fileModel;
    }


}
