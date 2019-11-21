<?php
return [
    //token在header中的name
    'name'                   => 'token',
    //token加密使用的secret
    'secret'                 => '552ac90778a976c72f7f673db174df30',
    //颁发者
    'iss'                    => 'iss',
    //使用者
    'aud'                    => 'aud',
    //过期时间，以秒为单位，默认2小时。提示：感觉刷新麻烦的可以设置的大一些，比如10年20年之类的
    'ttl'                    => 7200,
    //刷新时间，以秒为单位，默认14天。提示：只有过期时间到了才会生效，所以把过期时间设置的很大的懒人就可以忽略了
    'refresh_ttl'            => 1209600,
    //是否自动刷新，开启后可自动刷新token，附在header中返回，name为`Authorization`,字段为`Bearer `+$token
    'auto_refresh'           => true,
    //黑名单宽限期，以秒为单位，首次token刷新之后在此时间内原token可以继续访问
    'blacklist_grace_period' => 60
];