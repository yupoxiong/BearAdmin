<?php
/**
 * 阿里大于
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/6/5
 */
namespace app\admin\controller;

use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;


class Alidayu extends Base
{
    public function index()
    {
        if ($this->request->isPost()) {

            $post = $this->post;
            if (!isset($post['mobile'])) {
                return $this->do_error('手机号不能为空');
            }

            $mobile = $post['mobile'];
            if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
                return $this->do_error('手机号格式不正确');
            }

            $config = [
                'app_key'    => '236384',
                'app_secret' => '96c201d52ebfce2123f430569d8918d2',
                // 'sandbox'    => true,  // 是否为沙箱环境，默认false
            ];

            $client = new Client(new App($config));
            $req    = new AlibabaAliqinFcSmsNumSend;

            $req->setRecNum($mobile)
                ->setSmsParam([
                    'code' => rand(100000, 999999),
                ])
                ->setSmsFreeSignName('签名')
                ->setSmsTemplateCode('这里填写模版');

            $resp = $client->execute($req);
            if (isset($resp->result) && $resp->result->success) {
                return $this->do_success('发送成功');
            } else {
                return $this->do_error($resp->sub_msg);
            }

            //$user_id = Session::get('user.user_id');
            /**
             *
             * object(stdClass)#29 (2) {
             * ["result"] => object(stdClass)#35 (3) {
             * ["err_code"] => string(1) "0"
             * ["model"] => string(26) "107918611759^1110619378067"
             * ["success"] => bool(true)
             * }
             * ["request_id"] => string(13) "12kbd1pbkju6b"
             * }
             **********
             * object(stdClass)#29 (5) {
             * ["code"] => int(15)
             * ["msg"] => string(20) "Remote service error"
             * ["sub_code"] => string(31) "isv.TEMPLATE_MISSING_PARAMETERS"
             * ["sub_msg"] => string(184) "参数缺失，缺少参数：product，模板配置：验证码${code}，您正在注册成为${product}用户，感谢您的支持！，传参：{&quot;code&quot;:&quot;486306&quot;}"
             * ["request_id"] => string(12) "z2907vipro1a"
             * }
             */

        }
        return $this->fetch();
    }

}