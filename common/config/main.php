<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'oci:dbname=//aspdb01:1521/USAMIUP;charset=al32utf8',
            'username' => 'asweb',
            'password' => 'asweb_pass_00',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
    ],
];
