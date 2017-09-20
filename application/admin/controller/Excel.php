<?php
/**
 * Excel相关
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use app\common\model\ExcelExamples;
use Exception;
use PHPExcel_IOFactory;
use PHPExcel;
use think\Db;

class Excel extends Base
{

    /**
     * 列表
     */
    public function index()
    {

        if ($this->request->isPost()) {

            $file = request()->file('export');
            if(!$file){
                return $this->do_error('请上传文件');
            }
            $info = $file->validate(['ext' => 'xlsx'])->move(ROOT_PATH . 'uploads' . DS . 'excel');
            if ($info) {

                $exclePath    = $info->getSaveName();  //获取文件名
                $file_name    = ROOT_PATH . 'uploads' . DS . 'excel' . DS . $exclePath;   //上传文件的地址
                $objReader    = PHPExcel_IOFactory::createReader('Excel2007');
                $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
                $excel_array  = $obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式
                array_shift($excel_array);  //删除第一个数组(标题);
                $person = [];
                foreach ($excel_array as $k => $v) {
                    $person[$k]['name'] = $v[0];
                    $person[$k]['age']  = $v[1];
                    $person[$k]['sex']  = $v[2];
                    $person[$k]['city'] = $v[3];
                }

                $excel_examples = new ExcelExamples();
                if ($excel_examples->saveAll($person)) {
                    return $this->do_success('导入成功');
                }
                return $this->do_error('导入失败');
            }
            return $this->do_error('上传失败');

        }

        if (isset($this->param['act']) && $this->param['act'] == 'download') {
            $header = ['ID', '姓名', '年龄', '性别', '城市'];
            $data   = Db::name("ExcelExamples")->order("id desc")->select();
            return  $this->export($header, $data, "Excel导出例子", '2007');
        }
        $excel_examples = new ExcelExamples();

        $lists = $excel_examples
            ->order('id desc')
            ->paginate(10);

        $this->assign([
            'lists' => $lists,
            'page'  => $lists->render()
        ]);

        return $this->fetch();
    }


    function export($head, $body, $name = null, $version = '2007')
    {
        try {
            // 输出 Excel 文件头
            $name = empty($name) ? date('Y-m-d-H-i-s') : $name;

            $objPHPExcel   = new PHPExcel();
            $sheetPHPExcel = $objPHPExcel->setActiveSheetIndex(0);
            $char_index    = range("A", "Z");

            // Excel 表格头
            foreach ($head as $key => $val) {
                $sheetPHPExcel->setCellValue("{$char_index[$key]}1", $val);
            }

            // Excel body 部分
            foreach ($body as $key => $val) {
                $row = $key + 2;
                $col = 0;
                foreach ($val as $k => $v) {
                    $sheetPHPExcel->setCellValue("{$char_index[$col]}{$row}", $v);
                    $col++;
                }
            }

            // 版本差异信息
            $version_opt = [
                '2007' => [
                    'mime'       => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'ext'        => '.xlsx',
                    'write_type' => 'Excel2007',
                ],
                '2003' => ['mime'       => 'application/vnd.ms-excel',
                           'ext'        => '.xls',
                           'write_type' => 'Excel5',
                ],
                'pdf'  => ['mime'       => 'application/pdf',
                           'ext'        => '.pdf',
                           'write_type' => 'PDF',
                ],
                'ods'  => ['mime'       => 'application/vnd.oasis.opendocument.spreadsheet',
                           'ext'        => '.ods',
                           'write_type' => 'OpenDocument',
                ],
            ];

            header('Content-Type: ' . $version_opt[$version]['mime']);
            header('Content-Disposition: attachment;filename="' . $name . $version_opt[$version]['ext'] . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $version_opt[$version]['write_type']);
            $objWriter->save('php://output');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
