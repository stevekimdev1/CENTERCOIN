<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Log in</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/extend/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/extend/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/extend/dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">

<div class="register-box">
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Register</p>

            <form action="/bbs/register_form_update.php" method="post">
            <input type="hidden" name="w" value="" />
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="mb_id" placeholder="id">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="mb_password" placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-key"></span>
                    </div>
                </div>
            </div>

            <p class="login-box-msg">선택사항</p>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="mb_1" placeholder="나의 고객번호">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-users-cog"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="mb_2" placeholder="Connect Key">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="mb_3" placeholder="Secret Key">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <a href="/bbs/login.php" class="btn btn-default">Back</a>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="/extend/plugins/jquery/jquery.min.js"></script>
<script src="/extend/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/extend/dist/js/adminlte.min.js"></script>

</body>
</html>
