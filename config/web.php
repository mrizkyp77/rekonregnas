<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        // enter optional module parameters below - only if you need to  
        // use your own export download action or custom translation 
        // message source
         'downloadAction' => 'gridview/export/download',
         'i18n' => []
            ]
    ],      
    
    ////layout sebelum render
    'on beforeRequest' => function ($event) {
     if (!Yii::$app->user->isGuest){
        if (Yii::$app->user->identity->akses === 1){
         Yii::$app->layout = 'knpr-main';
         Yii::$app->name = 'SISKONPRE KNPR';
        } else if (Yii::$app->user->identity->akses === 2) {
         Yii::$app->layout = 'prov-main';   
         Yii::$app->name = 'SISKONPRE Provinsi';
        } else if (Yii::$app->user->identity->akses === 3) {
         Yii::$app->layout = 'multiregional-main';  
         Yii::$app->name = 'SISKONPRE MulReg';
        }
     }
    },
      
    'components' => [
        'request' => [
            //Sesuaikan dengan yang ada di web hosting
//            'hostInfo' => 'https://localhost/project_browser/html/cp.host4hope.com',
//            'baseUrl' => '/project_browser/html/cp.host4hope.com',
//            'scriptUrl' => 'index-local.php',
            
            'cookieValidationKey' => 'perdana14',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
			'enableSession' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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

        'db' => require(__DIR__ . '/db.php'),
        
        'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
        ],
    ],
        
    ],
//    'as access' => [
//        'class' => \yii\filters\AccessControl::className(),//AccessControl::className(),
//        'rules' => [
//            [
//                'actions' => ['login', 'error'],
//                'allow' => true,
//            ],
//            [
//                'actions' => ['logout', 'index'], // add all actions to take guest to login page
//                'allow' => true,
//                'roles' => ['@'],
//            ],
//        ],
//    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
