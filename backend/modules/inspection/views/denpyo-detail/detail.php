<script src="<?php echo yii\helpers\BaseUrl::base(true) . '/js/module/listworkslip.js?072001' ?>"></script>
<main id="contents" xmlns="http://www.w3.org/1999/html">
    <section class="readme">
        <h2 class="titleContent">作業伝票詳細</h2>
    </section>

    <article class="container">
        <?php
        if (Yii::$app->session->hasFlash('check_remove_car')) {
            ?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('check_remove_car') ?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php
        }
        if (Yii::$app->session->hasFlash('error')) {
            ?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('error') ?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php
        }
        if (Yii::$app->session->hasFlash('success')) {
            $link = '';
            if ($type && $type != Yii::$app->params['TYPE_GUEST']) {
                $link = '<a href="' . \yii\helpers\Url::base(true) . '/list-workslip?D01_KAKE_CARD_NO=' . $card . '"> >このお客様の作業履歴を見る</a>';
            }
            ?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('success') . $link ?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php
        }
        if (Yii::$app->session->hasFlash('check_remove')) {
            $check_remove = 'disable-btn';
            ?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('check_remove') ?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php
        }
        ?>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">作業コード</label>
                        <p class="txtValue"><?php echo $detail['D03_DEN_NO']; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">状況</label>
                        <p class="txtValue">
                            <?php
                            if ($detail['D03_STATUS'] == 1) {
                                echo '作業確定';
                            }
                            if ($detail['D03_STATUS'] == 0) {
                                echo '作業予約';
                            } ?>
                        </p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">受付日</label>
                        <p class="txtValue"><?php echo isset($detail['CHAR_D03_UPD_DATE']) ? Yii::$app->formatter->asDate($detail['CHAR_D03_UPD_DATE'], 'yyyy/MM/dd') : ''; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">受付担当者</label>
                        <p class="txtValue"><?php echo isset($detail['D01_UKE_TAN_NAMEN']) ? $detail['D01_UKE_TAN_NAMEN'] : ''; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">作業日</label>
                        <p class="txtValue"><?php echo $detail['D03_SEKOU_YMD'] != '' ? Yii::$app->formatter->asDate(date('d-M-y', strtotime($detail['D03_SEKOU_YMD'])), 'yyyy/MM/dd') : '' ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">お預かり時間</label>
                        <p class="txtValue">
                            <?php echo isset($detail['D03_AZU_BEGIN_HH']) ? str_pad($detail['D03_AZU_BEGIN_HH'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?>
                            :<?php echo isset($detail['D03_AZU_BEGIN_MI']) ? str_pad($detail['D03_AZU_BEGIN_MI'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?>
                            ～<?php echo isset($detail['D03_AZU_END_HH']) ? str_pad($detail['D03_AZU_END_HH'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?>
                            :<?php echo isset($detail['D03_AZU_END_MI']) ? str_pad($detail['D03_AZU_END_MI'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?>
                        </p>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <div class="flexHead">
                    <legend class="titleLegend">お客様情報</legend>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">お名前</label>
                        <p class="txtValue"><?php echo $detail['D01_CUST_NAMEN']; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">フリガナ</label>
                        <p class="txtValue"><?php echo $detail['D01_CUST_NAMEK']; ?></p>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">備考</label>
                        <p><?php echo \yii\bootstrap\Html::encode(nl2br($detail['D01_NOTE'])); ?></p>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <div class="flexHead">
                    <legend class="titleLegend">車両情報</legend>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">車名</label>
                        <p class="txtValue"><?php echo $detail['D03_CAR_NAMEN']; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">車検満了日</label>
                        <p class="txtValue"><?php echo $detail['D03_JIKAI_SHAKEN_YM'] != '' ? Yii::$app->formatter->asDate(date('d-M-y', strtotime($detail['D03_JIKAI_SHAKEN_YM'])), 'yyyy年MM月dd日') : '' ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">車検サイクル</label>
                        <p class="txtValue"><?php echo $detail['D02_SYAKEN_CYCLE'] ? $detail['D02_SYAKEN_CYCLE'] . '年' : ''; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">走行距離</label>
                        <p class="txtValue"><?php echo $detail['D03_METER_KM']; ?>km</p>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">運輸支局</label>
                        <p class="txtValue"><?php echo $detail['D03_RIKUUN_NAMEN']; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">分類コード</label>
                        <p class="txtValue"><?php echo $detail['D03_CAR_ID']; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">ひらがな</label>
                        <p class="txtValue"><?php echo $detail['D03_HIRA']; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">登録番号</label>
                        <p class="txtValue"><?php echo $detail['D03_CAR_NO']; ?></p>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">
                    車検料金
                </legend>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">車両サイズ</label>

                        <p class="txtValue"><?php echo Yii::$app->params['car_size'][(int)$denpyo_inspection['CAR_SIZE']]; ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">重量</label>
                        <p class="txtValue"><?php
                            if ($denpyo_inspection['CAR_SIZE']) {
                                echo Yii::$app->params['car_weight_' . (int)$denpyo_inspection['CAR_SIZE']][$denpyo_inspection['CAR_WEIGHT']];
                            } else
                                echo '';
                            ?>
                        </p>
                    </div>
                </div>

                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">基本点検料金</label>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">分類名</label>
                        <p class="txtValue"><?php echo isset($detail['fee_basic']['NAME']) ? \yii\helpers\Html::encode($detail['fee_basic']['NAME']) : '' ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">基本点検料金</label>
                        <p class="txtValue"><?php echo isset($detail['fee_basic']['VALUE']) ? number_format($detail['fee_basic']['VALUE']) : '0' ?>
                            円</p>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">法定検料金</label>
                    </div>

                </div>

                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">分類名</label>
                        <p class="txtValue"><?php echo isset($detail['fee_registion']['NAME']) ? \yii\helpers\Html::encode($detail['fee_registion']['NAME']) : '' ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">重量税</label>
                        <p class="txtValue"><?php echo number_format($denpyo_inspection['WEIGHT_TAX']) ?>円</p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">自賠責</label>
                        <p class="txtValue"><?php echo isset($detail['fee_registion']['MANDATORY_INSURANCE']) ? number_format($detail['fee_registion']['MANDATORY_INSURANCE']) : '0' ?>
                            円</p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">印紙代</label>
                        <p class="txtValue"><?php echo isset($detail['fee_registion']['STAMP_FEE']) ? number_format($detail['fee_registion']['STAMP_FEE']) : '0' ?>
                            円</p>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel"> 車検費用合計</label>
                        <p class="txtValue"><strong>
                                <?php
                                $total1 = $denpyo_inspection['WEIGHT_TAX'];
                                if (isset($detail['fee_registion']['MANDATORY_INSURANCE']))
                                    $total1 += $detail['fee_registion']['MANDATORY_INSURANCE'];
                                if (isset($detail['fee_registion']['STAMP_FEE']))
                                    $total1 += $detail['fee_registion']['STAMP_FEE'];
                                if (isset($detail['fee_basic']['VALUE']))
                                    $total1 += $detail['fee_basic']['VALUE'];
                                echo number_format((int)$total1);
                                ?>
                                円</strong>
                        </p>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">割引・割増料金</label>
                    </div>
                </div>
                <div class="formGroup lineBottom">
                    <div class="formItem">
                        <label class="titleLabel">割引分類名</label>
                        <p class="txtValue"><?php echo isset($detail['parent_discount']['NAME']) ? \yii\helpers\Html::encode($detail['parent_discount']['NAME']) : ''; ?></p>
                    </div>
                    <?php
                    $total_discount = 0;
                    foreach ($detail['discount_packages'] as $row) {
                        if (isset($detail['discount'][$row['ID']]['VALUE']) && $detail['discount'][$row['ID']]['VALUE']) {
                            ?>
                            <div class="formItem">
                                <label class="titleLabel"><?php echo \yii\helpers\Html::encode($row['NAME']) ?></label>
                                <p class="txtValue">
                                    <?php
                                    echo isset($detail['discount'][$row['ID']]['VALUE']) ? number_format($detail['discount'][$row['ID']]['VALUE']) . '円' : '';
                                    if (isset($detail['discount'][$row['ID']]['VALUE'])) $total_discount += $detail['discount'][$row['ID']]['VALUE'];
                                    ?>
                                </p>
                            </div>
                        <?php }
                    } ?>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">割引・割り増し適用後</label>
                        <p style="color:red" class="txtValue">
                            <?php
                            $total = $total1 + $total_discount;
                            echo number_format((int)$total);
                            ?>円
                        </p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">預かり金</label>
                        <p class="txtValue"><?php echo number_format($denpyo_inspection['EARNEST_MONEY']) ?>円</p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">差引金額</label>
                        <p style="color:red"
                           class="txtValue"> <?php $total_end = $denpyo_inspection['EARNEST_MONEY'] - $total;
                            echo number_format($total_end); ?>円</p>
                    </div>
                    <div class="formItem"></div>
                    <div class="formItem"></div>
                </div>


            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">貴重品・精算情報</legend>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">貴重品</label>
                        <?php if (isset($detail['D03_KITYOHIN']) && $detail['D03_KITYOHIN'] == 0) {
                            echo '<p class="txtValue">無し</p>';
                        } else {
                            echo '<p class="txtValue">有り</p>';
                        } ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">お客様確認</label>
                        <?php if (isset($detail['D03_KAKUNIN']) && $detail['D03_KAKUNIN'] == 0) {
                            echo '<p class="txtValue">未了承</p>';
                        } else {
                            echo '<p class="txtValue">了承済</p>';
                        } ?>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">精算方法</label>
                        <?php if (isset($detail['D03_SEISAN']) && $detail['D03_SEISAN'] == 0) {
                            echo '<p class="txtValue">現金</p>';
                        }
                        if (isset($detail['D03_SEISAN']) && $detail['D03_SEISAN'] == 1) {
                            echo '<p class="txtValue">プリカ</p>';
                        }
                        if (isset($detail['D03_SEISAN']) && $detail['D03_SEISAN'] == 2) {
                            echo '<p class="txtValue">クレジット</p>';
                        }
                        if (isset($detail['D03_SEISAN']) && $detail['D03_SEISAN'] == 3) {
                            echo '<p class="txtValue">掛</p>';
                        } ?>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <div class="formGroup">
                    <div class="formItem flx-2">
                        <label class="titleLabel">作業内容</label>
                        <p class="txtValue"><?php
                            $sagyo = '';
                            foreach ($detail['sagyo'] as $k => $v) {
                                $sagyo .= $job[$v['D04_SAGYO_NO']] . '、';
                            }
                            echo preg_replace('/、$/', '', $sagyo);
                            ?></p>
                    </div>
                    <div class="formItem flx-05">
                        <label class="titleLabel">作業者</label>
                        <p class="txtValue"><?php echo $detail['D03_TANTO_SEI'] . '' . $detail['D03_TANTO_MEI']; ?></p>
                    </div>
                    <div class="formItem flx-05">
                        <label class="titleLabel">確認者</label>
                        <p class="txtValue"><?php echo $detail['D03_KAKUNIN_SEI'] . '' . $detail['D03_KAKUNIN_MEI']; ?></p>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">その他作業内容</label>
                        <p class="txtValue"><?php echo nl2br(\yii\bootstrap\Html::encode($detail['D03_SAGYO_OTHER'])); ?></p>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">商品情報</legend>
                <div class="commodityBox on" id="commodity1">
                    <?php
                    foreach ($detail['product'] as $k => $v) {
                        ?>
                        <div class="formGroup">
                            <div class="formItem">
                                <label class="titleLabel">商品・荷姿コード</label>
                                <p class="txtValue"><?php echo $v['D05_COM_CD'] . $v['D05_NST_CD']; ?></p>
                            </div>
                            <div class="formItem">
                                <label class="titleLabel">品名</label>
                                <p class="txtValue"><?php if (isset($v['M05_COM_NAMEN'])) echo $v['M05_COM_NAMEN']; ?></p>
                            </div>
                        </div>
                        <div class="formGroup">
                            <div class="formItem">
                                <label class="titleLabel">数量</label>
                                <p class="txtValue"><?php echo floatval($v['D05_SURYO']); ?></p>
                            </div>
                            <div class="formItem">
                                <label class="titleLabel">単価</label>
                                <p class="txtValue"><?php echo isset($v['D05_TANKA']) && $v['D05_TANKA'] != '' ? number_format($v['D05_TANKA']) : '0'; ?>
                                    <span class="txtUnit">円</span></p>
                            </div>
                            <div class="formItem">
                                <label class="titleLabel">金額</label>
                                <p class="txtValue"><?php echo isset($v['D05_KINGAKU']) && $v['D05_KINGAKU'] != '' ? number_format($v['D05_KINGAKU']) : '0'; ?>
                                    <span class="txtUnit">円</span></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="formGroup lineTop">
                    <div class="flexRight">
                        <label class="titleLabelTotal">合計金額</label>
                        <p class="txtValue"><strong
                                class="totalPrice"><?php echo isset($detail['D03_SUM_KINGAKU']) && $detail['D03_SUM_KINGAKU'] != '' ? number_format($detail['D03_SUM_KINGAKU']) : '0'; ?></strong><span
                                class="txtUnit">円</span></p>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">その他</legend>
                <div class="formItem">
                    <label class="titleLabel">POS伝票番号</label>
                    <p class="txtValue"><?php echo $detail['D03_POS_DEN_NO']; ?></p>
                </div>
            </fieldset>
        </section>
        <!--csv-->
        <section class="bgContent" <?php echo $check_csv == 0 ? 'style="display: none"' : '' ?>>
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">保証書情報</legend>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">保証書番号</label>
                        <p class="txtValue"><?php echo isset($csv['M09_WARRANTY_NO']) ? $csv['M09_WARRANTY_NO'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">購入日</label>
                        <p class="txtValue">
                            <?php echo !empty($csv['M09_WARRANTY_NO']) ? Yii::$app->formatter->asDate(date('d-M-y', strtotime($csv['M09_INP_DATE'])), 'yyyy年MM月dd日') : ''; ?>
                        </p>

                    </div>
                    <div class="formItem">
                        <label class="titleLabel">保証期間</label>
                        <p class="txtValue">
                            <?php echo !empty($csv['M09_WARRANTY_NO']) ? Yii::$app->formatter->asDate(date('d-M-y', strtotime($csv['warranty_period'])), 'yyyy年MM月dd日') : ''; ?>
                        </p>
                    </div>
                </div>
                <div class="formGroup lineBottom">
                    <div class="formItem flx-05">
                        <label class="titleLabel">取付位置</label>
                    </div>
                    <div class="formItem flx-05">
                        <label class="titleLabel">メーカー</label>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">商品名</label>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">サイズ</label>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">セリアル番号</label>
                    </div>
                    <div class="formItem flx-05">
                        <label class="titleLabel">数量</label>
                    </div>
                </div>
                <div class="formGroup lineBottom">
                    <div class="formItem flx-05">
                        <p class="txtValue">右前</p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['right_front_manu']) ? $csv['right_front_manu'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['right_front_product']) ? $csv['right_front_product'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['right_front_size']) ? $csv['right_front_size'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['right_front_serial']) ? $csv['right_front_serial'] : '' ?></p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['right_front_no']) && $csv['right_front_no'] ? $csv['right_front_no'] : '' ?></p>
                    </div>
                </div>
                <div class="formGroup lineBottom">
                    <div class="formItem flx-05">
                        <p class="txtValue">左前</p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['left_front_manu']) ? $csv['left_front_manu'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['left_front_product']) ? $csv['left_front_product'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['left_front_size']) ? $csv['left_front_size'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['left_front_serial']) ? $csv['left_front_serial'] : '' ?></p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['left_front_no']) && $csv['left_front_no'] ? $csv['left_front_no'] : '' ?></p>
                    </div>
                </div>
                <div class="formGroup lineBottom">
                    <div class="formItem flx-05">
                        <p class="txtValue">右後</p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['right_behind_manu']) ? $csv['right_behind_manu'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['right_behind_product']) ? $csv['right_behind_product'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['right_behind_size']) ? $csv['right_behind_size'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['right_behind_serial']) ? $csv['right_behind_serial'] : '' ?></p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['right_behind_no']) && $csv['right_behind_no'] ? $csv['right_behind_no'] : '' ?></p>
                    </div>
                </div>
                <div class="formGroup lineBottom">
                    <div class="formItem flx-05">
                        <p class="txtValue">左後</p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['left_behind_manu']) ? $csv['left_behind_manu'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['left_behind_product']) ? $csv['left_behind_product'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['left_behind_size']) ? $csv['left_behind_size'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['left_behind_serial']) ? $csv['left_behind_serial'] : '' ?></p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['left_behind_no']) && $csv['left_behind_no'] ? $csv['left_behind_no'] : '' ?></p>
                    </div>
                </div>
                <div class="formGroup lineBottom">
                    <div class="formItem flx-05">
                        <p class="txtValue">その他A</p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['other_a_manu']) ? $csv['other_a_manu'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['other_a_product']) ? $csv['other_a_product'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['other_a_size']) ? $csv['other_a_size'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['other_a_serial']) ? $csv['other_a_serial'] : '' ?></p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['other_a_no']) && $csv['other_a_no'] ? $csv['other_a_no'] : '' ?></p>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem flx-05">
                        <p class="txtValue">その他B</p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['other_b_manu']) ? $csv['other_b_manu'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['other_b_product']) ? $csv['other_b_product'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['other_b_size']) ? $csv['other_b_size'] : '' ?></p>
                    </div>
                    <div class="formItem">
                        <p class="txtValue"><?php echo isset($csv['other_b_serial']) ? $csv['other_b_serial'] : '' ?></p>
                    </div>
                    <div class="formItem flx-05">
                        <p class="txtValue"><?php echo isset($csv['other_b_no']) && $csv['other_b_no'] ? $csv['other_b_no'] : '' ?></p>
                    </div>
                </div>
            </fieldset>
        </section>
        <!--confirm-->
        <?php
        $tire_arr = ['' => '', 'レ' => 'レ', 'Ｘ' => 'Ｘ', 'Ａ' => 'Ａ', 'Ｔ' => 'Ｔ', '／' => '／'];
        ?>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">作業前点検</legend>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">オイル量</label>
                        <p class="txtValue"><?php echo $confirm['oil_check'] == 1 ? 'OK' : ($confirm['oil_check'] == '0' ? 'NG' : '') ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">オイル漏れ</label>
                        <p class="txtValue"><?php echo $confirm['oil_leak_check'] == 1 ? 'OK' : ($confirm['oil_leak_check'] == '0' ? 'NG' : '') ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">キャップゲージ</label>
                        <p class="txtValue"><?php echo $confirm['cap_check'] == 1 ? 'OK' : ($confirm['cap_check'] == '0' ? 'NG' : '') ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">ドレンボルト</label>
                        <p class="txtValue"><?php echo $confirm['drain_bolt_check'] == 1 ? 'OK' : ($confirm['drain_bolt_check'] == '0' ? 'NG' : '') ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">タイヤ損傷・磨耗</label>
                        <p class="txtValue"><?php echo $confirm['tire_check'] == 1 ? 'OK' : ($confirm['tire_check'] == '0' ? 'NG' : '') ?></p>
                    </div>
                    <div class="formItem">
                        <label class="titleLabel">ボルト・ナット</label>
                        <p class="txtValue"><?php echo $confirm['nut_check'] == 1 ? 'OK' : ($confirm['nut_check'] == '0' ? 'NG' : '') ?></p>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">備考</label>
                        <p class="txtValue"><?php echo nl2br(\yii\helpers\Html::encode($confirm['note'])); ?></p>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">作業終了確認</legend>
                <table class="tablePrint bgWhite">
                    <tbody>
                    <tr>
                        <th rowspan="4">タ<br>イ<br>ヤ</th>
                        <td class="triggerCell">
                            <p class="leftside">リムバルブ</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['rim'] ? 'checked' : '' ?> disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                        <td class="triggerCell"><p class="leftside">ホイルキャップ</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['foil'] ? 'checked' : '' ?> disabled="">
                                    取付
                                </label>
                            </div>
                        </td>
                        <th rowspan="4">オ<br>イ<br>ル</th>
                        <td class="triggerCell">
                            <p class="leftside">オイル量</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['oil'] ? 'checked' : '' ?> disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">オイルキャップ</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['oil_cap'] ? 'checked' : '' ?>
                                           disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                        <th rowspan="4">バ<br>ッ<br>テ<br>リ<br>｜</th>
                        <td class="triggerCell">
                            <p class="leftside">ターミナル締付</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['terminal'] ? 'checked' : '' ?>
                                           disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="triggerCell">
                            <p class="leftside">トルクレンチ</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['torque'] ? 'checked' : '' ?>
                                           disabled="">
                                    締付
                                </label>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">持帰ナット</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['nut'] ? 'checked' : '' ?> disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">レベルゲージ</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['level'] ? 'checked' : '' ?> disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">ドレンボルト</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['drain_bolt'] ? 'checked' : '' ?>
                                           disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">ステー取付</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['stay'] ? 'checked' : '' ?> disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2" colspan="2">
                            <p class="leftside">空気圧</p>
                            <div class="areaAirCheck">
                                <div class="itemPrintAir">
                                    <p class="txtValue">
                                        <span class="txtUnit">前</span>
                                        <input disabled="disabled" class="textFormConf"
                                               value="<?php echo $confirm['pressure_front'] ?>" style="width:5em;"
                                               type="text">
                                        <span class="txtUnit">kpa</span>
                                    </p>
                                </div>
                                <div class="itemPrintAir">
                                    <p class="txtValue">
                                        <span class="txtUnit">後</span>
                                        <input disabled="disabled" class="textFormConf"
                                               value="<?php echo $confirm['pressure_behind'] ?>" style="width:5em;"
                                               type="text">
                                        <span class="txtUnit">kpa</span>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">パッキン</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['packing'] ? 'checked' : '' ?>
                                           disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">オイル漏れ</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['oil_leak'] ? 'checked' : '' ?>
                                           disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">バックアップ</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['backup'] ? 'checked' : '' ?>
                                           disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p class="leftside">次回交換目安</p>
                            <div class="checkPrint">
                                <p class="txtValue">
                                    <input disabled="" class="textFormConf"
                                           value="<?php echo $confirm['date'] ? Yii::$app->formatter->asDate(strtotime($confirm['date']), 'yyyy年MM月dd日') : '' ?>"
                                           style="width:9em;" type="text">
                                    <span class="txtUnit">または、</span>
                                </p>
                                <p class="txtValue">
                                    <input class="textFormConf"
                                           value="<?php echo isset($confirm['km']) ? $confirm['km'] : '' ?>"
                                           style="width:9em" disabled="disabled" type="number">
                                    <span class="txtUnit">km</span>
                                </p>
                            </div>
                        </td>
                        <td class="triggerCell">
                            <p class="leftside">スタートアップ</p>
                            <div class="checkPrint">
                                <label class="labelPrintCheck">
                                    <input type="checkbox" <?php echo $confirm['startup'] ? 'checked' : '' ?>
                                           disabled="">
                                    確認
                                </label>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="formGroup">
                    <div class="formItem">
                        <label class="titleLabel">備考</label>
                        <p class="txtValue"><?php echo \yii\helpers\Html::encode(nl2br($detail['D03_NOTE'])); ?></p>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">おすすめ整備</legend>
                <div id="commodity1" class="commodityBox on">
                    <?php
                    $total_price_suggest = 0;
                    foreach ($detail['product_suggest'] as $k => $v) {
                        ?>
                        <div class="formGroup">
                            <div class="formItem">
                                <label class="titleLabel">商品・荷姿コード</label>
                                <p class="txtValue"><?php echo $v['D05_COM_CD'] . $v['D05_NST_CD']; ?></p>
                            </div>
                            <div class="formItem">
                                <label class="titleLabel">品名</label>
                                <p class="txtValue"><?php if (isset($v['M05_COM_NAMEN'])) echo $v['M05_COM_NAMEN']; ?></p>
                            </div>
                        </div>
                        <div class="formGroup">
                            <div class="formItem">
                                <label class="titleLabel">数量</label>
                                <p class="txtValue"><?php echo floatval($v['D05_SURYO']); ?></p>
                            </div>
                            <div class="formItem">
                                <label class="titleLabel">単価</label>
                                <p class="txtValue"><?php echo isset($v['D05_TANKA']) && $v['D05_TANKA'] != '' ? number_format($v['D05_TANKA']) : '0'; ?>
                                    <span class="txtUnit">円</span></p>
                            </div>
                            <div class="formItem">
                                <label class="titleLabel">金額</label>
                                <p class="txtValue"><?php echo isset($v['D05_KINGAKU']) && $v['D05_KINGAKU'] != '' ? number_format($v['D05_KINGAKU']) : '0'; ?>
                                    <span class="txtUnit">円</span></p>
                            </div>
                        </div>
                        <?php if (isset($v['D05_KINGAKU'])) $total_price_suggest += (int)$v['D05_KINGAKU'];
                    } ?>


                </div>
                <div class="formGroup lineTop">
                    <div class="flexRight">
                        <label class="titleLabelTotal">合計金額</label>
                        <p class="txtValue"><strong
                                class="totalPrice"><?php echo number_format((int)$total_price_suggest); ?></strong><span
                                class="txtUnit">円</span></p>
                    </div>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">その他</legend>
                <div class="formItem">
                    <label class="titleLabel">POS伝票番号</label>
                    <p class="txtValue"><?php echo $detail_suggest['D03_POS_DEN_NO']; ?></p>
                </div>
            </fieldset>
        </section>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">コメント</legend>
                <?php
                foreach ($detail['comments'] as $row) {
                    ?>
                    <div class="formGroup">
                        <div class="formItem">
                            <p class="txtValue"><?php echo \yii\helpers\Html::encode($row['CONTENT']); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </fieldset>
        </section>

        <div id="sign-message" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            サインを保存しました。
        </div>
        <section class="bgContent">
            <fieldset class="fieldsetRegist">
                <legend class="titleLegend">
                    お客様サイン
                    <?php if ($detail['D03_SS_CD'] == $ss) { ?>
                        <a id="signature-btn"
                           class="btnFormTool large btnBlue <?php echo isset($check_remove) ? $check_remove : '' ?>"
                           href="#" style="<?php echo isset($check_remove) ? 'pointer-events: none;' : '' ?>">サインする</a>
                    <?php } ?>
                </legend>
                <div id="signature-block">
                    <img id="signature-img"<?php if ($sign) { ?> src="<?php echo $sign ?>"<?php } ?>>
                </div>
            </fieldset>
        </section>
    </article>
</main>
<footer id="footer">
    <div class="toolbar">
        <div class="toolbar-left" style="width: 40%">
            <a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/menu" class="btnTool btnGray">メニューへ戻る</a>
            <?php
            $url = Yii::$app->session->has('url_list_inspection') ? Yii::$app->session->get('url_list_inspection') : \yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/list';
            ?>
            <a href="<?php echo $url; ?>" class="btnTool btnBlue">作業履歴へ</a>

            <?php if ($check_file == 0) { ?>
                <a class="btnTool btnBlue off" href="#" data-alert="パンク保証書が作成されていません">パンク保証書を印刷</a>
            <?php } else if ($check_file == 1) { ?>
                <a id="pdf" class="btnTool btnBlue" href="#">パンク保証書を印刷</a>
            <?php } else if ($check_file == 2) { ?>
                <a class="btnTool btnBlue off" href="#" data-alert="パンク保証書が印刷済みです">パンク保証書を印刷</a>
            <?php } ?>
        </div>
        <div class="toolbar-right" style="width: 60%">
            <?php if ($detail['D03_SS_CD'] == $ss) { ?>
                <?php if (!empty($detail['sagyo']) && !empty($detail['D03_TANTO_SEI'] && !empty($detail['D03_KAKUNIN_SEI']) && $detail['D03_STATUS'] != '' && $detail['D03_STATUS'] == 0 && !empty($detail['product']))) { ?>
                    <a href="#modalWorkSlipComp"
                       class="btnSubmit cR marginBtn <?php echo isset($check_remove) ? $check_remove : '' ?>"
                       style="margin-right: 7px" data-toggle="modal">作業確定</a>
                <?php } ?>

                <a href="#modalRemoveConfirm" style="padding-left: 5px;padding-right: 5px" class="btnTool btnYellow"
                   data-toggle="modal">削除</a>
                <a href="<?php echo \yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/edit/' . $detail['D03_DEN_NO'] ?>"
                   class="btnTool marginBtn <?php echo isset($check_remove) ? $check_remove : '' ?>"
                   style="padding-left: 5px;padding-right: 5px; <?php echo isset($check_remove) ? 'pointer-events: none;' : '' ?>">編集</a>
            <?php } ?>

            <?php if ($sign) { ?>
                <a id="preview-btn"
                   href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/shaken/preview/<?php echo $detail['D03_DEN_NO']; ?>"
                   class="btnTool btnOrange cR marginBtn"
                   style="<?php echo isset($check_remove) ? 'pointer-events: none;' : '' ?>" target="_blank">作業伝票発行</a>
            <?php } else { ?>
                <a id="preview-btn" class="btnTool btnOrange cR disable-btn marginBtn" href="#" target="_blank"
                   data-alert="お客様サインが必要です" style="<?php echo isset($check_remove) ? 'pointer-events: none;' : '' ?>">作業伝票発行</a>
            <?php } ?>
            <a data-toggle="modal" target="_blank"
               class="btnTool marginBtn <?php echo isset($check_remove) ? $check_remove : '' ?>"
               style="<?php echo isset($check_remove) ? 'pointer-events: none;' : '' ?>"
               href="<?php echo \yii\helpers\BaseUrl::base(true) . '/shaken/preview/seisan/' . $denpyo_inspection['DENPYO_SUGGEST_NO']; ?>">清算書</a>
            <a data-toggle="modal" target="_blank"
               class="btnTool marginBtn <?php echo isset($check_remove) ? $check_remove : '' ?>"
               style="<?php echo isset($check_remove) ? 'pointer-events: none;' : '' ?>"
               href="<?php echo \yii\helpers\BaseUrl::base(true) . '/shaken/preview/osusume/' . $denpyo_inspection['DENPYO_SUGGEST_NO']; ?>">整備おすすめ</a>

        </div>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>
<!-- input den_no -->
<input id="den_no" hidden value="<?php echo $detail['D03_DEN_NO'] ?>">
<!-- sidemenu -->
<div id="sidr" class="sidr">
    <div class="closeSideMenu"><a href="#" id="sidrClose">Close</a></div>
    <ul>
        <li><a href="<?php echo \yii\helpers\BaseUrl::base(true) ?>/menu">SSサポートサイトTOP</a></li>
    </ul>
</div>
<!-- /sidemenu -->
<!-- modal 削除確認 -->
<div class="modal fade" id="modalRemoveConfirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">作業伝票削除</h4>
            </div>
            <div class="modal-body">
                <p class="note">作業伝票を削除します。よろしいですか？</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btnCancel flLeft" data-dismiss="modal" aria-label="Close">いいえ</a>
                <form method="post" action="<?php echo \yii\helpers\BaseUrl::base(true) ?>/shaken/denpyo/detail/remove">
                    <input type="hidden" name="den_no" value="<?php echo $detail['D03_DEN_NO']; ?>">
                    <button type="submit" class="btnSubmit flRight">はい</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /modal 削除確認 -->
<!-- modal 作業伝票確定 -->
<div class="modal fade" id="modalWorkSlipComp">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">作業伝票確定</h4>
            </div>
            <div class="modal-body">
                <p class="note">確定します。よろしいですか？</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btnCancel flLeft" data-dismiss="modal" aria-label="Close">いいえ</a>
                <form method="post" action="<?php echo \yii\helpers\BaseUrl::base(true) ?>/shaken/denpyo/detail/status">
                    <input type="hidden" name="den_no" value="<?php echo $detail['D03_DEN_NO']; ?>">
                    <button type="submit" class="btnSubmit flRight">はい</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSignature">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">お客様サイン</h4>
            </div>
            <div class="modal-body text-center">
                <canvas id="signature-canvas" width="1100" height="300">
                </canvas>
            </div>
            <div class="modal-footer">
                <a href="#" class="btnCancel flLeft" data-dismiss="modal" aria-label="Close">キャンセル</a> <a href="#"
                                                                                                          class="btnSubmit flRight">登録</a>
            </div>
        </div>
    </div>
</div>
<!-- /modal お客様サイン -->

<script>
    $(function () {
        $('#sign-message').hide();

        var canvas = $('#signature-canvas').get(0);
        var signaturePad = new SignaturePad(canvas, {
            minWidth: 2.8,
            maxWidth: 3.0
        });

        $('#signature-btn').on('click', function () {
            $('#sign-message').hide();
            $("#modalSignature").modal();
            return false;
        });

        $('#modalSignature .btnSubmit').on('click', function () {
            $.post(
                '<?php echo yii\helpers\BaseUrl::base(true) ?>/shaken/denpyo/detail/sign',
                {no: '<?php echo $detail['D03_DEN_NO']; ?>', sign: signaturePad.toDataURL()}
            ).done(function () {
                $('#signature-img').attr('src', signaturePad.toDataURL());
                $(window).scrollTop($('#signature-block').offset().top);
                signaturePad.clear();
                $("#modalSignature").modal('hide');
                $('#sign-message').show();

                var previewUrl = '<?php echo \yii\helpers\BaseUrl::base(true) ?>/shaken/preview/<?php echo $detail['D03_DEN_NO']; ?>';
                $('#preview-btn').removeClass('disable-btn').removeAttr('data-alert').attr('href', previewUrl).off('click');
            }).fail(function () {
                alert('サインの保存に失敗しました。');
            });

            return false;
        });

        $('#modalSignature .btnCancel').on('click', function () {
            signaturePad.clear();
        });

        $('#pdf').off('click').on('click', function () {
            var den_no = $('#den_no').val(),
                link = window.open('');
            link.document.title = 'View PDF';
            $.ajax({
                url: baseUrl + '/shaken/denpyo/detail/updatestatus',
                type: 'post',
                data: {
                    status: 1,
                    den_no: den_no,
                },
                success: function (data) {
                    $('#pdf').addClass('off').attr('href', '#').off('click').on('click', function () {
                        alert('パンク保証書は印刷済みです');
                        return false;
                    });
                    link.location = data;
                }
            });
        });
    });
</script>
