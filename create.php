<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 12/02/2018
 * Time: 01:48 PM
 */
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db.php";

require_once __DIR__ . "/class/comment.php";
require_once __DIR__ . "/class/post.php";
require_once __DIR__ . "/class/user.php";

$CurrentPageType = "Create Confession";
$main_page_title = "Create Confession";

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
                    <li><a class="btn" href="/confession">Khác</a></li>
                    <li><a class="btn" onclick="javascript:alert('Tell me your secret!');">Thông tin</a></li>
                </ul>
                <h4>Hãy thú tội đi nào</h4>
            </div>
            <div style=" font-size: 12px;">Chúng tôi không lưu bất cứ thông tin gì của bạn ngoại trừ những gì bạn viết
                dưới đây.
            </div>
            <br>
            <br>
            <div id="feed" class="_4-u2  _4mrt _5v3q">
                <form action="#" method="POST" id="create_confession" name="create_confession">
                    <ul class="form-style-1">
                        <li>
                            <label>Your Confession <span class="required">*</span></label>
                            <textarea name="content" id="content" class="field-long field-textarea"></textarea>
                        </li>
                        <li>
                            <input type="submit" value="Gửi"/>
                        </li>
                    </ul>
                </form>
                <style type="text/css">
                    .form-style-1 {
                        margin: 10px auto;
                        padding: 20px 12px 10px 20px;
                    }

                    .form-style-1 li {
                        padding: 0;
                        display: block;
                        list-style: none;
                        margin: 10px 0 0 0;
                    }

                    .form-style-1 label {
                        margin: 0 0 3px 0;
                        padding: 0px;
                        display: block;
                        font-weight: bold;
                    }

                    .form-style-1 input[type=text],
                    .form-style-1 input[type=date],
                    .form-style-1 input[type=datetime],
                    .form-style-1 input[type=number],
                    .form-style-1 input[type=search],
                    .form-style-1 input[type=time],
                    .form-style-1 input[type=url],
                    .form-style-1 input[type=email],
                    textarea,
                    select {
                        box-sizing: border-box;
                        -webkit-box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        border: 1px solid #BEBEBE;
                        padding: 7px;
                        margin: 0px;
                        -webkit-transition: all 0.30s ease-in-out;
                        -moz-transition: all 0.30s ease-in-out;
                        -ms-transition: all 0.30s ease-in-out;
                        -o-transition: all 0.30s ease-in-out;
                        outline: none;
                    }

                    .form-style-1 input[type=text]:focus,
                    .form-style-1 input[type=date]:focus,
                    .form-style-1 input[type=datetime]:focus,
                    .form-style-1 input[type=number]:focus,
                    .form-style-1 input[type=search]:focus,
                    .form-style-1 input[type=time]:focus,
                    .form-style-1 input[type=url]:focus,
                    .form-style-1 input[type=email]:focus,
                    .form-style-1 textarea:focus,
                    .form-style-1 select:focus {
                        -moz-box-shadow: 0 0 8px #88D5E9;
                        -webkit-box-shadow: 0 0 8px #88D5E9;
                        box-shadow: 0 0 8px #88D5E9;
                        border: 1px solid #88D5E9;
                    }

                    .form-style-1 .field-long {
                        width: 100%;
                    }

                    .form-style-1 .field-textarea {
                        height: 100px;
                    }

                    .form-style-1 input[type=submit], .form-style-1 input[type=button] {
                        background: #0084ff;
                        padding: 8px 15px 8px 15px;
                        border: none;
                        color: #dce2ff;
                    }

                    .form-style-1 input[type=submit]:hover, .form-style-1 input[type=button]:hover {
                        background: #1b90ff;
                        color: #fff;
                        box-shadow: none;
                        -moz-box-shadow: none;
                        -webkit-box-shadow: none;
                    }

                    .form-style-1 .required {
                        color: red;
                    }
                </style>
            </div>

        </div>
    </div> <!-- /container -->
</div>
</body>
<script type="text/javascript" language="javascript" src="/confession/public/library.js"></script>
<script type="text/javascript" language="javascript" src="/confession/public/mainv2.js"></script>
<script>
    $(document).ready(function () {
        //Add new confession
        $('#create_confession').on('submit', function (e) {
            e.preventDefault();
            PostManager.create();
        });
    });
</script>

</html>
