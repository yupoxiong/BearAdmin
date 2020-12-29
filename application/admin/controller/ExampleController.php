<?php
/**
 * 示例控制器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);


namespace app\admin\controller;


use think\Request;

class ExampleController extends Controller
{

    /**
     * echarts的示例
     */
    public function echarts(Request $request)
    {
        if ($request->isPost()) {
            switch ($request->param('type')) {
                case 1:
                case 2:
                default:
                    $data   = [6, 3, 4, 7, 9];
                    $cate   = ['裤子', '鞋子', '帽子', '袜子', '衣服'];
                    $result = [
                        'data' => $data,
                        'cate' => $cate,
                    ];

                    break;

                case 3:
                    $result = [
                        ['value' => 6,
                         'name'  => '裤子'
                        ],
                        ['value' => 3,
                         'name'  => '鞋子'
                        ],
                        ['value' => 4,
                         'name'  => '帽子'
                        ],
                        ['value' => 7,
                         'name'  => '袜子'
                        ],
                        ['value' => 9,
                         'name'  => '衣服'
                        ],
                    ];

                    break;

                case 4:
                    $result   = [
                        [
                            'value'=>[6, 3, 4, 7, 9],
                        ]
                    ];
                    break;
            }

            return admin_success('', URL_CURRENT, $result);
        }

        return $this->fetch();
    }
}