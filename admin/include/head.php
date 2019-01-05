<?php
session_start();

require_once __DIR__ . "/../lib/function.php";

if (isset($_SESSION['login']['success']) && $_SESSION['login']['success']) {
    require_once __DIR__ . "/../class/admin.php";
    $id = $_SESSION['login']['id'];
    $admin = new admin($id);
    $totalNotYetApproval = $admin->getTotalPostNotYetApproval();
} else {
    unset($_SESSION['login']);
    header("location:/admin/login.php?continue=" . urlencode(curentUrl()));
}
?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<!-- jQuery -->
<script src="./public/js/jquery.js"></script>

<link rel="stylesheet" href="./public/css/bootstrap.min.css" rel="stylesheet">
<script src="./public/js/bootstrap.min.js"></script>

<!-- Custom CSS -->
<link href="./public/css/sb-admin.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="./public/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<!-- jQuery ScrollTo -->
<script src="./public/js/jquery.scrollTo.min.js"></script>

<!-- time ago https://github.com/hustcc/timeago.js -->
<script src="./public/js/timeago/timeago.min.js"></script>
<script src="./public/js/timeago/timeago.locales.min.js"></script>

<!-- bootstrap-select https://developer.snapappointments.com/bootstrap-select/examples/ -->
<script src="./public/js/bootstrap-select.js"></script>
<link href="./public/css/bootstrap-select.min.css" rel="stylesheet">

<script src="./public/js/book.js"></script>



