<?php
/**
 * 代码生成配置
 * @author yupoxiong<i@yufuping.com>
 */

return [
    //模版目录
    'template' => [
        'path'       => Env::get('root_path') . 'extend/generate/stub/',
        'controller' => Env::get('root_path') . 'extend/generate/stub/Controller.stub',
        'model'      => Env::get('root_path') . 'extend/generate/stub/Model.stub',
        'validate'   => Env::get('root_path') . 'extend/generate/stub/Validate.stub',
        'view'       => [
            'index'         => Env::get('root_path') . 'extend/generate/stub/view/index.stub',
            'index_del1'    => Env::get('root_path') . 'extend/generate/stub/view/index/del1.stub',
            'index_del2'    => Env::get('root_path') . 'extend/generate/stub/view/index/del2.stub',
            'index_filter'  => Env::get('root_path') . 'extend/generate/stub/view/index/filter.stub',
            'index_export'  => Env::get('root_path') . 'extend/generate/stub/view/index/export.stub',
            'index_select1' => Env::get('root_path') . 'extend/generate/stub/view/index/select1.stub',
            'index_select2' => Env::get('root_path') . 'extend/generate/stub/view/index/select2.stub',
            'add'           => Env::get('root_path') . 'extend/generate/stub/view/add.stub',
        ],
    ],
    //生成文件目录
    'file_dir' => [
        'controller' => Env::get('app_path') . 'admin/controller/',
        'model'      => Env::get('app_path') . 'common/model/',
        'validate'   => Env::get('app_path') . 'common/validate/',
        'view'       => Env::get('app_path') . 'admin/view/',
    ],
];