<?php
/**
 * 登录相关
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\service;

use think\facade\Cache;
use think\facade\Event;
use think\facade\Cookie;
use think\facade\Session;
use app\admin\model\AdminUser;
use app\common\service\StringService;
use app\admin\exception\AdminServiceException;
use app\common\exception\CommonServiceException;

class AuthService extends AdminBaseService
{
    protected AdminUser $model;

    protected string $limitKeyPrefix = 'admin_login_count_';

    /**
     * @var string 保存登录用户信息cookie和session的[ID]key值
     */
    protected $store_uid_key = 'admin_user_id';
    /**
     * @var string 保存登录用户信息cookie和session的[签名]key值
     */
    protected $store_sign_key = 'admin_user_sign';
    /**
     * @var mixed|string 用来签名加密/解密的key
     */
    protected $admin_key = '_ThisClassDefaultKey_';

    public function __construct()
    {
        $this->model = new AdminUser();

        $this->admin_key      = config('admin.safe.admin_key') ?? $this->admin_key;
        $this->store_uid_key  = config('admin.safe.store_uid_key') ?? $this->store_uid_key;
        $this->store_sign_key = config('admin.safe.store_sign_key') ?? $this->store_sign_key;
    }

    /**
     * 用户登录
     * @param $username
     * @param $password
     * @return AdminUser
     * @throws AdminServiceException
     */
    public function login($username, $password): AdminUser
    {
        $admin_user = $this->model->where('username', '=', $username)->findOrEmpty();
        if ($admin_user->isEmpty()) {
            throw new AdminServiceException('用户不存在');
        }

        /** @var AdminUser $admin_user */
        $verify = password_verify($password, base64_decode($admin_user->password));
        if (!$verify) {
            throw new AdminServiceException('密码错误');
        }

        // 检查是否被冻结
        if ($admin_user->status !== 1) {
            throw new AdminServiceException('账号被冻结');
        }

        // Event_事件 管理用户登录
        Event::trigger('AdminUserLogin', $admin_user);

        return $admin_user;
    }

    /**
     * 检测登录限制
     *
     * @throws AdminServiceException
     */
    public function checkLoginLimit(): bool
    {
        $is_limit = (int)setting('admin.login.login_limit');
        if ($is_limit) {
            // 最大错误次数
            $max_count        = (int)setting('admin.login.login_max_count');
            $login_limit_hour = (int)setting('admin.login.login_limit_hour');
            // 缓存key
            $cache_key  = $this->limitKeyPrefix . md5(request()->ip());
            $have_count = (int)Cache::get($cache_key);
            if ($have_count >= $max_count) {
                throw new AdminServiceException('连续' . $max_count . '次登录失败，请' . $login_limit_hour . '小时后再试');
            }
            return true;
        }
        return true;
    }

    /**
     * 设置登录限制
     * @return bool
     */
    public function setLoginLimit(): bool
    {
        $is_limit = (int)setting('admin.login.login_limit');
        if ($is_limit) {
            // 最大错误次数
            $login_limit_hour = (int)setting('admin.login.login_limit_hour');
            // 缓存key
            $cache_key = $this->limitKeyPrefix . md5(request()->ip());
            if (Cache::has($cache_key)) {
                Cache::inc($cache_key);
                return true;
            }
            Cache::set($cache_key, 1, $login_limit_hour * 3600);
        }
        return true;
    }

    /**
     * 临时手动清除某个ip的限制
     * @param $ip
     * @return bool
     */
    public function clearLoginLimit($ip): bool
    {
        $cache_key = $this->limitKeyPrefix . md5($ip);
        return Cache::delete($cache_key);
    }

    /**
     * 设置用户登录信息
     * @param $admin_user
     * @param bool $remember
     */
    public function setAdminUserAuthInfo($admin_user, bool $remember): void
    {
        Session::set($this->store_uid_key, $admin_user->id);
        if ($remember) {
            Cookie::forever($this->store_uid_key, $admin_user->id);
            Cookie::forever($this->store_sign_key, $this->getUserSign($admin_user));
        }
    }

    /**
     * 获取登录用户信息
     * @return AdminUser
     * @throws AdminServiceException
     */
    public function getAdminUserAuthInfo(): AdminUser
    {
        //  当前管理员ID
        $admin_user_id = 0;
        // 当前获取登录信息的方式
        $store_from = 0;

        if (Session::has($this->store_uid_key)) {
            // session
            $store_from    = 1;
            $admin_user_id = (int)Session::get($this->store_uid_key);
        } else if (Cookie::has($this->store_uid_key)) {
            // cookie
            $store_from    = 2;
            $admin_user_id = (int)Cookie::get($this->store_uid_key);
        }

        if ($admin_user_id === 0) {
            throw new AdminServiceException('未找到登录信息');
        }

        $admin_user = $this->model
            ->where('id', '=', $admin_user_id)
            ->findOrEmpty();

        /** @var AdminUser $admin_user */
        if (!$admin_user) {
            throw new AdminServiceException('用户不存在');
        }

        if ($admin_user->status !== 1) {
            throw new AdminServiceException('用户被冻结');
        }

        // 如果是cookie中的用户，验证sign是否正确
        if ($store_from === 2) {
            $cookie_sign = Cookie::get($this->store_sign_key);
            $check_sign  = $this->getUserSign($admin_user);
            if ($cookie_sign !== $check_sign) {
                throw new AdminServiceException('Cookie签名验证失败');
            }
        }

        return $admin_user;
    }

    /**
     * 退出
     * @param AdminUser $admin_user
     * @return bool
     */
    public function logout(AdminUser $admin_user): bool
    {
        // Event_事件 管理用户退出
        Event::trigger('AdminUserLogout', $admin_user);
        $this->clearAuthInfo();
        return true;
    }

    /**
     * 清除登录用户信息
     */
    public function clearAuthInfo(): void
    {
        Session::delete($this->store_uid_key);
        Cookie::delete($this->store_uid_key);
        Cookie::delete($this->store_sign_key);
    }

    /**
     * 获取签名
     * @param $admin_user
     * @return string
     */
    public function getUserSign($admin_user): string
    {
        return md5(md5($this->admin_key . $admin_user->id) . $this->admin_key);
    }

    /**
     * 获取当前设备ID
     * @param $admin_user
     * @return string
     */
    public function getDeviceId($admin_user): string
    {
        $key       = 'device_id_uid_' . $admin_user->id;
        $device_id = Cookie::get($key);
        if (!$device_id) {
            try {
                $rand_text = StringService::getRandString(20);
            } catch (CommonServiceException $e) {
                $rand_text = time() . $admin_user->id . microtime();
            }

            $device_id = sha1('admin_user_' . $admin_user->id . $rand_text . time());
            Cookie::set($key, $device_id);
        }

        return $device_id;
    }

    /**
     * 在视图中检查指定url是否有权限
     * @param string $url url形式参考：user/edit，admin/user/edit，前缀"admin/"可以去掉
     * @return string
     * @throws AdminServiceException
     */
    public function viewCheckAuth(string $url): string
    {
        $user = $this->getAdminUserAuthInfo();

        $prefix = parse_name(app('http')->getName()) . '/';
        if (strpos(parse_name($url), $prefix) !== 0) {
            $url = $prefix . $url;
        }

        if (($user->id) === 1) {
            $result = '1';
        } else {
            $result = in_array(parse_name($url), array_map('parse_name', $user->auth_url), true) ? '1' : '0';
        }
        return $result;
    }
}
