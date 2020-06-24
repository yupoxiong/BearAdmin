<?php
/**
 * Ueditor
 * @author yupoxiong<i@yufuping.com>
 */

namespace tools;

class UEditor
{
    protected $config;
    protected $request;
    protected $param;

    public function __construct($config)
    {
        $this->config  = $config;
        $this->request = request();
        $this->param   = request()->param();
    }

    public function server($action)
    {


        $config = $this->config;

        switch ($action) {
            case 'config':
                $result = $config;
                break;
            /* 上传图片 */
            case 'uploadimage':
                $fieldName = $config['imageFieldName'];
                $result    = $this->upFile($fieldName);
                break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
                $config    = array(
                    'pathFormat' => $config['scrawlPathFormat'],
                    'maxSize'    => $config['scrawlMaxSize'],
                    'allowFiles' => $config['scrawlAllowFiles'],
                    'oriName'    => 'upfile'
                );

                $fieldName = $config['oriName'];
                $result    = $this->upBase64($config, $fieldName);
                break;
            /* 上传视频 */
            case 'uploadvideo':
                $fieldName = $config['videoFieldName'];
                $result    = $this->upFile($fieldName);
                break;
            /* 上传文件 */
            case 'uploadfile':
                $fieldName = $config['fileFieldName'];
                $result    = $this->upFile($fieldName);
                break;
            /* 列出图片 */
            case 'listimage':
                $allowFiles = $config['imageManagerAllowFiles'];
                $listSize   = $config['imageManagerListSize'];
                $path       = $config['imageManagerListPath'];
                $get        = $this->param;
                $result     = $this->fileList($allowFiles, $listSize, $get);
                break;
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $config['fileManagerAllowFiles'];
                $listSize   = $config['fileManagerListSize'];
                $path       = $config['fileManagerListPath'];
                $get        = $_GET;
                $result     = $this->fileList($allowFiles, $listSize, $get);
                break;
            /* 抓取远程文件 */
            case 'catchimage':
                $config    = array(
                    'pathFormat' => $config['catcherPathFormat'],
                    'maxSize'    => $config['catcherMaxSize'],
                    'allowFiles' => $config['catcherAllowFiles'],
                    'oriName'    => 'remote.png'
                );
                $fieldName = $config['catcherFieldName'];
                /* 抓取远程图片 */
                $list = array();
                isset($this->param[$fieldName]) ? $source = $this->param[$fieldName] : $source = [];

                foreach ($source as $imgUrl) {
                    $info   = json_decode($this->saveRemote($config, $imgUrl), true);
                    $list[] = array(
                        'state'    => $info['state'],
                        'url'      => $info['url'],
                        'size'     => $info['size'],
                        'title'    => htmlspecialchars($info['title']),
                        'original' => htmlspecialchars($info['original']),
                        'source'   => htmlspecialchars($imgUrl)
                    );
                }

                $result = array(
                    'state' => count($list) ? 'SUCCESS' : 'ERROR',
                    'list'  => $list
                );
                break;
            default:
                $result = ['state' => '请求地址出错'];
                break;
        }

        /* 输出结果 */
        if (isset($_GET['callback'])) {
            if (preg_match("/^[\w_]+$/", $_GET['callback'])) {
                return htmlspecialchars($_GET['callback']) . '(' . $result . ')';
            }
            return json(['state' => 'callback参数不合法']);
        }
        return json($result);
    }


    //上传文件
    private function upFile($fieldName)
    {
        $file = request()->file($fieldName);
        $info = $file->move(app()->getRootPath() . 'public/uploads/ueditor');
        if ($info) {//上传成功
            $fname = '/uploads/ueditor/' . str_replace('\\', '/', $info->getSaveName());

            $imgArr = explode(',', 'jpg,gif,png,jpeg,bmp,ttf,tif');
            $imgExt = strtolower($info->getExtension());
            $isImg  = in_array($imgExt, $imgArr);

            if ($isImg) {//如果是图片，开始处理
                //$image = Image::open($file);
                $thumbnail = 1;
                $water     = 1;

                //在这里你可以根据你需要，调用ThinkPHP5的图片处理方法了
                /* if($water == 1){//文字水印
                     $image->text('aiqingxiaoji.com','./public/static/4.ttf',180,'#ff0000')->save('.'.$fname);
                 }
                 if($water ==2 ){//图片水印
                     $image->water('./public/img/df81.png',9,100)->save('.'.$fname);
                 }*/
                /*if($thumbnail == 1){//生成缩略图
                    $image->thumb(500,500,1)->save('.'.$fname);
                }*/
            }

            $data = array(
                'state'    => 'SUCCESS',
                'url'      => $fname,
                'title'    => $info->getFilename(),
                'original' => $info->getFilename(),
                'type'     => '.' . $info->getExtension(),
                'size'     => $info->getSize(),
            );
        } else {
            $data = array(
                'state' => $file->getError(),
            );
        }

        return $data;

    }

    //列出图片
    private function fileList($allowFiles, $listSize, $get)
    {
        $dirname    = '/uploads/ueditor/';
        $allowFiles = substr(str_replace('.', '|', implode('', $allowFiles)), 1);

        /* 获取参数 */
        $size  = isset($get['size']) ? htmlspecialchars($get['size']) : $listSize;
        $start = isset($get['start']) ? htmlspecialchars($get['start']) : 0;
        $end   = $start + $size;

        /* 获取文件列表 */
        $path  = $dirname;
        $files = $this->getFiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                'state' => 'no match file',
                'list'  => array(),
                'start' => $start,
                'total' => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }

