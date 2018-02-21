<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'maintenance' => [
            'class' => 'backend\modules\maintenance\Maintenance',
        ],
        'registworkslip' => [
            'class' => 'backend\modules\registworkslip\RegistWorkslip',
        ],
        'listworkslip' => [
            'class' => 'backend\modules\listworkslip\ListWorkslip',
        ],
        'usappynumberchange' => [
            'class' => 'backend\modules\usappynumberchange\UsappyNumberChange',
        ],
        'pdf' => [
            'class' => 'backend\modules\pdf\Pdf',
        ],
        'admin' => [
            'class' => 'backend\modules\admin\Admin',
        ],
        'inspection' => [
            'class' => 'backend\modules\inspection\Inspection',
        ],
    ],
    'components' => [
        'request'=> [
            'enableCsrfValidation'=>false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
           'errorAction' => 'user/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/login'       => 'user/login',
                '/operator/login' => 'user/loginadmin',
                '/timeout'   => 'user/timeout',
                '/menu'        => 'site/index',
                '/maintenance' => 'maintenance/default/index',
                '/list-staff' => 'maintenance/staff/index',
                '/regist-staff' => 'maintenance/staff/staff',
                '/edit-staff' => 'maintenance/staff/staff',
                '/update-commodity' => 'maintenance/commodity/index',
                '/regist-workslip' => 'registworkslip/default/index',
                '/detail-workslip' => 'listworkslip/detail/index',
                '/list-workslip' => 'listworkslip/default/index',
                '/usappy-number-change' => 'usappynumberchange/default/index',
                '/usappy-number-change-confirm' => 'usappynumberchange/default/confirm',
                '/usappy-number-change-complete' => 'usappynumberchange/default/complete',
                '/exportpdf' => 'usappynumberchange/default/pdf',
                '/operator/punc' => 'pdf/zipfile/index',
                '/testapi' => 'usappynumberchange/default/testapi',
                '/preview' => 'listworkslip/detail/preview',
                '/preview2' => 'registworkslip/preview/index',
                '/operator/changepass' => 'user/changepass',
                '/admin/login' => 'user/adminlogin',
                '/shaken' => 'inspection/default/index',
                '/shaken/denpyo' => 'inspection/denpyo/create',
                '/shaken/denpyo/list' => 'inspection/denpyo-detail/list',
                '/shaken/denpyo/detail/<id:\d+>' => 'inspection/denpyo-detail/detail',
                '/shaken/denpyo/detail/sign' => 'inspection/denpyo-detail/sign',
                '/shaken/denpyo/detail/remove' => 'inspection/denpyo-detail/remove',
                '/shaken/denpyo/detail/status' => 'inspection/denpyo-detail/status',
                '/shaken/denpyo/detail/updatestatus' => 'inspection/denpyo-detail/updatestatus',
                '/fee' => 'inspection/fee/index',
                '/fee-basic/list' => 'inspection/fee-basic/list',
                '/fee-basic' => 'inspection/fee-basic/index',
                '/fee-basic/remove' => 'inspection/fee-basic/remove',
                '/fee-registration/list' => 'inspection/fee-registration/list',
                '/fee-registration' => 'inspection/fee-registration/index',
                '/fee-registration/remove' => 'inspection/fee-registration/remove',
                '/discount/list' =>'inspection/discount/list',
                '/discount' =>'inspection/discount/index',
                '/discount/detail/<id:\d+>' =>'inspection/discount/detail',
                '/discount/remove' =>'inspection/discount/remove',
                '/comment' => 'inspection/comment/index',
                '/comment/list' => 'inspection/comment/list',
                '/comment/delete' => 'inspection/comment/delete',
                '/car-size/change' => 'inspection/denpyo/carsize',
                '/shaken/denpyo/edit/<id:\d+>' => 'inspection/denpyo/edit',
                '/shaken/preview/osusume/<id:\d+>' => 'inspection/preview/osusume',
                '/shaken/preview/seisan/<id:\d+>' => 'inspection/preview/seisan',
                '/shaken/preview/<id:\d+>' => 'inspection/preview/index',
                '/shaken/denpyo/preview/osusume' => 'inspection/denpyo-preview/osusume',
                '/shaken/denpyo/preview/seisan' => 'inspection/denpyo-preview/seisan',
                '/shaken/denpyo/preview' => 'inspection/denpyo-preview/index'
            ]
        ],
    ],
    'params' => $params,
];
