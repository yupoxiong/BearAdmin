<?php
/**
 * 扩展功能控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Endroid\QrCode\ErrorCorrectionLevel;
use app\admin\model\AdminMailLogs;
use app\admin\model\ExcelExamples;
use Overtrue\EasySms\EasySms;
use Endroid\QrCode\QrCode;
use email\SendMail;
use tools\Ueditor;
use tools\AliOss;
use tools\Qiniu;
use Parsedown;

class Extend extends Base
{
    protected $extendUrl = [
        [
            'id'    => 1,
            'url'   => 'admin/extend/email',
            'icon'  => 'fa-info',
            'title' => '邮件发送',
        ],
        [
            'id'    => 2,
            'url'   => 'admin/extend/sms',
            'icon'  => 'fa-info',
            'title' => '短信发送',
        ],
        [
            'id'    => 3,
            'url'   => 'admin/extend/markdown',
            'icon'  => 'fa-info',
            'title' => 'MarkDown编辑器',
        ],
        [
            'id'    => 4,
            'url'   => 'admin/extend/qrcode',
            'icon'  => 'fa-info',
            'title' => '二维码生成',
        ],
        [
            'id'    => 5,
            'url'   => 'admin/extend/ueditor',
            'icon'  => 'fa-info',
            'title' => 'UEditor编辑器',
        ],
        [
            'id'    => 6,
            'url'   => 'admin/extend/aliyunoss',
            'icon'  => 'fa-info',
            'title' => '阿里云oss',
        ],

        [
            'id'    => 7,
            'url'   => 'admin/extend/qiniu',
            'icon'  => 'fa-info',
            'title' => '七牛云存储',
        ],

        [
            'id'    => 8,
            'url'   => 'admin/extend/excel',
            'icon'  => 'fa-info',
            'title' => 'excel',
        ],
    ];

    //扩展列表
    public function index()
    {

        $list   = $this->extendUrl;
        $colors = [
            'bg-aqua'   => 'bg-aqua',
            'bg-green'  => 'bg-green',
            'bg-yellow' => 'bg-yellow',
            'bg-red'    => 'bg-red',
            'bg-purple' => 'bg-purple',
            'bg-teal'   => 'bg-teal',
            'bg-navy'   => 'bg-navy',
        ];

        $this->assign([
            'list'   => $list,
            'colors' => $colors
        ]);

        return $this->fetch();
    }


    //发送邮件
    public function email()
    {
        if ($this->request->isPost()) {

            $param  = $this->request->param(false);
            $result = $this->validate($param, 'AdminMail.add');

            if (true !== $result) {
                return $this->error($result);
            }

            if (false !== strpos($param['address'], ',')) {
                $param['address'] = explode(',', $param['address']);
            }

            $param['user_id'] = $this->uid;
            $attachment       = [];
            $file             = request()->file('attachment');
            if (($file != null)) {
                $info = $file->validate(['size' => config('email_file_upload_max_size'), 'ext' => config('email_file_upload_ext')])->move(config('email_file_upload_path') . $this->uid);
                if ($info) {
                    $param['attachment_name'] = $attachment['name'] = $info->getInfo('name');
                    $param['attachment_path'] = $attachment['path'] = config('email_file_upload_path') . $this->uid . DS . $info->getSaveName();
                    $param['attachment_url']  = config('email_file_upload_url') . $this->uid . DS . $info->getSaveName();
                } else {
                    return $this->error($file->getError());
                }
            }

            $result = SendMail::send_email($param['address'], $param['subject'], $param['content'], $attachment);
            if ($result['error'] == 1) {
                $info['is_success']    = 0;
                $info['error_message'] = $result['message'];
                AdminMailLogs::create($param);
                return $this->error($result['message']);
            }
            AdminMailLogs::create($param);
            return $this->success('发送成功');
        }
        return $this->fetch();
    }


    //发送短信  目前只示范阿里大于，其他可参考https://github.com/overtrue/easy-sms/blob/master/README.md
    public function sms()
    {
        if ($this->request->isPost()) {
            $easySms = new EasySms(config('easysms'));
            $success = false;
            $result  = [];
            $msg     = '';
            try {
                $result  = $easySms->send($this->param['mobile'], [
                    'template' => 'SMS_8520..5029',
                    'data'     => [
                        'name' => '0210'
                    ],
                ]);
                $success = true;
            } catch (\Exception $e) {
                $msg = $e->getMessage();
            }

            if ($success && $result['alidayu']['status'] == 'success') {
                return $this->success('发送成功', self::URL_CURRENT);
            }

            return $this->error($msg);
        }

        $this->assign('sub_title', '发送');

        return $this->fetch();
    }


    //markdown编辑器
    public function markdown()
    {
        if ($this->request->isPost()) {

            $post      = $this->request->post(false);
            $Parsedown = new Parsedown();
            $content   = $Parsedown->text($post['content']);
            $this->assign('content', $content);
            return $this->fetch('markdown-view');
        }
        return $this->fetch();
    }


    //二维码生成
    public function qrcode()
    {
        if ($this->request->isPost()) {

            $param = $this->request->param(false);
            if (isset($param['content']) && !empty($param['content'])) {

                $qrcode_content = $param['content'];
                $qrcode         = new QrCode($qrcode_content);

                $qrcode
                    ->setWriterByName('png')
                    ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
                    ->setLogoPath(config('qrcode_path') . 'lueluelue.png');

                $qrcode->writeFile(config('qrcode_path') . $this->uid . '-qrcode.png');

                $data['qrcode'] = config('qrcode_url') . $this->uid . '-qrcode.png';

                return $this->success('success', self::URL_CURRENT, $data);

            }
            return $this->error('内容不能为空');
        }

        $this->assign('sub_title', '生成');
        return $this->fetch();
    }


    //UEditor
    public function ueditor()
    {
        if ($this->request->isPost()) {
            $param = $this->request->param(false);

            if (isset($param['content'])) {
                $this->assign([
                    'content' => $param['content'],

                ]);
                return $this->fetch('ue-view');
            }
            return $this->error('标题和内容不能为空');
        }

        return $this->fetch();
    }

    //UEditor上传等
    public function ueserver()
    {
        $config  = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(ROOT_PATH . "public/static/admin/plugins/ueditor/php/config.json")), true);
        $action  = $this->param['action'];
        $ueditor = new Ueditor($config);
        return $ueditor->server($action);
    }


    //阿里云oss
    public function aliyunoss()
    {
        if ($this->request->isPost()) {
            $file = request()->file('file');
            if (!$file) {
                return $this->error('请上传文件');
            }

            $info = $file->validate([
                'size' => config('file_upload_max_size'),
                'ext'  => config('file_upload_ext')
            ])->move(config('file_upload_path') . $this->uid);

            if ($info) {
                $file_info = [
                    'path' => $info->getPathname(),
                    'name' => $info->getFilename()
                ];

                $aliOss = new AliOss();
                $result = $aliOss->upload($file_info);

                return $this->success('success', self::URL_CURRENT, $result);
            }
            return $this->error($file->getError());
        }
        return $this->fetch();
    }


    //七牛云存储
    public function qiniu()
    {
        if ($this->request->isPost()) {
            $file = request()->file('file');
            if (!$file) {
                return $this->error('请上传文件');
            }

            $info = $file->validate([
                'size' => config('file_upload_max_size'),
                'ext'  => config('file_upload_ext')
            ])->move(config('file_upload_path') . $this->uid);

            if ($info) {
                $file_info = [
                    'path' => $info->getPathname(),
                    'name' => $info->getFilename()
                ];

                $qiniu  = new Qiniu();
                $data   = $qiniu->upload($file_info);
                $result = config('qiniu.url') . $data[0]['key'];
                return $this->success('success', self::URL_CURRENT, $result);
            }
            return $this->error($file->getError());
        }
        return $this->fetch();
    }


    public function excel()
    {
        if ($this->request->isPost()) {

            $file = request()->file('export');
            if (!$file) {
                return $this->error('请上传文件');
            }
            $info = $file->validate(['ext' => 'xlsx', 'size' => config('file_upload_max_size')])->move(ROOT_PATH . 'uploads' . DS . 'excel');
            if ($info) {

                $file_name    = $info->getPathname();
                $spreadsheet    = IOFactory::load($file_name);
                $excel_array = $spreadsheet->getActiveSheet()->toArray();
                array_shift($excel_array);  //删除第一个数组(标题);
                
                $person = [];
                foreach ($excel_array as $k => $v) {
                    $person[$k]['name'] = $v[0];
                    $person[$k]['age']  = $v[1];
                    $person[$k]['sex']  = $v[2];
                    $person[$k]['city'] = $v[3];
                }

                $excel_examples = new ExcelExamples();
                if ($excel_examples->saveAll($person)) {
                    return $this->success('导入成功');
                }
                return $this->error('导入失败');
            }
            return $this->error('上传失败');

        }

        $excel_examples = new ExcelExamples();

        if (isset($this->param['act']) && $this->param['act'] == 'download') {
            $header = ['姓名', '年龄', '性别', '城市'];
            $data   = $excel_examples->order("id desc")->select();

            foreach ($data as $d) {
                $record = [];
                //$record['id']=$d->id;
                $record['name'] = $d->name;
                $record['age']  = $d->age;
                $record['sex']  = $d->sex;
                $record['city'] = $d->city;
                $body[]         = $record;
            }
            return $this->export($header, $body, "Excel导出例子", '2007');
        }

        $list = $excel_examples
            ->order('id desc')
            ->paginate(10);

        $this->assign([
            'list' => $list,
            'page' => $list->render()
        ]);

        return $this->fetch();
    }


    //导出方法
    function export($head, $body, $name = null, $version = '2007',$title='记录')
    {
        //config('app_trace',false);
        try {
            // 输出 Excel 文件头
            $name = empty($name) ? date('Y-m-d-H-i-s') : $name;

            $spreadsheet   = new Spreadsheet();
            $sheetPHPExcel = $spreadsheet->setActiveSheetIndex(0);
            $char_index    = range("A", "Z");

            // Excel 表格头
            foreach ($head as $key => $val) {
                $sheetPHPExcel->setCellValue("{$char_index[$key]}1", $val);
            }

            $spreadsheet->getActiveSheet()->setTitle($title);

            // Excel body 部分
            foreach ($body as $key => $val) {
                $row = $key + 2;
                $col = 0;
                foreach ($val as $k => $v) {
                    $spreadsheet->getActiveSheet()->setCellValue("{$char_index[$col]}{$row}", $v);
                    $col++;
                }
            }

            // 版本差异信息
            $version_opt = [
                '2007' => [
                    'mime'       => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'ext'        => '.xlsx',
                    'write_type' => 'Xlsx',
                ],
                '2003' => ['mime'       => 'application/vnd.ms-excel',
                           'ext'        => '.xls',
                           'write_type' => 'Xls',
                ],
                'pdf'  => ['mime'       => 'application/pdf',
                           'ext'        => '.pdf',
                           'write_type' => 'PDF',
                ],
                'ods'  => ['mime'       => 'application/vnd.oasis.opendocument.spreadsheet',
                           'ext'        => '.ods',
                           'write_type' => 'OpenDocument',
                ],
            ];

            header('Content-Type: ' . $version_opt[$version]['mime']);
            header('Content-Disposition: attachment;filename="' . $name . $version_opt[$version]['ext'] . '"');
            header('Cache-Control: max-age=0');

            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            return $objWriter->save('php://output');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}