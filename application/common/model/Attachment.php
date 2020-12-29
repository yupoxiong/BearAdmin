<?php
/**
 * 附件模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\model;

use think\model\concern\SoftDelete;

class Attachment extends Model
{
    use SoftDelete;

    protected $name = 'attachment';
    protected $autoWriteTimestamp = true;

    protected $fileType = [
        '图片'   => ['jpg', 'bmp', 'png', 'jpeg', 'gif', 'svg'],
        '文档'   => ['txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf'],
        '压缩文件' => ['rar', 'zip', '7z', 'tar'],
        '音视'   => ['mp3', 'ogg', 'flac', 'wma', 'ape'],
        '视频'   => ['mp4', 'wmv', 'avi', 'rmvb', 'mov', 'mpg']
    ];

    protected $fileThumb = [
        'picture'      => ['jpg', 'bmp', 'png', 'jpeg', 'gif', 'svg'],
        'txt.svg'      => ['txt', 'pdf'],
        'pdf.svg'      => ['pdf'],
        'word.svg'     => ['doc', 'docx'],
        'excel.svg'    => ['xls', 'xlsx'],
        'archives.svg' => ['rar', 'zip', '7z', 'tar'],
        'audio.svg'    => ['mp3', 'ogg', 'flac', 'wma', 'ape'],
        'video.svg'    => ['mp4', 'wmv', 'avi', 'rmvb', 'mov', 'mpg']
    ];

    protected function initialize()
    {
        $thumb_path      = config('attachment.thumb_path');
        $this->fileThumb = [
            'picture'                    => ['jpg', 'bmp', 'png', 'jpeg', 'gif', 'svg'],
            $thumb_path . 'txt.svg'      => ['txt', 'pdf'],
            $thumb_path . 'pdf.svg'      => ['pdf'],
            $thumb_path . 'word.svg'     => ['doc', 'docx'],
            $thumb_path . 'excel.svg'    => ['xls', 'xlsx'],
            $thumb_path . 'archives.svg' => ['rar', 'zip', '7z', 'tar'],
            $thumb_path . 'audio.svg'    => ['mp3', 'ogg', 'flac', 'wma', 'ape'],
            $thumb_path . 'video.svg'    => ['mp4', 'wmv', 'avi', 'rmvb', 'mov', 'mpg']
        ];
        parent::initialize();
    }

    //关联后台用户
    public function adminUser()
    {
        return $this->belongsTo('AdminUser', 'admin_user_id', 'id');
    }

    //关联前台用户
    public function User()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }

    //格式化大小
    public function getSizeAttr($value)
    {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $value >= 1024 && $i < 4; $i++) {
            $value /= 1024;
        }
        return round($value, 2) . $units[$i];
    }

    //文件分类
    public function getFileTypeAttr($value, $data)
    {
        $type      = '其他';
        $extension = $data['extension'];
        foreach ($this->fileType as $name => $array) {
            if (in_array($extension, $array)) {
                $type = $name;
                break;
            }
        }
        return $type;
    }


    //文件预览
    public function getThumbnailAttr($value, $data)
    {
        $thumbnail = config('attachment.thumb_path') . 'unknown.svg';
        $extension = $data['extension'];
        foreach ($this->fileThumb as $name => $array) {
            if (in_array($extension, $array)) {
                $thumbnail = $name === 'picture' ? $data['url'] : $name;
                break;
            }
        }
        return $thumbnail;
    }

    public function getFileUrlAttr($value, $data): string
    {
        $url_pre =  request()->scheme() . '://' .  request()->host();
        return $url_pre . $data['url'];
    }


    public function upload($name, $path = '', $validate = [], $admin_user_id = 0, $user_id = 0)
    {

        if (!$_FILES[$name]['name']) {
            $this->error = '请选择文件';
            return false;
        }

        $file = request()->withFiles([$name => $_FILES[$name]])->file($name);
        if ($file) {
            $file_path = config('attachment.path') . $path;
            $file_url  = config('attachment.url') . $path;
            $validate  = array_merge(config('attachment.validate'), $validate);
            $info      = $file->validate($validate)->move($file_path);
            if ($info) {
                $file_info = [
                    'admin_user_id' => $admin_user_id,
                    'user_id'       => $user_id,
                    'original_name' => $info->getInfo('name'),
                    'save_name'     => $info->getFilename(),
                    'save_path'     => str_replace("\\", '/', $file_path . $info->getSaveName()),
                    'extension'     => $info->getExtension(),
                    'mime'          => $info->getInfo('type'),
                    'size'          => $info->getSize(),
                    'md5'           => $info->hash('md5'),
                    'sha1'          => $info->hash(),
                    'url'           => str_replace("\\", '/', $file_url . $info->getSaveName())
                ];
                return self::create($file_info);
            }

            $this->error = $file->getError();
        } else {
            $this->error = '无法获取文件';
        }
        return false;
    }


    /**
     * @param $name
     * @param string $path
     * @param array $validate
     * @param int $admin_user_id
     * @param int $user_id
     * @return array|bool
     */
    public function uploadMulti($name, $path = '', $validate = [], $admin_user_id = 0, $user_id = 0)
    {
        $result = [];

        $files = request()->withFiles([$name => $_FILES[$name]])->file($name);

        $file_path = config('attachment.path') . $path;
        $file_url  = config('attachment.url') . $path;
        $validate  = array_merge(config('attachment.validate'), $validate);

        if ($files) {
            foreach ($files as $file) {
                $info = $file->validate($validate)->move($file_path);
                if ($info) {
                    $file_info = [
                        'admin_user_id' => $admin_user_id,
                        'user_id'       => $user_id,
                        'original_name' => $info->getInfo('name'),
                        'save_name'     => $info->getFilename(),
                        'save_path'     => str_replace("\\", '/', $file_path . $info->getSaveName()),
                        'extension'     => $info->getExtension(),
                        'mime'          => $info->getInfo('type'),
                        'size'          => $info->getSize(),
                        'md5'           => $info->hash('md5'),
                        'sha1'          => $info->hash(),
                        'url'           => str_replace("\\", '/', $file_url . $info->getSaveName())
                    ];
                    $file_item = self::create($file_info);
                    $result[]  = $file_item->url;
                }
                $this->error = $file->getError();
            }
            if (count($result) > 0) {
                return $result;
            }

            return false;
        }

        $this->error = '无法获取文件';
        return false;
    }
}
