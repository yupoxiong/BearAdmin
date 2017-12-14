<?php
/**
 * Auth扩展基类
 * @author yupoxiong<i@yufuping.com>
 */

namespace tools;

use think\Db;
use think\Session;

class Auth
{

    //默认配置
    public $config = [
        'auth_on'           => true,
        'auth_type'         => 1,
        'auth_group'        => 'backend_auth_group',
        'auth_group_access' => 'backend_auth_group_access',
        'auth_rule'         => 'backend_auth_rule',
        'auth_user'         => 'backend_user'
    ];

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function __construct($config = null)
    {
        $this->config = !is_null($config) ? $config : $this->config;
    }

    /*
     * 获得权限$name可以是字符串或数组或逗号分割，uid为认证的用户id，
     * $or 是否为or关系，为true是，name为数组，只要数组中
     * 有一个条件通过则通过，如果为false需要全部条件通过。
     * */
    public function check($name, $user_id, $relation = 'or')
    {
        if (!$this->config['auth_on'])
        {
            return true;
        }

        $auth_list = $this->getAuthList($user_id);
        if (is_string($name)) {
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        $list = array(); //有权限的name
        foreach ($auth_list as $val) {
            if (in_array($val, $name))
                $list[] = $val;
        }
        if ($relation == 'or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return true;
        }
        return false;
    }

    //获得用户组
    public function getGroups($user_id)
    {
        static $groups = array();
        if (isset($groups[$user_id])) {
            return $groups[$user_id];
        }

        $user_groups = Db::name($this->config['auth_group_access'])
            ->alias('a')
            ->where("a.user_id='$user_id' and g.status='1'")
            ->leftJoin($this->config['auth_group'] . ' g', 'a.group_id=g.id')
            ->select();

        $groups[$user_id] = $user_groups ? $user_groups : array();
        return $groups[$user_id];
    }

    //获得权限列表
    protected function getAuthList($user_id)
    {

        static $auth_lists = array();
        if (isset($auth_lists[$user_id])) {
            return $auth_lists[$user_id];
        }

        //如果当前认证方式为登录认证
        if (2 == $this->config['auth_type'] && Session::has($this->config['auth_user'] . '_auth_list_' . $user_id)) {
            return Session::get($this->config['auth_user'] . '_auth_list_' . $user_id);
        }

        //读取用户所属用户组
        $groups = $this->getGroups($user_id);
        $ids    = [];
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            return array();
        }

        //读取用户组所有权限规则
        $rules = Db::name($this->config['auth_rule'])
            ->where('id', 'in', $ids)
            ->where('status', '=', 1)
            ->select();

        //循环规则，判断结果。
        $auth_list = array();
        foreach ($rules as $r) {
            if (!empty($r['condition'])) {
                //条件验证
                $user      = $this->getUserInfo($user_id);
                $command   = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $r['condition']);
                $condition = '';
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $auth_list[] = $r['url'];
                }
            } else {
                //存在就通过
                $auth_list[] = $r['url'];
            }
        }

        $auth_lists[$user_id] = $auth_list;

        if ($this->config['auth_type'] == 2) {
            Session::set($this->config['auth_user'] . '_auth_list_' . $user_id, $auth_list);
        }
        return $auth_list;
    }

    //获得用户资料
    protected function getUserInfo($user_id)
    {
        static $userinfo = array();
        if (!isset($userinfo[$user_id])) {
            $userinfo[$user_id] = Db::name($this->config['auth_user'])->find($user_id);
        }
        return $userinfo[$user_id];
    }


}
