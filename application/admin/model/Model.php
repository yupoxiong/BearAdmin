<?php
/**
 * 后台基础模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

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
        if (!empty($keywords) && count($this->searchField) > 0) {
            $this->searchField = implode('|', $this->searchField);
            $query->where($this->searchField, 'like', '%' . $keywords . '%');
        }

        //字段条件查询
        if (count($this->whereField) > 0 && count($param) > 0) {
            foreach ($param as $key => $value) {
                if (!empty($value) && in_array($key, $this->whereField)) {
                    $query->where($key, $value);
                }
            }
        }

        //排序
        $order = $param['_order'] ?? '';
        $by    = $param['_by'] ?? 'desc';
        $query->order($order ?: 'id', $by ?: 'desc');
    }

    //状态获取器
    public function getStatusTextAttr($value, $data)
    {
        return self::BOOLEAN_TEXT[$data['status']];
    }

}