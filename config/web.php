<?php

// arquivo de configuração principal do sistema
// feito para o trabalho da 5ª fase de CC

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'farmacia-sistema',
    'name' => 'Sistema de Farmácia - 5ª Fase CC',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'pt-BR',
    'timeZone' => 'America/Sao_Paulo',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // chave para validar cookies - importante para segurança
            'cookieValidationKey' => 'chave_secreta_do_sistema_farmacia_2024',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // regras de URL amigável
                'produto/<id:\d+>' => 'produto/view',
                'categoria/<id:\d+>' => 'categoria/view',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'America/Sao_Paulo',
            'currencyCode' => 'BRL',
            'numberFormatterSymbols' => [
                \NumberFormatter::CURRENCY_SYMBOL => 'R$ ',
            ],
        ],
    ],
    'params' => $params,
];

// configurações para desenvolvimento
if (YII_ENV_DEV) {
    // debug - para ver erros e informações
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    // gii - gerador de código
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
