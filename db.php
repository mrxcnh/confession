<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 10/3/2018
 * Time: 10:45 AM
 */

require_once __DIR__ . "/config.php";
class db
{
    public $_dbh = '';
    public $_cursor = NULL;
    public $kq;

    private function __construct()
    {
//echo "Create new database\n";debug
        $this->_dbh = new PDO('mysql:dbname=' . $GLOBALS['CONFIG_DATABASE']['SCHEMA'] . ';host=' . $GLOBALS['CONFIG_DATABASE']['HOST'], $GLOBALS['CONFIG_DATABASE']['USERNAME'], $GLOBALS['CONFIG_DATABASE']['PASSWORD']);
        $this->_dbh->query('set names "utf8"');
    }

    // Hold an instance of the class
    private static $instance;

    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            self::$instance = new db();
        }
        return self::$instance;
    }
    /*
    using
    $user1 = User::singleton();
    $user2 = User::singleton();
    $user3 = User::singleton();
     */

//Thực thi 1 câu truy vẫn có trả về dữ liệu(select)
    public function executeQuery($sql)
    {
        // file_put_contents("log.txt", $sql. "\n\n\n\n", FILE_APPEND);debug
        $this->_cursor = $this->_dbh->prepare($sql);
        $this->_cursor->execute();
        return $this->_cursor;
    }

//Thực thi 1 câu truy vẫn không trả về dữ liệu(update,delete,insert...)
    public function execute($sql)
    {
        // file_put_contents("log.txt", $sql. "\n\n\n\n", FILE_APPEND);debug
        try {
//            echo "\n$sql\n";
            $this->_cursor = $this->_dbh->prepare($sql);
            $bo = $this->_cursor->execute();
            return $bo;
        } catch (Exception $ex) {
//            var_dump($ex);
            return false;
        }

    }

//Funtion load data one record on table
    public function select_sql_one_row($sql)
    {
        if (!$result = $this->executeQuery($sql)) return false;
        $array = $result->fetch(PDO::FETCH_OBJ);
        if ($array != null) {
            $this->kq = $array;
            return true;
        } else return false;
    }

