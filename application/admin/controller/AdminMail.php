<?php
/**
 * 发送邮件插件
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/6/5
 */
namespace app\admin\controller;

use app\common\model\AdminMailLogs;
use email\PHPMailer;
use think\Session;

class AdminMail extends Base
{

    public function index()
    {
        if ($this->request->isPost()) {

            $post    = $this->request->post(false);
            $user_id = Session::get('user.user_id');

            if (isset($post['email_address']) && isset($post['email_subject']) && isset($post['email_content'])) {
                $address_from = trim($post['email_address']);
                $subject      = $post['email_subject'];
                $content      = $post['email_content'];

                if (false !== strpos($address_from, ',')) {
                    $address = explode(',', $address_from);
                } else {
                    if (!filter_var($address_from, FILTER_VALIDATE_EMAIL)) {
                        return $this->do_error('邮件地址格式错误');
                    }
                    $address = $address_from;
                }


                if (empty($subject)) {
                    return $this->do_error('标题不能为空');
                }

                if (empty($content)) {
                    return $this->do_error('内容不能为空');
                }

                $attachment = [];
                $email_info = [];
                $file       = request()->file('email_attachment');
                if (($file != null)) {
                    $info = $file->validate(['size' => config('email_file_upload_max_size'), 'ext' => config('email_file_upload_ext')])->move(config('email_file_upload_path') . $user_id);
                    if ($info) {
                        $email_info['attachment_name'] = $attachment['name'] = $info->getInfo('name');
                        $email_info['attachment_path'] = $attachment['path'] = config('email_file_upload_path') . $user_id . DS . $info->getSaveName();
                        $email_info['attachment_url']  = config('email_file_upload_url') . $user_id . DS . $info->getSaveName();
                    } else {
                        return $this->ajaxReturnError($file->getError());
                    }
                }

                $email_info['address'] = $address_from;
                $email_info['subject'] = $subject;
                $email_info['content'] = $content;
                $email_info['user_id'] = $user_id;

                $result = $this->send_email($address, $subject, $content, $attachment);
                if ($result['error'] == 1) {
                    $email_info['is_success']    = 0;
                    $email_info['error_message'] = $result['message'];
                    AdminMailLogs::create($email_info);
                    return $this->do_error($result['message']);
                }
                AdminMailLogs::create($email_info);
                return $this->do_success('发送成功');
            }
            return $this->do_error('地址，标题，内容不能为空');

        }
        return $this->fetch();
    }


    /**
     * 发送邮件
     * @param string $address 需要发送的邮箱地址 发送给多个地址需要写成数组形式
     * @param string $subject 标题
     * @param string $content 内容
     * @param array $attachment 附件
     * @return bool 是否成功
     * @throws \email\phpmailerException
     */
    function send_email($address, $subject, $content, $attachment = [])
    {
        $email_smtp      = config('email_smtp');
        $email_username  = config('email_username');
        $email_password  = config('email_password');
        $email_from_name = config('email_from_name');

        if (empty($email_smtp) || empty($email_username) || empty($email_password) || empty($email_from_name)) {
            return array("error" => 1, "message" => '邮箱配置不完整');
        }

        $phpmailer = new Phpmailer();
        // 设置PHPMailer使用SMTP服务器发送Email
        $phpmailer->IsSMTP();
        // 设置为html格式
        $phpmailer->IsHTML(true);
        // 设置邮件的字符编码'
        $phpmailer->CharSet = 'UTF-8';
        // 设置SMTP服务器。
        $phpmailer->Host = $email_smtp;
        // 设置为"需要验证"
        $phpmailer->SMTPAuth = true;
        // 设置用户名
        $phpmailer->Username = $email_username;
        // 设置密码
        $phpmailer->Password = $email_password;
        // 设置邮件头的From字段。
        $phpmailer->From = $email_username;
        // 设置发件人名字
        $phpmailer->FromName = $email_from_name;
        // 添加收件人地址，可以多次使用来添加多个收件人
        if (is_array($address)) {
            foreach ($address as $addressv) {
                $phpmailer->AddAddress($addressv);
            }
        } else {
            $phpmailer->AddAddress($address);
        }
        // 设置邮件标题
        $phpmailer->Subject = $subject;
        // 设置邮件正文
        $phpmailer->Body = $content;

        if (count($attachment) > 0) {
            $phpmailer->addAttachment($attachment['path'], $attachment['name']);
        }

        // 发送邮件。
        if (!$phpmailer->Send()) {
            $phpmailererror = $phpmailer->ErrorInfo;
            return array("error" => 1, "message" => $phpmailererror);
        } else {
            return array("error" => 0);
        }
    }

}