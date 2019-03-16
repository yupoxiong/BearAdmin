<?php
/**
 * 数据库备份类
 * @author yupoxiong<i@yufuping.com>
 * 参数说明
 * config:type(数据库类型),hostname,database,username,password,hostport,charset,savepath(备份存放目录),filename(备份文件名)
 */

namespace tools;

use PDOException;

class DataBackup
{
    //备份配置参数
    private $config = [];

    private $handler;

    //需要备份的表
    private $tables = array();

    //开始时间
    private $begin;

    //结果
    private $result = [];

    public function __construct($config)
    {
        set_time_limit(0);

        $this->result = [
            'status'    => 500,
            'result'    => [],
            'message'   => '',
            'timestamp' => time(),
        ];

        $this->config = $config;
        $this->begin  = microtime(true);
        header("Content-type: text/html;charset=utf-8");
        $this->connect();
    }


    //备份
    public function backup($tables = array())
    {
        //存储表定义语句的数组
        $ddl = array();
        //存储数据的数组
        $data = array();
        $this->setTables($tables);
        if (!empty($this->tables)) {
            foreach ($this->tables as $table) {
                $ddl[]  = $this->get_dbhead($table);
                $data[] = $this->get_dbdata($table);
            }

            return $this->writeToFile($this->tables, $ddl, $data);
        }
        return $this->error('数据库中没有表');
    }


    //还原
    public function restore($filename = '')
    {
        $savepath = $this->config['savepath'] . $filename;
        if (!file_exists($savepath)) {
            return $this->error('SQL文件不存在');
        } else {
            $sql = $this->parseSQL($savepath);
            try {

                $PDOStatement = $this->handler->prepare($sql);
                $affected = $PDOStatement->execute();

                return $this->success($affected.'还原成功!耗时'.round(microtime(true) - $this->begin, 4) . 's');

            } catch (PDOException $e) {
                return $this->error($e->getMessage());
            }
        }
    }


    //备份列表
    public function get_filelist($desc = 1)
    {
        $FilePath         = $this->config['savepath'];
        $FilePath         = opendir($FilePath);
        $FileAndFolderAyy = array();
        $i                = 1;
        while (false !== ($filename = readdir($FilePath))) {
            if ($filename != "." && $filename != "..") {
                $i++;
                $FileAndFolderAyy[$i]['name'] = $filename;
                $FileAndFolderAyy[$i]['time'] = $this->getfiletime($filename);
                $FileAndFolderAyy[$i]['size'] = $this->getfilesize($filename);
            }
        }

        if ($desc == 1) {
            rsort($FileAndFolderAyy);
        } else {
            sort($FileAndFolderAyy);
        }
        return $this->success('success', $FileAndFolderAyy);
    }


