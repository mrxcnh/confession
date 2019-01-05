<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 9/30/2018
 * Time: 11:53 AM
 */

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db.php";

class user
{
    public $id;
    public $ip;

    private $db;

    /**
     * user constructor.
     * @param $ip
     */
    public function __construct($ip)
    {
        $this->db = db::singleton();

        $this->ip = $ip;
        $this->db->inodate(TB_USER, ["ip" => $ip], "ip = '" . db::validSql($ip) . "'");
        if ($this->db->select_sql_one_row("SELECT id FROM ".TB_USER." WHERE ip = '" . db::validSql($ip) . "'")) {
            if (isset($this->db->kq->id)) $this->id = $this->db->kq->id;
            else return;
        } else return;
    }


    public static function getUserIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }



}