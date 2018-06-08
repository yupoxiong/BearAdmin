<?php
/**
 * 附件模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\model;

use traits\model\SoftDelete;

class Attachments extends Model
{
    use SoftDelete;

    protected $name = 'attachments';
    protected $autoWriteTimestamp = true;

    protected $filetype = [
        '图片'   => ['jpg', 'bmp', 'png', 'jpeg', 'gif', 'svg'],
        '文档'   => ['txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf'],
        '压缩文件' => ['rar', 'zip', '7z', 'tar'],
        '音视'   => ['mp3', 'ogg', 'flac', 'wma', 'ape'],
        '视频'   => ['mp4', 'wmv', 'avi', 'rmvb', 'mov', 'mpg']
    ];

    protected $filethumbnail = [
        'picture'                            => ['jpg', 'bmp', 'png', 'jpeg', 'gif', 'svg'],
        '/static/filethumbnail/txt.svg'      => ['txt', 'pdf'],
        '/static/filethumbnail/pdf.svg'      => ['pdf'],
        '/static/filethumbnail/word.svg'     => ['doc', 'docx'],
        '/static/filethumbnail/excel.svg'    => ['xls', 'xlsx'],
        '/static/filethumbnail/archives.svg' => ['rar', 'zip', '7z', 'tar'],
        '/static/filethumbnail/audio.svg'    => ['mp3', 'ogg', 'flac', 'wma', 'ape'],
        '/static/filethumbnail/video.svg'    => ['mp4', 'wmv', 'avi', 'rmvb', 'mov', 'mpg']
    ];

    //关联后台用户
    public function adminUser()
    {
        return $this->belongsTo('AdminUser', 'user_id', 'id');
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
        for ($i = 0; $value >= 1024 && $i < 4; $i++) $value /= 1024;
        return round($value, 2) . $units[$i];
    }

    //文件分类
    public function getFileTypeAttr($value, $data)
    {
        $type      = '其他';
        $extension = $data['extension'];
        foreach ($this->filetype as $name => $array) {
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
        $thumbnail = '/static/thumbnail/unkown.svg';
        $extension = $data['extension'];
        foreach ($this->filethumbnail as $name => $array) {
            if (in_array($extension, $array)) {
                $thumbnail = $name == 'picture' ? $data['url'] : $name;
                break;
            }
        }
        return $thumbnail;
    }

    public function getFileUrlAttr($value, $data)
    {
        $request = request();
        $url_pre = $request->scheme() . '://' . $request->host();
        return $url_pre . $data['url'];
    }


    public function upload($name, $path = '', $validate = [], $user_id = 0)
    {
        $config = config('attchment');
        $file   = request()->file($name);
        if ($file) {
            $file_path = $config['path'] . $path;
            $file_url  = $config['url'] . $path;
            $validate  = array_merge($config['validate'], $validate);
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
                return $this->create($file_info);
            } else {
                $this->error = $file->getError();
            }
        } else {
            $this->error='无法获取文件';
        }
        return false;
    }
}
