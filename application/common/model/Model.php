<?php
/**
 * 公共基础模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\model;

use think\db\Query;

class Model extends \think\Model
{
    //是否字段，使用场景：用户的是否冻结，文章是否为热门等等。
    const BOOLEAN_TEXT = [0 => '否', 1 => '是'];

    //是否为软删除
    public $softDelete = true;

    //软删除字段默认值
    protected $defaultSoftDelete = 0;

    //可搜索字段
    protected $searchField = [];

    //可作为条件的字段
    protected $whereField = [];

    //可做为时间范围查询的字段
    protected $timeField = [];

    //禁止删除的数据id
    public $noDeletionId = [];

    /**
     * 查询处理
     * @var Query $query
     * @var array $param
     */
    public function scopeWhere($query, $param)
    {
        //关键词like搜索
        $keywords = $param['_keywords'] ?? '';
        if (count($this->searchField) > 0 && $keywords !== '') {
            $this->searchField = implode('|', $this->searchField);
            $query->where($this->searchField, 'like', '%' . $keywords . '%');
        }

        //字段条件查询
        if (count($this->whereField) > 0 && count($param) > 0) {
            foreach ($param as $key => $value) {
                if ($value !== '' && in_array($key, $this->whereField)) {
                    $query->where($key, $value);
                }
            }
        }

        //时间范围查询
        if (count($this->timeField) > 0 && count($param) > 0) {
            foreach ($param as $key => $value) {
                if ($value !== '' && in_array($key, $this->timeField)) {
                    $field_type = $this->getFieldsType($this->name, $key);
                    $time_range = explode(' - ', $value);
                    $start_time = $time_range[0];
                    $end_time   = $time_range[1];
                    //如果是int，进行转换
                    if (false !== strpos($field_type, 'int')) {
                        $start_time = strtotime($start_time);
                        if (strlen($end_time) == 10) {
                            $end_time = $end_time . '23:59:59';
                        }
                        $end_time = strtotime($end_time);
                    }

                    $query->where($key, 'between', [$start_time, $end_time]);
                }
            }
        }

        //排序
        $order = $param['_order'] ?? '';
        $by    = $param['_by'] ?? 'desc';
        $query->order($order ?: 'id', $by ?: 'desc');
    }

}
