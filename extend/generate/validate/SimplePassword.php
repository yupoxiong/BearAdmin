<?php
/**
 * 简单密码
 * @author yupoxiong<i@yupoxiong.com>
 */
declare (strict_types=1);

namespace generate\validate;

class SimplePassword extends Rule
{
    protected string $name = 'simplePassword';
    protected string $msg = '至少1个字母和1个数字，6-16位';
}