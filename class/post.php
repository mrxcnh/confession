<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 9/30/2018
 * Time: 12:01 PM
 */
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/comment.php";
require_once __DIR__ . "/../function.php";

class post
{
    public $id;
    public $content;
    public $approval;
    public $approval_by;
    public $approval_time;
    public $view;
    public $like;
    public $dislike;
    public $cmt;
    public $date_created;//DateTime object

    public $rand = false;//có phải là post ngẫu nhiên hay không (post được khỏi tạo với id = null)

    private $db;


    public function getContent(){
        return getTextContent($this->content);
    }

    /**
     * post constructor.
     * @param $id
     */
    public function __construct($id = null)
    {
        $this->db = db::singleton();

        if($id !== null){
            if(!is_numeric($id) || intval($id) < 1) return;
            settype($id, "int");
            $this->id = $id;
        } else {
            $this->rand = true;
        }
    }

    public static function create($content, $user_id){
        $db = db::singleton();
        //Check if has a confession has same content
        if($db->select_sql_one_row("SELECT id FROM confession.post WHERE content = '" . db::validSql($content) . "'")) return false;

        //Check if user create 10 min confession before
        if($db->select_sql_one_row("SELECT id FROM confession.post WHERE user_id = '$user_id' AND date_created > date_sub(now(), interval 10 minute)")) return false;

        return $db->insert(
            "confession.post",
            ["content" => $content, "user_id" => $user_id]);
    }


    public function getProperties(){
        if($this->rand){
            if($this->db->select_sql_one_row("SELECT * FROM confession.post WHERE approval = 'yes' ORDER BY RAND() LIMIT 1")){
                return $this->parseData($this->db->kq);
            } else return false;
        } else {
            if(!is_numeric($this->id)) return false;
            if($this->db->select_sql_one_row("SELECT * FROM confession.post WHERE id = '$this->id'")){
                return $this->parseData($this->db->kq);
            } else return false;
        }
    }

    public function parseData($data){
        if(isset($data->id) && is_numeric($data->id)){
            $this->id = $data->id;
            $this->content = $data->content;
            $this->approval = $data->approval;
            $this->approval_by = $data->approval_by;
            $this->approval_time = $data->approval_time;
            $this->view = $data->view;
            $this->like = $data->like;
            $this->dislike = $data->dislike;
            $this->cmt = $data->cmt;
         
    $this->date_created = new DateTime($data->date_created);

            
            return true;
        } else return false;
    }

    public function isApproved(){
        return "yes" === $this->approval;
    }

    public function doLike( $user_id){
        return $this->doReaction("like", $user_id);
    }
    public function doDislike($user_id){
        return $this->doReaction("dislike", $user_id);
    }

    /**
     * $reaction: like or dislike
     * $lastReaction: like or dislike or null
     * */
    private function doReaction($reaction, $user_id)
    {
        if ("like" !== $reaction && "dislike" !== $reaction) return false;
        if (!is_numeric($this->id)) return false;
        if (!is_numeric($user_id)) return false;

        return $this->db->inodate(
            TB_USER_POST_REACTION,
            ["reaction" => $reaction, "post_id" => $this->id, "user_id" => $user_id],
            "post_id = '$this->id' AND user_id = '$user_id'");
    }

    public function getComments($last_comment_id = null, $total_include = false)
    {
        if(!is_numeric($this->id)) return false;

        $sql = "SELECT * FROM confession.comment WHERE post_id = '$this->id'";

        if (is_numeric($last_comment_id)) {
            //load more comment from $lastCommentId (ajax)
            settype($last_comment_id, "int");
            $sql .= " AND id > '$last_comment_id'";
        }

        $sql .= " ORDER BY id ASC LIMIT " . LIMIT_COMMENT_PER_CONFESSION;

        $result = array();
        if ($this->db->select_sql($sql)) {
            if(is_array($this->db->kq) && count($this->db->kq) > 0){
                $result['comments'] = array();
                    foreach ($this->db->kq as $comment){
                        $result['comments'][] = comment::newInstance($comment->id,$comment->content,$comment->post_id,$comment->user_id,$comment->like,$comment->dislike,$comment->date_created );
                }
            }

            if ($total_include && $this->db->select_sql_one_row("SELECT count(*) as total FROM ".TB_COMMENT." WHERE post_id = '$this->id'")) {
                $result['total'] = $this->db->kq->total;
            }
            return $result;
        } else return null;
    }


    /*
     * Điều kiện 1: Một người dùng chỉ được bình luân 1 post không quá 10 comment trong vòng 5 phút
     * Return id của comment đã insert or null
     * */
    public function doComment($comment_content, $user_id)
    {
        if(!is_numeric($this->id)) return null;

        //Check valid content
        if ($comment_content == null) return null;

        $comment_content = comment::validContent($comment_content);


        //Check điều kiện 1
        if ($this->db->select_sql_one_row("SELECT count(*) as count FROM confession.comment WHERE post_id = '$this->id' AND user_id = '$user_id' AND date_created > DATE_SUB(NOW(), INTERVAL 10 MINUTE)")) {
            $count = $this->db->kq->count;
            //bình luận quá nhiều
            if (is_numeric($count) && intval($count) > 10) {
                return null;
            }
        }


        $last_comment_id = $this->getLastCommenthasContent($comment_content, $user_id);
        if ($last_comment_id === null) {
            if (!$this->db->insert("confession.comment", ["post_id" => $this->id, "user_id" => $user_id, "content" => $comment_content])) return false;
            $last_comment_id = $this->getLastCommenthasContent($comment_content, $user_id);
        }

        return $last_comment_id;
    }


    /*
     *
     * Lấy ra comment có nội dung $content được đăng cách đây không quá 1 phút bởi người dùng #user_id
     *
     * Ngăn chặn người dùng bình luận cùng 1 nội dung trên 1 post quá nhiều (spam)
     *
     * */
    private function getLastCommenthasContent($comment_content, $user_id)
    {
        if ($this->db->select_sql_one_row("SELECT id FROM confession.comment WHERE post_id = '$this->id' AND user_id = '$user_id' AND content like \"" . db::validSql($comment_content) . "\" AND date_created > DATE_SUB(NOW(), INTERVAL 1 MINUTE) ")) {
            $comment_id = $this->db->kq->id;
            if (is_numeric($comment_id)) {
                settype($comment_id, "int");
                return $comment_id;
            } else return null;
        } else return null;
    }

    /*
     * Tăng thêm 1 lượt view
     * */
    public function increaseView(){
        return $this->db->execute("UPDATE confession.post SET view = view + 1 WHERE id = '$this->id'");
    }

}
