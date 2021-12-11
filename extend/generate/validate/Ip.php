<?php
/**
 * IP地址
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate\validate;

class Ip extends Rule
{
    protected string $name = 'ip';
    protected string  $msg = '必须为IP地址';
}
