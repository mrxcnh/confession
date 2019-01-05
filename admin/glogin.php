<?php
/**
 * Created by PhpStorm.
 * User: Long
 * Date: 10/5/2018
 * Time: 3:31 PM
 */
session_start();

require_once __DIR__ . "/../vendor/google/autoload.php";
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/class/auth.php";

//Step 1: Enter you google account credentials
$client = new Google_Client();
$client->setClientId(GOOGLE_OAUTH_CLIENT_ID);
$client->setClientSecret(GOOGLE_OAUTH_CLIENT_SECRET);
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/glogin.php';
$client->setRedirectUri($redirect_uri);
$client->setScopes("email");


if (isset($_GET['reset'])) {
    unset($_SESSION['google_access_token']);
}

if (isset($_SESSION['google_access_token']) && $_SESSION['google_access_token']) {
    $client->setAccessToken($_SESSION['google_access_token']);
    $oAuth = new Google_Service_Oauth2($client);
    $userData = $oAuth->userinfo->get();
    $admin = new auth($userData['email']);
    unset($_SESSION['login']);
    if ($admin->login()) {
        $_SESSION['login']['success'] = true;
        $_SESSION['login']['id'] = $admin->id;
        header("location: " . (isset($_GET['continue']) ? $_GET['continue'] : "/admin/index.php"));
    } else {
        $_SESSION['login']['success'] = false;
        $_SESSION['login']['error_message'] = "Email không tồn tại trong hệ thống";
        header("location: /admin/login.php");
    }
} else {
    if (!isset($_GET['code'])) {
        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    } else {
        $client->authenticate($_GET['code']);
        $_SESSION['google_access_token'] = $client->getAccessToken();
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
}

