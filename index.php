<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 9/28/2018
 * Time: 10:13 AM
 */
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/class/comment.php";
require_once __DIR__ . "/class/post.php";
require_once __DIR__ . "/class/user.php";

$CurrentPageType = "Confession";
$main_page_title = "Confession";

if(isset($_GET['id'])){
    $post =  new post($_GET['id']) ;
} else $post = new post();

if($post->getProperties() && $post->isApproved()){
    $comments = $post->getComments(null, true);
} else unset($post);


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Confession - Anigoo</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<link rel="stylesheet" type="text/css" href="/confession/public/style.css">
<div class="main">
    <div class="container">
        <div class="goo-white-card-has-padding">
            <div class="header">
                <ul class="nav nav-pills pull-right">
<!--                    <li><a onclick="javascript:(document.getElementById('feed')) ? window.location.href='/confession/' + (parseInt(confessionId) + 1) : alert('Không có confession tiếp đâu!')">Tiếp</a></li>-->
                    <li><a class="btn" id="copy_to_clipboard" data-clipboard-text="">Copy Link</a></li>
                    <li><a class="btn" onclick="window.location.href = $('#copy_to_clipboard').attr('data-clipboard-text')">Xem</a></li>
                    <li><a class="btn" href="/confession">Khác</a></li>
                    <li><a class="btn" href="/create.php">Thêm</a></li>
                    <li><a class="btn" onclick="javascript:alert('Tell me your secret!');">Thông tin</a></li>
                </ul>
                <h4>Hãy thú tội đi nào</h4>
            </div>
            <div style=" font-size: 12px;">Chúng tôi không lưu bất cứ thông tin gì của bạn ngoại trừ những gì bạn viết
                dưới đây.
            </div>
            <br>
            <br>
            <?php
            if (isset($post) && $post !== null) {
                ?>
                <div id="feed" class="_4-u2  _4mrt _5v3q" post_id="<?php echo $post->id ?>">
                    <div class=" _4pu6">
                        <div class="_1dwg _1w_m _q7o">
                            <div class="clearfix">Được tạo vào
                                ngày: <?php echo $post->date_created->format('d/m/Y'); ?>
                            </div>
                            <div class="_5pbx  _3ds9">
                                <div class="text-content <?php echo (strlen($post->content) < 100) ? 'big-text-content' : 'text-content'?>">
                                    <p><?php echo $post->getContent(); ?></p></div>
                            </div>
                        </div>
                        <div>
                            <div class="_sa_  _fgm _5vsi _192z _1sz4 _1i6z">
                                <div class="_37uu">
                                    <div class="_3399 _1f6t _4_dr _20h5">
                                        <div class="count-comments">
                                            <a class="total-comment-text">
                                                <span id="total-comment" first_set="true"><?php echo (isset($comments) && isset($comments['total']) && is_numeric($comments['total'])) ? $comments['total'] : 0; ?></span>
                                                Bình luận</a>
                                        </div>
                                        <div class="_4ar- _ipn count-reactions">
                                                <span class="count-like-reaction count-reaction">
                                                    <a class="_3emk _401_"><i class="count-reaction-icon "></i></a>
                                                    <span id="total-like"><?= $post->like ?></span>
                                                </span>
                                            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            <span class="count-dislike-reaction count-reaction ">
                                                    <a class="_3emk _401_"><i class="count-reaction-icon "
                                                                              style="transform: rotate(180deg);"></i></a>
                                                    <span id="total-dislike"><?= $post->dislike ?></span>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="_3399 _a7s _20h6 _610i _610j _125r _2h27 clearfix _zw3">
                                        <div class="_524d">
                                            <div class="_42nr _1mtp">
                                                <a class="reaction-button  like-button" onclick="PostManager.doReaction(Reaction.getInstance().Like)">
                                                    <span>Like</span>
                                                </a>
                                                <a class="reaction-button  dislike-button" onclick="PostManager.doReaction(Reaction.getInstance().Dislike)">
                                                    <span>Dislike</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="all-comment" class="_3-a6  _65_9"></div>
                            <div id="load-more-comments" onclick="CommentManager.loadMoreComment()" onmouseenter="CommentManager.loadMoreComment(false, true)" style="padding: 12px; text-align: center;"><a>Xem thêm</a></div>

                            <div class="write-comment">
                                <div class="write-comment-block">
                                    <div class="write-comment-input">
                                        <div id="comment-content" onclick="CommentManager.emptyInputComment()" aria-expanded="false"
                                             style="outline: none; user-select: text; white-space: pre-wrap; word-wrap: break-word;"
                                             contenteditable="true" data-contents="true" data-text="true">Viết bình luận ...
                                        </div>
                                    </div>
                                    <div class="send-comment2">
                                        <a id="send-comment" title="Shift + Enter"
                                           href="javascript: CommentManager.doComment(CommentManager.getCommentfromDom());">Gửi</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script type="text/javascript" language="javascript" src="/confession/public/library.js"></script>
                <script type="text/javascript" language="javascript" src="/confession/public/mainv2.js"></script>
                <script>
                    $(document).ready(function () {
                        CommentManager.loadMoreComment();
                    });
                </script>
                <?php
            } else echo "<h2 style='text-align: center;'>Đã có lỗi xảy ra</h2>";
            ?>
        </div>
    </div> <!-- /container -->
</div>
</body>
</html>
