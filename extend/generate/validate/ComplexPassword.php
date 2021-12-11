<?php
/**
 * 复杂密码
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate\validate;

class ComplexPassword extends Rule
{
    protected string $name = 'complexPassword';
    protected string $msg = '至少1个大写字母和1个小写字母和1个数字和1个特殊字符，8-16位';
}