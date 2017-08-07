<?php
/**
 * 二维码生成
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use think\Session;

class AdminQrcode extends Base
{

    /**
     * 生成二维码主页
     */
    public function index()
    {

        if ($this->request->isPost()) {

            $user_id = Session::get('user.user_id');

            $post = $this->request->post(false);
            if (isset($post['qrcode_content'])) {
                
                $qrcode_content = $post['qrcode_content'];
                $qrcode  = new QrCode($qrcode_content);
                $qrcode->setSize(300);
                $qrcode
                    ->setWriterByName('png')
                    ->setMargin(10)
                    ->setEncoding('UTF-8')
                    ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
                    ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
                    ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
                    /*->setLabel('Scan the code', 16, ROOT_PATH.'vendor/endroid/qrcode/assets/noto_sans.otf', LabelAlignment::CENTER)*/
                    ->setLogoPath(config('qrcode_path').'lueluelue.png')
                    ->setLogoWidth(100)
                    ->setValidateResult(false)
                ;

                $qrcode->writeFile(config('qrcode_path').$user_id.'-qrcode.png');

                $this->api_result['result']['url'] = config('qrcode_url').$user_id.'-qrcode.png';
                $this->api_result['message'] = '生成成功';
                $this->api_result['status'] = 200;
                return $this->ajaxReturnData($this->api_result);
            }
            return $this->ajaxReturnError('内容不能为空');
        } 
          
        return $this->fetch();
    }
    
}
