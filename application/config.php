<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => true,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [
        THINK_PATH . 'helper' . EXT,
        APP_PATH . 'common/helper/helper' . EXT
    ],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'trim,strip_tags,htmlspecialchars',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => true,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'              => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'      => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'   => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'        => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'         => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'        => true,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'      => '\app\common\exception\LogException',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'   => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace' => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache' => [
        // 使用复合缓存类型
        'type'    => 'complex',
        // 默认使用的缓存
        'default' => [
            // 驱动方式
            'type' => 'File',
            // 缓存保存目录
            'path' => CACHE_PATH,
        ],
        // 文件缓存
        'file'    => [
            // 驱动方式
            'type' => 'file',
            // 设置不同的缓存保存目录
            'path' => RUNTIME_PATH . 'file/',
        ],
        // redis缓存
        'redis'   => [
            // 驱动方式
            'type' => 'redis',
            // 服务器地址
            'host' => '127.0.0.1',
        ],
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'  => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'bearadmin_',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'   => [
        // cookie 名称前缀
        'prefix'    => 'bearadmin_',
        // cookie 保存时间
        'expire'    => 604800,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate' => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],



    /**
     * 应用加密字符串
     * 生成api token和加密cookie的时候使用
     * 可以用php think createkey命令生成
     */
    'app_key'  => '6757a9f87f0b5d65de33f8b55ac5d381',

    'helper_config' => [
        //高德地图web服务key，请替换成自己的
        'amap_web_key' => 'cb241360e2b73b5951371c60a1b095ef',
    ],

    'sys_log' => [
        'env'  => 'dev',//开发环境
        'path' => ROOT_PATH . 'runtime/errorlog/' . date("Y-m-d") . '.log',
    ],


    'qq_login'          => [
        'app_id'    => '填写你的',
        'app_secret' => '',
        'scope'      => 'get_user_info',
        'callback'   => [
            'default' => 'https://bearadmin.yufuping.com/admin/auth/qq.html',
            'mobile'  => 'https://bearadmin.yufuping.com/admin/auth/qq.html',
        ]
    ],

    // 设置空模块名为admin
    'empty_module'      => 'admin',

    //极验id和key
    'geetest'           => [
        'id'  => '填写你的id',
        'key' => '填写你的key'
    ],

    'http_exception_template' => [
        // 定义404错误的重定向页面地址
        404 => ROOT_PATH . 'public/404.html',
        // 还可以定义其它的HTTP status
    ],

    'reward_url' => [
        'wechat' => 'wxp://f2f0nlKFenUs_W0F4TfvABAjAigvkbphDKjV',
        'alipay' => 'HTTPS://QR.ALIPAY.COM/FKX02237XBDM3VGBVXTNC9',
        'jd'     => 'https://h5pay.jd.com/c2cIndex?t=70b4c1a48b1a923eb1ecc283ba122f8567c7cda70c18f6bd38fc02a0064b8b03e0b33641d759bf770a4ef43a3344ef1e',
        'qq'     => 'https://i.qianbao.qq.com/wallet/sqrcode.htm?m=tenpay&a=1&u=8553151',
        'unkown' => '/',
    ],

    //短信配置
    'easysms'    => [
        // HTTP 请求的超时时间（秒）
        'timeout'  => 5.0,

        // 默认发送配置
        'default'  => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
            // 默认可用的发送网关
            'gateways' => [
                'alidayu' // ,'yunpian', 'aliyun',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => ROOT_PATH . 'runtime/smslog/sms.log',
            ],
            'alidayu'  => [
                'app_key'    => 'appkey换成自己的',
                'app_secret' => 'secret换成自己的',
                'sign_name'  => '签名换成自己的',
                'tpl'        => 'SMS_85205029'
            ]
            /*,
            'yunpian' => [
                'api_key' => '824f0ff2f71cab52936axxxxxxxxxx',
            ],
            'aliyun' => [
                'access_key_id' => '',
                'access_key_secret' => '',
                'sign_name' => '',
            ],*/

        ],
    ],

    //阿里云oss配置
    'aliyun_oss' => [
        'KeyID'     => '',
        'KeySecret' => '',
        'EndPoint'  => '',
        'Bucket'    => '',
        'url'       => 'https://demo.oss.aliyun.com/'
    ],

    //七牛云存储
    'qiniu' => [
        'AccessKey' => '',
        'SecretKey' => '',
        'Bucket'=>'',
        'url'=>'http://demo.bkt.clouddn.com/'
    ],
    //附件配置
    'attchment'=>[
        'path'=>ROOT_PATH.'public/uploads/attachment/',  //上传目录配置（相对于根目录）
        'url'=>'/uploads/attachment/',  //url（相对于web目录）
        'validate'=>[
            //默认不超过50mb
            'size' => 52428800,
            //常用后缀
            'ext'  => 'bmp,ico,psd,jpg,jpeg,png,gif,doc,docx,xls,xlsx,pdf,zip,rar,7z,tz,mp3,mp4,mov,swf,flv,avi,mpg,ogg,wav,flac,ape',
        ],
    ]
];
