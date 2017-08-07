<?php
/**
 *
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/3/12
 */
$str = '{"status":"9","code":8,"test":""}';
$obj = json_decode($str);
echo $obj->code;