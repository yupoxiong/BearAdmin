<?php


namespace app\admin\controller;


use think\Db;

class DatabaseController extends Controller
{

    //表
    public function table()
    {
        $data = db()->query('SHOW TABLE STATUS');
        $data = array_map('array_change_key_case', $data);
        $this->assign([
            'data'  => $data,
            'total' => count($data),
        ]);
        return $this->fetch();
    }

    //查看表信息
    public function view($name)
    {
        if (!$name) {
            return error('请指定要查看的表');
        }

        $field_list = Db::query('SHOW FULL COLUMNS FROM `' . $name . '`');

        $data = [];
        foreach ($field_list as $key => $value) {
            $data[] = [
                'name'       => $value['Field'],
                'type'       => $value['Type'],
                'collation'  => $value['Collation'],
                'null'       => $value['Null'],
                'key'        => $value['Key'],
                'default'    => $value['Default'],
                'extra'      => $value['Extra'],
                'privileges' => $value['Privileges'],
                'comment'    => $value['Comment'],
            ];
        }

        $this->assign([
            'data' => $data,
        ]);
        return $this->fetch();
    }


    //优化表
    public function optimize($name)
    {
        if (!$name) {
            return error('请指定要优化的表');
        }
        $name   = is_array($name) ? implode('`,`', $name) : $name;
        $result = db()->query("OPTIMIZE TABLE `{$name}`");
        if ($result) {
            return success("数据表`{$name}`优化成功");
        }
        return error("数据表`{$name}`优化失败");
    }

    //修复表
    public function repair($name)
    {
        if (!$name) {
            return error('请指定要修复的表');
        }
        $name   = is_array($name) ? implode('`,`', $name) : $name;
        $result = db()->query("REPAIR TABLE `{$name}`");
        if ($result) {
            return success("数据表`{$name}`修复成功");
        }
        return error("数据表`{$name}`修复失败");
    }

}