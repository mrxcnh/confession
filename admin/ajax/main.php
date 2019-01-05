<?php
/**
 * Created by Long
 * Date: 11/29/2018
 * Time: 2:41 PM
 */
session_start();

if (!isset($_SESSION['login']['success']) || !($_SESSION['login']['success'])) return;

require_once __DIR__ . "/../class/admin.php";
$id = $_SESSION['login']['id'];

$admin = new admin($id);

header('Content-Type: application/json');

$start = (isset($_GET['start']) && is_numeric($_GET['start']) && intval($_GET['start']) >= 0) ? intval($_GET['start']) : 0;
$limit = (isset($_GET['limit']) && is_numeric($_GET['limit']) && intval($_GET['limit']) >= 0) ? intval($_GET['limit']) : 10;
$post_id = (isset($_GET['post_id']) && is_numeric($_GET['post_id']) && intval($_GET['post_id']) >= 0) ? intval($_GET['post_id']) : null;
$type = (isset($_GET['type']) && $_GET['type'] !== "") ? strtolower($_GET['type']) : null;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if($post_id == null) return ;
    if(!isset($_POST['action'])) return ;

    switch (strtolower($_POST['action'])){
        case "approval":
            {
                echo json_encode($admin->approvalPost($post_id));
                return;
            }
        case "unapproval":
            {
                echo json_encode($admin->unapprovalPost($post_id));
                return;
            }
    }

    return;
} else {
    $order_field = (isset($_GET['order_field']) && $_GET['order_field'] !== "") ? strtolower($_GET['order_field']) : "date_created";
    if($order_field !== "id" && $order_field !== "date_created" && $order_field !== "approval" && $order_field !== "cmt" && $order_field !== "like"
        && $order_field !== "dislike" && $order_field !== "view") $order_field = "date_created";
    if($order_field === "like") $order_field = "`like`";
    $order_by = (isset($_GET['order_by']) && ($_GET['order_by'] === "asc" || $_GET['order_by'] === "desc")) ? $_GET['order_by'] : "asc";

    $search_content = (isset($_GET['content']) && $_GET['content'] !== "") ? $_GET['content'] : null;

    switch ($type) {
        case "all post":
            {
                echo json_encode($admin->getAllPost($search_content, $start, $limit, $order_field, $order_by));
                return;
            }
        case "not verified":
            {
                echo json_encode($admin->getRecentlyPostNotYetApproval($search_content, $start, $limit, $order_field, $order_by));
                return;
            }
    }
}

