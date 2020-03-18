<?php

$params = require(__DIR__ . '/params.php');
$basePath = dirname(__DIR__);
$webroot = dirname($basePath);
define('SITE_PATH', 'http://localhost/hangvip');
define('EMAIL_SERVER', 'vae@demandvi.com');

$config = [
    'id' => 'app',
    'basePath' => $basePath,
    'bootstrap' => ['log'],
    'language' => 'en',
    //'sourceLanguage'=>'00',
    'runtimePath' => $webroot . '/runtime',
    'vendorPath' => $webroot . '/vendor',
    'modules' => [
        'user' => [
            'class' => 'amnah\yii2\user\Module',
        // set custom module properties here ...
        ],
    ],
    'components' => [
        //translate
        'i18n' => [
        'translations' => [
            'app' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'fileMap' => [
                    'app' =>'app.php'
                    ],
                ],
            ],
        ],
//       
        'cart' => [
            'class' => 'yz\shoppingcart\ShoppingCart',
        ],
        'ipAdd' => [
            'class' => 'app\components\iplocation\iplocation',
        ],
        /*'geoip' => [
            'class' => 'app\components\CGeoIP',
            'filename' => dirname(__DIR__) . '/components/GeoIP/GeoLiteCity.dat', // specify filename location for the corresponding database
            'mode' => 'STANDARD', // Choose MEMORY_CACHE or STANDARD mode
        ],*/
		'geoip' => [
            'class' => 'dpodium\yii2\geoip\components\CGeoIP',
        ],
        'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
            // uncomment this to use streams in safe_mode
            //'useStreamsFallback' => true,
            ],
            'services' => [ // You can change the providers and their classes.
                   'facebook' => [
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'clientId' => '409301182573688',
                    'clientSecret' => '1e70e51e1eedd2d1ed12fae6ef0bafbb',
                    'title' => 'Login Facebook',
                ],
                'google_oauth' => [
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'clientId' => '766372197314-mfuselip2fuamu17iq5kq4i4rejopjjv.apps.googleusercontent.com',
                    'clientSecret' => 'RqcxIy2N4ZGrIcVJ_ldFLUHu',
                    'title' => 'Login Google',
                ],             
                'yahoo' => [
                    'class' => 'nodge\eauth\services\YahooOpenIDService',
                    'title' => 'Login Yahoo',
                //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ],
            ],
        ],
        'users' => [
            'class' => 'amnah\yii2\user\components\Users',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mMm1cCQ8Xuahcsdubx8k',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 's1.vnetbank.com',
                'username' => 'smtp@vnsupermark.com',
                'password' => 'M@ilServer2015!',
                'port' => '465',
                'encryption' => 'ssl', // It is often used, check your provider or mail server specs
            ],
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'urlManager' => [
//            'class' => 'codemix\localeurls\UrlManager',
//            'languages' => ['en', 'it', 'fr', 'de', 'es'], // List all supported languages here
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
            'rules' => [
                '' =>'site/index',
                'site/login/<service:google_oauth|facebook|yahoo>' => 'site/login',
                'dang-nhap.html' => 'user/login',
                'don-hang.html' => 'user/order',
                'cap-nhat-tai-khoan.html' => 'user/account',
                'logout.html' => 'user/logout',
                'dang-ky.html' => 'user/register',
                'cap-nhat-gio-hang.html' => 'shopcart/update',
                'thanh-toan.html' => 'shopcart/index',
		'gioi-thieu.html' => 'gioithieu/index',
				
		'tin-tuc.html' => 'tintuc/index',
		'detail-news/<slug>' => 'tintuc/view',
				
                '<slug:[\w-]+>.htm' => 'the/cat',
                '<slug>.html' => 'the/chitiet',           
                '<controller:\w+>/view/<slug:[\w-]+>.html' => '<controller>/view',
                '<controller:\w+>/chi-tiet/<slug:[\w-]+>.html' => '<controller>/chitiet',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/cat/<slug:[\w-]+>' => '<controller>/cat',
            ],
        ],
        'i18n' => array(
            'translations' => array(
                'eauth' => array(
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ),
            ),
        ),
        'assetManager' => [
            // uncomment the following line if you want to auto update your assets (unix hosting only)
            'linkAssets' => false,
            //'forceCopy' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [YII_DEBUG ? 'jquery.js' : 'jquery.min.js'],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css'],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js'],
                ],
            ],
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
        'db1' => require(__DIR__ . '/db1.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';

    $config['components']['db']['enableSchemaCache'] = true;
}

//return array_merge_recursive($config, require($_SERVER['DOCUMENT_ROOT'] . '/vendor/noumo/easyii/config/easyii.php'));

return array_merge_recursive($config, require($webroot.'/vendor/noumo/easyii/config/easyii.php'));

