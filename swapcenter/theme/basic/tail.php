<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
    </div>
    <!-- copyright 시작 -->
    <footer class="main-footer">
        <strong>Copyright &copy; <?=date('Y')?> <a href="<?=$_SERVER['HTTP_HOST']?>"><?=$_SERVER['HTTP_HOST']?></a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
    </footer>

    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
                    <div class="modal fade" id="MemberModify">
                        <div class="modal-dialog modal-xl">                                                                                  
                            <div class="modal-content">                                                                                      
                                <div class="modal-header">                                                                                   
                                    <h4 class="modal-title">회원정보 수정하기</h4>                                                                            
                                    <button type="button" class="close" data-dismiss="modal" aria-label="닫기">                              
                                        <span aria-hidden="true">&times;</span>                                                              
                                    </button>                                                                                                
                                </div> 
                                <form method="post" action="/bbs/modify.php">
                                <input type="hidden" name="mb_id" value="<?=$member['mb_id']?>" />
                                <div class="modal-body">
                                    <div class="form-group">                                                                                 
                                        <label>비밀번호</label>
                                        <input type="password" class="form-control" name="mb_password" value="" placeholder="" maxlength="100" />
                                    </div>
                                    <div class="form-group">                                                                                 
                                        <label>나의 고객번호</label>
                                        <input type="text" class="form-control" name="mb_1" value="<?=$member['mb_1']?>" placeholder="" maxlength="100" />
                                    </div>
                                    <div class="form-group">                                                                                 
                                        <label>Connect Key</label>
                                        <input type="text" class="form-control" name="mb_2" value="<?=$member['mb_2']?>" placeholder="" maxlength="100" />
                                    </div>
                                    <div class="form-group">                                                                                 
                                        <label>Sceret Key</label>
                                        <input type="text" class="form-control" name="mb_3" value="<?=$member['mb_3']?>" placeholder="" maxlength="100" />
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                                    <button type="submit" class="btn btn-primary">수정</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- #MemberModify end -->

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
