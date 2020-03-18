<?php

use kartik\sidenav\SideNav;
use yii\easyii\modules\shopcart\api\Shopcart;
use yii\easyii\modules\subscribe\api\Subscribe;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;

$goodsCount = count(Shopcart::goods());
?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
<div id="wrapper" class="container">
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-menu">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= Url::home() ?>">Easyii shop</a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-menu">
                    <?=
                    Menu::widget([
                        'options' => ['class' => 'nav navbar-nav'],
                        'items' => [
                            ['label' => 'Home', 'url' => ['/site/index']],
                            ['label' => 'Shop', 'url' => ['/shop/index']],
                            ['label' => 'News', 'url' => ['/news/index']],
                            ['label' => 'Articles', 'url' => ['/articles/index']],
                            ['label' => 'Gallery', 'url' => ['/gallery/index']],
                            ['label' => 'Guestbook', 'url' => ['/guestbook/index']],
                            ['label' => 'FAQ', 'url' => ['/faq/index']],
                            ['label' => 'Contact', 'url' => ['/contact/index']],
                            ['label' => 'User', 'url' => ['/user']],
                            Yii::$app->users->isGuest ?
                                    ['label' => 'Login', 'url' => ['/user/login']] :
                                    ['label' => 'Logout (' . Yii::$app->users->displayName . ')',
                                'url' => ['/user/logout'],
                                'linkOptions' => ['data-method' => 'post']],
                        ],
                    ]);
                    ?>
                    <a href="<?= Url::to(['/shopcart']) ?>" class="btn btn-default navbar-btn navbar-right" title="Complete order">
                        <i class="glyphicon glyphicon-shopping-cart"></i>
                        <?php if ($goodsCount > 0) : ?>
                            <?= $goodsCount ?> <?= $goodsCount > 1 ? 'items' : 'item' ?> - <?= Shopcart::cost() .Yii::$app->session->get('notation')?>
                        <?php else : ?>
                            <span class="text-muted">empty</span>
                        <?php endif; ?>
                    </a>

                </div>
            </div>
        </nav>
    </header>
    <main>
        <?php if ($this->context->id != 'site') : ?>
            <br/>
            <?=
            Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ])
            ?>
        <?php endif; ?>
        <div class="main">    
            <div class="row">
                <?php
                
                if(Yii::$app->users->id){?>
                <div class="col-md-3">
                    <?php
                    echo SideNav::widget([
                        'type' => SideNav::TYPE_DEFAULT,
                        'heading' => 'Options',
                        'items' => [
                            [
                                'url' => '/user/account',
                                'label' => 'Acount',
                                'icon' => 'question-sign'
                            ],
                            [
                                'url' => '/user/order',
                                'label' => 'Order',
                                'icon' => 'question-sign',
                            ],

//                            [
//                                'label' => 'Help',
//                                'icon' => 'question-sign',
//                                'items' => [
//                                    ['label' => 'About', 'icon' => 'info-sign', 'url' => '#'],
//                                    ['label' => 'Contact', 'icon' => 'phone', 'url' => '#'],
//                                ],
//                            ],
                        ],
                    ]);
                    ?>
                </div>    
                <?php }?>
                <div class="col-md-9">
                    <?= $content ?>
                </div>
            </div>
        </div>
        <div class="push"></div>
    </main>
</div>
<footer>
    <div class="container footer-content">
        <div class="col-md-12">
            <p>Copyright 2010 - Gamecard.vn - a product of VAE Group. Website: <a target="_blank" class="color" href="#">Demandvi.com</a></p>
            <p>Chuyên phân phối 
                <a class="color" href="#"> thẻ game online</a> | 
                <a class="color" href="#">Zing xu</a> | 
                <a class="color" href="#">Gate</a> | 
                <a class="color" href="#">Garena</a> | 
                <a class="color" href="#">zing card</a> | 
                <a class="color" href="#">Oncash</a> | 
                <a class="color" href="#">@cash</a> | 
                <a class="color" href="#">Vcoin</a> |
                <a class="color" href="#">Thẻ điện thoại</a>         
            </p>
            <p>Thanh toán Trực tuyến với Paypal, Visa, master card. Bán hàng cho cộng đồng người Việt tại nước ngoài</p>
        </div>
    </div>
</footer>
<?php $this->endContent(); ?>