        /* 返回数据 */
        $result = array(
            'state' => 'SUCCESS',
            'list'  => $list,
            'start' => $start,
            'total' => count($files)
        );

        return $result;
    }


    /*遍历获取目录下的指定类型的文件*/
    private function getFiles($path, $allowFiles, &$files = array()): ?array
    {
        $public_path = app()->getRootPath().'public';


        if (substr($path, strlen($path) - 1) !== '/') {
            $path .= '/';
        }

        $real_path = $public_path . $path;

        if (!is_dir($real_path)) {

            return null;
        }

        $handle = opendir($real_path);

        while (false !== ($file = readdir($handle))) {
            if ($file !== '.' && $file !== '..') {
                $path2 = $path . $file;

                if (is_dir($public_path . $path2)) {
                    $this->getFiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ')$/i', $file)) {
                        $files[] = array(
                            'url'   => $path2,
                            'mtime' => filemtime($public_path . $path2)
                        );
                    }
                }
            }
        }

        return $files;
    }

    //抓取远程图片
    private function saveRemote($config, $fieldName)
    {
        $imgUrl = htmlspecialchars($fieldName);
        $imgUrl = str_replace('&amp;', '&', $imgUrl);

        //http开头验证
        if (strpos($imgUrl, 'http') !== 0) {
            $data = array(
                'state' => '链接不是http链接',
            );
            return json_encode($data);
        }
        //获取请求头并检测死链
        $heads = get_headers($imgUrl);
        if (!(false !== strpos($heads[0], '200') && false !== stripos($heads[0], 'OK'))) {
            $data = array(
                'state' => '链接不可用',
            );
            return ($data);
        }
        //格式验证(扩展名验证和Content-Type验证)
        $fileType = strtolower(strrchr($imgUrl, '.'));
        if (!in_array($fileType, $config['allowFiles'], true) || false !== stripos($heads['Content-Type'], 'image')) {
            $data = array(
                'state' => '链接contentType不正确',
            );
            return ($data);
        }

        //打开输出缓冲区并获取远程图片
        ob_start();
        $context = stream_context_create(
            array('http' => array(
                'follow_location' => false // don't follow redirects
            ))
        );
        readfile($imgUrl, false, $context);
        $img = ob_get_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

        $dirname          = app()->getRootPath() . 'public/uploads/ueditor/remote/';
        $file['oriName']  = $m ? $m[1] : '';
        $file['filesize'] = strlen($img);
        $file['ext']      = strtolower(strrchr($config['oriName'], '.'));
        $file['name']     = uniqid('ue', true) . $file['ext'];
        $file['fullName'] = $dirname . $file['name'];
        $fullName         = $file['fullName'];

        //检查文件大小是否超出限制
        if ($file['filesize'] >= $config['maxSize']) {
            $data = array(
                'state' => '文件大小超出网站限制',
            );
            return ($data);
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true) && !is_dir($dirname)) {
            $data = array(
                'state' => '目录创建失败',
            );
            return json_encode($data);
        }

        if (!is_writable($dirname)) {
            $data = array(
                'state' => '目录没有写权限',
            );
            return ($data);
        }

        //移动文件
        if (!(file_put_contents($fullName, $img) && file_exists($fullName))) { //移动失败
            $data = array(
                'state' => '写入文件内容错误',
            );
            return json_encode($data);
        }
        //移动成功
        $data = array(
            'state'    => 'SUCCESS',
            'url'      => substr($file['fullName'], 1),
            'title'    => $file['name'],
            'original' => $file['oriName'],
            'type'     => $file['ext'],
            'size'     => $file['filesize'],
        );

        return $data;
    }

    /* 处理base64编码的图片上传 */
    private function upBase64($config, $fieldName)
    {

        $base64Data = $this->param[$fieldName];
        $img        = base64_decode($base64Data);

        $dirname          = app()->getRootPath() .'public/'. 'uploads/ueditor/scrawl/';
        $file['filesize'] = strlen($img);
        $file['oriName']  = $config['oriName'];
        $file['ext']      = '.png';
        $file['name']     = md5(uniqid('ue', true)) . $file['ext'];
        $file['fullName'] = $dirname . $file['name'];
        $file['urlName'] = '/uploads/ueditor/scrawl/' . $file['name'];
        $fullName         = $file['fullName'];

        //检查文件大小是否超出限制
        if ($file['filesize'] >= $config["maxSize"]) {
            $data = array(
                'state' => '文件大小超出网站限制',
            );
            return ($data);
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0755, true) && !is_dir($dirname)) {
            $data = array(
                'state' => '目录创建失败',
            );
            return json_encode($data);
        }

        if (!is_writable($dirname)) {
            $data = array(
                'state' => '目录没有写权限',
            );
            return $data;
        }

        //移动文件
        if (!(file_put_contents($fullName, $img) && file_exists($fullName))) { //移动失败
            $data = array(
                'state' => '写入文件内容错误',
            );
        } else { //移动成功
            $data = array(
                'state'    => 'SUCCESS',
                'url'      => $file['urlName'],
                'title'    => $file['name'],
                'original' => $file['oriName'],
                'type'     => $file['ext'],
                'size'     => $file['filesize'],
            );
        }

        return $data;
    }

}