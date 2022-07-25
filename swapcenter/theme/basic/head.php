<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (empty($member['mb_id'])) { goto_url('/bbs/login.php'); }

if (!empty($member['mb_2']) && !empty($member['mb_2']) && $member['mb_level'] == 2) {
    api_login_check($member); // api connect
}

include_once(G5_THEME_PATH.'/head.sub.php');
?>
    <!-- 상단 네비 시작 -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/bbs/logout.php" alt="로그아웃" title="로그아웃">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- 상단 네비 종료 -->

    <!-- 좌측 레이어 메뉴 시작 -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="/"  data-toggle="modal" data-target="#MemberModify" class="d-block"><?=$member['mb_id']?></a>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="/" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Home</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/bbs/board.php?bo_table=send" class="nav-link">
                            <i class="nav-icon fas fa-people-arrows"></i>
                            <p>신청내역</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <!-- 좌측 레이어 메뉴 종료 -->

    <!-- 본문 시작 -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!-- <h1 class="m-0 text-dark"><?=!empty($g5['sub_title']) ? $g5['sub_title'] : $g5['title']?></h1> -->
                        <h1 class="m-0 text-dark"><img src="/img/swap_img.png" alt="logo" style="width:120px;" /></h1>
                    </div>
                    <?php /*
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?=G5_ERP_URL?>">Home</a></li>
                            <li class="breadcrumb-item <?=empty($g5['sub_title']) ? 'active' : ''?>"><?=$g5['title']?></li>
                            <?php if (!empty($g5['sub_title'])) { ?> <li class="breadcrumb-item active"><?=$g5['sub_title']?></li> <?php } ?>
                        </ol>
                    </div>
                    */?>
                </div>
            </div>
        </div>
