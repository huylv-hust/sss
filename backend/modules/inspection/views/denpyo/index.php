<?php
    use backend\components\api;
    use yii\helpers\Html;
    $login_info = Yii::$app->session->get('login_info');
    $post = Yii::$app->request->post();
?>
<script src="<?php echo \yii\helpers\BaseUrl::base() ?>/js/module/inspection/denpyo.js"></script>

<form id="login_form" method="post">
    <main id="contents">
        <?php
        if (Yii::$app->session->hasFlash('check_remove_car')) {
            ?>
            <div class="alert alert-danger"><?php echo Yii::$app->session->getFlash('check_remove_car') ?>
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php }?>
        <!-- INPUT HIDDEN FOR DENPYO -->
        <input type="hidden" id="type_redirect" value="<?php echo isset($type_redirect) ? $type_redirect : ''; ?>">
        <input type="hidden" id="D03_DEN_NO" value="<?php echo isset($den_no) ? $den_no : ''?>" name="D03_DEN_NO">
        <input type="hidden" id="D01_SS_CD" name="D01_SS_CD" value="<?= isset($den_no) ? $denpyo['D03_SS_CD'] : $login_info['M50_SS_CD']; ?>" class="textForm">
        <input type="hidden" name="D03_STATUS" value="<?= isset($den_no) ? $denpyo['D03_STATUS'] : 0; ?>" class="textForm">
        <input type="hidden" name="D03_CUST_NO" value="<?= isset($den_no) ? $denpyo['D03_CUST_NO'] : ''; ?>" class="textForm">
        <!-- END INPUT HIDDEN FOR DENPYO -->
        <section class="readme">
            <h2 class="titleContent">車検見積</h2>
            <p class="rightside">受付日 <?php echo date('Y年m月d日'); ?></p>
        </section>
        <article class="container denpyo">
            <p class="note">項目を入力してください。<span class="must">*</span>は必須入力項目です。<br>
                商品情報にタイヤを追加すると、保証書作成用の入力フォームが表示されます。</p>
            <section class="bgContent">
                <fieldset class="fieldsetRegist">
                    <div class="formGroup">
                        <div class="formItem2">
                            <label class="titleLabel">受付担当者<span class="must">*</span></label>
                            <?= \yii\helpers\Html::dropDownList('M08_NAME_MEI_M08_NAME_SEI', isset($post['M08_NAME_MEI_M08_NAME_SEI']) ? $post['M08_NAME_MEI_M08_NAME_SEI'] : (isset($den_no) ? $cus_info['D01_UKE_JYUG_CD'] : ''), $tm08_sagyosya['jyug_cd'], array('class' => 'selectForm D01_UKE_JYUG_CD', 'id' => 'D01_UKE_JYUG_CD')) ?>
                        </div>
                        <div class="formItem2">
                            <label class="titleLabel">作業日</label>
                            <?php
                                $sekou_ymd = isset($post['D03_SEKOU_YMD']) ? $post['D03_SEKOU_YMD'] : (isset($den_no) ? $denpyo['D03_SEKOU_YMD'] : date('Ymd'));
                            ?>
                            <input name="D03_SEKOU_YMD" type="text" value="<?php echo $sekou_ymd;?>" class="textForm dateform">
                        </div>
                        <div class="formItem2">
                            <label class="titleLabel">お預かり時間</label>
                            <select class="selectForm" name="D03_AZU_BEGIN_HH">
                                <?php
                                $selected = isset($post['D03_AZU_BEGIN_HH']) ? $post['D03_AZU_BEGIN_HH'] : (isset($den_no) ? $denpyo['D03_AZU_BEGIN_HH'] : (int)date('H'));
                                for ($i = 0; $i < 24; ++$i) {
                                    if ($i == $selected) {
                                ?>
                                        <option selected="selected" value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <span class="txtUnit">：</span>
                            <select class="selectForm" name="D03_AZU_BEGIN_MI">
                                <?php
                                $mi = (int) date('i') - (int) date('i') % 10;
                                $selected = isset($post['D03_AZU_BEGIN_MI']) ? $post['D03_AZU_BEGIN_MI'] : (isset($den_no) ? $denpyo['D03_AZU_BEGIN_MI'] : $mi);
                                for ($i = 0; $i < 60; $i = $i + 10) {
                                    if ($i == $selected) {
                                        ?>
                                        <option selected="selected"
                                                value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                    <?php } else { ?>
                                        <option
                                            value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <span class="txtUnit">〜</span>
                            <select class="selectForm" name="D03_AZU_END_HH">
                                <?php
                                $selected = isset($post['D03_AZU_END_HH']) ? $post['D03_AZU_END_HH'] : (isset($den_no) ? $denpyo['D03_AZU_END_HH'] : (int) date('H') + 1);
                                for ($i = 0; $i < 24; ++$i) {
                                    if ($i == $selected) {
                                        ?>
                                        <option selected="selected"
                                                value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                    <?php } else { ?>
                                        <option
                                            value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <span class="txtUnit">：</span>
                            <select class="selectForm" name="D03_AZU_END_MI">
                                <?php
                                $mi = (int) date('i') - (int) date('i') % 10;
                                $selected = isset($post['D03_AZU_END_MI']) ? $post['D03_AZU_END_MI'] : (isset($den_no) ? $denpyo['D03_AZU_END_MI'] : $mi);
                                for ($i = 0; $i < 60; $i = $i + 10) {
                                    if ($i == $selected) {
                                        ?>
                                        <option selected="selected"
                                                value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                    <?php } else { ?>
                                        <option
                                            value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </section>

            <!-- SECTION CUSTOMER -->
            <section class="bgContent">
                <input type="hidden" id="hidden_cust_no" name="D01_CUST_NO" value="<?php echo $cus_info['D01_CUST_NO'];?>">
                <input type="hidden" id="hidden_cust_yubin_bango" name="D01_YUBIN_BANGO" value="<?php echo $cus_info['D01_YUBIN_BANGO'];?>">
                <input type="hidden" id="hidden_cust_addr" name="D01_ADDR" value="<?php echo Html::encode($cus_info['D01_ADDR']);?>">
                <input type="hidden" id="hidden_cust_tel_no" name="D01_TEL_NO" value="<?php echo $cus_info['D01_TEL_NO'];?>">
                <input type="hidden" id="hidden_cust_mobtel_no" name="D01_MOBTEL_NO" value="<?php echo $cus_info['D01_MOBTEL_NO'];?>">
                <input type="hidden" id="hidden_cust_namen" name="D01_CUST_NAMEN" value="<?php echo Html::encode($cus_info['D01_CUST_NAMEN']);?>">
                <input type="hidden" id="hidden_cust_namek" name="D01_CUST_NAMEK" value="<?php echo Html::encode($cus_info['D01_CUST_NAMEK']);?>">
                <input type="hidden" id="hidden_cust_note" name="D01_NOTE" value="<?php echo Html::encode($cus_info['D01_NOTE']);?>">
                <fieldset class="fieldsetRegist">
                    <div class="flexHead">
                        <legend class="titleLegend">お客様情報</legend>
                        <?php if(!isset($den_no)) {?>
                        <a data-toggle="modal" class="btnTool flRight" href="#modalEditCustomer">編集</a>
                        <?php }?>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">お名前</label>
                            <p class="txtValue" id="text_cust_namen"><?php echo Html::encode($cus_info['D01_CUST_NAMEN']);?></p>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">フリガナ</label>
                            <p class="txtValue" id="text_cust_namek"><?php echo Html::encode($cus_info['D01_CUST_NAMEK']);?></p>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">備考</label>
                            <p id="text_cust_note"><?php echo nl2br(Html::encode($cus_info['D01_NOTE']));?></p>
                        </div>
                    </div>
                </fieldset>
            </section>
            <!-- END SECTION CUSTOMER -->

            <!-- SECTION CAR -->
            <section class="bgContent" id="section_car">
                <input type="hidden" name="D03_CAR_SEQ" value="<?php echo isset($post['D03_CAR_SEQ']) ? $post['D03_CAR_SEQ'] : (isset($den_no) ? $denpyo['D03_CAR_SEQ'] : $cars[0]['D02_CAR_SEQ'])?>">
                <input type="hidden" name="D03_CAR_NAMEN" value="<?php echo isset($post['D03_CAR_NAMEN']) ? $post['D03_CAR_NAMEN'] : (isset($den_no) ? $denpyo['D03_CAR_NAMEN'] : $cars[0]['D02_CAR_NAMEN'])?>">
                <input type="hidden" name="D03_RIKUUN_NAMEN" value="<?php echo isset($post['D03_RIKUUN_NAMEN']) ? $post['D03_RIKUUN_NAMEN'] : (isset($den_no) ? $denpyo['D03_RIKUUN_NAMEN'] : $cars[0]['D02_RIKUUN_NAMEN'])?>">
                <input type="hidden" name="D03_CAR_ID" value="<?php echo isset($post['D03_CAR_ID']) ? $post['D03_CAR_ID'] : (isset($den_no) ? $denpyo['D03_CAR_ID'] : $cars[0]['D02_CAR_ID'])?>">
                <input type="hidden" name="D03_CAR_NO" value="<?php echo isset($post['D03_CAR_NO']) ? $post['D03_CAR_NO'] : (isset($den_no) ? $denpyo['D03_CAR_NO'] : $cars[0]['D02_CAR_NO'])?>">
                <input type="hidden" name="D03_HIRA" value="<?php echo isset($post['D03_HIRA']) ? $post['D03_HIRA'] : (isset($den_no) ? $denpyo['D03_HIRA'] : $cars[0]['D02_HIRA'])?>">
                <input type="hidden" name="D03_METER_KM" value="<?php echo isset($post['D03_METER_KM']) ? $post['D03_METER_KM'] : (isset($den_no) ? $denpyo['D03_METER_KM'] : $cars[0]['D02_METER_KM'])?>">
                <input type="hidden" name="D03_JIKAI_SHAKEN_YM" value="<?php echo isset($post['D03_JIKAI_SHAKEN_YM']) ? $post['D03_JIKAI_SHAKEN_YM'] : (isset($den_no) ? $denpyo['D03_JIKAI_SHAKEN_YM'] : $cars[0]['D02_JIKAI_SHAKEN_YM'])?>">
                <input type="hidden" name="D02_SYAKEN_CYCLE" value="<?php echo isset($post['D02_SYAKEN_CYCLE']) ? $post['D02_SYAKEN_CYCLE'] : (isset($den_no) ? $denpyo['D02_SYAKEN_CYCLE'] : $cars[0]['D02_SYAKEN_CYCLE'])?>">
                <fieldset class="fieldsetRegist">
                    <div class="flexHead">
                        <legend class="titleLegend">車両情報</legend>
                        <?php if(!isset($den_no)) {?>
                            <a data-toggle="modal" class="btnTool flRight" href="#modalEditCar">編集</a>
                        <?php }?>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">対象車両</label>
                            <select class="selectForm" name="D02_CAR_SEQ_SELECT" id="D02_CAR_SEQ">
                                <?php
                                if($total_car) {
                                    foreach ($cars as $car) {
                                        if($car['D02_CAR_SEQ']) {
                                            if((isset($post['D02_CAR_SEQ_SELECT']) && $car['D02_CAR_SEQ'] == $post['D02_CAR_SEQ_SELECT']) || (isset($den_no) && $car['D02_CAR_SEQ'] == $denpyo['D03_CAR_SEQ'])) {
                                                echo '<option selected value="' . $car['D02_CAR_SEQ'] . '">' . $car['D02_CAR_NO'] . '</option>';
                                            } else {
                                                echo '<option value="' . $car['D02_CAR_SEQ'] . '">' . $car['D02_CAR_NO'] . '</option>';
                                            }
                                        }
                                    }
                                }

                                ?>
                            </select>
                        </div>
                        <?php
                            foreach($cars as $k => $v) {
                                $class = (($v['D02_CAR_SEQ'] != 1 && !isset($den_no)) || (isset($den_no) && $v['D02_CAR_SEQ'] != $denpyo['D03_CAR_SEQ'])) ? 'hide' : '';
                                if($v['D02_CAR_SEQ']) {
                        ?>
                                    <div class="car_info car_seq_<?php echo $v['D02_CAR_SEQ'] . ' ' . $class; ?>">
                                        <input type="hidden" value="<?php echo $v['D02_CAR_SEQ'];?>" class="D03_CAR_SEQ_<?php echo $v['D02_CAR_SEQ'];?>">
                                        <input type="hidden" value="<?php echo $v['D02_CAR_NAMEN'];?>" class="D03_CAR_NAMEN_<?php echo $v['D02_CAR_SEQ'];?>">
                                        <input type="hidden" value="<?php echo $v['D02_RIKUUN_NAMEN'];?>" class="D03_RIKUUN_NAMEN_<?php echo $v['D02_CAR_SEQ'];?>">
                                        <input type="hidden" value="<?php echo $v['D02_CAR_ID'];?>" class="D03_CAR_ID_<?php echo $v['D02_CAR_SEQ'];?>">
                                        <input type="hidden" value="<?php echo $v['D02_HIRA'];?>" class="D03_HIRA_<?php echo $v['D02_CAR_SEQ'];?>">
                                        <input type="hidden" value="<?php echo $v['D02_METER_KM'];?>" class="D03_METER_KM_<?php echo $v['D02_CAR_SEQ'];?>">
                                        <input type="hidden" value="<?php echo $v['D02_JIKAI_SHAKEN_YM'];?>" class="D03_JIKAI_SHAKEN_YM_<?php echo $v['D02_CAR_SEQ'];?>">
                                        <input type="hidden" value="<?php echo $v['D02_SYAKEN_CYCLE'];?>" class="D02_SYAKEN_CYCLE_<?php echo $v['D02_CAR_SEQ'];?>">
                                        <input type="hidden" value="<?php echo $v['D02_CAR_NO'];?>" class="D02_CAR_NO<?php echo $v['D02_CAR_SEQ'];?>">
                                    </div>
                                    <div class="formItem flx-2 carDataBasic car_seq_<?php echo $v['D02_CAR_SEQ'] . ' ' . $class; ?> ">
                                        <label class="titleLabel">車名</label>
                                        <p class="txtValue"><?php echo $v['D02_CAR_NAMEN']; ?></p>
                                    </div>
                                    <div class="formItem carDataBasic car_seq_<?php echo $v['D02_CAR_SEQ'] . ' ' . $class; ?>">
                                        <label class="titleLabel">車検満了日</label>
                                        <p class="txtValue"><?php echo $v['D02_JIKAI_SHAKEN_YM']; ?></p>
                                    </div>
                                    <div class="formItem carDataBasic car_seq_<?php echo $v['D02_CAR_SEQ'] . ' ' . $class; ?>">
                                        <label class="titleLabel">走行距離</label>
                                        <p class="txtValue"><?php echo $v['D02_METER_KM'] . 'km'; ?></p>
                                    </div>
                                    <div class="formItem carDataBasic car_seq_<?php echo $v['D02_CAR_SEQ'] . ' ' . $class; ?>">
                                        <label class="titleLabel">運輸支局</label>
                                        <p class="txtValue"><?php echo $v['D02_RIKUUN_NAMEN']; ?></p>
                                    </div>
                                    <div class="formItem carDataBasic car_seq_<?php echo $v['D02_CAR_SEQ'] . ' ' . $class; ?>">
                                        <label class="titleLabel">分類コード</label>
                                        <p class="txtValue"><?php echo $v['D02_CAR_ID']; ?></p>
                                    </div>
                                    <div class="formItem carDataBasic car_seq_<?php echo $v['D02_CAR_SEQ'] . ' ' . $class; ?>">
                                        <label class="titleLabel">ひらがな</label>
                                        <p class="txtValue"><?php echo $v['D02_HIRA']; ?></p>
                                    </div>
                                    <div class="formItem carDataBasic car_seq_<?php echo $v['D02_CAR_SEQ'] . ' ' . $class; ?>">
                                        <label class="titleLabel">登録番号</label>
                                        <p class="txtValue"><?php echo $v['D02_CAR_NO']; ?></p>
                                    </div>
                        <?php
                            }}
                        ?>

                    </div>

                </fieldset>
            </section>
            <!-- END SECTION CAR -->

            <!-- SECTION FEE -->
            <section class="bgContent">
                <fieldset class="fieldsetRegist">
                    <div class="flexHead">
                        <legend class="titleLegend">車検料金</legend>
                    </div>
                    <div class="formGroup">
                        <div class="formItem2">
                            <label class="titleLabel">車両サイズ</label>
                            <?php echo \yii\helpers\Html::dropDownList('denpyo_inspection[CAR_SIZE]', isset($post['denpyo_inspection']['CAR_SIZE']) ? $post['denpyo_inspection']['CAR_SIZE'] : (isset($den_no) ? $denpyo_inspection['CAR_SIZE'] : ''), Yii::$app->params['car_size'], array('class' => ['selectForm', 'select_car_size']))?>
                        </div>
                        <div class="formItem2">
                            <label class="titleLabel" >重量</label>
                            <input type="hidden" id="hidden_car_weight_hidden" value="<?php echo isset($den_no) ? $denpyo_inspection['CAR_WEIGHT'] : '';?>">
                            <select name="denpyo_inspection[CAR_WEIGHT]" class="selectForm car_weight"></select>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">基本点検料金</label>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="labelForm">分類名</label>
                            <input type="hidden" id="fee_basic_value" value='<?php echo json_encode($fee_basic['value']);?>'>
                            <?php echo \yii\helpers\Html::dropDownList('denpyo_inspection[FEE_BASIC_ID]', isset($post['denpyo_inspection']['FEE_BASIC_ID']) ? $post['denpyo_inspection']['FEE_BASIC_ID'] : (isset($den_no) ? $denpyo_inspection['FEE_BASIC_ID'] : ''), $fee_basic['select'], array('class' => ['selectForm', 'fee_basic_select'], 'style' => 'max-width:500px'))?>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="labelForm">基本点検料金</label>
                            <?php
                                $fee_basic_value = isset($post['denpyo_inspection']['FEE_BASIC_ID']) ? $fee_basic['value'][$post['denpyo_inspection']['FEE_BASIC_ID']] : (isset($den_no) && isset($fee_basic['value'][$denpyo_inspection['FEE_BASIC_ID']]) ? $fee_basic['value'][$denpyo_inspection['FEE_BASIC_ID']] : '');
                                $class = '';
                                if(!$fee_basic_value) {
                                    $class = 'hide';
                                }
                            ?>
                            <span id="text_fee_basic_value"><?php echo $fee_basic_value;?></span>
                            <span id="unit_fee_basic_value" class="txtUnit <?php echo $class;?>">円</span>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">法定料金</label>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="labelForm">分類名</label>
                            <input type="hidden" id="fee_registration_value" value='<?php echo json_encode($fee_registration['value']);?>'>
                            <?php echo \yii\helpers\Html::dropDownList('denpyo_inspection[FEE_REGISTRATION_ID]', isset($post['denpyo_inspection']['FEE_REGISTRATION_ID']) ? $post['denpyo_inspection']['FEE_REGISTRATION_ID'] : (isset($den_no) ? $denpyo_inspection['FEE_REGISTRATION_ID'] : ''), $fee_registration['select'], array('class' => ['selectForm', 'fee_registration_select'], 'style' => 'max-width:500px'))?>
                        </div>
                    </div>
                    <?php
                        $weight_tax = '';
                        $mandatory_insurance = '';
                        $stamp_fee = '';
                        if(isset($post['denpyo_inspection']['FEE_REGISTRATION_ID']) && isset($fee_registration['value'][$post['denpyo_inspection']['FEE_REGISTRATION_ID']])) {
                            $weight_tax = $post['denpyo_inspection']['WEIGHT_TAX'];
                            $mandatory_insurance = $fee_registration['value'][$post['denpyo_inspection']['FEE_REGISTRATION_ID']]['MANDATORY_INSURANCE'];
                            $stamp_fee = $fee_registration['value'][$post['denpyo_inspection']['FEE_REGISTRATION_ID']]['STAMP_FEE'];
                        } elseif (isset($den_no) && isset($fee_registration['value'][$denpyo_inspection['FEE_REGISTRATION_ID']])) {
                            $weight_tax = $denpyo_inspection['WEIGHT_TAX'];
                            $mandatory_insurance = $fee_registration['value'][$denpyo_inspection['FEE_REGISTRATION_ID']]['MANDATORY_INSURANCE'];
                            $stamp_fee = $fee_registration['value'][$denpyo_inspection['FEE_REGISTRATION_ID']]['STAMP_FEE'];
                        }
                    ?>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="labelForm">重量税</label>
                            <input type="text" id="input_weight_tax" name="denpyo_inspection[WEIGHT_TAX]" class="textForm" value="<?php echo $weight_tax; ?>" maxlength="10" <?php echo !isset($denpyo_inspection['FEE_REGISTRATION_ID']) || !$denpyo_inspection['FEE_REGISTRATION_ID'] ? 'disabled' : ''; ?>>
                            <span class="txtUnit">円</span>
                            <a class="btnSubmit" style="height: 37px; line-height: 37px; font-size: 20px" href="" target="_blank">エコカー税</a>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="labelForm">自賠責</label>
                            <span id="text_mandatory_insurance" class=""><?php echo $mandatory_insurance;?></span>
                            <span id="unit_mandatory_insurance" class="<?php echo $mandatory_insurance ? '' : 'hide';?> txtUnit">円</span>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="labelForm">印紙代</label>
                            <span id="text_stamp_fee" class=""><?php echo $stamp_fee;?></span>
                            <span id="unit_stamp_fee" class="<?php echo $stamp_fee ? '' : 'hide';?> txtUnit">円</span>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="" style="width: 15%; font-size: 25px">合計</label>
                            <span id="text_total_fee" class="" style=" font-size: 25px; font-weight: bold">0</span>
                            <span id="unit_total_fee" class="txtUnit" style=" font-size: 25px; font-weight: bold">円</span>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">割引・割増料金</label>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="labelForm">分類名</label>
                            <?php echo \yii\helpers\Html::dropDownList('denpyo_inspection[PARENT_DISCOUNT_ID]', isset($den_no) ? $denpyo_inspection['PARENT_DISCOUNT_ID'] : '', $parent_discount, array('class' => ['selectForm', 'parent_discount_select'], 'style' => 'max-width:500px'))?>
                        </div>
                    </div>
                    <div id="section_discount">
                        <?php if(isset($den_no)) {
                            foreach($packages_discount as $key => $value) {
                                $discounts = [];
                                foreach ($value['discount'] as $k => $v)
                                {
                                    $discounts[$k] = $v['VALUE'] . ' (' . $v['DESCRIPTION'] . ')';
                                }
                        ?>
                            <div class="formGroup">
                                <div class="formItem">
                                    <label class="labelForm"><?php echo yii\helpers\Html::encode($value['NAME'])?></label>
                        <?php
                            if($value['type'] == 'select') {
                                $discounts  = ['' => ''] + $discounts;
                                echo \yii\helpers\Html::dropDownList('denpyo_inspection[DISCOUNT][]', in_array($key, $discount) ? array_search($key, $discount) : '', $discounts, array('class' => ['selectForm', 'select_discount']));
                            }
                            if($value['type'] == 'checkbox') {
                        ?>
                                <input <?php echo in_array($key, $discount) ? 'checked' : ''?> name="denpyo_inspection[DISCOUNT][]" value="<?php echo key($discounts)?>" type="checkbox" class="checks checkbox_discount">
                                <span class="spanSingleCheck <?php echo in_array($key, $discount) ? 'checked' : ''?>"><?php echo current($discounts); ?></span>
                        <?php
                            }
                        ?>
                                    <span class="txtUnit">円</span>
                                </div>
                            </div>
                        <?php  }} ?>
                    </div>

                    <div class="formGroup">
                        <div class="formItem">
                            <label class="" style="width: 20%; font-size: 22px; color: red">割引・割増適用後</label>
                            <span id="total_fee_discount" style="font-size: 22px; font-weight: bold; color: red">0</span>
                            <span class="txtUnit" style="font-weight: bold; font-size: 22px; color: red">円</span>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="labelbold" style="width: 20%">預かり金</label>
                            <input type="text" id="earnest_money" name="denpyo_inspection[EARNEST_MONEY]" class="textForm" value="<?php echo isset($den_no) ? $denpyo_inspection['EARNEST_MONEY'] : ''; ?>" maxlength="10">
                            <span class="txtUnit">円</span>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="" style="width: 20%; font-size: 22px; color: red">差引金額</label>
                            <span id="actually_paid" style="font-size: 22px; font-weight: bold; color: red">0</span>
                            <span class="txtUnit" style="font-weight: bold; font-size: 22px; color: red">円</span>
                        </div>
                    </div>
                </fieldset>
            </section>
            <!-- END SECTION FEE -->

            <!-- SECTION COMMENT -->
            <section class="bgContent">
                <div class="hide" id="hidden_select_parent_comment">
                    <?php echo \yii\helpers\Html::dropDownList('parent_comment', '', $parent_comment, array('class' => ['selectForm', 'select_parent_comment'], 'style' => 'max-width:260px'))?>
                </div>
                <fieldset class="fieldsetRegist">
                    <div class="flexHead">
                        <legend class="titleLegend">コメント</legend>
                    </div>
                    <div class="group_comment">
                        <a class="addComment">追加</a>
                        <?php
                            if(!isset($den_no) || empty($comments)) {
                        ?>
                        <div class="formGroup comment" attr-index="0">
                            <div class="formItem2">
                                <label class="titleLabel">分類</label>
                                <?php echo \yii\helpers\Html::dropDownList('parent_comment', '', $parent_comment, array('class' => ['selectForm', 'select_parent_comment'], 'style' => 'max-width:260px'))?>
                            </div>
                            <div class="formItem2">
                                <label class="titleLabel">コメント</label>
                                <select name="denpyo_inspection[COMMENTS][]" class="selectForm select_comment"> </select>
                            </div>
                        </div>
                        <?php } else {
                                foreach($comments as $k => $v) {
                        ?>
                                    <div class="formGroup comment" attr-index="<?php echo $k;?>">
                                        <div class="formItem2">
                                            <label class="titleLabel">分類</label>
                                            <?php echo \yii\helpers\Html::dropDownList('parent_comment', $v['PARENT_COMMENT_ID'], $parent_comment, array('class' => ['selectForm', 'select_parent_comment'], 'style' => 'max-width:260px'))?>
                                        </div>
                                        <div class="formItem2">
                                            <label class="titleLabel">コメント</label>
                                            <input type="hidden" class="hidden_comment_<?php echo $k;?>" value="<?php echo $v['ID'];?>">
                                            <select name="denpyo_inspection[COMMENTS][]" class="selectForm select_comment"></select>
                                        </div>
                                    </div>
                        <?php }} ?>
                    </div>
                </fieldset>
            </section>
            <!-- END SECTION COMMENT -->

            <!-- SECTION PAYMENT METHOD -->
            <section class="bgContent">
                <fieldset class="fieldsetRegist">
                    <legend class="titleLegend">貴重品・精算情報</legend>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">貴重品</label>
                            <div class="radioGroup">
                                <div class="radioItem">
                                    <?php
                                        $check = isset($post['D03_KITYOHIN']) && $post['D03_KITYOHIN'] ? 'checked' : (isset($den_no) && $denpyo['D03_KITYOHIN'] ? 'checked' : '');
                                    ?>
                                    <input <?php echo $check; ?> type="radio" name="D03_KITYOHIN" id="valuables1" class="radios" value="1">
                                    <label class="labelRadios <?php echo $check; ?>" for="valuables1">有り</label>
                                </div>
                                <div class="radioItem">
                                    <?php
                                    $check = isset($post['D03_KITYOHIN']) && $post['D03_KITYOHIN'] == 0 ? 'checked' : (isset($den_no) && $denpyo['D03_KITYOHIN'] == 0 ? 'checked' : '');
                                    ?>
                                    <input <?php echo $check; ?> type="radio" name="D03_KITYOHIN" id="valuables2" class="radios" value="0">
                                    <label class="labelRadios <?php echo $check; ?>" for="valuables2">無し</label>
                                </div>
                            </div>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">お客様確認</label>
                            <div class="checkGroup">
                                <div class="checkItem checkSingle">
                                    <?php
                                    $check = isset($post['D03_KAKUNIN'])  ? 'checked' : (isset($den_no) && $denpyo['D03_KAKUNIN'] ? 'checked' : '');
                                    ?>
                                    <input <?php echo $check; ?> type="checkbox" name="D03_KAKUNIN" id="agree1" class="checks" value="1">
                                    <label class="labelSingleCheckBtn <?php echo $check; ?>">了解済OK</label>
                                </div>
                            </div>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">精算方法</label>
                            <div class="radioGroup">
                                <div class="radioItem">
                                    <?php
                                    $check = isset($post['D03_SEISAN']) && $post['D03_SEISAN'] == 0  ? 'checked' : (isset($den_no) && $denpyo['D03_SEISAN'] == 0 ? 'checked' : '');
                                    ?>
                                    <input <?php echo $check; ?> type="radio" name="D03_SEISAN" id="pays1" class="radios" value="0">
                                    <label class="labelRadios <?php echo $check; ?>" for="pays1">現金</label>
                                </div>
                                <div class="radioItem">
                                    <?php
                                    $check = isset($post['D03_SEISAN']) && $post['D03_SEISAN'] == 1  ? 'checked' : (isset($den_no) && $denpyo['D03_SEISAN'] == 1 ? 'checked' : '');
                                    ?>
                                    <input <?php echo $check; ?> type="radio" name="D03_SEISAN" id="pays2" class="radios" value="1">
                                    <label class="labelRadios <?php echo $check; ?>" for="pays2">プリカ</label>
                                </div>
                                <div class="radioItem">
                                    <?php
                                    $check = isset($post['D03_SEISAN']) && $post['D03_SEISAN'] == 2  ? 'checked' : (isset($den_no) && $denpyo['D03_SEISAN'] == 2 ? 'checked' : '');
                                    ?>
                                    <input <?php echo $check; ?> type="radio" name="D03_SEISAN" id="pays3" class="radios" value="2">
                                    <label class="labelRadios <?php echo $check; ?>" for="pays3">クレジット</label>
                                </div>
                                <div class="radioItem">
                                    <?php
                                    $check = isset($post['D03_SEISAN']) && $post['D03_SEISAN'] == 3  ? 'checked' : (isset($den_no) && $denpyo['D03_SEISAN'] == 3 ? 'checked' : '');
                                    ?>
                                    <input <?php echo $check; ?> type="radio" name="D03_SEISAN" id="pays4" class="radios" value="3">
                                    <label class="labelRadios <?php echo $check; ?>" for="pays4">掛</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </section>
            <!-- END SECTION PAYMENT METHOD -->

            <!-- SECTION WORK -->
            <section class="bgContent">
                <fieldset class="fieldsetRegist">
                    <div class="formGroup">
                        <div class="formItem flx-2">
                            <label class="titleLabel">作業内容</label>
                            <div class="checkGroup">
                            <?php
                                $i = 1;
                                foreach($all_works as $k => $v) {
                                    $check = '';
                                    if((isset($post['M01_SAGYO_NO']) && in_array($v['M01_SAGYO_NO'], $post['M01_SAGYO_NO'])) || (isset($den_no) && in_array($v['M01_SAGYO_NO'], $sagyo_used)))
                                    {
                                        $check = 'checked';
                                    }
                            ?>
                                    <div class="checkItem">
                                        <input type="checkbox" name="M01_SAGYO_NO[]" id="workDetai<?= $i ?>"
                                               value="<?= $v['M01_SAGYO_NO'] ?>" class="checks" <?= $check ?> >
                                        <label class="labelChecks <?php echo $check; ?>"
                                               for="workDetail<?= $i ?>"><?= $v['M01_SAGYO_NAMEN'] ?></label>
                                    </div>
                            <?php $i++; }?>
                            </div>
                        </div>
                        <div class="formItem flx-05">
                            <label class="titleLabel">作業者</label>
                            <?php
                            $value = isset($post['D03_TANTO_MEI_D03_TANTO_SEI']) ? $post['D03_TANTO_MEI_D03_TANTO_SEI'] : (isset($den_no) && ($denpyo['D03_TANTO_SEI'] || $denpyo['D03_TANTO_MEI']) ? $denpyo['D03_TANTO_SEI'] . '[]' . $denpyo['D03_TANTO_MEI'] : '');
                            echo \yii\helpers\Html::dropDownList('D03_TANTO_MEI_D03_TANTO_SEI', $value, $tm08_sagyosya['name'], array('class' => 'selectForm D03_TANTO_MEI_D03_TANTO_SEI', 'id' => 'D03_TANTO_MEI_D03_TANTO_SEI', 'style' => 'width: 180px'));
                            ?>
                        </div>
                        <div class="formItem flx-05">
                            <label class="titleLabel">確認者</label>
                            <?php
                            $value = isset($post['D03_KAKUNIN_MEI_D03_KAKUNIN_SEI']) ? $post['D03_KAKUNIN_MEI_D03_KAKUNIN_SEI'] : (isset($den_no) && ($denpyo['D03_KAKUNIN_SEI'] || $denpyo['D03_KAKUNIN_MEI']) ? $denpyo['D03_KAKUNIN_SEI'] . '[]' . $denpyo['D03_KAKUNIN_MEI'] : '');
                            echo \yii\helpers\Html::dropDownList('D03_KAKUNIN_MEI_D03_KAKUNIN_SEI', $value, $tm08_sagyosya['name'], array('class' => 'selectForm D03_KAKUNIN_MEI_D03_KAKUNIN_SEI', 'id' => 'D03_KAKUNIN_MEI_D03_KAKUNIN_SEI', 'style' => 'width: 180px'));
                            ?>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">その他作業内容</label>
                            <textarea maxlength="1000" class="textarea"
                                      name="D03_SAGYO_OTHER"><?= isset($post['D03_SAGYO_OTHER']) ? HTML::encode($post['D03_SAGYO_OTHER']) : (isset($den_no) ? HTML::encode($denpyo['D03_SAGYO_OTHER']) : '') ?></textarea>
                        </div>
                    </div>
                </fieldset>
            </section>
            <!-- END SECTION WORK -->

            <!-- SECTION PRODUCT -->
            <input type="hidden" id="vat" value="<?php echo Yii::$app->params['vat'];?>">
            <section class="bgContent">
                <fieldset class="fieldsetRegist">
                    <a class="addCommodity" href="#">追加</a>
                    <legend class="titleLegend">商品情報</legend>
                    <?php
                        $disabled = '';
                        if(isset($den_no) && file_exists('data/pdf/' . $den_no . '.pdf')) {
                            $disabled = 'disabled';
                        }
                        echo '<input id="check_pdf" name="check_pdf" type="hidden" value="' . $disabled . '">';
                        $i = 1;
                        $showWarranty = false;
                        foreach ($listDenpyoCom as $k => $v) {
                            if (in_array((int) $v['D05_COM_CD'], range(42000, 42999))) {
                                $showWarranty = true;
                                $isTaisa = true;
                            } else {
                                $isTaisa = false;
                            }
                    ?>
                            <div id="commodity<?php echo $i;?>" class="commodityBox product <?php echo $i == 1 || (isset($den_no) && $i <= $totalDenpyoCom) ? 'on' : ''?>" attr-index="<?php echo $i;?>" type="product">
                                <?php if ($i > 1 && ($isTaisa == false || !isset($den_no) || $disabled == '')) {
                                    echo '<a class="removeCommodity" href="#">削除</a>';
                                }
                                ?>
                                <input name="D05_NST_CD<?= $i ?>" id="nstcd<?= $i ?>" type="hidden" value="<?= $v['D05_NST_CD'] ?>"/>
                                <input name="D05_COM_CD<?= $i ?>" id="comcd<?= $i ?>" type="hidden" class="D05_COM_CD" value="<?= $v['D05_COM_CD'] ?>"/>
                                <input name="D05_COM_SEQ<?= $i ?>" id="comseq<?= $i ?>" type="hidden" value="<?= $i ?>">
                                <input name="LIST_NAME[<?= $i ?>]" id="list<?= $i ?>" type="hidden"
                                       value="<?= isset($product_name[$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]) ? $product_name[$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]['M05_COM_NAMEN'] : '' ?>"/>
                                <div class="formGroup">
                                    <div class="formItem">
                                        <label class="titleLabel">商品・荷姿コード
                                            <a class="<?php echo $isTaisa == false || $disabled == '' ? '' : 'no_event';?> btnFormTool large searchGoods openSearchCodeProduct btnBlue" style="cursor: pointer" rel="<?= $i ?>">商品検索</a>
                                        </label>
                                        <input
                                            rel="<?= $i ?>" type="text" name="code_search<?= $i ?>"
                                            id="code_search<?= $i ?>"
                                            maxlength="9"
                                            value="<?= $v['D05_COM_CD'] . $v['D05_NST_CD'] ?>"
                                            class="textForm codeSearchProduct <?php echo ($disabled == 'disabled' && $isTaisa) ? 'no_event' : ''; ?>">
                                    </div>
                                    <div class="formItem">
                                        <label class="titleLabel">品名</label>
                                        <p class="txtValue" id="txtValueName<?= $i ?>">
                                            <?php echo isset($product_name[$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]) ? $product_name[$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]['M05_COM_NAMEN'] : ''; ?>
                                        </p>
                                    </div>
                                    <div class="formItem">
                                        <label class="titleLabel">参考価格</label>
                                        <p class="txtValue" id="txtValuePrice<?= $i ?>">
                                            <?php echo isset($product_name[$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]) ? $product_name[$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]['M05_LIST_PRICE'] : ''; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="formGroup">
                                    <div class="formItem">
                                        <label class="titleLabel">数量</label>
                                        <input maxlength="6" type="text"
                                               value="<?= $v['D05_SURYO'] ? ($v['D05_SURYO'] < 1 ? floatval($v['D05_SURYO']) : $v['D05_SURYO']) : '' ?>"
                                               name="D05_SURYO<?= $i ?>" rel="<?= $i ?>" id="no_<?= $i ?>"
                                               class="textForm noProduct <?php if ($disabled == 'disabled' && $isTaisa) echo 'no_event' ?>">
                                    </div>
                                    <div class="formItem">
                                        <label class="titleLabel">単価</label>
                                        <input maxlength="10" type="text" name="D05_TANKA<?= $i ?>" rel="<?= $i ?>"
                                               id="price_<?= $i ?>"
                                               value="<?= $v['D05_TANKA'] ?>"
                                               class="textForm priceProduct <?php if ($disabled == 'disabled' && $isTaisa) echo 'no_event' ?>">
                                        <span class="txtUnit">円</span>
                                    </div>
                                    <div class="formItem">
                                        <label class="titleLabel">金額</label>
                                        <input maxlength="10" type="text" name="D05_KINGAKU<?= $i ?>" rel="<?= $i ?>"
                                               id="total_<?= $i ?>"
                                               value="<?= $v['D05_KINGAKU']; ?>"
                                               class="textForm totalPriceProduct <?php if ($disabled == 'disabled' && $isTaisa) echo 'no_event' ?>">
                                        <span class="txtUnit">円</span>
                                    </div>
                                </div>
                            </div>
                    <?php
                        $i++;}
                    ?>
                    <div class="formGroup lineTop">
                        <div class="formItem">
                            <label class="titleLabel">POS伝票番号</label>
                            <input type="text" class="textForm" name="D03_POS_DEN_NO" maxlength="50" value="<?php echo isset($den_no) ? $denpyo['D03_POS_DEN_NO'] : '' ?>">
                        </div>
                        <div class="flexRight">
                            <label class="titleLabelTotal">合計金額</label>
                            <p class="txtValue" style="position: relative">
                                <strong class="totalPrice" id="shaken_totalPrice"><?php echo isset($den_no) ? $denpyo['D03_SUM_KINGAKU'] : ''; ?></strong><span
                                    class="txtUnit">円</span>
                                <input maxlength="10" type="hidden" id="shaken_D03_SUM_KINGAKU"
                                       value="<?php echo isset($den_no) ? $denpyo['D03_SUM_KINGAKU'] : ''; ?>" name="D03_SUM_KINGAKU"/>
                            </p>
                        </div>
                    </div>
                </fieldset>
                <?php if(!isset($den_no)) { ?>
                <div class="row text-center mt10 hide" id="puncon_warrantyBox_Box">
                    <div class="checkItem checkSingle">
                        <input id="puncon" type="checkbox" name="puncon" value="1" class="checks">
                        <label class="warrantyBtn" id="puncon_warrantyBox" for="puncon">パンク保証を入力する</label>
                    </div>
                </div>
                <?php } else {
                        if($showWarranty) {
                ?>
                    <div class="row text-center mt10" id="puncon_warrantyBox_Box">
                        <div class="checkItem checkSingle">
                            <input id="puncon" type="checkbox" name="puncon" <?php echo $disabled != '' || $csvExists ? 'checked' : ''; ?> value="1" class="checks">
                            <label for="puncon" class="warrantyBtn <?php echo $disabled != '' || $csvExists ? 'checked' : '';?> <?php echo $disabled != '' ? 'no_event' : '';?>" id="puncon_warrantyBox" >パンク保証を入力する</label>
                        </div>
                    </div>
                <?php } else {?>
                    <div class="row text-center mt10 hide" id="puncon_warrantyBox_Box">
                        <div class="checkItem checkSingle">
                            <input id="puncon" type="checkbox" name="puncon" value="1" class="checks">
                            <label class="warrantyBtn" id="puncon_warrantyBox" for="puncon">パンク保証を入力する</label>
                        </div>
                    </div>
                <?php }}?>
            </section>
            <!-- END SECTION PRODUCT -->

            <!-- SECTION WARRANTY -->
            <?php
                $class_warrantyBox = $disabled != '' || $csvExists ? 'on' : '';
                $class_toggleWarranty = $disabled != '' ? '' : 'toggleWarranty';
                $class_checkWarranty = $disabled != '' ? 'checked no_event' : '';
                $ck_checkWarranty = $disabled != '' ? 'checked' : '';
            ?>
            <input type="hidden" id="warranty_item" value='<?php echo json_encode(Yii::$app->params['items']); ?>'>
            <section id="warrantyBox" class="bgContent <?php echo $class_warrantyBox;?>">
                <fieldset class="fieldsetRegist">
                    <legend class="titleLegend">保証書情報</legend>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">保証書番号</label>
                            <p class="txtValue <?php echo $class_toggleWarranty;?>" id="text_warranty_no">
                                <?php echo $csv['M09_WARRANTY_NO']; ?>
                            </p>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">購入日</label>
                            <p class="txtValue <?php echo $class_toggleWarranty;?>" id="text_warranty_date">
                                <?php if ($csv['M09_INP_DATE'] && $csv['M09_WARRANTY_NO'])
                                    echo Yii::$app->formatter->asDate(date('Y/m/d', strtotime($csv['M09_INP_DATE'])), 'yyyy年MM月dd日');
                                else
                                    echo date('Y年m月d日')
                                ?>
                            </p>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">保証期間</label>
                            <p class="txtValue <?php echo $class_toggleWarranty;?>" id="text_warranty_period">
                                <?php if ($csv['warranty_period'])
                                    echo Yii::$app->formatter->asDate(date('Y/m/d', strtotime($csv['warranty_period'])), 'yyyy年MM月dd日');
                                else
                                    echo date('Y年m月d日', strtotime('+ 6 month'));
                                ?>
                                <input type="hidden" name="M09_WARRANTY_NO" id="M09_WARRANTY_NO" value="<?php echo $csv['M09_WARRANTY_NO']; ?>"/>
                                <input type="hidden" name="M09_INP_DATE" id="M09_INP_DATE" value="<?php echo $csv['M09_INP_DATE'] && $csv['M09_WARRANTY_NO'] ? $csv['M09_INP_DATE'] : date('Y/m/d'); ?>"/>
                                <input type="hidden" name="warranty_period" id="warranty_period"
                                       value="<?php echo $csv['warranty_period'] && $csv['M09_WARRANTY_NO'] ? $csv['warranty_period'] : date('Y/m/d', strtotime('+ 6 month')); ?>"/>
                            </p>
                        </div>

                        <div class="formItem">
                            <div class="checkGroup">
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" name="warranty_check" <?= $ck_checkWarranty ?> value="1" id="inspection_checkWarranty" class="hide">
                                    <label class="inspection_labelSingleCheckBtn <?php echo $class_checkWarranty; ?>" id="checkWarranty_label" for="inspection_checkWarranty">保証書を作成</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="formGroup lineBottom">
                        <div class="formItem flx-05">
                            <label class="titleLabel">取付位置</label>
                        </div>
                        <div class="formItem flx-05">
                            <label class="titleLabel">メーカー</label>
                        </div>
                        <div class="formItem flx-2">
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
                    <?php $warranty_item = ['' => '', 'BS' => 'BS', 'YH' => 'YH', 'DF' => 'DF', 'TY' => 'TY','FK' => 'FK'] ?>
                    <?php for ($i = 1; $i < 7; ++$i) { ?>
                        <div class="formGroup lineBottom">
                            <div class="formItem flx-05">
                                <p class="txtValue">
                                    <?php
                                    if ($i == 1) {
                                        echo '右前';
                                        $name = 'right_front';
                                    } elseif ($i == 2) {
                                        echo '左前';
                                        $name = 'left_front';
                                    } elseif ($i == 3) {
                                        $name = 'right_behind';
                                        echo '右後';
                                    } elseif ($i == 5) {
                                        $name = 'other_a';
                                        echo 'その他A';
                                    } elseif ($i == 6) {
                                        $name = 'other_b';
                                        echo 'その他B';
                                    } else {
                                        $name = 'left_behind';
                                        echo '左後';
                                    }
                                    ?>

                                </p>
                            </div>
                            <div class="formItem flx-05">
                                <?= \yii\helpers\Html::dropDownList($name . '_manu', $csv[$name . '_manu'], $warranty_item, array('class' => 'selectForm select_product', 'id' => $name . '_manu', 'disabled' => !$disabled ? false : true)) ?>
                            </div>
                            <div class="formItem flx-2">
                                <select <?php echo $disabled == '' ? '' : 'disabled'; ?> id="<?php echo $name ?>_product" class="selectForm select_product_second" data-value="<?php echo $csv[$name . '_product'] ?>" name="<?php echo $name ?>_product">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="formItem">
                                <input <?php echo $disabled == '' ? '' : 'disabled'; ?> type="text" value="<?= $csv[$name . '_size'] ?>" class="textForm"
                                       id="<?= $name . '_size' ?>" name="<?= $name . '_size' ?>" style="width:180px" maxlength="9">
                            </div>
                            <div class="formItem">
                                <input <?php echo $disabled == '' ? '' : 'disabled'; ?> type="text" value="<?= $csv[$name . '_serial'] ?>" class="textForm"
                                       id="<?= $name . '_serial' ?>" name="<?= $name . '_serial' ?>" style="width:180px" maxlength="4">
                            </div>
                            <div class="formItem flx-05">
                                <p class="txtValue number_product_p">
                                    <?php echo $csv[$name . '_manu'] != '' ? 1 : ''; ?>
                                </p>
                                <input type="hidden" value="<?php echo $csv[$name . '_manu'] != '' ? 1 : 0; ?>" class="number_product_hidden"
                                       id="<?= $name . '_no' ?>" name="<?= $name . '_no' ?>">
                            </div>
                        </div>
                    <?php } ?>
                </fieldset>
            </section>
            <!-- END SECTION WARRANTY -->

            <!-- SECTION WORK BEFORE INSPECTION -->
            <section class="bgContent">
                <fieldset class="fieldsetRegist">
                    <legend class="titleLegend">作業前点検</legend>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">オイル量</label>
                            <div class="radioGroup">
                                <div class="radioItem">
                                    <input type="radio" value="1" name="oil_check" <?php echo isset($confirm['oil_check']) && $confirm['oil_check'] ? 'checked' : '';?>  class="radios" id="oil_check1">
                                    <label class="labelRadios <?php echo isset($confirm['oil_check']) && $confirm['oil_check'] ? 'checked' : '';?>" for="oil_check1">OK</label>
                                </div>
                                <div class="radioItem">
                                    <input type="radio" value="0" name="oil_check" <?php echo isset($confirm['oil_check']) && $confirm['oil_check'] == '0' ? 'checked' : '';?> class="radios" id="oil_check0">
                                    <label class="labelRadios <?php echo isset($confirm['oil_check']) && $confirm['oil_check'] == '0' ? 'checked' : '';?>" for="oil_check0">NG</label>
                                </div>
                            </div>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">オイル漏れ</label>
                            <div class="radioGroup">
                                <div class="radioItem">
                                    <input type="radio" value="1" name="oil_leak_check" <?php echo isset($confirm['oil_leak_check']) && $confirm['oil_leak_check'] ? 'checked' : '';?> class="radios" id="oil_leak_check1">
                                    <label class="labelRadios <?php echo isset($confirm['oil_leak_check']) && $confirm['oil_leak_check'] ? 'checked' : '';?>" for="oil_leak_check1">OK</label>
                                </div>
                                <div class="radioItem">
                                    <input type="radio" value="0" name="oil_leak_check" <?php echo isset($confirm['oil_leak_check']) && $confirm['oil_leak_check'] == '0' ? 'checked' : '';?> class="radios" id="oil_leak_check0">
                                    <label class="labelRadios <?php echo isset($confirm['oil_leak_check']) && $confirm['oil_leak_check'] == '0' ? 'checked' : '';?>" for="oil_leak_check0">NG</label>
                                </div>
                            </div>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">キャップゲージ</label>
                            <div class="radioGroup">
                                <div class="radioItem">
                                    <input type="radio" value="1" <?php echo isset($confirm['cap_check']) && $confirm['cap_check'] ? 'checked' : '';?> name="cap_check" class="radios" id="cap_check1">
                                    <label class="labelRadios <?php echo isset($confirm['cap_check']) && $confirm['cap_check'] ? 'checked' : '';?>" for="cap_check1">OK</label>
                                </div>
                                <div class="radioItem">
                                    <input type="radio" value="0" <?php echo isset($confirm['cap_check']) && $confirm['cap_check'] == '0' ? 'checked' : '';?> name="cap_check" class="radios" id="cap_check0">
                                    <label class="labelRadios <?php echo isset($confirm['cap_check']) && $confirm['cap_check'] == '0' ? 'checked' : '';?>" for="cap_check0">NG</label>
                                </div>
                            </div>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">ドレンボルト</label>
                            <div class="radioGroup">
                                <div class="radioItem">
                                    <input type="radio" value="1" <?php echo isset($confirm['drain_bolt_check']) && $confirm['drain_bolt_check'] ? 'checked' : '';?> name="drain_bolt_check" class="radios" id="drain_bolt_check1">
                                    <label class="labelRadios <?php echo isset($confirm['drain_bolt_check']) && $confirm['drain_bolt_check'] ? 'checked' : '';?>" for="drain_bolt_check1">OK</label>
                                </div>
                                <div class="radioItem">
                                    <input type="radio" value="0" <?php echo isset($confirm['drain_bolt_check']) && $confirm['drain_bolt_check']== '0' ? 'checked' : '';?> name="drain_bolt_check" class="radios" id="drain_bolt_check0">
                                    <label class="labelRadios <?php echo isset($confirm['drain_bolt_check']) && $confirm['drain_bolt_check'] == '0' ? 'checked' : '';?>" for="drain_bolt_check0">NG</label>
                                </div>
                            </div>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">タイヤ損傷・磨耗</label>
                            <div class="radioGroup">
                                <div class="radioItem">
                                    <input type="radio" value="1" <?php echo isset($confirm['tire_check']) && $confirm['tire_check'] ? 'checked' : '';?> name="tire_check" class="radios" id="tire_check1">
                                    <label class="labelRadios <?php echo isset($confirm['tire_check']) && $confirm['tire_check'] ? 'checked' : '';?>" for="tire_check1">OK</label>
                                </div>
                                <div class="radioItem">
                                    <input type="radio" value="0" <?php echo isset($confirm['tire_check']) && $confirm['tire_check'] == '0' ? 'checked' : '';?> name="tire_check" class="radios" id="tire_check0">
                                    <label class="labelRadios <?php echo isset($confirm['tire_check']) && $confirm['tire_check'] == '0' ? 'checked' : '';?>" for="tire_check0">NG</label>
                                </div>
                            </div>
                        </div>
                        <div class="formItem">
                            <label class="titleLabel">ボルト・ナット</label>
                            <div class="radioGroup">
                                <div class="radioItem">
                                    <input type="radio" value="1" <?php echo isset($confirm['nut_check']) && $confirm['nut_check'] ? 'checked' : '';?> name="nut_check" class="radios" id="nut_check1">
                                    <label class="labelRadios <?php echo isset($confirm['nut_check']) && $confirm['nut_check'] ? 'checked' : '';?>" for="nut_check1">OK</label>
                                </div>
                                <div class="radioItem">
                                    <input type="radio" value="0" <?php echo isset($confirm['nut_check']) && $confirm['nut_check'] == '0' ? 'checked' : '';?> name="nut_check" class="radios" id="nut_check0">
                                    <label class="labelRadios <?php echo isset($confirm['nut_check']) && $confirm['nut_check'] == '0' ? 'checked' : '';?>" for="nut_check0">NG</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">備考</label>
                            <textarea class="textarea" name="note"><?php echo isset($confirm['note']) ? Html::encode($confirm['note']) : ''; ?></textarea>
                        </div>
                    </div>
                </fieldset>
            </section>
            <!-- END SECTION WORK BEFORE INSPECTION -->

            <!-- SECTION WORK AFTER CONFIRM -->
            <section class="bgContent">
                <fieldset class="fieldsetRegist">
                    <legend class="titleLegend">作業終了確認</legend>
                    <table class="tablePrint bgWhite">
                        <tbody><tr>
                            <th rowspan="4">タ<br>イ<br>ヤ</th>
                            <td class="triggerCell">
                                <p class="leftside">リムバルブ</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="rim" value="1" <?php echo isset($confirm['rim']) && $confirm['rim'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['rim']) && $confirm['rim'] ? 'checked' : ''?>">確認</label>
                                </div>
                            </td>
                            <td class="triggerCell"><p class="leftside">ホイルキャップ</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="foil" value="1" <?php echo isset($confirm['foil']) && $confirm['foil'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['foil']) && $confirm['foil'] ? 'checked' : ''?>">取付</label>
                                </div>
                            </td>
                            <th rowspan="4">オ<br>イ<br>ル</th>
                            <td class="triggerCell">
                                <p class="leftside">オイル量</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="oil" value="1" <?php echo isset($confirm['oil']) && $confirm['oil'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['oil']) && $confirm['oil'] ? 'checked' : ''?>">確認</label>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">オイルキャップ</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="oil_cap" value="1" <?php echo isset($confirm['oil_cap']) && $confirm['oil_cap'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['oil_cap']) && $confirm['oil_cap'] ? 'checked' : ''?>">確認</label>
                                </div>
                            </td>
                            <th rowspan="4">バ<br>ッ<br>テ<br>リ<br>｜</th>
                            <td class="triggerCell">
                                <p class="leftside">ターミナル締付</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="terminal" value="1" <?php echo isset($confirm['terminal']) && trim($confirm['terminal']) ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['terminal']) && trim($confirm['terminal']) ? 'checked' : ''?>">確認</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="triggerCell">
                                <p class="leftside">トルクレンチ</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="torque" value="1" <?php echo isset($confirm['torque']) && $confirm['torque'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['torque']) && $confirm['torque'] ? 'checked' : ''?>">締付</label>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">持帰ナット</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="nut" value="1" <?php echo isset($confirm['nut']) && $confirm['nut'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['nut']) && $confirm['nut'] ? 'checked' : ''?>">確認</label>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">レベルゲージ</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="level" value="1" <?php echo isset($confirm['level']) && $confirm['level'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['level']) && $confirm['level'] ? 'checked' : ''?>">確認</label>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">ドレンボルト</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="drain_bolt" value="1" <?php echo isset($confirm['drain_bolt']) && $confirm['drain_bolt'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['drain_bolt']) && $confirm['drain_bolt'] ? 'checked' : ''?>">確認</label>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">ステー取付</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="stay" value="1" <?php echo isset($confirm['stay']) && $confirm['stay'] ? 'checked' : ''?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['stay']) && $confirm['stay'] ? 'checked' : ''?>">確認</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" rowspan="2">
                                <p class="leftside">空気圧</p>
                                <?php
                                $array_number = ['' => ''];
                                for($i = 0; $i < 21; $i ++) {
                                    $array_number[$i] = $i;
                                }
                                $array_aftercomma = ['' => ''];
                                for($i = 0; $i < 10; $i ++) {
                                    $array_aftercomma[$i] = $i;
                                }
                                ?>
                                <div class="areaAirCheck">
                                    <div class="itemPrintAir">
                                        <p class="txtValue">
                                            <span class="txtUnit">前</span>
                                    <span class="spcValue">
                                        <?php
                                        $pressure_1 = isset($confirm['pressure_front']) ? explode('.', $confirm['pressure_front'])[0] : '';
                                        $pressure_2 = isset($confirm['pressure_front']) && isset(explode('.', $confirm['pressure_front'])[1]) ? explode('.', $confirm['pressure_front'])[1] : '';
                                        ?>
                                        <?= \yii\helpers\Html::dropDownList('pressure_front_1', $pressure_1, $array_number) ?>
                                        .
                                        <?= \yii\helpers\Html::dropDownList('pressure_front_2', $pressure_2, $array_aftercomma) ?>
                                    </span>
                                            <span class="txtUnit">kpa</span>
                                        </p>
                                    </div>
                                    <div class="itemPrintAir">
                                        <p class="txtValue">
                                            <span class="txtUnit">後</span>
                                    <span class="spcValue">
                                         <?php
                                         $pressure_1 = isset($confirm['pressure_behind']) ? explode('.', $confirm['pressure_behind'])[0] : '';
                                         $pressure_2 = isset($confirm['pressure_behind']) && isset(explode('.', $confirm['pressure_behind'])[1]) ? explode('.', $confirm['pressure_behind'])[1] : '';
                                         ?>
                                        <?= \yii\helpers\Html::dropDownList('pressure_behind_1', $pressure_1, $array_number) ?>
                                        .
                                        <?= \yii\helpers\Html::dropDownList('pressure_behind_2', $pressure_2, $array_aftercomma) ?>
                                    </span>
                                            <span class="txtUnit">kpa</span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">パッキン</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="packing" value="1" <?php echo isset($confirm['packing']) && $confirm['packing'] ? 'checked' : '' ?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['packing']) && $confirm['packing'] ? 'checked' : '' ?>">確認</label>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">オイル漏れ</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="oil_leak" value="1" <?php echo isset($confirm['oil_leak']) && $confirm['oil_leak'] ? 'checked' : '' ?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['oil_leak']) && $confirm['oil_leak'] ? 'checked' : '' ?>">確認</label>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">バックアップ</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="backup" value="1" <?php echo isset($confirm['backup']) && $confirm['backup'] ? 'checked' : '' ?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['backup']) && $confirm['backup'] ? 'checked' : '' ?>">確認</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p class="leftside">次回交換目安</p>
                                <div class="checkPrint">
                                    <p class="txtValue">
                                        <input type="text" name="date" placeholder="" value="<?php echo isset($confirm['date']) ? $confirm['date'] : ''; ?>" class="textFormConf dateform">
                                        <span class="txtUnit">または、</span>
                                    </p>
                                    <p class="txtValue">
                                        <input type="number" style="width:8em" value="<?php echo isset($confirm['km']) ? $confirm['km'] : ''; ?>" name="km" class="textFormConf">
                                        <span class="txtUnit">km</span>
                                    </p>
                                </div>
                            </td>
                            <td class="triggerCell">
                                <p class="leftside">スタートアップ</p>
                                <div class="checkItem checkSingle">
                                    <input type="checkbox" class="checks" name="startup" value="1" <?php echo isset($confirm['startup']) && $confirm['startup'] ? 'checked' : '' ?>/>
                                    <label class="labelSingleCheckBtn <?php echo isset($confirm['startup']) && $confirm['startup'] ? 'checked' : '' ?>">確認</label>
                                </div>
                            </td>
                        </tr>
                        </tbody></table>
                    <div class="formGroup">
                        <div class="formItem">
                            <label class="titleLabel">備考</label>
                            <textarea class="textarea" name="D03_NOTE"><?= isset($den_no) ? HTML::encode($denpyo['D03_NOTE']) : ''; ?></textarea>
                        </div>
                    </div>
                </fieldset>
            </section>
            <!-- END SECTION WORK AFTER CONFIRM -->

            <!-- SECTION SUGGEST -->
            <section class="bgContent">
                <fieldset class="fieldsetRegist">
                    <a class="addSuggest" href="#">追加</a>
                    <legend class="titleLegend">おすすめ整備</legend>
                    <?php
                    $i = 1;
                    foreach ($suggest['listDenpyoCom'] as $k => $v) {
                        ?>
                        <div id="commodity_suggest<?php echo $i;?>" class="commodityBox product <?php echo $i == 1 || (isset($suggest_den_no) && $i <= $suggest['totalDenpyoCom']) ? 'on' : ''?>" attr-index="<?php echo $i;?>" type="suggest">
                            <?php if ($i > 1) {
                                echo '<a class="removeSuggest" href="#">削除</a>';
                            }
                            ?>
                            <input name="denpyo_suggest[D05_NST_CD<?= $i ?>]" id="nstcd<?= $i ?>" type="hidden" value="<?= $v['D05_NST_CD'] ?>"/>
                            <input name="denpyo_suggest[D05_COM_CD<?= $i ?>]" id="comcd<?= $i ?>" type="hidden" class="D05_COM_CD" value="<?= $v['D05_COM_CD'] ?>"/>
                            <input name="denpyo_suggest[D05_COM_SEQ<?= $i ?>]" id="comseq<?= $i ?>" type="hidden" value="<?= $i ?>">
                            <input name="denpyo_suggest[LIST_NAME][<?= $i ?>]" id="list<?= $i ?>" type="hidden"
                                   value="<?= isset($suggest['product_name'][$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]) ? $suggest['product_name'][$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]['M05_COM_NAMEN'] : '' ?>"/>
                            <div class="formGroup">
                                <div class="formItem">
                                    <label class="titleLabel">商品・荷姿コード
                                        <a class="btnFormTool large searchGoods openSearchCodeProduct btnBlue" style="cursor: pointer" rel="<?= $i ?>">商品検索</a>
                                    </label>
                                    <input
                                        rel="<?= $i ?>" type="text" name="code_search_suggest<?= $i ?>"
                                        id="code_search<?= $i ?>"
                                        maxlength="9"
                                        value="<?= $v['D05_COM_CD'] . $v['D05_NST_CD'] ?>"
                                        class="textForm codeSearchProduct">
                                </div>
                                <div class="formItem">
                                    <label class="titleLabel">品名</label>
                                    <p class="txtValue" id="txtValueName<?= $i ?>">
                                        <?php echo isset($suggest['product_name'][$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]) ? $suggest['product_name'][$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]['M05_COM_NAMEN'] : ''; ?>
                                    </p>
                                </div>
                                <div class="formItem">
                                    <label class="titleLabel">参考価格</label>
                                    <p class="txtValue" id="txtValuePrice<?= $i ?>">
                                        <?php echo isset($suggest['product_name'][$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]) ? $suggest['product_name'][$v['D05_COM_CD'].'_'. $v['D05_NST_CD']]['M05_LIST_PRICE'] : ''; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="formGroup">
                                <div class="formItem">
                                    <label class="titleLabel">数量</label>
                                    <input maxlength="6" type="text"
                                           value="<?= $v['D05_SURYO'] ? ($v['D05_SURYO'] < 1 ? floatval($v['D05_SURYO']) : $v['D05_SURYO']) : '' ?>"
                                           name="denpyo_suggest[D05_SURYO<?= $i ?>]" rel="<?= $i ?>" id="no_<?= $i ?>"
                                           class="textForm noProduct">
                                </div>
                                <div class="formItem">
                                    <label class="titleLabel">単価</label>
                                    <input maxlength="10" type="text" name="denpyo_suggest[D05_TANKA<?= $i ?>]" rel="<?= $i ?>"
                                           id="price_<?= $i ?>"
                                           value="<?= $v['D05_TANKA'] ?>"
                                           class="textForm priceProduct">
                                    <span class="txtUnit">円</span>
                                </div>
                                <div class="formItem">
                                    <label class="titleLabel">金額</label>
                                    <input maxlength="10" type="text" name="denpyo_suggest[D05_KINGAKU<?= $i ?>]" rel="<?= $i ?>"
                                           id="total_<?= $i ?>"
                                           value="<?= $v['D05_KINGAKU']; ?>"
                                           class="textForm totalPriceProduct">
                                    <span class="txtUnit">円</span>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i++;}
                    ?>
                    <div class="formGroup lineTop">
                        <div class="formItem">
                            <label class="titleLabel">POS伝票番号</label>
                            <input type="text" class="textForm" name="denpyo_suggest[D03_POS_DEN_NO]" maxlength="50" value="<?php echo isset($suggest_den_no) ? $suggest['D03_POS_DEN_NO'] : '' ?>">
                        </div>
                        <div class="flexRight">
                            <label class="titleLabelTotal">合計金額</label>
                            <p class="txtValue" style="position: relative">
                                <strong class="totalPrice" id="suggest_totalPrice"><?php echo isset($suggest_den_no) ? $suggest['D03_SUM_KINGAKU'] : ''; ?></strong><span
                                    class="txtUnit">円</span>
                                <input maxlength="10" type="hidden" id="suggest_D03_SUM_KINGAKU"
                                       value="<?php echo isset($suggest_den_no) ? $suggest['D03_SUM_KINGAKU'] : ''; ?>" name="denpyo_suggest[D03_SUM_KINGAKU]"/>
                            </p>
                        </div>
                    </div>
                </fieldset>
            </section>
            <!-- END SECTION SUGGEST -->
        </article>
    </main>
<footer id="footer">
    <div class="toolbar">
        <div class="toolbar-left">
            <?php
                $url_back = isset($den_no) ? \yii\helpers\BaseUrl::base(true).'/shaken/denpyo/detail/'.$den_no : \yii\helpers\BaseUrl::base(true).'/shaken';
            ?>
            <a class="btnBack" href="<?php echo $url_back;?>">戻る</a>
            <div style="width:150px;" class="btnSet">
                <a id="preview" class="btnTool" href="#">作業指示書</a>
            </div>
        </div>
        <div class="toolbar-right">
            <a id="btnRegistWorkSlip" class="btnSubmit cR">登録</a>
        </div>
    </div>
    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
</footer>
</form>

<!-- MODAL CUSTOMER -->
<div class="modal fade in" id="modalEditCustomer">
    <div class="modal-dialog modal-form">
        <form id="modal_customer" novalidate="novalidate">
            <div class="modal-content user_info">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title pull-left">
                        お客様情報登録
                        <span class="note">&#12288;項目を入力してください。<span class="must">*</span>は必須入力項目です。</span>
                    </h4>
                    <div style="margin-right:15px" class="pull-right">
                        <input type="button" class="btnSubmit disabled flRight" id="agreeFormBtn" disabled value="登録する">
                    </div>
                </div>
                <div class="modal-body modal-body">
                    <div id="updateInfo"></div>
                    <section class="bgContent">
                        <input type="hidden" name="D01_KAIIN_CD" id="D01_KAIIN_CD" value="<?php echo $cus_info['D01_KAIIN_CD']; ?>">
                        <input type="hidden" name="D01_CUST_NO" value="<?php echo $cus_info['D01_CUST_NO']; ?>">
                        <fieldset class="fieldsetRegist">
                            <legend class="titleLegend">お客様情報</legend>
                            <div class="formGroup">
                                <div class="formItem">
                                    <label class="titleLabel">お名前<span class="must">*</span></label>
                                    <input type="text" id="autokana-name" name="D01_CUST_NAMEN" class="textForm" value="<?php echo $cus_info['D01_CUST_NAMEN']; ?>" maxlength="22">
                                </div>
                                <div class="formItem">
                                    <label class="titleLabel">フリガナ<span class="must">*</span></label>
                                    <input type="text" id="D01_CUST_NAMEK" class="textForm" name="D01_CUST_NAMEK" value="<?php echo $cus_info['D01_CUST_NAMEK']; ?>" maxlength="30">
                                </div>
                                <div class="formItem">
                                    <label class="titleLabel">掛カード</label>
                                    <input type="text" class="textForm" id="D01_KAKE_CARD_NO" name="D01_KAKE_CARD_NO" value="<?php echo $cus_info['D01_KAKE_CARD_NO']; ?>" maxlength="16">
                                </div>
                            </div>
                            <div class="formGroup">
                                <div class="formItem">
                                    <label class="titleLabel">郵便番号<a href="javascript:void(0)" class="btnFormTool" id="btn_get_address">住所検索</a></label>
                                    <input type="text" id="D01_YUBIN_BANGO" class="textForm" name="D01_YUBIN_BANGO" value="<?php echo $cus_info['D01_YUBIN_BANGO']; ?>" maxlength="7">

                                </div>
                                <div class="formItem flx-2">
                                    <label class="titleLabel">ご住所</label>
                                    <input type="text" class="textForm" id="D01_ADDR" name="D01_ADDR" value="<?php echo $cus_info['D01_ADDR']; ?>" maxlength="35">
                                </div>
                            </div>
                            <div class="formGroup">
                                <div class="formItem">
                                    <label class="titleLabel">電話番号<span class="must">*</span><span class="must txtSub">(どちらか必須)</span></label>
                                    <input type="text" class="textForm" id="D01_TEL_NO" name="D01_TEL_NO" value="<?php echo $cus_info['D01_TEL_NO']; ?>" maxlength="12">
                                </div>
                                <div class="formItem">
                                    <label class="titleLabel">携帯電話番号<span class="must">*</span><span class="must txtSub">(どちらか必須)</span></label>
                                    <input type="text" class="textForm" name="D01_MOBTEL_NO" id="D01_MOBTEL_NO" value="<?php echo $cus_info['D01_MOBTEL_NO']; ?>" maxlength="12">
                                </div>
                                <div class="formItem">
                                </div>
                            </div>
                            <div class="formGroup">
                                <div class="formItem">
                                    <label class="titleLabel">備考</label>
                                    <textarea id="D01_NOTE" name="D01_NOTE" class="textarea" maxlength="1000"><?php echo Html::encode($cus_info['D01_NOTE']); ?></textarea>
                                </div>
                            </div>
                        </fieldset>
                    </section>
                </div>
                <div style="text-align:left" class="modal-footer">
                    <div class="checkItem checkSingle">
                        <input type="checkbox" class="checks" value="1" id="agreeCheck" name="agreeCheck">
                        <label for="agreeCheck" class="confirmCheck user_confirm" id="agreeLabel">
                            プライバシーポリシーに同意する
                        </label>
                    </div>
                    (約款・個人情報は<a href="<?php echo \yii\helpers\BaseUrl::base();?>/img/PrivacyPolicy.pdf" target="_blank" id="pp-btn">こちら</a>で確認ください)
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END MODAL CUSTOMER -->

<!-- MODAL CAR -->
<div class="modal fade in" id="modalEditCar">
    <input type="hidden" id="url_car_api" value="<?php echo Yii::$app->params['api']['car']['url_car'];?>">
    <input type="hidden" id="car_palaces" value='<?php echo json_encode($car_places); ?>'>
    <div class="modal-dialog modal-form">
        <form action="" id="modal_car" novalidate="novalidate">
            <div class="modal-content">
                <div class="modal-header clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title pull-left">車両情報登録</h4>
                    <div style="margin-right:15px" class="pull-right">
                        <a id="updateCar" style="cursor: pointer;" class="btnSubmit">登録する</a>
                    </div>
                </div>
                <div class="modal-body modal-form">
                    <div id="updateCarInfo"></div>
                    <p class="note">項目を入力してください。<span class="must">*</span>は必須入力項目です。</p>
                    <?php
                        $k = 1;
                        foreach($cars as $key => $value) {
                            if ($value['D02_CAR_SEQ']) {
                                $label_delete = '削除';
                                $class = 'accOpen';
                            } else {
                                $class = 'accClose';
                                $label_delete = '追加';
                            }
                    ?>
                            <section class="bgContent accordion dataCar<?= $k ?> <?= $class ?>" id="dataCar<?= $k ?>" rel="<?= $k ?>">
                                <input type="hidden"
                                       value="<?php echo $value['D02_CUST_NO'] ? $value['D02_CUST_NO'] : $cus_info['D01_CUST_NO'] ?>"
                                       name="D02_CUST_NO" id="D02_CUST_NO"/>
                                <input type="hidden" value="<?= $k; ?>" name="D02_CAR_SEQ" id="D02_CAR_SEQ"/>
                                <input type="hidden" value="<?= $value['D02_CAR_NAMEN'] ?>" name="D02_CAR_NAMEN" id="D02_CAR_NAMEN"/>
                                <?php
                                if (isset($value['ApiCar'])) {
                                    $apiFieldCarApi = [];
                                    foreach ($value['ApiCar'] as $key_api => $val_api) {
                                        $apiFieldCarApi[$key_api] = $val_api;
                                    }
                                    echo '<input type="hidden" value="' . base64_encode(json_encode($apiFieldCarApi)) . '"  name="CAR_API_FIELD" id="CAR_API_FIELD" />';
                                } else {
                                    echo '<input type="hidden" value=""  name="CAR_API_FIELD" id="CAR_API_FIELD" />';
                                }
                                ?>
                                <fieldset class="fieldsetRegist">
                                    <div class="accordionHead">
                                        <legend class="titleLegend"><?= $k ?>台目</legend>
                                        <a class="toggleAccordion" id="delete<?= $k ?>"
                                           style="cursor: pointer;"><?= $label_delete ?></a>
                                    </div>
                                    <div class="accordionBody">
                                        <div class="formGroup">
                                            <div class="formItem">
                                                <label class="titleLabel">メーカー<span class="must">*</span></label>
                                                <?php
                                                $api = new Api();
                                                $maker = $api->getListMaker();
                                                $list_maker = [];
                                                $list_grade = ['' => ''];
                                                $list_type = ['' => ''];
                                                $maker_code = 0;
                                                foreach ($maker as $mak) {
                                                    $list_maker[$mak['maker_code']] = $mak['maker'];
                                                }

                                                $makers = array('' => 'メーカーを選択して下さい', '-111' => 'その他');
                                                static $_GENRE_NAMES = array('1' => '----- 国産車 -----', '2' => '----- 輸入車 -----');

                                                foreach ($list_maker as $maker_key => $maker_value) {
                                                    $genre_code = substr($maker_key, 0, 1);
                                                    if ($genre_code > '2') {
                                                        $genre_code = '2';
                                                    }
                                                    $genre_name = $_GENRE_NAMES[$genre_code];
                                                    if (isset($makers[$genre_name]) == false) {
                                                        $makers[$genre_name] = array();
                                                    }
                                                    $makers[$genre_name][$maker_key] = $maker_value;
                                                }

                                                $list_model = ['' => ''];
                                                if ((int)$value['D02_MAKER_CD'] > 0) {
                                                    $model = $api->getListModel($value['D02_MAKER_CD']);
                                                    foreach ($model as $mod) {
                                                        $list_model[$mod['model_code']] = $mod['model'];
                                                    }
                                                }
                                                if ($value['D02_MODEL_CD']) {
                                                    $list_year = ['0' => ''];
                                                    $year = $api->getListYearMonth($value['D02_MAKER_CD'], $value['D02_MODEL_CD']);
                                                    foreach ($year as $y) {
                                                        $list_year[$y['year']] = $y['year'];
                                                    }

                                                    if ($value['D02_SHONENDO_YM']) {
                                                        $type = $api->getListTypeCode($value['D02_MAKER_CD'], $value['D02_MODEL_CD'], substr($value['D02_SHONENDO_YM'], 0, 4));
                                                        foreach ($type as $tp) {
                                                            $list_type[$tp['type_code']] = $tp['type'];
                                                        }
                                                    }

                                                    if ($value['D02_TYPE_CD']) {
                                                        $grade = $api->getListGradeCode($value['D02_MAKER_CD'], $value['D02_MODEL_CD'], substr($value['D02_SHONENDO_YM'], 0, 4), $value['D02_TYPE_CD']);
                                                        foreach ($grade as $gra) {
                                                            $list_grade[$gra['grade_code']] = $gra['grade'];
                                                        }
                                                    }
                                                }
                                                ?>
                                                <?= \yii\helpers\Html::dropDownList('D02_MAKER_CD[' . $k . ']', $value['D02_MAKER_CD'], $makers, array('class' => 'selectForm D02_MAKER_CD', 'id' => 'D02_MAKER_CD', 'rel' => $k)) ?>
                                                <?php
                                                if ($value['D02_MAKER_CD'] == '-111') {
                                                    if (isset($value['ApiCar']['car_carName']))
                                                        echo '<input type="text" id="D02_CAR_NAMEN_OTHER" name="D02_CAR_NAMEN_OTHER[' . $k . ']"' . ' class="textForm D02_CAR_NAMEN_OTHER" value="' . $value['ApiCar']['car_carName'] . '">';
                                                    else
                                                        echo '<input type="text" id="D02_CAR_NAMEN_OTHER" name="D02_CAR_NAMEN_OTHER[' . $k . ']"' . ' class="textForm D02_CAR_NAMEN_OTHER" value="' . $value['D02_CAR_NAMEN'] . '">';
                                                } else
                                                    echo '<input type="text" id="D02_CAR_NAMEN_OTHER"  name="D02_CAR_NAMEN_OTHER[' . $k . ']"' . ' class="textForm D02_CAR_NAMEN_OTHER" value="" style="display:none">';
                                                ?>
                                            </div>
                                            <div class="formItem flx-2">
                                                <label class="titleLabel">車名<span class="must">*</span></label>
                                                <?= \yii\helpers\Html::dropDownList('D02_MODEL_CD[' . $k . ']', $value['D02_MODEL_CD'], $list_model, array('class' => 'selectForm D02_MODEL_CD', 'id' => 'D02_MODEL_CD', 'rel' => $k)) ?>
                                            </div>
                                        </div>
                                        <div class="formGroup">
                                            <div class="formItem">
                                                <label class="titleLabel">初年度登録年月</label>
                                                <input maxlength="6" type="text" id="D02_SHONENDO_YM<?= $k ?>"
                                                       name="D02_SHONENDO_YM[<?= $k ?>]"
                                                       value="<?php echo $value['D02_SHONENDO_YM']; ?>"
                                                       class="textForm D02_SHONENDO_YM ymform">
                                            </div>
                                            <div class="formItem">
                                                <label class="titleLabel">型式</label>
                                                <?= \yii\helpers\Html::dropDownList('D02_TYPE_CD[' . $k . ']', $value['D02_TYPE_CD'], $list_type, array('class' => 'selectForm D02_TYPE_CD', 'id' => 'D02_TYPE_CD', 'rel' => $k)) ?>
                                            </div>
                                            <div class="formItem">
                                                <label class="titleLabel">グレード</label>
                                                <?= \yii\helpers\Html::dropDownList('D02_GRADE_CD[' . $k . ']', $value['D02_GRADE_CD'], $list_grade, array('class' => 'selectForm D02_GRADE_CD', 'id' => 'D02_GRADE_CD', 'rel' => $k)) ?>
                                            </div>
                                        </div>
                                        <div class="formGroup">
                                            <div class="formItem">
                                                <label class="titleLabel">車検満了日</label>
                                                <input maxlength="8" type="text"
                                                       value="<?= $value['D02_JIKAI_SHAKEN_YM'] ?>"
                                                       name="D02_JIKAI_SHAKEN_YM[<?= $k ?>]" id="D02_JIKAI_SHAKEN_YM<?= $k ?>"
                                                       class="textForm dateform D02_JIKAI_SHAKEN_YM">
                                            </div>
                                            <div class="formItem">
                                                <label class="titleLabel">走行距離<span class="must">*</span></label>
                                                <input maxlength="6" type="text" value="<?= $value['D02_METER_KM'] ?>"
                                                       name="D02_METER_KM[<?= $k ?>]" id="D02_METER_KM"
                                                       class="textForm formWidthSM D02_METER_KM">
                                                <span class="txtUnit">km</span></div>
                                            <div class="formItem">
                                                <label class="titleLabel">車検サイクル</label>
                                                <?= \yii\helpers\Html::dropDownList('D02_SYAKEN_CYCLE[' . $k . ']', $value['D02_SYAKEN_CYCLE'], Yii::$app->params['d02SyakenCycle'], array('class' => 'selectForm D02_SYAKEN_CYCLE', 'id' => 'D02_SYAKEN_CYCLE')) ?>
                                                <span class="txtUnit">年</span></div>
                                        </div>
                                        <div class="formGroup">
                                            <div class="formItem flx-05">
                                                <label class="titleLabel">都道府県<span class="must">*</span></label>
                                                <select name="prefecture" class="selectForm" style="width:160px">
                                                    <option value=""></option>
                                                    <?php foreach (Yii::$app->params['car_regions'] as $region => $prefectures) { ?>
                                                        <optgroup label="<?php echo htmlspecialchars($region) ?>">
                                                            <?php foreach (array_keys($prefectures) as $prefecture) { ?>
                                                                <option value="<?php echo htmlspecialchars($prefecture) ?>"><?php echo htmlspecialchars($prefecture) ?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="formItem flx-05">
                                                <label class="titleLabel">運輸支局<span class="must">*</span></label>
                                                <select id="D02_RIKUUN_NAMEN" name="D02_RIKUUN_NAMEN[<?= $k ?>]" class="selectForm D02_RIKUUN_NAMEN">
                                                    <option value=""></option>
                                                    <?php
                                                        if($value['D02_RIKUUN_NAMEN']) {
                                                    ?>
                                                        <option value="<?= $value['D02_RIKUUN_NAMEN'] ?>" selected><?= $value['D02_RIKUUN_NAMEN'] ?></option>
                                                    <?php
                                                        }
                                                    ?>

                                                </select>
                                            </div>
                                            <div class="formItem flx-05">
                                                <label class="titleLabel">分類コード<span class="must">*</span></label>
                                                <input maxlength="3" type="text" value="<?= $value['D02_CAR_ID'] ?>"
                                                       name="D02_CAR_ID[<?= $k ?>]"
                                                       id="D02_CAR_ID" class="textForm formWidthXS D02_CAR_ID">
                                            </div>
                                            <div class="formItem flx-05">
                                                <label class="titleLabel">ひらがな<span class="must">*</span></label>
                                                <input maxlength="1" type="text" value="<?= $value['D02_HIRA'] ?>"
                                                       name="D02_HIRA[<?= $k ?>]"
                                                       id="D02_HIRA" class="textForm formWidthXXS D02_HIRA">
                                            </div>
                                            <div class="formItem">
                                                <label class="titleLabel">登録番号<span class="must">*</span></label>
                                                <input maxlength="4" type="text" value="<?= $value['D02_CAR_NO'] ?>"
                                                       name="D02_CAR_NO[<?= $k ?>]"
                                                       id="D02_CAR_NO" class="textForm formWidthXS D02_CAR_NO">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </section>

                    <?php
                        $k++;
                        }
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END MODAL CAR -->

<!-- MODAL PRODUCT -->
<div id="modalCodeSearch" class="modal fade">
    <input type="hidden" value="" id="index_modalCodeSearch">
    <input type="hidden" value="" id="type_modalCodeSearch">
    <input type="hidden" value="" id="condition">
    <div class="modal-dialog widthS">
        <div class="modal-content">
            <div class="modal-body">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <form id="codeSearchForm" class="formSearchList" action="">
                    <section class="bgContent">
                        <div class="formGroup">
                            <div class="formItem flexHorizontal">
                                <label class="titleLabel">商品コード</label>
                                <div class="itemFlex pl10">
                                    <input id="code_search_value" type="text" value="" class="textForm"/>
                                    <a id="code_search_btn" class="btnFormTool" href="#">検索する</a>
                                </div>
                            </div>
                        </div>

                        <div class="formGroup">
                            <div class="formItem flexHorizontal">
                                <label class="titleLabel">カテゴリ&#12288;</label>
                                <div class="radioGroup itemFlex pl10">
                                    <?php
                                    $a_search = [
                                        '1' => 'タイヤ',
                                        '2' => 'オイル',
                                        '3' => 'バッテリー',
                                        '4' => 'コーティング',
                                        '5' => 'リペア',
                                        '6' => '車検',
                                        '8' => '作業',
                                        '7' => 'その他',
                                    ];
                                    foreach($a_search as $key => $val)
                                    {
                                        echo '<div class="radioItem">
										<input type="radio" name="search_M05_KIND_DM_NO" value="'.$key.'" id="search_M05_KIND_DM_NO'.$key.'" class="checks">
										<label class="labelChecksSearch inspection_labelRadios kind_dm_no_search clickSearch" id="labelSearch_M05_KIND_DM_NO'.$key.'" rel="'.$key.'" for="search_M05_KIND_DM_NO'.$key.'" rel="'.$key.'">'.$val.'</label>
										</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
                <table class="tableList">
                    <tbody>
                    <tr>
                        <th>商品コード</th>
                        <th>荷姿コード</th>
                        <th>品名</th>
                    </tr>
                    <?php foreach ($products as $product) { ?>
                        <tr data-item="<?= $product['M05_COM_CD'] . $product['M05_NST_CD'] ?>,<?= (int) $product['M05_COM_CD'] ?>">
                            <td><?= $product['M05_COM_CD'] ?></td>
                            <td><?= $product['M05_NST_CD'] ?></td>
                            <td><?= $product['M05_COM_NAMEN'] ?></td>
                            <input type="hidden" value="<?= $product['M05_COM_NAMEN'] ?>"
                                   id="name<?= $product['M05_COM_CD'] . $product['M05_NST_CD'] ?>"/>
                            <input type="hidden" value="<?= $product['M05_LIST_PRICE'] ?>"
                                   id="price<?= $product['M05_COM_CD'] . $product['M05_NST_CD'] ?>"/>
                            <input type="hidden" value="<?= $product['M05_KIND_COM_NO'] ?>"
                                   id="kind<?= $product['M05_COM_CD'] . $product['M05_NST_CD'] ?>"/>
                            <input type="hidden" value="<?= $product['M05_LARGE_COM_NO'] ?>"
                                   id="large<?= $product['M05_COM_CD'] . $product['M05_NST_CD'] ?>"/>
                            <input type="hidden" value="<?= $product['M05_COM_CD'] ?>"
                                   id="comcd<?= $product['M05_COM_CD'] . $product['M05_NST_CD'] ?>"/>
                            <input type="hidden" value="<?= $product['M05_NST_CD'] ?>"
                                   id="nstcd<?= $product['M05_COM_CD'] . $product['M05_NST_CD'] ?>"/>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <nav class="paging">

                    <?php
                    echo yii\widgets\LinkPager::widget([
                        'pagination' => $product_pagination,
                        'nextPageLabel' => '&gt;',
                        'prevPageLabel' => '&lt;',
                        'firstPageLabel' => '&laquo;',
                        'lastPageLabel' => '&raquo;',
                    ])
                    ?>

                </nav>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL PRODUCT -->

<!-- MODAL CONFIRM SUBMIT -->
<?php
    $action = isset($den_no) ? \yii\helpers\BaseUrl::base(true) . '/shaken/denpyo/edit/' . $den_no : \yii\helpers\BaseUrl::base(true) . '/shaken/denpyo';
?>
<input type="hidden" id="url_action" value="<?= $action ?>">
<div id="modalRegistConfirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">作業伝票作成</h4>
            </div>
            <div class="modal-body">
                <p class="note">入力した内容で作業伝票を作成します。よろしいですか？</p>
            </div>
            <div class="modal-footer">
                <a aria-label="Close" data-dismiss="modal" class="btnCancel flLeft" href="#">いいえ</a>
                <a class="btnSubmit cR flRight btnSubmitDenpyo" style="cursor: pointer">はい</a></div>
        </div>
    </div>
</div>
<!-- END MODAL CONFIRM SUBMIT -->