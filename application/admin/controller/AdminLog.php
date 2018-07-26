<?php
/**
 * 后台操作日志
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\AdminLogs;
use app\admin\model\AdminUsers;
use crypt\Crypt;

class AdminLog extends Base
{
    //日志列表
    public function index()
    {
        $model      = new AdminLogs();
        $page_param = ['query' => []];
        if (isset($this->param['title']) && !empty($this->param['title'])) {
            $page_param['query']['title'] = $this->param['title'];
            $keywords                     = "%" . $this->param['title'] . "%";
            $model->whereLike('title', $keywords);
            $this->assign('title', $this->param['title']);
        }

        if (isset($this->param['user_id']) && ($this->param['user_id']) > 0) {
            $user_id                        = $this->param['user_id'];
            $page_param['query']['user_id'] = $user_id;
            $model->where('user_id', $user_id);
            $this->assign('user_id', $user_id);
        }

        if (isset($this->param['start_date']) && !empty($this->param['start_date'])) {
            $page_param['query']['start_date'] = $this->param['start_date'];
            $start_date                        = $this->param['start_date'];
            $model->whereTime('create_time', '>=', $start_date);
            $this->assign('start_date', $this->param['start_date']);
        }

        if (isset($this->param['end_date']) && !empty($this->param['end_date'])) {
            $page_param['query']['end_date'] = $this->param['end_date'];
            $end_date                        = $this->param['end_date'];
            $model->whereTime('create_time', '<=', strtotime($end_date . '+1 day'));
            $this->assign('end_date', $this->param['end_date']);
        }

        $list = $model->field('id,user_id,title,log_url,log_type,log_ip,create_time')
            ->with('adminUser')
            ->order('id desc')
            ->paginate($this->webData['list_rows'], false, $page_param);

        $this->assign([
            'list'      => $list,
            'page'      => $list->render(),
            'total'     => $list->total(),
            'user_list' => AdminUsers::all()
        ]);

        return $this->fetch();
    }


    //操作日志详情
    public function view()
    {
        $key         = null != config('app_key') ? config('app_key') : 'beautiful_taoqi';
        $log_info    = AdminLogs::get($this->id);
        $log_data    = unserialize(Crypt::decrypt($log_info->adminLogData->data, $key));
        $log_url     = $log_info['log_url'];
        $resource_id = $log_info['resource_id'];

        $pre_log_where = [
            'log_url' => $log_url,
            'id'      => ['<', $log_info['id']]
        ];

        $pre_log      = new AdminLogs();
        $pre_log_info = $pre_log
            ->where($pre_log_where)
            ->where(function ($query) use ($resource_id) {
                $query->where(['resource_id' => 0])->whereOr(['resource_id' => $resource_id]);
            })
            ->order('id desc')
            ->find();

        if ($pre_log_info) {
            $pre_log_data = unserialize(Crypt::decrypt($pre_log_info->adminLogData->data, $key));
            if (!isset($this->param['show_password'])) {
                if (array_key_exists('password', $pre_log_data)) {
                    $pre_log_data['password'] = '******';
                }

                if (array_key_exists('newpassword', $pre_log_data)) {
                    $pre_log_data['newpassword'] = '******';
                }

                if (array_key_exists('newpassword_do', $pre_log_data)) {
                    $pre_log_data['newpassword_do'] = '******';
                }
            }
            foreach ($pre_log_data as $key => $value) {
                if (is_array($value)) {
                    $pre_log_data[$key] = implode(',', $value);
                }
            }

            $this->assign([
                'pre_log'      => $pre_log_info,
                'pre_log_data' => $pre_log_data,
                'data_size'    => sizeof($pre_log_data),
            ]);

            $span_pre = '<span class="text-red">';
            $span_pro = '</span>';

            foreach ($pre_log_data as $key => $value) {
                if (array_key_exists($key, $log_data)
                    &&
                    ($log_data[$key] != $value)
                    &&
                    (!is_array($value))
                    &&
                    (!is_array($log_data[$key]))
                ) {
                    $log_data[$key] = $span_pre . $log_data[$key] . $span_pro;
                }
            }
        }

        if (!isset($this->param['show_password'])) {
            if (array_key_exists('password', $log_data)) {
                $log_data['password'] = '******';
            }

            if (array_key_exists('newpassword', $log_data)) {
                $log_data['newpassword'] = '******';
            }

            if (array_key_exists('newpassword_do', $log_data)) {
                $log_data['newpassword_do'] = '******';
            }
        }

        foreach ($log_data as $key => $value) {
            if (is_array($value)) {
                $log_data[$key] = implode(',', $value);
            }
        }

        $this->assign([
            'log'       => $log_info,
            'log_data'  => $log_data,
            'data_size' => sizeof($log_data)
        ]);

        return $this->fetch();
    }
}