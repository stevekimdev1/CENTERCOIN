<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/extend/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/extend/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/extend/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <p class="text-center"><img src="/img/swap_img.png" alt="logo" style="width:200px;" /></p>
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="/bbs/login_check.php" method="post">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="mb_id" placeholder="ID">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="mb_password" placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-8"></div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
            </div>
            </form>

            <div class="social-auth-links text-center mb-3">
                <p>- OR -</p>
                <a href="/bbs/register_form.php" class="btn btn-block btn-danger">
                    Register
                </a>
            </div>
        </div>
    </div>
</div>

<script src="/extend/plugins/jquery/jquery.min.js"></script>
<script src="/extend/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/extend/dist/js/adminlte.min.js"></script>

</body>
</html>
