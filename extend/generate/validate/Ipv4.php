<?php
/**
 * ipv4地址
 * @author yupoxiong<i@yupoxiong.com>
 */
declare (strict_types=1);

namespace generate\validate;

class Ipv4 extends Rule
{
    protected string $name = 'ipv4';
    protected string  $msg = '必须为IPV4地址';
}
