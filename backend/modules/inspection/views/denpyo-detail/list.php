<main id="contents">
    <section class="readme">
        <h2 class="titleContent">車検履歴</h2>
    </section>
    <article class="container">
        <?php if (Yii::$app->session->hasFlash('success')) { ?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('success') ?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if (Yii::$app->session->hasFlash('error')) { ?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('error') ?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <form class="formSearchList" method="get" action="<?php echo \yii\helpers\BaseUrl::base(true)?>/shaken/denpyo/list">
            <section class="bgContent">
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">車検日</label>
                        <input type="text" value="<?php echo isset($filters['start_time']) ? $filters['start_time'] : ''?>" class="textForm dateform" name="start_time">
                        <span class="txtUnit">〜</span>
                        <input type="text" value="<?php echo isset($filters['end_time'])? $filters['end_time'] : ''?>" class="textForm dateform" name="end_time">
                        <input type="submit" name="submit" value="検索" class="btnSearch" />
                    </div>
                </div>
            </section>
        </form>
        <section class="nolineContent">
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
            <?php if(count($list)) {?>
            <table class="tableList">
                <tbody><tr>
                        <th>顧客名</th>
                        <th>車番</th>
                        <th>日付</th>
                        <th>車検コード</th>
                        <th>SS名</th>
                        <th>見積書</th>
                    </tr>
                    <?php foreach($list as $row){?>
                    <tr>
                        <td><?php echo $row['D01_CUST_NAMEN']?></td>
                        <td><?php echo $row['D03_CAR_NO']?></td>
                        <td><?php echo date('Ymd',strtotime($row['D03_INP_DATE']))?></td>
                        <td><a href="<?php echo \yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/detail/'.$row['D03_DEN_NO'] ?>"><?php echo $row['D03_DEN_NO']?></a></td>
                        <td><?php
                                if (isset($all_ss[$row['D03_SS_CD']]))
                                {
                                    echo $all_ss[$row['D03_SS_CD']];
                                }
                                else
                                {
                                    echo '';
                                } ?>
                        </td>
                        <td class="alRight">
                            <?php if(in_array($row['DENPYO_SUGGEST_NO'],$arr_denpyo_no)) {?>
                            <a href="<?php echo \yii\helpers\BaseUrl::base(true).'/shaken/preview/osusume/'.$row['DENPYO_SUGGEST_NO'] ?>" target="_blank" class="btnFormTool">事前見積</a>
                            <a href="<?php echo \yii\helpers\BaseUrl::base(true).'/shaken/preview/seisan/'.$row['DENPYO_SUGGEST_NO'] ?>" target="_blank" class="btnFormTool">請求書</a>
                            <?php if ($row['sign']) { ?>
                                    <a class="btnFormTool btnOrange" target="_blank" href="<?php echo \yii\helpers\BaseUrl::base(true) . '/shaken/preview/'.$row['D03_DEN_NO'] ?>">表示</a>
                            <?php } else { ?>
                                    <a class="btnFormTool btnOrange disable-btn" href="#" data-alert="お客様サインが必要です">表示</a>
                            <?php } }?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else {?>
                <section class="nolineContent">
                    <div class="noData">入力条件に該当する作業伝票が存在しません</div>
                </section>
            <?php } ?>
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
        </section>
    </article>
</main>
<footer id="footer">
    <div class="toolbar">
        <div class="toolbar-left" style="width: 40%">
            <a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/shaken" class="btnTool btnGray">メニューへ戻る</a>
        </div>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>