<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 9/30/2018
 * Time: 12:44 PM
 */

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db.php";

//Danger - this function will reset database  - not delete confession in post table
// not touch admin table
function resetDatabase($db)
{
//    $db->execute("SET SQL_SAFE_UPDATES = 0;");
    $db->execute("UPDATE " . $GLOBALS['tb_post'] . " SET like = 100000, dislike = 100000, cmt = 100000");
    $db->execute("DELETE FROM " . $GLOBALS['tb_user_post_reaction']);
    $db->execute("DELETE FROM " . $GLOBALS['tb_user_cmt_reaction']);
    $db->execute("DELETE FROM " . $GLOBALS['tb_comment']);
    $db->execute("DELETE FROM " . $GLOBALS['tb_user']);
    $db->execute("UPDATE " . $GLOBALS['tb_post'] . " SET `like` = '0', `dislike` = '0', `cmt` = '0', `view` = '0', `approval` = 'not yet', `approval_time` = null, `approval_by` = null");
}