<?php
/**
 * 工具trait
 */
namespace generate\traits;

trait Tools
{
    public function getMiddleStr($str, $leftStr, $rightStr)
    {
        $left  = strpos($str, $leftStr);
        $right = strpos($str, $rightStr, $left);
        if ($left < 0 || $right < $left) {
            return '';
        }
        return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
    }

    /**
     * 获取字段信息
     * @param $field_name
     * @param $field_type
     * @return array
     * 常用类型
     * tinyint,smallint,mediumint,int,bigint,float,double,decimal
     * char,varchar,tinytext/tinyblob,text/blob,longtext/longblob
     * date,datetime,timestamp,time,year
     */
    public function getFieldInfo($field_name, $field_type): array
    {
        //默认类型
        $type = 'tinyint';
        //默认长度
        $length = 10;
        //默认小数点后位数
        $digit = 0;

        if (0 === strpos($field_type, 'tinyint')) {
            $type   = 'tinyint';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'smallint')) {
            $type   = 'smallint';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'mediumint')) {
            $type   = 'mediumint';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'int')) {
            $type   = 'int';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'bigint')) {
            $type   = 'bigint';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'float')) {
            $type   = 'float';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'double')) {
            $type   = 'double';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'decimal')) {
            $type   = 'decimal';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'char')) {
            $type   = 'char';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'varchar')) {
            $type   = 'varchar';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'tinytext')) {
            $type   = 'tinytext';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'tinyblob')) {
            $type   = 'tinyblob';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'text')) {
            $type   = 'text';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'blob')) {
            $type   = 'blob';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'longtext')) {
            $type   = 'longtext';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'longblob')) {
            $type   = 'longblob';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'date')) {
            $type   = 'date';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'datetime')) {
            $type   = 'datetime';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'timestamp')) {
            $type   = 'timestamp';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'time')) {
            $type   = 'time';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'year')) {
            $type   = 'year';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');

        } else if (0 === strpos($field_type, 'json')) {
            $type   = 'json';
            $length = $this->getMiddleStr($field_type, $type . '(', ')');
        }
        return [
            'name'   => $field_name,
            'type'   => $type,
            'length' => $length,
            'digit'  => $digit,
        ];
    }
}
