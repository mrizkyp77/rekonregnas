<?php

return [
    'adminEmail' => 'mrizkyp77@gmail.com',
     'urlRules' => [
        '' => 'site/index',
        'login/' => 'site/login',
        '<controller:[\w-]+>/<action:\w+>' => '<controller>/<action>',
        '<controller:[\w-]+>/<id:\d+>' => '<controller>/view',
        '<controller:[\w-]+>/create' => '<controller>/create',
        '<controller:[\w-]+>/update/<id:\d+>' => '<controller>/update',
        '<controller:[\w-]+>/delete/<id:\d+>' => '<controller>/delete',
        '<controller:[\w-]+>/bulk-delete' => '<controller>/bulk-delete',
    ]
];
