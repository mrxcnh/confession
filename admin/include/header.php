<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
        </button>
        <a class="navbar-brand" href="#">QUẢN TRỊ HỆ THỐNG</a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Xin chào:&nbsp;<span
                        id="admin-title"><?= $admin->name ?></span>
                <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-gear"></i>Đổi mật khẩu</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>



    
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li style="background:#1b926c;color:#fff;">
                <a href="index.php" style="color:#fff;"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
                <a href="#" data-toggle="collapse" data-target="#demo_dm">
                    <i class="fa fa-fw fa-file"></i> Confession <i class="fa fa-fw fa-caret-down"></i>
                </a>
                <ul id="demo_dm">
                    <li>
                        <a href="list_post.php">Danh sách</a>
                    </li>
                </ul>
            </li>
            <!-- <li>
                 <a href="javascript:;" data-toggle="collapse" data-target="#demo_bv"><i
                             class="fa fa-fw fa-file"></i> Thể loại <i class="fa fa-fw fa-caret-down"></i></a>
                 <ul id="demo_bv" class="collapse">
                     <li>
                         <a href="#">Danh sách</a>
                     </li>
                     <li>
                         <a href="#">Tìm kiếm</a>
                     </li>
                     <li>
                         <a href="#">Thêm mới</a>
                     </li>


                 </ul>
             </li>-->

        </ul>
    </div>
    <!-- /.navbar-collapse -->

</nav>
