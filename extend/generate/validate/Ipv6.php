<?php
/**
 * ipv6地址
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate\validate;

class Ipv6 extends Rule
{
    protected string $name = 'ipv6';
    protected string  $msg = '必须为IPV6地址';
}
