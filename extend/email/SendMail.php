<?php
/**
 * 发送邮件扩展
 * @author yupoxiong<i@yufuping.com>
 */
namespace  email;
class SendMail
{

    /**
     * 发送邮件
     * @param string $address 需要发送的邮箱地址 发送给多个地址需要写成数组形式
     * @param string $subject 标题
     * @param string $content 内容
     * @param array $attachment 附件
     * @return bool 是否成功
     * @throws \email\phpmailerException
     */
    public static function send_email($address, $subject, $content, $attachment = [])
    {
        $email_smtp      = config('email_smtp');
        $email_username  = config('email_username');
        $email_password  = config('email_password');
        $email_from_name = config('email_from_name');


        if (empty($email_smtp) || empty($email_username) || empty($email_password) || empty($email_from_name)) {
            return array("error" => 1, "message" => '邮箱配置不完整');
        }

        $phpmailer = new PHPMailer();
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

        $phpmailer->Port = 465;

        $phpmailer->SMTPSecure = 'ssl';

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