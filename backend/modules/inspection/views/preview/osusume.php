<main>
    <?php
    if (Yii::$app->session->hasFlash('error')) {
        ?>
        <div class="alert alert-danger on" style="font-size: 20px; text-align: center" ><?php echo Yii::$app->session->getFlash('error') ?>
            <button class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php
    }
    ?>
    <section class="" style="margin-top: 15px" >
        <div class="" style="width: 65%; float: left;">
            <div class="divTable">
                <p style="width: 50%;"></p>
                <p class="titleBox">整備おすすめ</p>
            </div>
            <div class="borderBottom height-line divTable" style="text-align: center">
                <p class="font30 marginB"><u><?php echo yii\helpers\Html::encode($customer['D01_CUST_NAMEN'])?> 様</u></>
            </div>
            <div class="borderBottom height-line divTable" style="display: table">
                <p class="font20 marginB">住所&#12288;〒<?php echo $customer['D01_YUBIN_BANGO'] ? substr($customer['D01_YUBIN_BANGO'],0,3).'-'.substr($customer['D01_YUBIN_BANGO'],3,4) : ''?></p>
                <p class="font20 marginB"><?php echo yii\helpers\Html::encode($customer['D01_ADDR'])?></p>
            </div>
            <div class="borderBottom height-line divTable" style="display: table; text-align: left">
                <div style="float: left; width: 50%">
                    <p class="font20 marginB">TEL(自宅)</p>
                    <p style="margin-left: 10%" class="font22 marginB"><?php echo $customer['D01_TEL_NO']?></p>
                </div>
                <div style="float: right; width: 50%">
                    <p class="font20 marginB">TEL(勤務先/携帯)</p>
                    <p style="margin-left: 10%" class="font22 marginB"><?php echo $customer['D01_MOBTEL_NO']?></p>
                </div>
            </div>
        </div>
        <div style="width: 35%; float:left; text-align: right">
            <div>
                <p class="font20 margin0">
                    <span>依頼日:</span>
                    <span style="margin-left: 15px">H<?php echo (substr($denpyo['D03_SEKOU_YMD'],0,4)-1988).' '.substr($denpyo['D03_SEKOU_YMD'],4,2).' '.substr($denpyo['D03_SEKOU_YMD'],6,2) ?></span>
                </p>
                <p class="font20 margin0">
                    <span></span>
                    <span style="margin-left: 15px">No.<?php echo $denpyo['D03_DEN_NO']?></span>
                </p>
            </div>
            <div class="margin15">
                <p class="font25 margin0">株式会社東日本宇佐美</p>
                <p class="font35 margin0"><?php echo $ss?></p>
                <p class="font25 margin0"><?php echo $address?></p>
            </div>
            <div class="font20 margin15">
                <span>〒<?php echo $zipcode ? substr($zipcode,0,3).'-'.substr($zipcode,3,4) : ''?></span>
                <span style="margin-left: 7.5%">TEL&#12288;<?php echo $tel?></span>
            </div>
        </div>
    </section>
    <div style="clear: both"></div>
    <section>
        <table border="1px" style="text-align: center">
            <tr>
                <td style="width: 13%">車検コース</td>
                <td colspan="3"></td>
                <td style="width: 13%">登録番号</td>
                <td style="width: 18%"><?php echo $denpyo['D03_RIKUUN_NAMEN'].$denpyo['D03_CAR_ID'].$denpyo['D03_HIRA'].$denpyo['D03_CAR_NO'] ?></td>
            </tr>
            <tr>
                <td>車種名</td>
                <td><?php echo yii\helpers\Html::encode($denpyo['D03_CAR_NAMEN'])?></td>
                <td style="width: 13%">入庫年月日</td>
                <td style="width: 18%"></td>
                <td>完了予定日</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>車検満了</td>
                <td><?php echo $denpyo['D03_JIKAI_SHAKEN_YM'] ? '平成'.(substr($denpyo['D03_JIKAI_SHAKEN_YM'],0,4)-1988).'年'.substr($denpyo['D03_JIKAI_SHAKEN_YM'],4,2).'月'.substr($denpyo['D03_JIKAI_SHAKEN_YM'],6,2).'日' : '' ?></td>
                <td>走行距離</td>
                <td>
                    <span><?php echo $denpyo['D03_METER_KM']?></span>
                    <span style="float: right">Km</span>
                </td>
            </tr>
        </table>
    </section>
    <section>
        <table border="1px">
            <tr>
                <th class="thColor" style="width: 20%; text-align: center">
                    <span style="width: 25%; display: block; float: left">法</span>
                    <span style="width: 25%; display: block; float: left">定</span>
                    <span style="width: 25%; display: block; float: left">費</span>
                    <span style="width: 25%; display: block; float: left">用</span>

                </th>
                <th class="thColor"  style="width: 15%">金額</th>
                <th style="width: 1%" rowspan="7"></th>
                <th class="thColor"  style="width: 49%; text-align: center">
                    <span style="width: 20%; display: block; float: left">車</span>
                    <span style="width: 20%; display: block; float: left">検</span>
                    <span style="width: 20%; display: block; float: left">諸</span>
                    <span style="width: 20%; display: block; float: left">費</span>
                    <span style="width: 20%; display: block; float: left">用</span>
                </th>
                <th class="thColor" style="width: 15%">金額</th>
            </tr>
            <tr>
                <td>重量税</td>
                <td style="text-align: right"><?php echo $weight_tax ? number_format($weight_tax) : ''?></td>
                <td>基本点検</td>
                <td style="text-align: right"><?php echo $fee_basic ? number_format($fee_basic) : ''?></td>
            </tr>
            <tr>
                <td>自賠責保険料</td>
                <td style="text-align: right"><?php echo $mandatory_insurance ? number_format($mandatory_insurance) : ''?></td>
                <td>完成検査</td>
                <td style="text-align: right"><?php //echo number_format('')?></td>
            </tr>
            <tr>
                <td>印紙代（指定）</td>
                <td style="text-align: right"><?php echo $stamp_fee ? number_format($stamp_fee) : ''?></td>
                <td>事務手数料</td>
                <td style="text-align: right"><?php //echo number_format('')?></td>
            </tr>
            <tr>
                <td>証紙代（指定）</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="thColor" style="text-align: center">
                    <span style="width: 14.2%; display: block; float: left">法</span>
                    <span style="width: 14.2%; display: block; float: left">定</span>
                    <span style="width: 14.2%; display: block; float: left">費</span>
                    <span style="width: 14.2%; display: block; float: left">用</span>
                    <span style="width: 14.2%; display: block; float: left">の</span>
                    <span style="width: 14.2%; display: block; float: left">小</span>
                    <span style="width: 14.2%; display: block; float: left">計</span>
                </td>
                <?php $fee_registration = $weight_tax + $stamp_fee + $mandatory_insurance?>
                <td style="text-align: right"><?php echo $fee_registration ? number_format($fee_registration) : ''?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="thColor" style="text-align: center" colspan="4">①小&#12288;計</td>
                <?php $fee = $fee_registration + $fee_basic?>
                <td class="thColor" style="text-align: right"><?php echo $fee ? number_format($fee) : ''?></td>
            </tr>
        </table>
    </section>
    <section>
        <table border="1px" style="width: 60%; text-align: center">
            <tr>
                <td style="width: 30%">お奨めコース</td>
                <td style="width: 70%"></td>
            </tr>
        </table>
    </section>
    <section>
        <table border="1px" style="text-align: center">
            <tr>
                <th class="thColor" style="width: 3%">No.</th>
                <th class="thColor" style="width: 38%">項目</th>
                <th class="thColor" style="width: 40%">ご説明</th>
                <th class="thColor" style="width: 16%">金額</th>
                <th class="thColor"></th>
            </tr>
            <?php for ($i = 0; $i < 30; $i++) {?>
            <tr>
                <td><?php echo $i+1;?></td>
                <td style="text-align: left"><?php echo isset($product[$i]['M05_COM_NAMEN']) ? $product[$i]['M05_COM_NAMEN'] : ''?></td>
                <td style="text-align: left"></td>
                <td style="text-align: right"><?php echo isset($product[$i]['D05_KINGAKU']) ? number_format($product[$i]['D05_KINGAKU']) : ''?></td>
                <td></td>
            </tr>
            <?php }?>

            <tr>
                <td colspan="3">②小　　計</td>
                <td style="text-align: right"><?php echo $suggest['D03_SUM_KINGAKU'] ? number_format($suggest['D03_SUM_KINGAKU']) : ''?></td>
                <td></td>
            </tr>
        </table>
    </section>
    <section style="margin-top: 15px;">
        <div class="font20" style="width: 50%; float: left; border: solid black 1px">
            <div style="display: table; width: 100%; margin-left: 10px">
                <div style="display: table-cell; width: 35%">※チェック欄</div>
                <div style="display: table-cell; width: 35%">
                    <input type="checkbox" value="" style="pointer-events: none">
                    <span>車検証</span>
                </div>
                <div style="display: table-cell; width: 35%">
                    <input type="checkbox" value="" style="pointer-events: none">
                    <span>印鑑</span>
                </div>
            </div>
            <div style="display: table; width: 100%; margin-left: 10px">
                <div style="display: table-cell; width: 35%"></div>
                <div style="display: table-cell; width: 35%">
                    <input type="checkbox" value="" style="pointer-events: none">
                    <span>自賠責保険証</span>
                </div>
                <div style="display: table-cell; width: 35%">
                    <input type="checkbox" value="" style="pointer-events: none">
                    <span>費用</span>
                </div>
            </div>
            <div style="display: table; width: 100%; margin-left: 10px">
                <div style="display: table-cell; width: 35%"></div>
                <div style="display: table-cell; width: 35%">
                    <input type="checkbox" value="" style="pointer-events: none">
                    <span>納税証明書</span>
                </div>
                <div style="display: table-cell; width: 35%">
                    <input type="checkbox" value="" style="pointer-events: none">
                    <span>代車あり</span>
                </div>
            </div>
        </div>
        <div class="font20" style="width: 45%; float: right; text-align: right;">
            <div style="border-bottom: solid black 1px">
                <div class="margin5">
                    <span>車検基本費用:</span>
                    <input type="text" class="font20" style="width: 35%; border: solid black 1px; pointer-events: none; text-align: right" value="<?php echo $fee ? number_format($fee) : ''?>">
                    <span> 円</span>
                </div>
                <div class="margin5">
                    <span>ご依頼整備合計:</span>
                    <input type="text" class="font20" style="width: 35%; border: solid black 1px; pointer-events: none; text-align: right" value="<?php echo $suggest['D03_SUM_KINGAKU'] ? number_format($suggest['D03_SUM_KINGAKU']) : ''?>">
                    <span> 円</span>
                </div>
            </div>
            <div class="margin5">
                <span>総合計:</span>
                <?php $sum = $fee + $suggest['D03_SUM_KINGAKU']?>
                <input type="text" class="font20" style="width: 35%; border: solid black 1px; pointer-events: none; text-align: right" value="<?php echo $sum ? number_format($sum) : ''?>">
                <span> 円</span>
            </div>
        </div>
    </section>
</main>