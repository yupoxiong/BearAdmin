<?php
/**
 * 公共基础模型
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\common\model;

use think\Model;
use think\db\Query;

/**
 * @method getFieldType(string $key)
 */
class CommonBaseModel extends Model
{
    // 是否字段，使用场景：用户的是否冻结，文章是否为热门等等。
    public const BOOLEAN_TEXT = [0 => '否', 1 => '是'];

    /**
     * @var mixed
     */
    protected $defaultSoftDelete = 0;

    // 可作为搜索关键词的字段
    public array $searchField = [];
    // 可作为条件查询的字段
    public array $whereField = [];
    // 可作为多选查询的字段
    public array $multiWhereField = [];
    // 可作为时间范围查询的字段
    public array $timeField = [];
    // 不可删除的数据ID
    public array $noDeletionIds = [];

    /**
     * 查询处理
     * @param Query $query
     * @var array $param
     */
    public function scopeWhere(Query $query, array $param): void
    {
        //关键词like搜索
        $keywords = $param['_keywords'] ?? '';
        if ('' !== $keywords && count($this->searchField) > 0) {
            $searchField = implode('|', $this->searchField);
            $query->where($searchField, 'like', '%' . $keywords . '%');
        }

        //字段条件查询
        if (count($this->whereField) > 0 && count($param) > 0) {
            foreach ($param as $key => $value) {
                if ($value !== '' && in_array($key, $this->whereField, true)) {
                    $query->where($key, $value);
                }
            }
        }

        //字段条件查询
        if (count($this->multiWhereField) > 0 && count($param) > 0) {
            foreach ($param as $key => $value) {
                if (is_array($value) && !empty($value) && in_array($key, $this->multiWhereField, true)) {
                    $where = '';
                    foreach ($value as $item) {
                        $str   = "FIND_IN_SET('" . $item . "'," . $key . ") ";
                        $where .= empty($where) ? $str : ' OR ' . $str;
                        $query->where($where);
                    }
                }
            }
        }

        //时间范围查询
        if (count($this->timeField) > 0 && count($param) > 0) {
            foreach ($param as $key => $value) {
                if ($value !== '' && in_array($key, $this->timeField, true)) {
                    $field_type = $this->getFieldType($key);
                    $time_range = explode(' - ', $value);
                    [$start_time, $end_time] = $time_range;
                    //如果是int，进行转换
                    if (false !== strpos($field_type, 'int')) {
                        $start_time = strtotime($start_time);
                        if (strlen($end_time) === 10) {
                            $end_time .= ' 23:59:59';
                        }
                        $end_time = strtotime($end_time);
                    }
                    $query->whereBetweenTime($key, $start_time, $end_time);
                }
            }
        }
        //排序
        $order = $param['_order'] ?? '';
        $by    = $param['_by'] ?? 'desc';
        $query->order($order ?: 'id', $by ?: 'desc');
    }

    /**
     * api模块相关scope
     * @param Query $query
     * @param array $param
     */
    public function scopeApiWhere(Query $query, array $param): void
    {
        $this->scopeWhere($query, $param);
    }

    /**
     * 当前ID是否包含在不可删除的ID中
     * @param $id
     * @return false|string
     */
    public function inNoDeletionIds($id)
    {
        if (count($this->noDeletionIds) > 0) {
            if (is_array($id)) {
                if (array_intersect($this->noDeletionIds, $id)) {
                    return implode(',', $id);
                }
            } else if (in_array((int)$id, $this->noDeletionIds, true)) {
                return $id;
            }
        }
        return false;
    }

    /**
     * 是否状态获取器
     * @param $value
     * @param $data
     * @return string
     */
    public function getStatusTextAttr($value, $data): string
    {
        return self::BOOLEAN_TEXT[$data['status']];
    }
}
