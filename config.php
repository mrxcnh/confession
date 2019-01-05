<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 9/30/2018
 * Time: 1:42 PM
 */

date_default_timezone_set('Asia/Ho_Chi_Minh');

define("LIMIT_COMMENT_PER_CONFESSION", 10);
define("TB_ADMIN", "confession.admin");
define("TB_COMMENT", "confession.comment");
define("TB_POST", "confession.post");
define("TB_USER", "confession.user");
define("TB_USER_POST_REACTION", "confession.user_post_reaction");
define("TB_USER_CMT_REACTION", "confession.user_cmt_reaction");

/////////////////// Cấu hình Database  ///////////////////
$CONFIG_DATABASE['HOST'] = 'localhost';
$CONFIG_DATABASE['SCHEMA'] = 'confession';
$CONFIG_DATABASE['USERNAME'] = 'root';
$CONFIG_DATABASE['PASSWORD'] = "mysql";

define("ADMIN_SALT_PASSWORD", "XinchaoVietNam2018");

$CONFIG_ADMIN['LIMIT_POST'] = 10;

define("GOOGLE_OAUTH_CLIENT_ID","43640032866-90307kjp875jlabbflhri62fhgq8dc4o.apps.googleusercontent.com");
define("GOOGLE_OAUTH_CLIENT_SECRET","v4UnfvodW6qePpY52UZL-TAV");
define("GOOGLE_API_KEY","AIzaSyAsQOAuWNPyOqm04j8QT7ViBjnPi4RuTRE");