    //下载备份
    public function downloadFile($fileName)
    {
        $fileName = $this->config['savepath'] . $fileName;
        if (file_exists($fileName)) {
            ob_end_clean();
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . filesize($fileName));
            header('Content-Disposition: attachment; filename=' . basename($fileName));
            return readfile($fileName);
        }
        return $this->error('文件不存在');
    }


    //写入文件
    private function writeToFile($tables = array(), $ddl = array(), $data = array())
    {
        $str = "/*\r\n Database Backup \r\n";
        $str .= "Server:{$this->config['hostname']}:{$this->config['hostport']}\r\n";
        $str .= "Database:{$this->config['database']}\r\n";
        $str .= "Data:" . date('Y-m-d H:i:s', time()) . "\r\n*/\r\n";
        $str .= "SET FOREIGN_KEY_CHECKS=0;\r\n";
        $i = 0;
        foreach ($tables as $table) {
            $str .= "-- ----------------------------\r\n";
            $str .= "-- Table structure for {$table}\r\n";
            $str .= "-- ----------------------------\r\n";
            $str .= "DROP TABLE IF EXISTS `{$table}`;\r\n";
            $str .= $ddl[$i] . "\r\n";
            $str .= "-- ----------------------------\r\n";
            $str .= "-- Records of {$table}\r\n";
            $str .= "-- ----------------------------\r\n";
            $str .= $data[$i] . "\r\n";
            $i++;
        }

        if (!file_exists($this->config['savepath'])) {
            mkdir($this->config['savepath']);
        }

        if (file_put_contents($this->config['savepath'] . $this->config['filename'], $str)) {
            return $this->success(
                '备份成功!花费时间' . round((microtime(true) - $this->begin), 4) . 's'
            );
        }
        return $this->error('备份失败');
    }


    //删除备份
    public function deleteFile($filename)
    {
        $savepath = $this->config['savepath'] . $filename;
        if (@unlink($savepath)) {
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }


    //返回错误
    private function error($message = 'error', $result = [])
    {
        $this->result['status']  = 500;
        $this->result['message'] = $message;
        $this->result['result']  = $result;
        return $this->result;
    }


    //执行成功返回
    private function success($message = 'success', $result = [])
    {
        $this->result['status']  = 200;
        $this->result['message'] = $message;
        $this->result['result']  = $result;
        return $this->result;
    }


    // 获取备份创建时间
    private function getfiletime($file)
    {
        $savepath = $this->config['savepath'] . $file;
        $a        = filemtime($savepath);
        $time     = date("Y-m-d H:i:s", $a);
        return $time;
    }


    //获取备份大小
    private function getfilesize($file)
    {
        $perms = stat($this->config['savepath'] . $file);
        $size  = $perms['size'];
        $a     = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos   = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size, 2) . $a[$pos];
    }


    //解析SQL文件为SQL语句数组
    private function parseSQL($savepath = '')
    {
        $sql = file_get_contents($savepath);
        $sql = explode("\r\n", $sql);
        //先消除--注释
        $sql = array_filter($sql, function ($data) {
            if (empty($data) || preg_match('/^--.*/', $data)) {
                return false;
            } else {
                return true;
            }
        });
        $sql = implode('', $sql);
        $sql = preg_replace('/\/\*.*\*\//', '', $sql);
        return $sql;
    }


    //首次进行pdo连接
    private function connect()
    {
        try {
            $this->handler = new \PDO("{$this->config['type']}:host={$this->config['hostname']};port={$this->config['hostport']};dbname={$this->config['database']};",
                $this->config['username'],
                $this->config['password'],
                array(
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->config['charset']};",
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ));
            return true;
        } catch (PDOException $e) {
            return $this->error($e->getMessage());
        }
    }


    //查询
    private function query($sql = '')
    {
        $stmt = $this->handler->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_NUM);
        $list = $stmt->fetchAll();
        return $list;
    }


    //获取全部表
    private function get_dbname($dbName = '*')
    {
        $sql    = 'SHOW TABLES';
        $list   = $this->query($sql);
        $tables = array();
        foreach ($list as $value) {
            $tables[] = $value[0];
        }
        return $tables;
    }


    //获取表定义语句
    private function get_dbhead($table = '')
    {
        $sql = "SHOW CREATE TABLE `{$table}`";
        $ddl = $this->query($sql)[0][1] . ';';
        return $ddl;
    }


    //获取表数据
    private function get_dbdata($table = '')
    {
        $sql  = "SHOW COLUMNS FROM `{$table}`";
        $list = $this->query($sql);
        //字段
        $columns = '';
        //需要返回的SQL
        $query = '';
        foreach ($list as $value) {
            $columns .= "`{$value[0]}`,";
        }
        $columns = substr($columns, 0, -1);
        $data    = $this->query("SELECT * FROM `{$table}`");
        foreach ($data as $value) {
            $dataSql = '';
            foreach ($value as $v) {
                if(null!==$v){
                    $dataSql .= "'{$v}',";
                }else{
                    $dataSql .= "null,";
                }

            }
            $dataSql = substr($dataSql, 0, -1);
            $query .= "INSERT INTO `{$table}` ({$columns}) VALUES ({$dataSql});\r\n";
        }
        return $query;
    }


    //设置要备份的表
    private function setTables($tables = array())
    {
        if (!empty($tables) && is_array($tables)) {
            //备份指定表
            $this->tables = $tables;
        } else {
            //备份全部表
            $this->tables = $this->get_dbname();
        }
    }
}