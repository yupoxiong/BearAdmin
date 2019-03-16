<?php
/*
 * Auth类
 * 忘记这是谁写的了，可能是流年大神或者是谁，在一个项目里拿过来加了点代码就用了
 */
namespace tools;

use crypt\Crypt;
use crypt\SafeCookie;
use think\Db;
use think\Config;
use think\Session;
use think\Cookie;
use think\Request;
use think\Loader;
use app\admin\model\AdminLogs;

/**
 * 权限认证类
 */
class AdminAuth
{
    protected $request;

    //默认配置
    protected $config = [
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'admin_groups', // 用户组数据表名
        'auth_group_access' => 'auth_group_access', // 用户-用户组关系表
        'auth_rule'         => 'admin_menus', // 权限规则表
        'auth_user'         => 'admin_users', // 用户信息表
    ];
    static $crypt_key;
    
    public function __construct()
    {
        //可设置配置项 auth, 此配置项为数组。
        if ($auth = Config::get('admin_auth')) {
            $this->config = array_merge($this->config, $auth);
        }
        // 初始化request
        $this->request    = Request::instance();
        $this->param      = $this->request->param();
        $this->module     = $this->request->module();
        $this->controller = $this->request->controller();
        $this->action     = $this->request->action();
        self::$crypt_key  = Config::get('app_key') != null ? Config::get('app_key') : 'beautiful_taoqi';
    }


