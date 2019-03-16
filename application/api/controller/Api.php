<?php
/**
 * Api基础控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\api\controller;

use think\exception\ValidateException;
use Lcobucci\JWT\Parser as TokenParser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use think\exception\HttpResponseException;
use think\Loader;
use think\Request;
use think\Response;

class Api
{
    //Request实例
    protected $request;

    //请求参数
    protected $param;

    //token
    protected $token;

    //是否需要验证token
    protected $needAuth = true;

    //当前请求用户id,默认0
    protected $uid = 0;

    //验证失败是否抛出异常
    protected $failException = false;

    //是否批量验证
    protected $batchValidate = false;

    public function __construct(Request $request = null)
    {
        if (is_null($request)) {
            $request = Request::instance();
        }
        $this->request = $request;
        $this->param   = $request->param();
        $this->token   = $this->request->header('AppAuthorization');

        //如果当前需要验证token
        if (true == $this->needAuth) {
            //缺少token
            if (is_null($this->token)) {
                $this->error('miss token');
            }

            $signer = new Sha256();
            try{
                $token  = (new TokenParser())->parse((string)$this->token);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            
            //验证成功后给当前uid赋值
            if (true == ($token->verify($signer, config('app_key')))) {
                $this->uid = $token->getClaim('uid');
            }else{
                $this->error('token error');
            }
        }
    }

    //成功返回
    protected function success($data = '', $msg = 'success', $code = 1, $type = 'json', array $header = [])
    {
        $this->result($data, $code, $msg, $type, $header);
    }


    //失败返回
    protected function error($msg = 'fail', $data = '', $code = 0, $type = 'json', array $header = [])
    {
        $this->result($data, $code, $msg, $type, $header);
    }

    //返回结果，参考tp自带result方法
    protected function result($data, $code = 0, $msg = '', $type = 'json', array $header = [])
    {
        $msg = lang($msg, [], config('default_lang'));
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];

        $type     = $type ?: $this->getResponseType();
        $response = Response::create($result, $type)->header($header);

        throw new HttpResponseException($response);
    }


    //访问空页面
    public function _empty()
    {
        return $this->error('Api not found');
    }


    //参考tp自带Controller  validate
    protected function validateFailException($fail = true)
    {
        $this->failException = $fail;
        return $this;
    }

    //参考tp自带Controller  validate
    protected function validate($data, $validate, $message = [], $batch = false, $callback = null)
    {
        if (is_array($validate)) {
            $v = Loader::validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $v = Loader::validate($validate);
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }
        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        if (is_array($message)) {
            $v->message($message);
        }

        if ($callback && is_callable($callback)) {
            call_user_func_array($callback, [$v, &$data]);
        }

        if (!$v->check($data)) {
            if ($this->failException) {
                throw new ValidateException($v->getError());
            } else {
                return $v->getError();
            }
        } else {
            return true;
        }
    }
}