//Funtion load datas on table
    public function select_sql($sql)
    {
        if (!$result = $this->executeQuery($sql)) return false;
        $array = $result->fetchAll(PDO::FETCH_OBJ);
        if (is_array($array) && count($array) > 0) {
            $this->kq = $array;
            return true;
        } else return false;
    }

    public function select($colName, $table, $condition)
    {
        $sql = "SELECT " . trim($colName) . " FROM " . trim($table) . " WHERE " . trim($condition) . " ;";
        $bool = $this->select_sql($sql);
//        var_dump($sql . " - " . $bool);
        return $bool;
    }

    public function disconnect()
    {
        $this->_dbh = NULL;
    }

    public static function validSql($value)
    {
        if ($value == null) {
            return null;
        }
        //Các câu lệnh sau phải đúng thứ tự
        $value = str_replace("\\", "\\\\", $value);
        $value = str_replace("\"", "\\\"", $value);
        $value = str_replace("\'", "\\\'", $value);
        return $value;
    }


    /*
    $b_return = insert("anime", array("id" => 5, "name" => "Dragon Ball"));
    return boolean
     */
    public function insert($table, $cols)
    { //Truy vấn insert
        if (!is_array($cols)) {
//        echo "$cols is not array";
            return false;
        }
        $totalColumn = count($cols);
        if ($totalColumn == 0) {
//            echo "Number of column == 0";
            return false;
        }

        $str_colName = "";//Tên các cột
        $str_colValue = "";//Giá trị các cột
        $colNames = array_keys($cols);
        for ($i = 0; $i < $totalColumn; $i++) {
            $colName = $colNames[$i];
            $colValue = $cols[$colName];
            if ($colName != null && $colValue != null) {
                $str_colValue .= "\"" . db::validSql($colValue) . "\"";
                $str_colName .= trim($colName);
                if ($i < $totalColumn - 1) {
                    $str_colName .= ", ";
                    $str_colValue .= ", ";
                }
            }
        }
        $str_colValue = trim($str_colValue);
        $str_colName = trim($str_colName);

        while (substr($str_colName, strlen($str_colName) - 1) == ",") {
            $str_colName = trim(substr($str_colName, 0, strlen($str_colName) - 1));
        }
        while (substr($str_colValue, strlen($str_colValue) - 1) == ",") {
            $str_colValue = trim(substr($str_colValue, 0, strlen($str_colValue) - 1));
        }
        while (substr($str_colName, 0, 1) == ",") {
            $str_colName = trim(substr($str_colName, 1));
        }
        while (substr($str_colValue, 0, 1) == ",") {
            $str_colValue = trim(substr($str_colValue, 1));
        }

        $sql = "INSERT INTO " . $table . "(" . $str_colName . ")" . " VALUES " . "(" . $str_colValue . ");";

        return $this->execute($sql);//Thực thi câu lệnh insert
    }

    public function update($table, $cols, $condition)
    {
        if (!is_array($cols)) {
//        echo "$cols is not array";
            return false;
        }
        $totalColumn = count($cols);
        if ($totalColumn == 0) {
//            echo "Number of column == 0";
            return false;
        }


        $set = "";
        $colNames = array_keys($cols);
        for ($i = 0; $i < $totalColumn; $i++) {
            $colName = $colNames[$i];
            $colValue = $cols[$colName];

            if ($colName != null && $colValue != null) {
                $set .= $colName . " = \"" . db::validSql($colValue) . "\"";
                if ($i < $totalColumn - 1) {
                    $set .= ", ";
                }
            }
        }
        //Loại bỏ các dấu , thừa trong set
        $set = trim($set);
        while (substr($set, strlen($set) - 1) == ",") {
            $set = trim(substr($set, 0, strlen($set) - 1));
        }
        while (substr($set, 0, 1) == ",") {
            $set = trim(substr($set, 1));
        }

        if ($condition == null || trim($condition) == "") {
            $condition = "1";
        }
        $sql = "UPDATE " . $table . " SET " . $set . " WHERE " . $condition;

        return $this->execute($sql);//Thực thi câu lệnh update
    }

    /*
        * insert or update records
        * Nếu bản ghi đã tồn tại trong csdl thì {
        * - nếu bản ghi có sự khác biệt với dữ liệu insert vào thì update lại
        * - nếu bản ghi không có sự khác biệt thì không làm gì cả
        }
        * Còn bản ghi chưa có thì insert
        * inodate = insert + or + update
         */
    public function inodate($table, $cols, $condition)
    {
        if (!is_array($cols)) {
//        echo "$cols is not array";
            return false;
        }
        $totalColumn = count($cols);
        if ($totalColumn == 0) {
//            echo "Number of column == 0";
            return false;
        }
        $colNames = array_keys($cols);
        $str_colName = implode(", ",$colNames);//Tên các cột

        //Loại bỏ các dấu , thừa trong set
        $str_colName = trim($str_colName);
        while (substr($str_colName, strlen($str_colName) - 1) == ",") {
            $str_colName = trim(substr($str_colName, 0, strlen($str_colName) - 1));
        }
        while (substr($str_colName, 0, 1) == ",") {
            $str_colName = trim(substr($str_colName, 1));
        }

        if ($this->select($str_colName, $table, $condition)) {
            $kq = (array)$this->kq[0];
            $update_array = array();
            for ($i = 0; $i < $totalColumn; $i++) {
                $colName = $colNames[$i];
                $colValueDB = trim($kq[$colName]);
                $colValueUpdate = trim($cols[$colName]);
                if (($colValueDB == null && $colValueUpdate != null) || $colValueUpdate != $colValueDB) {
                    $update_array[$colName] = $colValueUpdate;
                }
            }
            if (count($update_array) > 0) {
//            echo "Update to database\n";
                return $this->update($table, $update_array, $condition);
            } else {
//            echo "Nothing to update\n";
                return true;
            }

        } else {
//echo "Insert to database\n";
            return $this->insert($table, $cols);
        }
    }

}

?>