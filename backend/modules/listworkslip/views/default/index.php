<script src="<?php echo yii\helpers\BaseUrl::base(true) . '/js/module/listworkslip.js?042201' ?>"></script>
<main id="contents">
    <article class="container">
        <?php if (Yii::$app->session->hasFlash('success')) { ?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('success') ?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <form action="" method="get" class="formSearchList" id="listworkslip">
            <section class="bgContent mt10">
                <div class="formGroup">
                    <div class="formItem flx-2">
                        <label class="titleLabel">施行日（予約日）<span class="must">*</span></label>
                        <?= yii\helpers\Html::input('text', 'start_time', isset($filters['start_time']) ? $filters['start_time'] : '', ['class' => 'textForm dateform', 'maxlength' => '8', 'id' => 'start_time']) ?>
                        <span class="txtUnit">〜</span>
                        <?= yii\helpers\Html::input('text', 'end_time', isset($filters['end_time']) ? $filters['end_time'] : '', ['class' => 'textForm dateform', 'maxlength' => '8', 'id' => 'end_time']) ?>
					</div>
                    <div class="formItem">
                        <label class="titleLabel">状態</label>
                        <?= yii\helpers\Html::dropDownList('status', isset($filters['status']) ? $filters['status'] : '', $status, array('class' => 'selectForm', 'id' => 'status')) ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">作業内容</label>
                        <?= yii\helpers\Html::dropDownList('job', isset($filters['job']) ? $filters['job'] : '', $job, array('class' => 'selectForm', 'id' => 'selectJob')) ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">車番</label>
                        <?= yii\helpers\Html::input('text', 'car', isset($filters['car']) ? $filters['car'] : '', ['class' => 'textForm formWidthXS', 'maxlength' => '4', 'id' => 'car']) ?>
                    </div>
                </div>
                <div class="formGroup">
					<div class="formItem flx-2">
						<label class="titleLabel">カード番号</label>
						<?= yii\helpers\Html::input('text', 'D01_KAKE_CARD_NO', isset($filters['D01_KAKE_CARD_NO']) ? $filters['D01_KAKE_CARD_NO'] : '', ['class' => 'textForm', 'id' => 'D01_KAKE_CARD_NO', 'maxlength' => '16']) ?>
					</div>
                    <div class="formItem flx-2">
                        <label class="titleLabel">POS伝票番号</label>
                        <?= yii\helpers\Html::input('text', 'D03_POS_DEN_NO', isset($filters['D03_POS_DEN_NO']) ? $filters['D03_POS_DEN_NO'] : '', ['class' => 'textForm', 'id' => 'D03_POS_DEN_NO']) ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">　</label>
                        <a href="#" class="btnSearch">検索</a>
                    </div>
                </div>
            </section>

        </form>
        <section class="nolineContent">
            <?php if (Yii::$app->session->hasFlash('empty')) { ?>
                <div class="noData"><?php echo Yii::$app->session->getFlash('empty'); ?></div>
            <?php } else { ?>
                <nav class="paging">
                    <?php
                    echo yii\widgets\LinkPager::widget([
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
                        <th style="width:15%" class="text-center">顧客名</th>
                        <th style="width:8%">車番</th>
                        <th style="width:10%" class="text-center">日付</th>
                        <th style="width:12%" class="text-center">状態</th>
                        <th style="width:15%" class="text-center">作業内容</th>
                        <th style="width:10%" class="text-center">作業コード</th>
                        <th style="width:20%;" class="text-center">SS名</th>
                        <th style="width:10%;" class="text-center">確認書</th>
                    </tr>
                </table>
                <div id="wslist-box">
                <table class="tableList" style="margin-top:0px">
                    <?php
                    if (isset($page)) {
                        $i = 1 + 20 * ($page - 1);
                    } else {
                        $i = 1;
                    }
                    foreach ($list as $k => $v) { ?>
                        <tr>
                            <td style="width:15%"><?php echo $v['D01_CUST_NAMEN']; ?></td>
                            <td style="width:8%"><?php echo $v['D03_CAR_NO']; ?></td>
                            <td style="width:10%"><?php echo $v['D03_SEKOU_YMD']; ?></td>
                            <td style="width:12%">
                                <?php
                                if ($v['D03_STATUS'] == 1) {
                                    echo $status[2];
                                }
                                if ($v['D03_STATUS'] != '' && $v['D03_STATUS'] == 0 && $v['D03_SEKOU_YMD'] <= date('Ymd')) {
                                    echo $status[0];
                                }
                                if ($v['D03_STATUS'] != '' && $v['D03_STATUS'] == 0 && $v['D03_SEKOU_YMD'] > date('Ymd')) {
                                    echo $status[1];
                                } ?>
                            </td>
                            <td style="width:15%">
                                <?php
                                $job_no = \backend\modules\listworkslip\controllers\DefaultController::getJob($v['D03_DEN_NO']);
                                $job_name = '';
                                foreach ($job_no as $key => $val) {
                                    $job_name .= $job[$val] . '、';
                                }
                                $job_name = preg_replace('/、$/', '', $job_name);
                                echo $job_name;
                                ?>
                            </td>
                            <td style="width:10%">
                                <a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/detail-workslip?den_no=<?php echo $v['D03_DEN_NO']; ?>"><?php echo $v['D03_DEN_NO']; ?></a>
                            </td>
                            <td style="width:20%"><?php
                                if (isset($all_ss[$v['D03_SS_CD']])) {
                                    echo $all_ss[$v['D03_SS_CD']];
                                } else {
                                    echo '';
                                } ?></td>
                            <td style="width:10%" class="text-right">
                                <?php if ($v['sign']) { ?>
                                    <a class="btnFormTool btnOrange" target="_blank" href="<?php echo \yii\helpers\BaseUrl::base() ?>/preview?den_no=<?php echo $v['D03_DEN_NO']; ?>">表示</a>
                                <?php } else { ?>
                                    <a class="btnFormTool btnOrange disable-btn" href="#" data-alert="お客様サインが必要です">表示</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                </div>
            <?php } ?>
        </section>
    </article>
</main>
<footer id="footer">
    <div class="toolbar"><a href="<?php echo \yii\helpers\BaseUrl::base(true) . '/menu'; ?>" class="btnBack">メニューに戻る</a>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>