<?php
/**
 * Api Auth认证类
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\api\controller;

use Exception;
use Firebase\JWT\JWT;
use think\Config;
/*use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Keys;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Signature;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Rsa\Sha512;*/

class Auth
{

    public $exp, $token;
    function __construct()
    {
        //self::$key = null != Config::get('app_key') ? Config::get('app_key') : 'beautiful_taoqi';
        /**
         * iss: 该JWT的签发者
        sub: 该JWT所面向的用户
        aud: 接收该JWT的一方
        exp(expires): 什么时候过期，这里是一个Unix时间戳
        iat(issued at): 在什么时候签发的
         */
        $this->token = [
            "iss" => "https://yufuping.com",
            "aud" => "http://example.com",
            "iat" => time(),
            "nbf" => time(),
            'exp' => 2145888000,
        ];
    }

    public function test(){
        echo strtotime('2038-01-01');
    }

    public function testen()
    {
        $param = [
            "iss" => "https://yufuping.com",  //issuer jwt的签发者。
            "aud" => "http://example.com",    //接收该JWT的一方
            "iat" => time(),              //issued at。
            "nbf" => time(),           //not before
            'exp' => time()+36,
            'user' =>['uid'=>1,'name'=>'amdin','role'=>'supper admin']
        ];
        $auth = new Auth();


        //$auth->enToken($token,Config::get('app_key'));
        echo (date("Y-m-d H:i:s",time()));
        dump($auth->enToken($param,Config::get('app_key')));

    }


    public function testde()
    {
        $token = $_GET['token'];
        $auth = new Auth();
        $key= Config::get('app_key');

        dump($auth->deToken($token,$key));
    }


    public function auth($user_name,$password){

    }

    /**
     * 生成token
     * @param $body
     * @param $key
     * @return string
     */
    static function enToken($body, $key){
        $jwt = JWT::encode($body, $key);
        return $jwt;

    }

    /**
     * 解析token
     * @param $token
     * @param $key
     * @return bool|object
     * @internal param $jwt
     */
    static function deToken($token,$key){
        try {
            $result = JWT::decode($token, $key, array('HS256'));
            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * 以下为本地测试时SSL证书报错，所以暂时不用啦，以后考虑升级当前版本使用。
     */
    /*
    private $signer;
    protected $key;

    public function createSigner()
    {
        $this->signer = new Sha256();
    }

    function __construct()
    {
        $this->key = null != Config::get('app_key') ? Config::get('app_key') : 'beautiful_taoqi';
        $this->signer = new Sha256();
    }


    public function auth1(){

        $user = (object) ['uid'=>101,'name' => 'admin', 'email' => '8553151@qq.com'];

        $token = (new Builder())
            ->setId($user->uid)
            ->set('user', $user)
            ->setAudience('http://app.com')
            ->setIssuer('https://yufuping.com')
            ->setExpiration(time() + 3600)
            ->sign( $this->signer, $this->key)
            ->getToken();
        echo $token;
    }


    public function get($token){
        //从jwt获取信息
        $token = (new Parser())->parse((string) $token); // Parses from a string
        echo $token->getClaim('user'); // will print "1"
    }*/

}