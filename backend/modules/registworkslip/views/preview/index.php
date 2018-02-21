<main>
    <section class ="preview">
        <div>
            <h1 class="bold entry-title lfloat" style="margin: 0 0 0 40%">【作業指示書】</h1>
            <div class="entry-title rfloat">
                <a href="javascript:print();" class="iconPrint">印刷</a>
                <a href="javascript:window.close();" class="iconClose">閉じる</a>
            </div>
            <div style="clear: both"></div>
        </div>
    </section>
    <article>
        <section>
            <table border="1px">
                <tr>
                    <th colspan="3" class="width3">状態</th>
                    <th colspan="4" class="width4">受付担当</th>
                    <th colspan="4" class="width4">受付日</th>
                    <th colspan="5" class="width5">受付時間</th>
                </tr>
                <tr class="height2">
                    <td colspan="3" class="sizeup">
                        <?php
                        if ($post['D03_STATUS'] == 1) {
                            echo '作業確定';
                        }
                        if ($post['D03_STATUS'] == 0) {
                            echo '作業予約';
                        } ?>
                    </td>
                    <td colspan="4" class="sizeup"><?php echo isset($ss_user) ? $ss_user : ''?></td>
                    <td colspan="4" class="sizeup"><?php echo isset($post['D03_UPD_DATE']) ? Yii::$app->formatter->asDate(strtotime($post['D03_UPD_DATE']), 'yyyy年MM月dd日') : date('Y年m月d日'); ?></td>
                    <td colspan="5">
                        <span class="sizeup"><?php echo isset($post['D03_AZU_BEGIN_HH']) ? str_pad($post['D03_AZU_BEGIN_HH'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?>:<?php echo isset($post['D03_AZU_BEGIN_MI']) ? str_pad($post['D03_AZU_BEGIN_MI'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?></span>
                        <span>～</span>
                        <span class="sizeup"><?php echo isset($post['D03_AZU_END_HH']) ? str_pad($post['D03_AZU_END_HH'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?>:<?php echo isset($post['D03_AZU_END_MI']) ? str_pad($post['D03_AZU_END_MI'], 2, '0', STR_PAD_LEFT) : str_pad('00', 2, '0', STR_PAD_LEFT); ?></span>
                    </td>
                </tr>
            </table>
        </section>
        <section>
            <p>【お客様情報】</p>
            <table border="1px">
                <tr>
                    <th colspan="2" class="width2"> フリガナ</th>
                    <td colspan="5" class="width5 sizeup"><?php echo $post['D01_CUST_NAMEK']; ?></td>
                    <td colspan="9" rowspan="2" class="width9 tleft ttop">
                        <?php echo isset($post['D01_NOTE']) ? nl2br($post['D01_NOTE']) : ''; ?>
                    </td>
                </tr>
                <tr class="height2">
                    <td colspan="7" class="width7 sizeup"><?php echo $post['D01_CUST_NAMEN']; ?> 様</td>
                </tr>
            </table>
        </section>
        <section>
            <p>【車両情報】</p>
            <?php if (!isset($post['D02_CAR_SEQ_SELECT'])) {
                $D02_CAR_NAMEN = '';
                $D02_JIKAI_SHAKEN_YM = '';
                $D02_SYAKEN_CYCLE = '';
                $D02_METER_KM = '';
                $D02_RIKUUN_NAMEN = '';
                $D02_CAR_ID = '';
                $D02_HIRA = '';
                $D02_CAR_NO = '';

            } else {
                $D02_CAR_NAMEN = $post['D02_CAR_NAMEN_' . $post['D02_CAR_SEQ_SELECT']];
                $D02_JIKAI_SHAKEN_YM = $post['D02_JIKAI_SHAKEN_YM_' . $post['D02_CAR_SEQ_SELECT']];
                $D02_SYAKEN_CYCLE = $post['D02_SYAKEN_CYCLE' . $post['D02_CAR_SEQ_SELECT']];
                $D02_METER_KM = $post['D02_METER_KM_' . $post['D02_CAR_SEQ_SELECT']];
                $D02_RIKUUN_NAMEN = $post['D02_RIKUUN_NAMEN_' . $post['D02_CAR_SEQ_SELECT']];
                $D02_CAR_ID = $post['D02_CAR_ID_' . $post['D02_CAR_SEQ_SELECT']];
                $D02_HIRA = $post['D02_HIRA_' . $post['D02_CAR_SEQ_SELECT']];
                $D02_CAR_NO = $post['D02_CAR_NO_' . $post['D02_CAR_SEQ_SELECT']];
            } ?>
            <table border="1px">
                <tr>
                    <th colspan="4" class="width4">メーカー</th>
                    <th colspan="4" class="width4">車名</th>
                    <th colspan="3" class="width3">初年度登録</th>
                    <th colspan="2">型式</th>
                    <th colspan="3" class="width3">グレード</th>
                </tr>
                <tr>
                    <td colspan="4" class="width4"><?php echo $car['D02_MAKER_CD'] ? $car['D02_MAKER_CD'] : ''?></td>
                    <td colspan="4" class="width4"><?php echo $D02_CAR_NAMEN;; ?></td>
                    <td colspan="3" class="width3"><?php echo $car['D02_SHONENDO_YM'] != '' ? substr($car['D02_SHONENDO_YM'],0,4).'年'.substr($car['D02_SHONENDO_YM'],4,2) . '月' : '' ?></td>
                    <td colspan="2"><?php echo $car['D02_TYPE_CD']?></td>
                    <td colspan="3" class="width3 size"><?php echo $car['D02_GRADE_CD']?></td>
                </tr>
                <tr>
                    <th colspan="4" class="width4">車検満了日</th>
                    <th colspan="4" class="width4">走行距離</th>
                    <th colspan="3" class="width3">車検サイクル</th>
                    <th colspan="5">フル車番</th>
                </tr>
                <tr>
                    <td colspan="4" class="width4"><?php echo $D02_JIKAI_SHAKEN_YM != '' ? Yii::$app->formatter->asDate(date('d-M-y', strtotime($D02_JIKAI_SHAKEN_YM)), 'yyyy年MM月d日') : '' ?></td>
                    <td colspan="4" class="width4"><?php echo $D02_METER_KM ? $D02_METER_KM.'<span>Km</span>' : ''; ?></td>
                    <td colspan="3" class="width3"><?php echo $D02_SYAKEN_CYCLE ? $D02_SYAKEN_CYCLE.'<span>年</span>' : ''; ?></td>
                    <td colspan="5" class="width3">
                        <?php echo $D02_RIKUUN_NAMEN ?>
                        <?php echo $D02_CAR_ID ?>
                        <?php echo $D02_HIRA ?>
                        <?php echo $D02_CAR_NO ?>
                    </td>
                </tr>
            </table>
        </section>
        <section>
            <p>【貴重品・精算情報】</p>
            <table border="1px">
                <tr>
                    <th colspan="4" class="width4">貴重品</th>
                    <th colspan="4" class="width4">お客様了承済み</th>
                    <th colspan="8" class="width8">精算方法</th>
                </tr>
                <tr>
                    <td colspan="4" class="width4">
                        <span style="width: 30px" class="<?php if (isset($post['D03_KITYOHIN']) && $post['D03_KITYOHIN'] == 1) {
                            echo 'rcorners';
                        } ?>">　有　</span>
                        <span>　　・　　</span>
                        <span style="width: 30px" class="<?php if (isset($post['D03_KITYOHIN']) && $post['D03_KITYOHIN'] == 0) {
                            echo 'rcorners';
                        } ?>">　無　</span>
                    </td>
                    <td colspan="4" class="width4">
                        <?php if (isset($post['D03_KAKUNIN']) && $post['D03_KAKUNIN']) {
                            echo '<p class="txtValue">　了承済　</p>';
                        } else {
                            echo '<p class="txtValue">　未了承　</p>';
                        } ?>
                    </td>
                    <td colspan="8" class="width8">
                        <?php if (isset($post['D03_SEISAN']) && $post['D03_SEISAN'] == 0) {
                            echo '<span class="rcorners">　現金　</span>';
                        }else{
                            echo '<span>　現金　</span>';
                        }?>
                        <span>・</span>
                        <?php if (isset($post['D03_SEISAN']) && $post['D03_SEISAN'] == 1) {
                            echo '<span class="rcorners">　プリカ　</span>';
                        }else{
                            echo '<span>　プリカ　</span>';
                        }?>
                        <span>・</span>
                        <?php if (isset($post['D03_SEISAN']) && $post['D03_SEISAN'] == 2) {
                            echo '<span class="rcorners">　クレジット　</span>';
                        }else{
                            echo '<span>　クレジット　</span>';
                        }?>
                        <span>・</span>
                        <?php if (isset($post['D03_SEISAN']) && $post['D03_SEISAN'] == 3) {
                            echo '<span class="rcorners">　掛　</span>';
                        }else{
                            echo '<span>　掛　</span>';
                        }?>
                    </td>
                </tr>
            </table>
        </section>
        <section>
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
                $count = $count > 7 ? $count : 7;
                for ($k = 1; $k <= $count; $k++) {
                    if (isset($post['D05_COM_CD' . $k])) {
                        ?>
                        <tr>
                            <td colspan="5" class="tleft">
                                <span><?php echo $post['M05_COM_NAMEN' . $k]; ?></span>
                            </td>
                            <td colspan="3">
                                <span><?php echo $post['D05_COM_CD' . $k] . $post['D05_NST_CD' . $k]; ?></span>
                            </td>
                            <td colspan="2">
                                <span><?php echo $post['D05_SURYO' . $k]; ?></span>
                            </td>
                            <td class="tright" colspan="3">
                                <span class="rtab"><?php echo $post['D05_TANKA' . $k] != '' ? number_format($post['D05_TANKA' . $k]).'　円' : ''; ?></span>
                            </td>
                            <td class="tright" colspan="3">
                                <span class="rtab"><?php echo $post['D05_KINGAKU' . $k] != '' ? number_format($post['D05_KINGAKU' . $k]).'　円' : ''; ?></span>
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
                <tr>
                    <td colspan="5" class="tleft">
                        <span><input type="checkbox">タイヤ交換料（バランス料込）</span>
                    </td>
                    <td colspan="3"></td>
                    <td colspan="2"></td>
                    <td colspan="3"></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="5" class="tleft">
                        <span><input type="checkbox">オイル交換料</span>
                    </td>
                    <td colspan="3"></td>
                    <td colspan="2"></td>
                    <td colspan="3"></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="5" class="tleft">
                        <span><input type="checkbox">バッテリー交換料</span>
                    </td>
                    <td colspan="3"></td>
                    <td colspan="2"></td>
                    <td colspan="3"></td>
                    <td colspan="3"></td>
                </tr>
                <tr class=" top_border">
                    <td colspan="8"></td>
                    <td colspan="5">合計金額</td>
                    <td colspan="3"><?php echo isset($post['D03_SUM_KINGAKU']) && $post['D03_SUM_KINGAKU'] != '' ? number_format($post['D03_SUM_KINGAKU']).' 円' : ''; ?></td>
                </tr>
            </table>
        </section>
        <section>
            <p>【作業前点検】</p>
            <table border="1px">
                <tr>
                    <th colspan="2" class="">オイル量</th>
                    <td colspan="2" class="" >
                        <span style="width: 30px" class="<?php if (isset($post['oil_check']) && $post['oil_check'] == 1) {
                            echo 'rcorners';
                        } ?>">　OK　</span>
                        <span>・</span>
                        <span style="width: 30px" class="<?php if (isset($post['oil_check']) && $post['oil_check'] == 0) {
                            echo 'rcorners';
                        } ?>">　NG　</span>
                    </td>
                    <th colspan="2" class="">キャップ・ゲージ</th>
                    <td colspan="2" class="" >
                        <span style="width: 30px" class="<?php if (isset($post['cap_check']) && $post['cap_check'] == 1) {
                            echo 'rcorners';
                        } ?>">　OK　</span>
                        <span>・</span>
                        <span style="width: 30px" class="<?php if (isset($post['cap_check']) && $post['cap_check'] == 0) {
                            echo 'rcorners';
                        } ?>">　NG　</span>
                    </td>
                    <th colspan="2" class="">タイヤ損傷・磨耗</th>
                    <td colspan="2" class="">
                        <span style="width: 30px" class="<?php if (isset($post['tire_check']) && $post['tire_check'] == 1) {
                            echo 'rcorners';
                        } ?>">　OK　</span>
                        <span>・</span>
                        <span style="width: 30px" class="<?php if (isset($post['tire_check']) && $post['tire_check'] == 0) {
                            echo 'rcorners';
                        } ?>">　NG　</span>
                    </td>
                    <td colspan="4" rowspan="2" class="width3 tleft ttop">
                        <div class="over">
                            備考<br>
                            <?php echo isset($post['note']) ? nl2br($post['note']) : ''?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="">オイル漏れ</th>
                    <td colspan="2" class="" >
                        <span style="width: 30px" class="<?php if (isset($post['oil_leak_check']) && $post['oil_leak_check'] == 1) {
                            echo 'rcorners';
                        } ?>">　OK　</span>
                        <span>・</span>
                        <span style="width: 30px" class="<?php if (isset($post['oil_leak_check']) && $post['oil_leak_check'] == 0) {
                            echo 'rcorners';
                        } ?>">　NG　</span>
                    </td>
                    <th colspan="2" class="">ドレンボルト</th>
                    <td colspan="2" class="" >
                        <span style="width: 30px" class="<?php if (isset($post['drain_bolt_check']) && $post['drain_bolt_check'] == 1) {
                            echo 'rcorners';
                        } ?>">　OK　</span>
                        <span>・</span>
                        <span style="width: 30px" class="<?php if (isset($post['drain_bolt_check']) && $post['drain_bolt_check'] == 0) {
                            echo 'rcorners';
                        } ?>">　NG　</span>
                    </td>
                    <th colspan="2" class="">ボルト・ナット</th>
                    <td colspan="2" class="" >
                        <span style="width: 30px" class="<?php if (isset($post['nut_check']) && $post['nut_check'] == 1) {
                            echo 'rcorners';
                        } ?>">　OK　</span>
                        <span>・</span>
                        <span style="width: 30px" class="<?php if (isset($post['nut_check']) && $post['nut_check'] == 0) {
                            echo 'rcorners';
                        } ?>">　NG　</span>
                    </td>
                </tr>
            </table>
        </section>
        <section>
            <p>【作業終了確認】※確認項目はレ印チェック</p>
            <table border="1px">
                <tr>
                    <th class="width1" rowspan="8">
                        <p>タ</p>
                        <p>イ</p>
                        <p>ヤ</p>
                    </th>
                    <th class="width2" colspan="2">交換箇所</th>
                    <th class="width3" colspan="3">セリアルナンバー</th>
                    <th class="width2" colspan="2">リムバルブ</th>
                    <th class="width1" rowspan="8">
                        <p>オ</p>
                        <p>イ</p>
                        <p>ル</p>
                    </th>
                    <th class="width2" colspan="2">オイル量</th>
                    <th class="width2" colspan="2">オイルキャップ</th>
                    <th class="width1" rowspan="8">
                        <p>バ</p>
                        <p>ッ</p>
                        <p>テ</p>
                        <p>リ</p>
                        <p>｜</p>
                    </th>
                    <th class="width2"colspan="2">ターミナル締付</th>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <span class="<?php echo isset($post['tire_1']) && $post['tire_1'] ? 'rcorners' : '' ?>">　右　前　</span>
                    </td>
                    <td colspan="3"><?php echo isset($post['front_right']) ? $post['front_right'] : ''?></td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['rim']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['oil']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['oil_cap']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['terminal']) ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <span class="<?php echo isset($post['tire_2']) && $post['tire_2'] ? 'rcorners' : '' ?>">　左　前　</span>
                    </td>
                    <td colspan="3"><?php echo isset($post['front_left']) ? $post['front_left'] : ''?></td>
                    <th colspan="2">トルクレンチ</th>
                    <th colspan="2">レベルゲージ</th>
                    <th colspan="2">ドレンボルト</th>
                    <th colspan="2">ステー取付</th>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <span class="<?php echo isset($post['tire_3']) && $post['tire_3'] ? 'rcorners' : '' ?>">　右　後　</span>
                    </td>
                    <td colspan="3"><?php echo isset($post['behind_right']) ? $post['behind_right'] : ''?></td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['torque']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['level']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['drain_bolt']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['stay']) ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <span class="<?php echo isset($post['tire_4']) && $post['tire_4'] ? 'rcorners' : '' ?>">　左　後　</span>
                    </td>
                    <td colspan="3"><?php echo isset($post['behind_left']) ? $post['behind_left'] : ''?></td>
                    <th colspan="2">ホイルキャップ</th>
                    <th colspan="2">パッキン</th>
                    <th colspan="2">オイル漏れ</th>
                    <th colspan="2">バックアップ</th>
                </tr>
                <tr>
                    <th colspan="5">空気圧</th>
                    <td colspan="2">
                        <span>取付</span>
                        <input type="checkbox" <?php echo isset($post['foil']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['packing']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['oil_leak']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['backup']) ? 'checked' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <?php
                        $pressure_front_1 =  $post['pressure_front_1'] ? $post['pressure_front_1'] : '';
                        if ($post['pressure_front_2']) {
                            if ($post['pressure_front_1']) {
                                $pressure_front_2 =  '.' . $post['pressure_front_2'];
                            }else {
                                $pressure_front_2 =  '0.' . $post['pressure_front_2'];
                            }
                        }else{
                            if($post['pressure_front_1']) {
                                $pressure_front_2 =  '.0';
                            }else{
                                $pressure_front_2 =  '';
                            }

                        }
                        ?>
                        <span class="lfloat">前</span>
                        <span><?php echo $pressure_front_1.$pressure_front_2?></span>
                        <span class="rfloat">Kpa</span>
                    </td>
                    <th colspan="2">持帰ナット</th>
                    <th colspan="4">交換目安</th>
                    <th colspan="2">スタートアップ</th>
                </tr>
                <tr>
                    <td colspan="5">
                        <?php
                        $pressure_behind_1 =  $post['pressure_behind_1'] ? $post['pressure_behind_1'] : '';
                        if ($post['pressure_behind_2']) {
                            if ($post['pressure_behind_1']) {
                                $pressure_behind_2 =  '.' . $post['pressure_behind_2'];
                            }else {
                                $pressure_behind_2 =  '0.' . $post['pressure_behind_2'];
                            }
                        }else{
                            if($post['pressure_behind_1']) {
                                $pressure_behind_2 =  '.0';
                            }else{
                                $pressure_behind_2 =  '';
                            }
                        }
                        ?>
                        <span class="lfloat">後</span>
                        <span><?php echo $pressure_behind_1.$pressure_behind_2?></span>
                        <span class="rfloat">Kpa</span>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['nut']) ? 'checked' : '' ?>>
                    </td>
                    <td colspan="4">
                        <?php
                        if($post['date'] && $post['km']){
                            echo Yii::$app->formatter->asDate(strtotime($post['date']), 'yyyy年MM月dd日').'または、'.$post['km'].'Km';
                        }elseif($post['date']) {
                            echo '<span>'.Yii::$app->formatter->asDate(strtotime($post['date']), 'yyyy年MM月dd日').'</span>';
                        }elseif($post['km']){
                            echo '<span>'.$post['km'].'Km</span>';
                        }
                        ?>
                    </td>
                    <td colspan="2">
                        <span>確認</span>
                        <input type="checkbox" <?php echo isset($post['startup']) ? 'checked' : '' ?>>
                    </td>
                </tr>

            </table>
        </section>
        <section>
            <table border="1px">
                <tr>
                    <td colspan="10" rowspan="2" class="width10 tleft ttop">
                        <p>備考</p>
                        <p><?php echo nl2br($post['D03_NOTE']); ?></p>
                    </td>
                    <th colspan="3" class="width3">作業者</th>
                    <th colspan="3" class="width3">確認者</th>
                </tr>
                <tr class="height2">

                    <td colspan="3" class="width3 sizeup"><?php echo isset($post['tanto']) ? $post['tanto'] : ''; ?></td>
                    <td colspan="3" class="width3 sizeup"><?php echo isset($post['kakunin']) ? $post['kakunin'] : ''; ?></td>
                </tr>
            </table>
        </section>
    </article>
</main>