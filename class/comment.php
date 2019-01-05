<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 9/30/2018
 * Time: 12:10 PM
 */
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../function.php";

class comment
{
    public $id;
    public $content;
    public $post_id;
    public $user_id;
    public $like;
    public $dislike;
    public $date_created;//datetime object

    private $db;

    public function getFakeInstance()
    {
        $fake = (object)[
            "comment_id" => $this->id,
            "comment" => $this->getContent(),
            "confession_id" => $this->post_id,
            "time_post" => $this->date_created->format('d/m/Y'),
            "like" => $this->like,
            "dislike" => $this->dislike,
        ];
        return $fake;
    }

    public static function newInstance($id, $content, $post_id, $user_id, $like, $dislike, $date_created)
    {
        $objComment = new comment();
        $objComment->id = $id;
        $objComment->content = $content;
        $objComment->post_id = $post_id;
        $objComment->user_id = $user_id;
        $objComment->like = $like;
        $objComment->dislike = $dislike;
        if (is_string($date_created)) {
            $objComment->date_created = new DateTime($date_created);
        } else $objComment->date_created = $date_created;
        return $objComment;
    }

    /**
     * comment constructor.
     * @param $id : 2 type
     * 1. int comment id
     * 2. string: postid_commentid
     */
    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->db = db::singleton();
            $this->id = self::parseCommentId($id);
        }
    }

    public function getContent()
    {
        return getTextContent($this->content);
    }

    public function getProperties()
    {
        if (!is_numeric($this->id)) return;
        if ($this->db->select_sql_one_row("SELECT * FROM " . TB_COMMENT . " WHERE id = '$this->id'")) {
            $data = $this->db->kq;
            $this->content = $data->content;
            $this->post_id = $data->post_id;
            $this->user_id = $data->user_id;
            $this->dislike = $data->like;
            $this->date_created = $data->date_created;
        }
    }

    public function doLike($user_id)
    {
        return $this->doReaction("like", $user_id);
    }

    public function doDislike($user_id)
    {
        return $this->doReaction("dislike", $user_id);
    }

    /**
     * $reaction: like or dislike
     * $lastReaction: like or dislike or null
     * */
    public function doReaction($reaction, $user_id)
    {
        if ("like" !== $reaction && "dislike" !== $reaction) return false;
        if (!is_numeric($this->id)) return false;
        if (!is_numeric($user_id)) return false;

        return $this->db->inodate(
            TB_USER_CMT_REACTION,
            ["reaction" => $reaction, "cmt_id" => $this->id, "user_id" => $user_id],
            "cmt_id = '$this->id' AND user_id = '$user_id'");
    }

    //min length = 5
    public static function validContent($content)
    {
        if ($content == null) return null;
        $content = trim($content);
        return (strlen($content) >= 5) ? $content : null;
    }

    public static function parseCommentId($id, $post_id = null)
    {
        if ($id !== null) {
            if ($post_id != null && !startsWith($id, $post_id . "_")) {
                return null;
            }

            if (strpos($id, '_') !== false) {
                $id = explode("_", $id)[1];
            }

            if (is_numeric($id)) {
                settype($id, "int");
                if ($id > 0) {
                    return $id;
                }
            }
        }
        return null;
    }

}