    //检查权限
    public function check($name, $uid, $relation = 'or', $type = 1, $mode = 'url')
    {
        //超级管理员不限制
        if (Session::get('user.user_id') == 1) {
            return true;
        }
        if (!$this->config['auth_on']) {
            return true;
        }
        // 获取用户需要验证的所有有效规则列表
        $authList = $this->getAuthList($uid, $type);
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = [$name];
            }
        }
        $list = []; //保存验证通过的规则名
        $REQUEST = '';
        if ('url' == $mode) {
            $REQUEST = unserialize(strtolower(serialize($this->request->param())));
        }
        foreach ($authList as $auth) {
            $query = preg_replace('/^.+\?/U', '', $auth);
            if ('url' == $mode && $query != $auth) {
                parse_str($query, $param); //解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $auth      = preg_replace('/\?.*$/U', '', $auth);
                if (in_array($auth, $name) && $intersect == $param) {
                    //如果节点相符且url参数满足
                    $list[] = $auth;
                }
            } else {
                if (in_array($auth, $name)) {
                    $list[] = $auth;
                }
            }
        }
        if ('or' == $relation && !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ('and' == $relation && empty($diff)) {
            return true;
        }

        return false;
    }


    //返回用户组
    public function getGroups($uid)
    {
        static $groups = [];
        if (isset($groups[$uid])) {
            return $groups[$uid];
        }
        // 转换表名
        $type              = Config::get('database.prefix') ? 1 : 0;
        $auth_group_access = Loader::parseName($this->config['auth_group_access'], $type);
        $auth_group        = Loader::parseName($this->config['auth_group'], $type);
        // 执行查询
        $user_groups  = Db::view($auth_group_access, 'uid,group_id')
            ->view($auth_group, 'name,rules', "{$auth_group_access}.group_id={$auth_group}.id", 'LEFT')
            ->where("{$auth_group_access}.uid='{$uid}' and {$auth_group}.status=1")
            ->select();
        $groups[$uid] = $user_groups ?: [];

        return $groups[$uid];
    }

    //获得权限列表
    protected function getAuthList($uid, $type)
    {
        static $_authList = []; //保存用户验证通过的权限列表
        $t = implode(',', (array)$type);
        if (isset($_authList[$uid . $t])) {
            return $_authList[$uid . $t];
        }
        if (2 == $this->config['auth_type'] && Session::has('_auth_list_' . $uid . $t)) {
            return Session::get('_auth_list_' . $uid . $t);
        }
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids    = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid . $t] = [];
            return [];
        }
        $map = array(
            'id'     => ['in', $ids],
            'type'   => $type,
            'status' => 1,
        );
        //读取用户组所有权限规则
        $rules = Db::name($this->config['auth_rule'])->where($map)->field('condition,url')->select();
        //循环规则，判断结果。
        $authList = []; //
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) {
                //根据condition进行验证
                $user    = $this->getUserInfo($uid); //获取用户信息,一维数组
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
                $condition = '';
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = strtolower($rule['url']);
                }
            } else {
                //只要存在就记录
                $authList[] = strtolower($rule['url']);
            }
        }
        $_authList[$uid . $t] = $authList;
        if (2 == $this->config['auth_type']) {
            //规则列表结果保存到session
            Session::set('_auth_list_' . $uid . $t, $authList);
        }

        return array_unique($authList);
    }

    
    //获得用户资料,根据自己的情况读取数据库
    protected function getUserInfo($id)
    {
        static $userinfo = [];
        $user = Db::name($this->config['auth_user']);
        // 获取用户表主键
        $_pk = is_string($user->getPk()) ? $user->getPk() : 'id';
        if (!isset($userinfo[$id])) {
            $userinfo[$id] = $user->where($_pk, $id)->find();
        }
        return $userinfo[$id];
    }


    //用户登录
    public static function login($id, $name, $remember = false)
    {
        if (empty($id) && empty($name)) {
            return false;
        }
        $user = [
            'id'   => $id,
            'name' => $name,
            'timestamp' => time()
        ];

        Session::set('user', $user);
        Session::set('user_sign', self::data_auth_sign($user));

        //记住登录
        if ($remember == true) {
            SafeCookie::set('user', $user);
            SafeCookie::set('user_sign', self::data_auth_sign($user));
        } else {
            if (Cookie::has('user') || Cookie::has('user_sign')) {
                Cookie::delete('user');
                Cookie::delete('user_sign');
            }
        }
        return true;
    }


    //退出
    public static function logout()
    {
        Session::delete('user');
        Session::delete('user_sign');
        if (Cookie::has('user')) {
            Cookie::delete('user');
            Cookie::delete('user_sign');
        }
        return true;
    }


    //是否登录
    public static function is_login()
    {
        $user = Session::get('user');
        if (empty($user)) {
            if (Cookie::has('user') && Cookie::has('user_sign')) {
                $user      = SafeCookie::get('user');
                $user_sign = SafeCookie::get('user_sign');
                $is_sin    = $user_sign == self::data_auth_sign($user) ? $user : false;
                if ($is_sin) {
                    Session::set('user', $user);
                    Session::set('user_sign', $user_sign);
                    return true;
                }
            }
            return false;
        }
        return Session::get('user_sign') == self::data_auth_sign($user) ? $user : false;
    }

    //创建行为日志
    public function createLog($title, $log_type, $resource_id=0)
    {
        $user_id = Session::get('user.id');
        $data = [
            'user_id'     => $user_id,
            'title'       => $title,
            'resource_id' => $resource_id,
            'log_type'    => $log_type,
            'log_url'     => $this->request->pathinfo(),
            'log_ip'      => ip2long($this->request->ip())
        ];

        //加密数据，防脱库
        $crypt_data = Crypt::encrypt(serialize($this->request->param()), self::$crypt_key);
        $log_data = [
            'data' => $crypt_data
        ];

        $log = AdminLogs::create($data);
        if ($log) {
            return $log->adminLogData()->save($log_data);
        }
        return false;
    }

    
    //数据签名认证
    private static function data_auth_sign($data)
    {
        $code = http_build_query($data); //url编码并生成query字符串
        $sign = sha1($code); //生成签名
        return $sign;
    }

    //获取菜单列表
    public function getMenuList($uid, $type)
    {
        static $_authList = []; //保存用户验证通过的权限列表
        $t = implode(',', (array)$type);
        if (isset($_authList[$uid . $t])) {

            return $_authList[$uid . $t];
        }

        if (2 == $this->config['auth_type'] && Session::has('_auth_list_' . $uid . $t)) {

            return Session::get('_auth_list_' . $uid . $t);
        }
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));

        }

        $ids = array_unique($ids);
        if (empty($ids)) {

            $_authList[$uid . $t] = [];
            return [];
        }
        $map = array(
            'id'     => ['in', $ids],
            'type'   => $type,
            'status' => 1,
        );
        //读取用户组所有权限规则
        $rules = Db::name($this->config['auth_rule'])->where($map)->field('condition,url,id')->select();
        //循环规则，判断结果。
        $authList = []; //
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) {
                //根据condition进行验证
                $user    = $this->getUserInfo($uid); //获取用户信息,一维数组
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
                $condition = '';
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = strtolower($rule['id']);
                }
            } else {
                //只要存在就记录
                $authList[] = strtolower($rule['id']);
            }
        }
        $_authList[$uid . $t] = $authList;
        if (2 == $this->config['auth_type']) {
            //规则列表结果保存到session
            Session::set('_auth_list_' . $uid . $t, $authList);
        }

        $authList = array_unique($authList);
        $idss = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($authList as $gg) {
            $idss = array_merge($idss, explode(',', trim($gg, ',')));
        }

        $map_menu = ['is_show' => 1];

        $uid = Session::get('user.id');

        if($uid<>1){
            $map_menu['id'] = ['in', $idss];
        }

        return Db::name('admin_menus')->where($map_menu)->order('sort_id asc,id asc')->field('id,title,url,icon,is_show,parent_id')->column('*', 'id');
    }
    
    
}