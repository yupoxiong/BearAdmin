<?php
/**
 * 附件扩展
 * @author yupoxiong<i@yufuping.com>
 */

namespace tools;

use app\common\model\Attachments;

class Attachment
{
    protected $config;

    public function __construct($config = null)
    {
        $this->config = config('attchment');
        if ($config != null) {
            $this->config = $config;
        }
    }

    /**
     * 上传
     * @param string $name 字段名
     * @param null|string $path 上传路径
     * @param array|null $validate 验证规则
     * @param int $user_id
     * @return array
     */
    public function upload($name, $path = '', $validate = [], $user_id = 0)
    {
        $result = [
            'code' => 0,
            'msg'  => 'fail',
            'data' => ''
        ];
        $file   = request()->file($name);
        if ($file) {
            $file_path = $this->config['path'] . $path;
            $file_url  = $this->config['url'] . $path;
            $validate  = array_merge($this->config['validate'], $validate);
            $info      = $file->validate($validate)->move($file_path);
            if ($info) {
                $file_info = [
                    'user_id'       => $user_id,
                    'original_name' => $info->getInfo('name'),
                    'save_name'     => $info->getFilename(),
                    'save_path'     => str_replace("\\", "/",$file_path . $info->getSaveName()),
                    'extension'     => $info->getExtension(),
                    'mime'          => $info->getInfo('type'),
                    'size'          => $info->getSize(),
                    'md5'           => $info->hash('md5'),
                    'sha1'          => $info->hash(),
                    'url'           => str_replace("\\", "/",$file_url . $info->getSaveName())
                ];

                $data = Attachments::create($file_info);
                if ($data) {
                    $result['code'] = 1;
                    $result['data'] = $file_info['url'];
                    $result['msg']  = '上传成功';
                } else {
                    $result['msg'] = '保存失败';
                }
            } else {
                $result['msg'] = '保存失败,错误信息:' . $file->getError();
            }
        } else {
            $result['msg'] = '无法获取文件';
        }
        return $result;
    }

    /**
     * 下载
     */
    public static function download()
    {

    }


    /**
     * 移动目录
     */
    public static function move()
    {

    }
}