<?php
/**
 * 中等密码
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate\validate;

class MiddlePassword extends Rule
{
    protected string $name = 'middlePassword';
    protected string $msg = '至少1个大写字母和1个小写字母和1个数字，8-16位';
}