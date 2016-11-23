<main>
    <?php
    if (Yii::$app->session->hasFlash('error')) {
        ?>
        <div class="alert alert-danger on" style="font-size: 20px; text-align: center"><?php echo Yii::$app->session->getFlash('error') ?>
            <button class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php
    }
    ?>
    <section class ="preview">
        <div style="margin-bottom: 10px">
            <h1 class="bold entry-title lfloat" style="margin: 0 0 0 40%">【作業確認書】</h1>
            <div class="entry-title rfloat">
                <a href="javascript:print();" class="iconPrint">印刷</a>
                <a href="javascript:window.close();" class="iconClose">閉じる</a>
            </div>
            <div style="clear: both"></div>
        </div>
        <p class="tleft space">お客様にご記入いただいた個人情報は、お車の作業・整備の記録管理及びお客様の連絡の為だけに使用し、当該目的以外の利用、第三者への提供は一切行いません。</p>
    </section>
    <article>
        <section class ="preview">
            <p>【お客様情報】</p>
            <table border="1px">
                <tr>
                    <th colspan="2" class="width2">フリガナ</th>
                    <td colspan="5" class="width5 sizeup"><?php echo yii\helpers\Html::encode($customer['D01_CUST_NAMEK']); ?></td>
                    <th colspan="4" class="width4">受付日</th>
                    <th colspan="5" class="width5">受付時間</th>
                </tr>
                <tr class="height2">
                    <td colspan="7" class="sizeup"><?php echo yii\helpers\Html::encode($customer['D01_CUST_NAMEN']); ?> 様</td>
                    <td colspan="4" class="sizeup"><?php echo isset($denpyo['D03_UPD_DATE']) ? Yii::$app->formatter->asDate($denpyo['D03_UPD_DATE'], 'yyyy年MM月dd日') : date('Y年m月d日'); ?></td>
                    <td colspan="5">
                        <span class="sizeup"><?php echo isset($denpyo['D03_AZU_BEGIN_HH']) ? str_pad($denpyo['D03_AZU_BEGIN_HH'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?>:<?php echo isset($denpyo['D03_AZU_BEGIN_MI']) ? str_pad($denpyo['D03_AZU_BEGIN_MI'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?></span>
                        <span>～</span>
                        <span class="sizeup"><?php echo isset($denpyo['D03_AZU_END_HH']) ? str_pad($denpyo['D03_AZU_END_HH'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?>:<?php echo isset($denpyo['D03_AZU_END_MI']) ? str_pad($denpyo['D03_AZU_END_MI'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?></span>
                    </td>
                </tr>
            </table>
        </section>
        <section class ="preview">
            <p>【車両情報】</p>

            <table border="1px">
                <tr>
                    <th colspan="4" class="width4">メーカー</th>
                    <th colspan="4" class="width4">車名</th>
                    <th colspan="3" class="width3">初年度登録</th>
                    <th colspan="2">型式</th>
                    <th colspan="3" class="width3">グレード</th>
                </tr>
                <tr>
                    <td colspan="4" class="width4"><?php echo isset($car['D02_MAKER_CD']) && $car['D02_MAKER_CD'] ? $car['D02_MAKER_CD'] : 'その他'?></td>
                    <td colspan="4" class="width4"><?php echo yii\helpers\Html::encode($denpyo['D03_CAR_NAMEN']); ?></td>
                    <td colspan="3" class="width3"><?php echo isset($car['D02_SHONENDO_YM']) && $car['D02_SHONENDO_YM'] ? substr($car['D02_SHONENDO_YM'],0,4).'年'.substr($car['D02_SHONENDO_YM'],4,2) . '月' : '' ?></td>
                    <td colspan="2"><?php echo isset($car['D02_TYPE_CD']) && $car['D02_TYPE_CD'] ? $car['D02_TYPE_CD'] : ''?></td>
                    <td colspan="3" class="width3 size"><?php echo isset($car['D02_GRADE_CD']) && $car['D02_GRADE_CD'] ? $car['D02_GRADE_CD'] : ''?></td>
                </tr>
                <tr>
                    <th colspan="4" class="width4">車検満了日</th>
                    <th colspan="4" class="width4">走行距離</th>
                    <th colspan="3" class="width3">車検サイクル</th>
                    <th colspan="5">フル車番</th>
                </tr>
                <tr>
                    <td colspan="4" class="width4"><?php echo $denpyo['D03_JIKAI_SHAKEN_YM'] != '' ? Yii::$app->formatter->asDate(date('d-M-y', strtotime($denpyo['D03_JIKAI_SHAKEN_YM'])), 'yyyy年MM月dd日') : '' ?></td>
                    <td colspan="4" class="width4"><?php echo $denpyo['D03_METER_KM'] ? $denpyo['D03_METER_KM'].'<span>Km</span>' : ''; ?></td>
                    <td colspan="3" class="width3"><?php echo isset($car['D02_SYAKEN_CYCLE']) && $car['D02_SYAKEN_CYCLE'] ? $car['D02_SYAKEN_CYCLE'].'<span>年</span>' : ''; ?></td>
                    <td colspan="5" class="width3">
                        <?php echo $denpyo['D03_RIKUUN_NAMEN']?>
                        <?php echo $denpyo['D03_CAR_ID']?>
                        <?php echo $denpyo['D03_HIRA']?>
                        <?php echo $denpyo['D03_CAR_NO']?>
                    </td>
                </tr>
            </table>
        </section>
        <section class ="preview">
            <p>【貴重品・精算情報】</p>
            <table border="1px">
                <tr>
                    <th colspan="4" class="width4">貴重品</th>
                    <th colspan="4" class="width4">お客様了承済み</th>
                    <th colspan="8" class="width8">精算方法</th>
                </tr>
                <tr>
                    <td colspan="4" class="width4">
                        <span style="width: 30px" class="<?php if (isset($denpyo['D03_KITYOHIN']) && $denpyo['D03_KITYOHIN'] == 1) {
                            echo 'rcorners';
                        } ?>">　有　</span>
                        <span>　　・　　</span>
                        <span style="width: 30px" class="<?php if (isset($denpyo['D03_KITYOHIN']) && $denpyo['D03_KITYOHIN'] == 0) {
                            echo 'rcorners';
                        } ?>">　無　</span>
                    </td>
                    <td colspan="4" class="width4">
                        <?php if (isset($denpyo['D03_KAKUNIN']) && $denpyo['D03_KAKUNIN']) {
                            echo '<p class="txtValue">　了承済　</p>';
                        } else {
                            echo '<p class="txtValue">　未了承　</p>';
                        } ?>
                    </td>
                    <td colspan="8" class="width8">
                        <?php if (isset($denpyo['D03_SEISAN']) && $denpyo['D03_SEISAN'] == 0) {
                            echo '<span class="rcorners">　現金　</span>';
                        }else{
                            echo '<span>　現金　</span>';
                        }?>
                        <span>・</span>
                        <?php if (isset($denpyo['D03_SEISAN']) && $denpyo['D03_SEISAN'] == 1) {
                            echo '<span class="rcorners">　プリカ　</span>';
                        }else{
                            echo '<span>　プリカ　</span>';
                        }?>
                        <span>・</span>
                        <?php if (isset($denpyo['D03_SEISAN']) && $denpyo['D03_SEISAN'] == 2) {
                            echo '<span class="rcorners">　クレジット　</span>';
                        }else{
                            echo '<span>　クレジット　</span>';
                        }?>
                        <span>・</span>
                        <?php if (isset($denpyo['D03_SEISAN']) && $denpyo['D03_SEISAN'] == 3) {
                            echo '<span class="rcorners">　掛　</span>';
                        }else{
                            echo '<span>　掛　</span>';
                        }?>
                    </td>
                </tr>
            </table>
        </section>
        <section class ="preview">
            <p>【商品情報】</p>
            <table border="1px">
                <tr>
                    <th class="width5" colspan="5">メーカー名 ・ 商品名 ・ サイズ　etc</th>
                    <th class="width3" colspan="3">商品CD</th>
                    <th class="width2" colspan="2">数量</th>
                    <th class="width3" colspan="3">単価</th>
                    <th class="width3" colspan="3">金額</th>
                </tr>
                <?php
                $count = 10;//count($product') > 5 ? count($product) : 5;
                for ($k = 0; $k < $count; $k++) {
                    if (isset($product[$k])) {
                        ?>
                        <tr>
                            <td colspan="5" class="tleft">
                                <span><?php echo isset($product[$k]['M05_COM_NAMEN']) ? $product[$k]['M05_COM_NAMEN'] : ''; ?></span>
                            </td>
                            <td colspan="3">
                                <span><?php echo $product[$k]['D05_COM_CD'].$product[$k]['D05_NST_CD']?></span>
                            </td>
                            <td colspan="2">
                                <span><?php echo $product[$k]['D05_SURYO'] < 1 ? '0'.$product[$k]['D05_SURYO'] : $product[$k]['D05_SURYO']; ?></span>
                            </td>
                            <td class="tright" colspan="3">
                                <span class="rtab"><?php echo isset($product[$k]['D05_TANKA']) && $product[$k]['D05_TANKA'] != '' ? number_format($product[$k]['D05_TANKA']).'　円' : ''; ?></span>
                            </td>
                            <td class="tright" colspan="3">
                                <span class="rtab"><?php echo isset($product[$k]['D05_KINGAKU']) && $product[$k]['D05_KINGAKU'] != '' ? number_format($product[$k]['D05_KINGAKU']).'　円' : ''; ?></span>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="3"></td>
                            <td colspan="2"></td>
                            <td colspan="3"></td>
                            <td colspan="3"></td>
                        </tr>
                    <?php    }} ?>
                <tr class=" top_border">
                    <td colspan="8"></td>
                    <td colspan="5">合計金額</td>
                    <td colspan="3"><?php echo isset($denpyo['D03_SUM_KINGAKU']) && $denpyo['D03_SUM_KINGAKU'] != '' ? number_format($denpyo['D03_SUM_KINGAKU']).' 円' : ''; ?></td>
                </tr>
                <tr>
                    <th colspan="3" class="width3">【作業前点検】</th>
                    <th colspan="13">【作業終了確認】※確認項目はレ印チェック</th>
                </tr>
                <tr>
                    <th colspan="2" class="tleft">オイル量</th>
                    <td style = "witdth: 3,5%"><?php echo $confirm['oil_check'] == 1 ? 'OK' : ($confirm['oil_check'] == '0' ? 'NG' : '')?></td>
                    <th class="width1" rowspan="8">
                        <p>タ</p>
                        <p>イ</p>
                        <p>ヤ</p>
                    </th>
                    <th colspan="2" class="width2">リムバルブ</th>
                    <th colspan="2" class="width2">ホイルキャップ</th>
                    <th class="width1" rowspan="8">
                        <p>オ</p>
                        <p>イ</p>
                        <p>ル</p>
                    </th>
                    <th colspan="2" class="width2">オイル量</th>
                    <th colspan="2" class="width2">オイルキャップ</th>
                    <th class="width1" rowspan="8">
                        <p>バ</p>
                        <p>ッ</p>
                        <p>テ</p>
                        <p>リ</p>
                        <p>｜</p>
                    </th>
                    <th colspan="2">ターミナル締付</th>
                </tr>
                <tr>
                    <th colspan="2" class="tleft">オイル漏れ</th>
                    <td style = "witdth: 3,5%"><?php echo $confirm['oil_leak_check'] == 1 ? 'OK' : ($confirm['oil_leak_check'] == '0' ? 'NG' : '')?></td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"   <?php echo $confirm['rim'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>取付</span>
                        <input type="checkbox"  <?php echo $confirm['foil'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo $confirm['oil'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo $confirm['oil_cap'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['terminal'] ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="tleft">キャップ・ゲージ</th>
                    <td style = "width: 3.5%"><?php echo $confirm['cap_check'] == 1 ? 'OK' : ($confirm['cap_check'] == '0' ? 'NG' : '')?></td>
                    <th colspan="2">トルクレンチ</th>
                    <th colspan="2">持帰ナット</th>
                    <th colspan="2">レベルゲージ</th>
                    <th colspan="2">ドレンボルト</th>
                    <th colspan="2">ステー取付</th>
                </tr>
                <tr>
                    <th colspan="2" class="tleft">ドレンボルト</th>
                    <td style = "width: 3.5%"><?php echo $confirm['drain_bolt_check'] == 1 ? 'OK' : ($confirm['drain_bolt_check'] == '0' ? 'NG' : '')?></td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['torque'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['nut'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['level'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['drain_bolt'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['stay'] ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="tleft">タイヤ損傷・磨耗</th>
                    <td style = "width: 3.5%"><?php echo $confirm['tire_check'] == 1 ? 'OK' : ($confirm['tire_check'] == '0' ? 'NG' : '')?></td>
                    <th colspan="4"></th>
                    <th colspan="2">パッキン</th>
                    <th colspan="2">オイル漏れ</th>
                    <th colspan="2">バックアップ</th>
                </tr>
                <tr>
                    <th colspan="2" class="tleft">ボルト・ナット</th>
                    <td style = "width: 3.5%"><?php echo $confirm['nut_check'] == 1 ? 'OK' : ($confirm['nut_check'] == '0' ? 'NG' : '')?></td>
                    <th colspan="4">空気圧</th>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['packing'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['oil_leak'] ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['backup'] ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" rowspan="2" class="width3 tleft ttop">
                        <div class="over">
                            備考<br>
                            <?php echo nl2br($confirm['note']); ?>
                        </div>
                    </td>
                    <td colspan="4">
                        <span class="lfloat">前</span>
                        <span><?php echo isset($confirm['pressure_front']) ? $confirm['pressure_front'] : '' ?></span>
                        <span class="rfloat">Kpa</span>
                    </td>
                    <td colspan="4">次回交換目安</td>
                    <td colspan="2">スタートアップ</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <span class="lfloat">後</span>
                        <span><?php echo isset($confirm['pressure_behind']) ? $confirm['pressure_behind'] : '' ?></span>
                        <span class="rfloat">Kpa</span>
                    </td>
                    <td colspan="4">
                        <?php
                        if($confirm['date'] && $confirm['km']){
                            echo Yii::$app->formatter->asDate(strtotime($confirm['date']), 'yyyy年MM月dd日').'または、'.$confirm['km'].'Km';
                        }elseif($confirm['date']) {
                            echo '<span>'.Yii::$app->formatter->asDate(strtotime($confirm['date']), 'yyyy年MM月dd日').'</span>';
                        }elseif($confirm['km']){
                            echo '<span>'.$confirm['km'].'Km</span>';
                        }
                        ?>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox"  <?php echo $confirm['startup'] ? 'checked' : '' ?>>
                    </td>
                </tr>
            </table>
        </section>
        <section class ="preview">
            <table border="1px">
                <tr>
                    <th colspan="7" class="width7 tleft">
                        <span>備考</span>
                    </th>
                    <th colspan="3" class="width3">作業者</th>
                    <th colspan="3" class="width3">確認者</th>
                    <th colspan="3" class="width5">お客様確認</th>
                </tr>
                <tr class="height2">
                    <td colspan="7" class="width7 tleft">
                        <p><?php echo yii\helpers\Html::encode(nl2br($denpyo['D03_NOTE'])); ?></p>
                    </td>
                    <td colspan="3" class="width3 sizeup"><?php echo $denpyo['D03_TANTO_SEI'] . '' . $denpyo['D03_TANTO_MEI']; ?></td>
                    <td colspan="3" class="width3 sizeup"><?php echo $denpyo['D03_KAKUNIN_SEI'] . '' . $denpyo['D03_KAKUNIN_MEI']; ?></td>
                    <td colspan="3" class="width5">
                        <?php if ($sign) { ?>
                            <img src="<?php echo $sign ?>" width="275" height="75">
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </section>
        <p style="font-weight: bold" class="tcenter">※取付作業伝票は、パンク保証サービスの補償時に必要となりますので、保証書と一緒に大事に保管して下さい。</p>
    </article>
</main>