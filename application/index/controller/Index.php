<?php
/**
 * 网站首页
 *
 */

namespace app\index\controller;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use think\Config;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function hello()
    {
        return 'hello';
    }

    //打赏
    public function reward()
    {
        $chanel  = get_browser_type($this->request->header('user-agent'));
        $pay_uri = Config::get('reward_url.' . $chanel);
        if ($chanel == 'alipay' || $chanel == 'jd') {
            $this->redirect($pay_uri);
        } else {


            if ($chanel == 'wechat' || $chanel == 'qq') {
                $qrcode = new QrCode($pay_uri);
                $qrcode
                    ->setWriterByName('png')
                    ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
                   /* ->setLogoPath(ROOT_PATH . 'public' . '/favicon-96x96.png');*/

                $qrcode->writeFile(ROOT_PATH . 'public' . '/reward-' . $chanel . '.png');
                $this->assign([
                    'chanel'        => $chanel,
                    'reward_qrcode' => $this->request->domain() . '/reward-' . $chanel . '.png',
                ]);
                return $this->fetch();
            }
        }
    }
}