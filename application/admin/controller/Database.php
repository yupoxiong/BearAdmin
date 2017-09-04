<?php
/**
 *
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;


class Database extends Base
{
    /**
     * 数据库列表
     */
    public function index()
    {
        $list  = db()->query('SHOW TABLE STATUS');
        $list  = array_map('array_change_key_case', $list);//全部小写
        $this->assign('lists', $list);
        
        return $this->fetch();
    }

    /**
     * 优化表
     * @param  String $tables 表名
     */
    public function optimize($tables = null)
    {
        if ($tables) {
            $Db = db();
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list   = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if ($list) {
                    return $this->success("数据表优化完成！");
                } else {
                    return $this->error("数据表优化出错请重试！");
                }
            }
        } else {
            return $this->error("请指定要优化的表！");
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     */
    public function repair($tables = null)
    {
        if ($tables) {
            $Db = db();
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list   = $Db->query("REPAIR TABLE `{$tables}`");

                if ($list) {
                    return $this->success("数据表修复完成！");
                } else {
                    return $this->error("数据表修复出错请重试！");
                }
            }
        } else {
            return $this->error("请指定要修复的表！");
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     */
    public function repair_list()
    {
        //列出备份文件列表
        $path = config('data_backup_path');
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);
        $list = array();
        foreach ($glob as $name => $file) {
            if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                if (isset($list["{$date} {$time}"])) {
                    $info         = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time']     = strtotime("{$date} {$time}");

                $list["{$date} {$time}"] = $info;
            }
        }
        $this->assign('_list', $list);
        return $this->fetch();
    }

    /**
     * 下载表
     */
    public function downfile()
    {
        $file = $this->request->param('file');
        $type = $this->request->param('type');
        if (empty($file) || empty($type) || !in_array($type, array("zip", "gz", "sql"))) {
            $this->error("下载地址不存在");
        }
        $name = $file . '-*.sql*';
        $filePath = glob(realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name);
        if (!is_file($filePath[0])) {
            $this->error("该文件不存在，可能是被删除");
        }
        $filename = basename($filePath[0]);
        /* 执行下载 */ //TODO: 大文件断点续传
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($filePath[0]));
        readfile($filePath[0]);
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function del($time = 0)
    {
        if ($time) {
            $name = date('Ymd-His', $time) . '-*.sql*';
            $path = realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if (count(glob($path))) {
                return $this->error('备份文件删除失败，请检查权限！');
            } else {
                return $this->success('备份文件删除成功！');
            }
        } else {
            return $this->error('参数错误！');
        }
    }

    /**
     * 还原数据库
     */
    public function import($time = 0, $part = null, $start = null)
    {
        if (is_numeric($time) && is_null($part) && is_null($start)) {
            //初始化
            //获取备份文件信息
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach ($files as $name) {
                $basename        = basename($name);
                $match           = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz              = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);
            //检测文件正确性
            $last = end($list);
            if (count($list) === $last[0]) {
                session('backup_list', $list); //缓存备份列表
                return $this->success('初始化完成！', '', array('part' => 1, 'start' => 0));
            } else {
                return $this->error('备份文件可能已经损坏，请检查！');
            }
        } elseif (is_numeric($part) && is_numeric($start)) {
            $list = session('backup_list');
            $db = new \com\Database($list[$part], array('path' => realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR, 'compress' => $list[$part][2]));
            $start = $db->import($start);
            if (false === $start) {
                return $this->error('还原数据出错！');
            } elseif (0 === $start) {
                //下一卷
                if (isset($list[++$part])) {
                    $data = array('part' => $part, 'start' => 0);
                    return $this->success("正在还原...#{$part}", '', $data);
                } else {
                    session('backup_list', null);
                    return $this->success('还原完成！');
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if ($start[1]) {
                    $rate = floor(100 * ($start[0] / $start[1]));
                    return $this->success("正在还原...#{$part} ({$rate}%)", '', $data);
                } else {
                    $data['gz'] = 1;
                    return $this->success("正在还原...#{$part}", '', $data);
                }
            }
        } else {
            return $this->error('参数错误！');
        }
    }

    /**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     */
    public function export($tables = null, $id = null, $start = null)
    {
        if (request()->isPost() && !empty($tables) && is_array($tables)) {
            //初始化检测
            $path = config('data_backup_path');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = array(
                'path' => realpath($path) . DIRECTORY_SEPARATOR,
                'part' => config('data_backup_part_size'),
                'compress' => config('data_backup_compress'),
                'level' => config('data_backup_compress_level')
            );
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if (is_file($lock)) {
                return $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, time());
            }
            //检查备份目录是否可写
            if (!is_writeable($config['path'])) {
                return $this->error('备份目录不存在或不可写，请检查后重试！');
            }
            session('backup_config', $config);
            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', time()),
                'part' => 1
            );
            session('backup_file', $file);
            //缓存要备份的表
            session('backup_tables', $tables);
            //创建备份文件
            $Database = new \com\Database($file, $config);
            if (false !== $Database->create()) {
                $tab = array('id' => 0, 'start' => 0);
                return $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                return $this->error('初始化失败，备份文件创建失败！');
            }
        }elseif (request()->isGet() && is_numeric($id) && is_numeric($start)) {
            //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new \com\Database(session('backup_file'), session('backup_config'));
            $start    = $Database->backup($tables[$id], $start);
            if (false === $start) {
                //出错
                return $this->error('备份出错！');
            } elseif (0 === $start) {
                //下一表
                if (isset($tables[++$id])) {
                    $tab = array('id' => $id, 'start' => 0);
                    return $this->success('备份完成！', '', array('tab' => $tab));
                } else {
                    //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    return $this->success('备份完成！');
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                return $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }
        }else {
            return $this->error('参数错误！');
        }
    }










}