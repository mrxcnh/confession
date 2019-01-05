<!DOCTYPE html>
<html lang="en">

<head>
    <title>Danh sách confession</title>
    <?php
    include __DIR__ . "/include/head.php";
    ?>
</head>

<body>

<div id="wrapper">
    <?php
    include __DIR__ . "/include/header.php";
    ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Truyện
                    </h1>
                    <ol class="breadcrumb">
                        <li class="active">
                            <i class="fa fa-list"></i> Danh sách
                        </li>
                    </ol>
                </div>
            </div>
            <style>
                #order_by_group > div {
                    float: left;
                    margin-left: 10px;
                    margin-right: 10px;
                }
            </style>
            <div class="row">
                <div class="col-lg-12">
                    <div id="order_by_group" style="margin-bottom: 20px">
                        <div style="padding: 6px 12px;"><label>Tìm kiếm với:</label></div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="search_content" placeholder="Nhập nội dung">
                        </div>
                        <div style="padding: 6px 12px;"><label>Sắp xếp theo:</label></div>
                        <div>
                            <select id="order_field" class="selectpicker show-tick" data-width="auto">
                                <option value="id">Id</option>
                                <option value="date_created">Ngày tạo</option>
                                <option value="approval">Trạng thái phê duyệt</option>
                                <option value="cmt">Số lượng comment</option>
                                <option value="like">Số lượng like</option>
                                <option value="dislike">Số lượng dislike</option>
                                <option value="view">Số lượng view</option>
                            </select>
                        </div>
                        <div>
                            <select id="order_by" class="selectpicker show-tick" data-width="auto">
                                <option value="asc">Tăng dần</option>
                                <option value="desc">Giảm dần</option>
                            </select>
                        </div>
                        <div>
                            <button class="btn btn-primary" id="filter_button" onclick="getPosts(0)">Lọc</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="search-result">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nội dung</th>
                                <th>Trạng thái phê duyệt</th>
                                <th>Comment</th>
                                <th>Like</th>
                                <th>Disklike</th>
                                <th>View</th>
                                <th>Ngày tạo</th>

                                <th>Phê duyệt</th>
                                <th>Không phê duyệt</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        getPosts(0);
                        $(window).scroll(function () {
                            if ($(window).scrollTop() + $(window).height() > $(document).height() - 150) {
                                if (moreResult && !pending)
                                    getPosts(start);
                            }
                        });
                        $('#search_content').keydown(function (e){
                            if(e.keyCode === 13){
                               $("#filter_button").click();
                            }
                        })
                    });
                </script>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>
    <?php
    include __DIR__ . "/include/footer.php";
    ?>
</div>

</body>

</html>



