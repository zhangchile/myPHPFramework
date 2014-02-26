<?php
/** SQL辅助类 **/
class SQLQuery
{
    protected $_dbHandle;//连接数据库的资源对象
    protected $_result;

    /** 连接数据库 **/
    function connect($address, $account, $pwd, $name)
    {
        $this->_dbHandle = @mysql_connect($address, $account, $pwd);
        if($this->_dbHandle != 0) {
            if(mysql_select_db($name, $this->_dbHandle)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** 断开数据库连接 **/
    function disconnect()
    {
        if(@mysql_close($this->_dbHandle) != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function selectAll()
    {
        $query = "select * from `" . $this->_table . '`';
        return $this->query($query);
    }

    function select($id)
    {
        $query = 'select * from `' . $this->_table . '` where `id` = \'' . mysql_real_escape_string($id) . '\'';
        return $this->query($query, 1);
    }

    function query($query, $singleResult = 0)
    {
        $this->_result = mysql_query($query, $this->_dbHandle);
        if(preg_match("/select/i", $query))
        {
            $result = array();
            $table = array();
            $field = array();
            $tempResults = array();            
            $numOffFields = mysql_num_fields($this->_result);//结果集中字段的数目
            for($i = 0; $i < $numOffFields; $i++) {
                //取得结果集中指定字段所在的表名
                array_push($table, mysql_field_table($this->_result, $i));
                //取得结果中指定字段的字段名
                array_push($field, mysql_field_name($this->_result, $i));
            }
            while($row = mysql_fetch_row($this->_result)) {
                for($i = 0; $i < $numOffFields; $i++) {
                    $table[$i] = trim(ucfirst($table[$i]), 's');
                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
                }
                if($singleResult == 1) {
                    mysql_free_result($this->_result);
                    return $tempResults;
                }
                array_push($result, $tempResults);
            }
            mysql_free_result($this->_result);
            return $result;
        }
    }
    //获取行数
    function getNumRows()
    {
        return mysql_num_rows($this->_result);
    }

    function freeResult()
    {
        return msql_free_result($this->_result);//释放结果集,成功为true，失败为false;
    }

    function getError()
    {
        return mysql_error($this->_dbHandle);
    }
}