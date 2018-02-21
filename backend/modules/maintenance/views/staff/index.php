<script src="<?php echo \yii\helpers\BaseUrl::base(true) ?>/js/module/maintenance.js"></script>
<main id="contents">
    <section class="readme">
        <h2 class="titleContent">作業者一覧</h2>
    </section>
    <article class="container">
        <?php if(Yii::$app->session->hasFlash('success')) {?>
        <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('success')?>
            <button data-dismiss="alert" class="close">×</button>
        </div>
        <?php }?>
        <form class="formSearchList" action="" method="get">
            <section class="bgContent">
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">支店<span class="must">*</span></label>
                        <?= \yii\helpers\Html::dropDownList('M08_HAN_CD', isset($filters['M08_HAN_CD']) ? $filters['M08_HAN_CD'] : '', $all_branch, array('class' => 'selectForm', 'id' => 'selectBranch')) ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">SS<span class="must">*</span></label>
                        <?= \yii\helpers\Html::dropDownList('M08_SS_CD', isset($filters['M08_SS_CD']) ? $filters['M08_SS_CD'] : '', $all_ss_search, array('class' => 'selectForm', 'id' => 'selectSS')) ?>
                    </div>
                    <div class="formItem flx-05">
                        <label class="titleLabel">　</label>
                        <a href="javascript:void(0)" class="btnSearch">検索</a>
                    </div>
                </div>
            </section>
        </form>
        <section class="nolineContent">
            <!-- <div class="noData">入力条件に該当する作業者が存在しません。</div> -->
            <nav class="paging">
                <?php
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                    'nextPageLabel' => '&gt;',
                    'prevPageLabel' => '&lt;',
                    'firstPageLabel' => '&laquo;',
                    'lastPageLabel' => '&raquo;',
                ]);
                ?>
            </nav>
            <table class="tableList">
                <tr>
                    <th style="width:25%">販売店名</th>
                    <th style="width:25%">SS名</th>
                    <th style="width:10%">表示順</th>
                    <th style="width:15%">従業員CD</th>
                    <th style="width:15%">作業者名</th>
                </tr>
            </table>
            <div id="wslist-box">
            <table class="tableList" style="margin-top:0px">
                <?php
                    foreach($staffs as $staff) {
                ?>
                <tr>
                    <td style="width:25%">
                        <?php echo isset($all_branch[$staff['M08_HAN_CD']]) ? $all_branch[$staff['M08_HAN_CD']] : '';?>
                    </td>
                    <td style="width:25%"><?php echo isset($all_ss[$staff['M08_SS_CD']]) ? $all_ss[$staff['M08_SS_CD']] : '';?></td>
                    <td style="width:10%"><?php echo $staff['M08_ORDER'];?></td>
                    <td style="width:15%"><?php echo $staff['M08_JYUG_CD'];?></td>
                    <td style="width:15%">
                        <a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/edit-staff?branch=<?php echo $staff['M08_HAN_CD'];?>&ss=<?php echo $staff['M08_SS_CD']?>&cd=<?php echo $staff['M08_JYUG_CD']?>">
                            <?php echo $staff['M08_NAME_SEI'].$staff['M08_NAME_MEI'];?>
                        </a>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </table>
            </div>
        </section>
    </article>
</main>
<footer id="footer">
    <div class="toolbar">
        <div class="toolbar-left">
            <a class="btnBack" href="<?php echo \yii\helpers\BaseUrl::base(true) ?>">メニューに戻る</a>
        </div>
        <div class="toolbar-right">
            <a class="btnSubmit" href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/regist-staff">新規登録</a>
        </div>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>