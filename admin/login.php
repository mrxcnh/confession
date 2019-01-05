<!--Tài khoản đăng nhập: alphahoai@gmail.com | 12345678 hoặc admin@gmail.com | password-->
<?php
session_start();
if (isset($_SESSION['login']['success']) && $_SESSION['login']['success']) {
    header("location:/admin/index.php");
    return;
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    require_once __DIR__ . "/class/auth.php";
    $email = $_POST['email'];
    $password = $_POST['password'];
    $auth = new auth($email, $password);
    unset($_SESSION['login']);
    if ($auth->login()) {
        $_SESSION['login']['success'] = true;
        $_SESSION['login']['id'] = $auth->id;
        header("location: " . (isset($_GET['continue']) ? $_GET['continue'] : "/admin/index.php"));
        return ;
    } else {
        $_SESSION['login']['success'] = false;
        $_SESSION['login']['error_message'] = "Sai email hoặc mật khẩu";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Đăng nhập</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="public/css/login.css">
    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <?php
            if (isset($_SESSION['login']['success'])) {
                if ($_SESSION['login']['success'] === false) {
                    echo "<div style='color: #a94442;background-color: #f2dede;border-color: #ebccd1;padding: 15px;margin-bottom: 20px;   border: 1px solid transparent;'>" . $_SESSION['login']['error_message'] . "</div>";
                } else {
                    header("location:" . (isset($_GET['continue']) ? isset($_GET['continue']) : "/admin/index.php"));
                }
            }
            ?>
            <form action="#" method="post" class="login100-form validate-form">
					<span class="login100-form-title p-b-26">
						Đăng nhập
					</span>

                <div class="wrap-input100 validate-input" data-validate="Valid email is: a@b.c">
                    <input class="input100" type="text" name="email">
                    <span class="focus-input100" data-placeholder="Email"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
                    <input class="input100" type="password" name="password">
                    <span class="focus-input100" data-placeholder="Password"></span>
                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn">
                            Đăng nhập
                        </button>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 10px;">
                    <a href="/admin/glogin.php?reset">
                        <button aria-label="" class="GoogleConnectButton active" type="button"
                                style="border: 0px; height: 40px; display: block; border-radius: 25px; -webkit-font-smoothing: antialiased; padding: 1px; font-size: 15px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: left; margin-bottom: 9px; margin-right: 0px; background-clip: padding-box; transition: opacity 0.2s linear 0s; position: relative; width: 100%; background-color: rgb(66, 133, 244);"
                                data-gapiattached="true">
                            <div style="border-radius: 2px; background-color: rgb(255, 255, 255); display: inline-block; height: 25px; margin-left: 7px; text-align: center; width: 25px;">
                                <div style="position: relative; margin: 2px auto auto;">
                                    <svg height="20" viewBox="0 0 512 512" width="20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g fill="none" fill-rule="evenodd">
                                            <path d="M482.56 261.36c0-16.73-1.5-32.83-4.29-48.27H256v91.29h127.01c-5.47 29.5-22.1 54.49-47.09 71.23v59.21h76.27c44.63-41.09 70.37-101.59 70.37-173.46z"
                                                  fill="#4285f4"></path>
                                            <path d="M256 492c63.72 0 117.14-21.13 156.19-57.18l-76.27-59.21c-21.13 14.16-48.17 22.53-79.92 22.53-61.47 0-113.49-41.51-132.05-97.3H45.1v61.15c38.83 77.13 118.64 130.01 210.9 130.01z"
                                                  fill="#34a853"></path>
                                            <path d="M123.95 300.84c-4.72-14.16-7.4-29.29-7.4-44.84s2.68-30.68 7.4-44.84V150.01H45.1C29.12 181.87 20 217.92 20 256c0 38.08 9.12 74.13 25.1 105.99l78.85-61.15z"
                                                  fill="#fbbc05"></path>
                                            <path d="M256 113.86c34.65 0 65.76 11.91 90.22 35.29l67.69-67.69C373.03 43.39 319.61 20 256 20c-92.25 0-172.07 52.89-210.9 130.01l78.85 61.15c18.56-55.78 70.59-97.3 132.05-97.3z"
                                                  fill="#ea4335"></path>
                                            <path d="M20 20h472v472H20V20z"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <span style="color: rgb(255, 255, 255); display: inline-block; position: absolute; text-align: center; top: 50%; transform: translateY(-50%); vertical-align: top; -webkit-font-smoothing: auto; width: 83%;">Tiếp tục với Google</span>
                        </button>
                    </a>
                </div>

                <div class="text-center p-t-20" style="display: none">
						<span class="txt1">
							Don’t have an account?
						</span>

                    <a class="txt2" href="#">
                        Sign Up
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
<script src="public/js/core/jquery.min.js"></script>
<script src="public/js/core/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="public/js/login.js"></script>

</body>
</html>