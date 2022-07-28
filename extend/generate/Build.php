<?php
/**
 * build基础类
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate;

class Build
{
    // 数据
    protected array $data;
    // 配置
    protected array $config;
    // 模版
    protected array $template;
    // 代码
    protected string $code;

    /**
     * @param $field_name
     * @param int $type 1返回去掉_id的字段名，如果没有_id的话就返回原字段；
     * 2返回list，例如type字段的type_list，channel_id的channel_list;
     * 3为常量LIST，例如TYPE_LIST，CHANNEL_LIST；
     * 4为显示字段name,例如type_name，channel_name；
     * 5为4的获取器使用，例如type_name变成TypeName
     * 这里要注意，如果原字段是_id结尾的，会干掉_id，例如channel_id_list不仅长，而且容易产生歧义，
     * 实际channel_list的话就非常明确，这是渠道列表,是一个二维数组。
     * @return false|string
     */
    public function getSelectFieldFormat($field_name, int $type = 1)
    {
        $_id_suffix   = '_id';
        $_list_suffix = '_list';
        $_name_suffix = '_name';

        switch ($type) {

            case 1:
            default:
                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if ($_id_post !== false && strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);
                }
                break;
            case 2:

                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if ($_id_post !== false && strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);
                }
                $result .= $_list_suffix;
                break;

            case 3:
                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if ($_id_post !== false && strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);
                }
                $result = strtoupper($result . $_list_suffix);
                break;

            case 4:
                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if ($_id_post !== false && strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);

                }
                $result .= $_name_suffix;
                break;

            case 5:
                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if ($_id_post !== false && strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);
                }
                $result .= $_name_suffix;
                $result = parse_name($result, 1, true);
                break;
        }

        return $result;
    }

}