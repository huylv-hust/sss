<script src="<?php echo \yii\helpers\BaseUrl::base() ?>/js/module/inspection/discount.js"></script>
<main id="contents">
    <section class="readme">
        <h2 class="titleContent"> 割引・割増料金登録・編集</h2>
    </section>
    <div class="container">
        <div class="row">
            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-danger col-md-12">
                    <?php echo Yii::$app->session->getFlash('success') ?>
                    <button data-dismiss="alert" class="close">×</button>
                </div>
            <?php }
            if (Yii::$app->session->hasFlash('error')) { ?>
                <div class="alert alert-danger col-md-12">
                    <?php echo Yii::$app->session->getFlash('error') ?>
                    <button data-dismiss="alert" class="close">×</button>
                </div>
            <?php } ?>

            <?php
            $request = Yii::$app->request;
            $id = $request->get('id');
            if ($request->isPost) {
                $discount = $request->post('discount');
                $package = $request->post('package');
                $parent = $request->post('parent');
            }
            if (!$id && !$request->isPost) {
                $discount[0][0] = ['VALUE' => '', 'DESCRIPTION' => '', 'ID' => 0];
                $package[0]['NAME'] = '';
                $package[0]['ID'] = 0;
            }
            ?>

            <form class="form-horizontal col-dm-12" method="post" id="fee_form">
                <div class="form-group gray">
                    <div class="col-sm-10">
                        <div class="form-group booking-div-form">
                            <label class="col-sm-2 font-normal">分類名</label>
                            <div class="col-sm-9 ">
                                <input class="form-control textForm booking-input" type="text" name="parent[NAME]"
                                       value="<?php echo(isset($parent['NAME']) ? \yii\helpers\Html::encode($parent['NAME']) : '') ?>"
                                       maxlength="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="vmiddle form-group">
                    <div class="col-sm-10 title_discount">
                        <?php foreach ($package as $k => $v) { ?>
                            <div class="booking_title_discount" attr-index="<?php echo $k; ?>" style="margin-top: 10px">
                                <div class="form-group gray booking-div-form">
                                    <label class="col-sm-2">割引・割増名</label>
                                    <div class="col-sm-9">
                                        <input class="form-control textForm booking-input package" type="text"
                                               name="package[<?php echo $k; ?>][NAME]"
                                               value="<?php echo \yii\helpers\Html::encode($v['NAME']); ?>"
                                               maxlength="100">
                                        <input type="hidden" class="package_id" name="package[<?php echo $k; ?>][ID]"
                                               value="<?php echo $v['ID']; ?>">
                                    </div>
                                    <div class="col-sm-1">
                                        <?php
                                        if (!in_array($v['ID'], $package_used)) {
                                            ?>
                                            <button type="button" class="btn btn-sm btn-danger remove_title_discount"
                                                    style="float: right"><i class="glyphicon glyphicon-remove"></i></button>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group booking-div-form">
                                    <label class="col-sm-2 font-normal">割引・割増金額</label>
                                    <div class="col-sm-10">
                                        <div class="row vmiddle">
                                            <div class="col-sm-11 discount">
                                                <?php foreach ($discount[$k] as $key => $value) { ?>
                                                    <div class="row booking-row"
                                                         attr-index="<?php echo $value['ID']; ?>"
                                                         style="margin-bottom: 0">
                                                        <div class="col-sm-6">
                                                            <label class="col-sm-3 font-normal">説明</label>
                                                            <input
                                                                class="form-control textForm booking-input-200 description"
                                                                type="text"
                                                                name="discount[<?php echo $k; ?>][<?php echo $value['ID']; ?>][DESCRIPTION]"
                                                                value="<?php echo \yii\bootstrap\Html::encode($value['DESCRIPTION']) ?>"
                                                                maxlength="250">
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <label class="col-sm-3 font-normal">金額</label>
                                                            <input
                                                                class="col-sm-8 form-control textForm booking-input-200 value number_han"
                                                                type="text"
                                                                name="discount[<?php echo $k;; ?>][<?php echo $value['ID']; ?>][VALUE]"
                                                                value="<?php echo $value['VALUE'] ?>" maxlength="11">
                                                            <span class="col-sm-1">円</span>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <?php
                                                            if (!in_array($value['ID'], $discount_used)) {
                                                                ?>
                                                                <button
                                                                    class="btn btn-danger btn-sm booking-btn-remove remove_discount"
                                                                    type="button"><i
                                                                        class="glyphicon glyphicon-minus"></i>
                                                                </button>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <input type="hidden" class="discount_id"
                                                               name="discount[<?php echo $k; ?>][<?php echo $value['ID']; ?>][ID]"
                                                               value="<?php echo $value['ID'] ?>">
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="col-sm-1">
                                                <!-- thêm thằng con vl comment tieng viet-->
                                                <button class="btn btn-sm btn-success add_discount" type="button"
                                                        style="float: right"><i class="glyphicon glyphicon-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-sm-2">
                        <!-- thêm thằng cha-->
                        <button class="btn btn-success add_title_discount" type="button"><i
                                class="glyphicon glyphicon-plus"></i></button>
                    </div>
                </div>
                <footer id="footer">
                    <div class="toolbar">
                        <div class="toolbar-left">
                            <a href="<?php echo Yii::$app->session->get('url_list_discount') ?>" class="btnBack">戻る</a>
                        </div>
                        <div class="toolbar-right">
                            <button href="edit-staff" class="btnSubmit"
                                    type="submit"><?php echo $id ? '更新' : '登録' ?></button>
                        </div>
                    </div>
                    <p class="copyright">Copyright(C) Usami Koyu Corp. All Rights Reserved.</p>
                </footer>
                <input type="hidden" class="package_remove" name="package_remove" value="0">
                <input type="hidden" class="discount_remove" name="discount_remove" value="0">
            </form>
        </div>
    </div>
</main>