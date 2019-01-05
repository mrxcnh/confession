<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 10/4/2018
 * Time: 5:00 PM
 */
require_once __DIR__ . "/../../db.php";
require_once __DIR__ . "/../../config.php";

class admin
{
    public $id;
    public $email;
    public $name;
    public $avatar;

    private $db;

    /**
     * admin constructor.
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->db = db::singleton();
        if (is_numeric($this->id)) {
            if ($this->db->select_sql_one_row("SELECT id,avatar, name, email, date_created FROM " . TB_ADMIN . " WHERE id = $this->id")) {
                $result = $this->db->kq;
                $this->email = $result->email;
                $this->name = $result->name;
                $this->avatar = $result->avatar;
            }
        }
    }


    public function getTotalPost()
    {
        if ($this->db->select_sql_one_row("SELECT count(*) as total FROM " . TB_POST)) {
            return intval($this->db->kq->total);
        } else return null;
    }

    public function getTotalPostNotYetApproval()
    {
        if ($this->db->select_sql_one_row("SELECT count(*) as total FROM " . TB_POST . " WHERE approval = 'not yet'")) {
            return intval($this->db->kq->total);
        } else return null;
    }

    /*
     * Tổng số các bài nổi bật gần đây
     * Nổi bật: có lượng like or dislike > 2/3 mức trung bình
     * Gần đây: 7 ngày
     * */
    public function getTotalHighlightsPost()
    {
        if ($this->db->select_sql_one_row("SELECT count(*) as total FROM " . TB_POST . " WHERE approval = 'not yet'")) {
            return intval($this->db->kq->total);
        } else return null;
    }

    public function getTotalPost24h()
    {
        if ($this->db->select_sql_one_row("SELECT count(*) as total FROM " . TB_POST . " WHERE date_created > DATE_SUB(NOW(), INTERVAL 24 HOUR)")) {
            return intval($this->db->kq->total);
        } else return null;
    }

    /*
     * $page: 1, 2, 3
     * */
    public function getAllPost($search_content = null, $start = 0, $limit = 10, $order_field = "date_created", $order_by = "asc")
    {
        $sql = "SELECT post.id, post.content, post.approval, post.approval_time, admin.name as approval_by, post.view, post.like, post.dislike, post.cmt, post.date_created FROM confession.post LEFT JOIN confession.admin ON post.approval_by = admin.id";
        if($search_content != null) $sql .= " WHERE post.content like '%" . db::validSql($search_content) . "%'";
        $sql .= " ORDER BY $order_field $order_by LIMIT $start,$limit";

        if ($this->db->select_sql($sql)) {
            return $this->db->kq;
        } else return null;
    }

    /*
     * Danh sách các bài chưa được phê duyệt từ cũ tới mới
     *
     * */
    public function getRecentlyPostNotYetApproval($search_content = null, $start = 0, $limit = 10, $order_field = "date_created", $order_by = "asc")
    {
        $sql = "SELECT id, content, date_created, user_id as uid,(SELECT count(*) FROM confession.post where user_id = uid) as num_user_post FROM confession.post WHERE approval = 'not yet'";
        if($search_content != null) $sql .= " AND post.content like '%" . db::validSql($search_content) . "%'";
        $sql .= " ORDER BY $order_field $order_by LIMIT $start,$limit";

        if ($this->db->select_sql($sql)) {
            return $this->db->kq;
        } else return null;
    }

    /*
     * Danh sách các bài post vừa được phê duyệt từ mới tới cũ (tính theo thời gian được phê duyệt)
     * */
    public function getRecentlyPostApproved()
    {
        if ($this->db->select_sql("SELECT " . TB_POST . ".*, " . TB_ADMIN . ".name as approval_by_name FROM " . TB_POST . " INNER JOIN " . TB_ADMIN . " ON " . TB_ADMIN . ".id = " . TB_POST . ".approval_by  WHERE approval = 'yes' ORDER BY approval_time DESC LIMIT " . $GLOBALS['CONFIG_ADMIN']['LIMIT_POST'])) {
            return $this->db->kq;
        } else return null;
    }

    public function approvalPost($post_id){
        if($this->db->select_sql_one_row("SELECT * FROM post WHERE id = $post_id")){
            if("yes" === $this->db->kq->approval) return false;
            return $this->db->execute("UPDATE post SET approval = 'yes', approval_by = $this->id WHERE id = $post_id");
        } else return false;
    }

    public function unapprovalPost($post_id){
        if($this->db->select_sql_one_row("SELECT * FROM post WHERE id = $post_id")){
            if("no" === $this->db->kq->approval) return false;
            return $this->db->execute("UPDATE post SET approval = 'no', approval_by = null WHERE id = $post_id");
        } else return false;
    }

}