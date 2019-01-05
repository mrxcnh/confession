<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 9/30/2018
 * Time: 1:32 PM
 */
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/function.php";
require_once __DIR__ . "/class/comment.php";
require_once __DIR__ . "/class/post.php";
require_once __DIR__ . "/class/user.php";

function isRequiredPost()
{
    if (isset($_POST['action']) && $_POST['action'] !== "create") return true;
    if (isset($_POST['edge']) && $_POST['edge'] !== "confession") return true;
    return false;
}

//check valid post params

if (isRequiredPost()) {
    if (!isset($_POST['post_id'])) {
        echo_error("missing post_id");
    }
}

if (!isset($_POST['edge'])) {
    echo_error("missing edge");
}
if (!isset($_POST['action'])) {
    echo_error("what is your action?");
}

$db = db::singleton();

if (isRequiredPost()) {
    //check valid confession id (has approved)
    $post = new post($_POST['post_id']);
    if (!$post->getProperties()) {
        echo_error("invalid post");
    }
    if (!$post->isApproved()) {
        echo_error("invalid post");
    }
}

$user_ip = user::getUserIp();
$user = new user($user_ip);
if (!is_numeric($user->id)) {
    echo_error("sorry. We have a problem!");
}


switch (mb_strtolower($_POST['edge'])) {
    case "confession":
        {
            switch (mb_strtolower($_POST['action'])) {
                case "reaction":
                    {
                        if (isset($_POST['type'])) {
                            switch (mb_strtolower($_POST['type'])) {
                                case "like":
                                    {
                                        if ($post->doLike($user->id)) {
                                            echo_success();
                                        } else {
                                            echo_error();
                                        }
                                    }
                                case "dislike":
                                    {
                                        if ($post->doDislike($user->id)) {
                                            echo_success();
                                        } else {
                                            echo_error();
                                        }
                                    }
                                default:
                                    {
                                        echo_error("Invalid type reaction");
                                    }
                            }

                        } else {
                            echo_error("Mising type reaction");
                        }

                        break;
                    }
                case "create":
                    {
                        if (!isset($_POST['content'])) {
                            echo_error("missing content");
                        }

                        $content = trim($_POST['content']);

                        if (strlen($content) < 10) {
                            echo_error("content too short");
                        }

                        if (post::create($content, $user->id)) {
                            echo_success();
                        } else {
                            echo_error();
                        }
                        break;
                    }
                case "comment":
                    {
                        if (isset($_POST['content'])) {
                            $content = urldecode($_POST['content']);
                            $comment_id = $post->doComment($content, $user->id);
                            if (is_numeric($comment_id)) {
                                echo_success("Comment success", ["comment_id" => $post->id . "_" . $comment_id, "post_id" => $post->id]);
                            } else echo_error("insert comment error");
                        } else {
                            echo_error("mising comment content");
                        }
                        break;
                    }
                case "load_more_comment":
                    {
                        if (isset($_POST['last_comment_id'])) {
                            $last_comment_id = comment::parseCommentId($_POST['last_comment_id'], $post->id);
                            if ($last_comment_id === null) {
                                echo_error("Invalid last_comment_id", ["comment_id" => $_POST['last_comment_id']]);
                            }
                        } else {
                            $last_comment_id = null;
                        }
                        $data = $post->getComments($last_comment_id);
                        if (is_array($data) && isset($data['comments']) && count($data['comments']) > 0) {
                            $comments = array();
                            foreach ($data['comments'] as $cmt) {
                                $comments[] = $cmt->getFakeInstance();
                            }
                            echo_success("Load more comment success", ['data' => $comments]);
                        } else echo_success("No more comment", ['No_More' => true]);
                    }
                default:
                    {
                        echo_error("invalid action", ["action" => $_POST['action']]);
                    }
            }
        }
    case "comment":
        {
            if (isset($_POST['comment_id'])) {
                $comment_id = comment::parseCommentId($_POST['comment_id'], $post->id);
                if (!is_numeric($comment_id)) {
                    echo_error("Invalid comment id", ["comment_id" => $_POST['comment_id'], 'post_id' => $post->id]);
                }
                $comment = new comment($comment_id);

            } else return;

            switch (mb_strtolower($_POST['action'])) {
                case "reaction":
                    {

                        if (isset($_POST['type'])) {
                            switch (mb_strtolower($_POST['type'])) {
                                case "like":
                                    {
                                        if ($comment->doLike($user->id)) {
                                            echo_success();
                                        } else echo_error();
                                        break;
                                    }
                                case "dislike":
                                    {
                                        if ($comment->doDislike($user->id)) {
                                            echo_success();
                                        } else echo_error();
                                        break;
                                    }
                                default:
                                    {
                                        echo_error("Invalid type reaction");
                                    }
                            }

                        } else {
                            echo_error("Mising type reaction");
                        }

                        break;
                    }
                default:
                    {
                        echo_error("invalid action", ["action" => $_POST['action']]);
                    }
            }
        }


    default:
        {
            echo_error("invalid edge", ["edge" => $_POST['edge']]);
        }
}


function echo_error($message = null, $param = [])
{
    _return(false, $message, $param);
}

function echo_success($message = null, $param = [])
{
    _return(true, $message, $param);
}

function _return($value, $message = null, $param = [])
{
    if (!is_array($param)) $param = [];
    $param["success"] = $value;
    if ($message !== null) {
        $param["message"] = $message;
    }
    print_r(json_encode($param));
    exit();
}