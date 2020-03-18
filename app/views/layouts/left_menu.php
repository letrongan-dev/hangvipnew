<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\helpers\Image;
use yii\easyii\modules\event\api\Event;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\modules\gallery\api\Gallery;
use yii\easyii\modules\article\api\Article;
use yii\easyii\modules\customerreviews\api\CustomerReviews as Customer;
use yii\easyii\modules\gxcuserchecklistcount\models\Gxcuserchecklistcount;
use yii\easyii\modules\gxcuserchecklist\models\Gxcuserchecklist;
use yii\easyii\modules\gxcuserdoiqua\models\Gxcuserdoiqua;

$addToCartForm = new \app\models\AddToCartForm();

$controllernow = Yii::$app->controller->id;
$goodsCount = \Yii::$app->cart->getCount();
$model = Yii::$app->getModule("user")->model("LoginForm");
$asset = app\assets\AppAsset::register($this);
$withoutLg = Url::current(['lg' => NULL], TRUE);
$end_url = end((explode('/', Yii::$app->request->getUrl())));
$chekurl_array = array('login', 'register', 'shopcart', 'account', 'order', 'forgot');

if (!isset(Yii::$app->users->id)) {
    ?>

    <div class="col-sm-12 col-xs-6 oder-view">        
        <?php if ($end_url <> 'login' and $end_url <> 'register') { ?>
            <img alt="thanh toán" src="<?= $asset->baseUrl ?>/images/Shopping_cart.png" style="margin-left:5px;" />        

            (<?= $goodsCount ?>) Item 
            <span style="color:#333;">$</span>
            <?= formatprice(\Yii::$app->cart->getCost()) ?> VND
            <br/><br/>
            <a href="<?= SITE_PATH ?>/shopcart">Thanh toán</a>
        <?php } else { ?>

        <?php } ?>
    </div>

    <?php if ($end_url <> 'login' and $end_url <> 'register') { ?>

    <?php } else { ?>

        <div id="logineauth" class="col-sm-12 col-xs-6 user-area">
            <img alt="vnsupermark login" class="login-img" src="<?= $asset->baseUrl ?>/images/control/login-img.png">

            <div class="col-xs-4 img-facebook">
                <?= Html::a('<img alt="login facebook" src="' . $asset->baseUrl . '/images/control/login-facebook.png">', SITE_PATH . '/site/login/facebook', ['data-eauth-service' => 'facebook']) ?>
            </div>
            <div class="col-xs-4 img-google">
                <?= Html::a('<img alt="login google" src="' . $asset->baseUrl . '/images/control/login-google.png">', SITE_PATH . '/site/login/google_oauth', ['data-eauth-service' => 'google_oauth']) ?>
            </div>
            <div class="col-xs-4 img-yahoo">
                <?= Html::a('<img alt="login yahoo" src="' . $asset->baseUrl . '/images/control/login-yahoo.png">', SITE_PATH . '/site/login/yahoo', ['data-eauth-service' => 'yahoo']) ?>
            </div>

        </div>

    <?php } ?>

<?php } else { ?>
    <!-- Ready login -->
    <div class="col-xs-12" style="padding:0;">
        <div class="col-sm-12 col-xs-6 then-login">
            <h4>Chào bạn: <a href="<?= SITE_PATH ?>/user/account"><?= Yii::$app->users->displayName ?></a></h4>
            <div id="then_login" class="col-sm-12 col-xs-6 oder-view">        

                <img alt="thanh toán" src="<?= $asset->baseUrl ?>/images/Shopping_cart.png" style="margin-left:5px;" />

                <span class="count_checkout"> (<?= $goodsCount ?>) Item  $ </span> 
                <span class="price_checkout">
                    <?= formatprice(\Yii::$app->cart->getCost()) ?> VND
                </span> 

                <a href="<?= SITE_PATH ?>/shopcart">Thanh toán</a>

            </div>

            <div class="col-xs-3 then-login-button then-one">
                <a href="<?= SITE_PATH ?>/user/account">
                    Tài khoản
                </a>
            </div>
            <div class="col-xs-6 then-login-button then-tow">
                <a href="<?php echo SITE_PATH ?>/user/order">
                    Lịch sử giao dịch
                </a>
            </div>
            <div class="col-xs-3 then-login-button then-three">
                <a href="<?php echo SITE_PATH ?>/user/logout">
                    Đăng xuất
                </a>
            </div>

            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>            
<?php } ?>

<div class="clearfix"></div>

<style>
    ul.danhmuaright{ margin-left:0px; padding-left:1px; padding-right:1px;}
    ul.danhmuaright li{border-bottom:1px solid #e7e7e7;line-height: 30px;}
    ul.danhmuaright li:last-child{border-bottom:0px;}
    ul.danhmuaright li a{font-size:15px; padding-left: 5px;}
    ul.danhmuaright li a:hover{color: #6f053a !important;}  
    .paddingleftright{padding-left: 0px !important;padding-right: 0px !important;}
    .moigioi img{border:1px solid #ccc; padding:2px; border-radius: 5px;}
    .moigioi a{font-size: 14px;}	
</style>

<div id="menu_box" class="advance-box col-xs-12" style="margin-bottom:10px; margin-top:10px;">    
    <h4 class="col-xs-12" style="margin-bottom: 5px;">
        <span class="glyphicon glyphicon-play"> </span> Danh mục
    </h4>
    <ul class="col-xs-12 danhmuaright">        
        <?php
        $catalog_data = Catalog::cats();
        foreach ($catalog_data as $catalog) {
            if ($catalog->status <> 0 and $catalog->depth == 0) {
                ?>
                <li style="position: relative;">
                    <img style="margin-top:-4px;" src="<?php echo SITE_PATH . $catalog->image; ?>" alt="<?php echo $catalog->title; ?>" />
                    <a href="<?php echo SITE_PATH; ?>/<?php echo $catalog->slug ?>.html">
                        <?php echo $catalog->title; ?>
                    </a>
                    <?php if (!empty($catalog->children)) { ?>
                        <span style="position: absolute; top: 8px; right:7px; font-size:12px;cursor: pointer;" class="glyphicon glyphicon-menu-down dropdownul"></span>
                        <ul style="border-top:1px solid #e7e7e7; margin-left: 0px; display:none;">
                            <?php
                            foreach ($catalog->children as $children_id) {
                                $cata_item = Catalog::cat($children_id);
                                if (!empty($cata_item)) {
                                    echo '<li style="background: none; margin-left:19px;">-<a style="padding-left: 5px;" href="' . SITE_PATH . '/' . $cata_item->slug . '.html">' . $cata_item->title . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    <?php } ?>
                </li>
                <?php
            }
        }
        ?>
    </ul>
</div>
<?php if (!in_array($end_url, $chekurl_array)) { ?>
    <div class="advance-box col-xs-12 customer">
        <h4 class="col-xs-12" style="border-radius:0;">
            <span class="glyphicon glyphicon-play"> </span> Nhận xét khách hàng
        </h4>
        <ul class="col-xs-12" style="margin-left: 0px;">
            <?php
            $review_item = Customer::items();
            $i = 0;
            foreach ($review_item as $customer) {
                $i++;
                if ($i < 5) {
                    ?>
                    <li class="col-xs-12" style="padding:0;">
                        <div class="flag" style="height:25px;">
                                                    <!--<img src="<?php //echo $asset->baseUrl.$customer['flag']    ?>">-->
                            <img src="<?= $asset->baseUrl . '/images/iconc.png' ?>">
                        </div>
                        <p style="color:red;">
                            <b><?= $customer['review']->name . ': ' ?></b>
                            <span style="color:blue;"><?= $customer['review']->content ?></span>
                        </p>
                        <hr style="margin:0 0 10px 0;">
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
        <div class="col-xs-12">
            <!--<a class="pull-left" href="#" style="color:#FD9F53 !important;text-decoration: underline;font-size:12px;">Xem ý kiến khác<span style="margin-top:2px;">>></span></a>-->
            <?php if (isset(Yii::$app->users->id)) { ?>
                <button class="hand-sm-button pull-right customer-send" data-toggle="modal" data-target="#myModal">
                    Gửi ý kiến
                </button>
            <?php } else { ?>
                <a href="<?= SITE_PATH . '/user/login' ?>" class="hand-sm-button pull-right customer-send">
                    Gửi ý kiến
                </a>
            <?php } ?>
        </div>
    </div>


    <div class="advance-box col-xs-12">	
        <h4 class="col-xs-12">
            <span class="glyphicon glyphicon-play"> </span> Sự kiện
        </h4>
        <div class="col-xs-12" style="padding:0;">
            <?php
            $items = Article::Itemcachesk();
            foreach ($items as $article) {
                ?>
                <h6>
                    <a href="<?php echo SITE_PATH; ?>/su-kien/<?php echo $article->slug; ?>.html">
                        <span class="glyphicon glyphicon-bell"></span><?php echo $article->title; ?>
                    </a>
                </h6>
            <?php } ?>
        </div>	 
    </div>

    <div class="clearfix"></div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Cám ơn bạn đã đóng góp ý kiên</h4>
                </div>
                <div class="modal-body">
                    <?php
                    if (isset(Yii::$app->users->id)) {
                        Customer::form();
                    } else {
                        echo 'Xin hãy đăng nhập để gửi ý kiến.';
                    }
                    ?>
                    <button type="button" class="btn btn-default pull-left" style="top:-30px;position:relative;" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="advance-box col-xs-12" style="margin-bottom:10px;">    
    <h4 class="col-xs-12">
        <span class="glyphicon glyphicon-play"> </span> Chính sách giao hàng
    </h4>
    <ul class="col-xs-12">
        <?php
        $items = Article::Itemcachecs();
        foreach ($items as $article) {
            ?>
            <li>
                <a href="<?php echo SITE_PATH; ?>/chinh-sach-giao-hang/<?php echo $article->slug; ?>.html">
                    <?php echo $article->title; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>
<div class="advance-box col-xs-12 moigioi">
    <h4 style="margin-bottom: 20px;" class="right-title"><span class="glyphicon glyphicon-play"> </span>Sản phẩm bán chạy</h4>
    <div id="moigioi-container">                        	
        <ul style="list-style: none;">
            <?php
            $itemrun = Item::find()->orderBy('item_id DESC')->limit(10)->all();
            foreach ($itemrun as $item) {
                ?>
                <li style="list-style: none;">
                    <div class="col-xs-12 paddingleftright" style="text-align: center; border-bottom:1px dashed #ccc; margin-bottom: 10px;">
                        <?php $form = ActiveForm::begin(['action' => Url::to(['/shopcart/buynow', 'id' => $item->item_id]), 'options' => ['style' => 'padding-bottom:10px;']]); ?>
                        <div class="col-xs-12 paddingleftright">
                            <div class="col-xs-12 paddingleftright eve-img">
                                <?= '<a href="' . SITE_PATH . '/' . $item->slug . '.html">' . Html::img(Image::thumb($item->image, null, 170)) . '</a>' ?></div>
                            <div class="col-xs-12 paddingleftright eve-title">
                                <?= '<a class="col-xs-12" href="' . SITE_PATH . '/' . $item->slug . '.html">' . $item->title . '</a>' ?></div>
                            <?= $form->field($addToCartForm, 'count')->hiddenInput(['maxlength' => 255, 'value' => '1'])->label(false); ?>
                            <?= $form->field($addToCartForm, 'item_id', ['options' => ['style' => 'height:0;margin:0;']])->hiddenInput(['value' => $item->item_id])->label(false) ?>
                            <div class="clearfix"></div> 
                        </div>
                        <?php if ($item->giakm != 0) { ?>            
                            <?= Html::a('<span style="color:#333;text-decoration: line-through;">' . formatprice($item->giagoc) . ' VND</span> ' . formatprice($item->giakm) . ' VND', ['the/chitiet', 'slug' => $item->slug], ['class' => 'eve-price']) ?>
                        <?php } else { ?>            
                            <?= Html::a(formatprice($item->giagoc) . " VND", ['the/chitiet', 'slug' => $item->slug], ['class' => 'eve-price']) ?>
                        <?php } ?>
                        <?= Html::submitButton('Mua ngay', ['class' => 'eve-button']) ?>
                        <?php ActiveForm::end(); ?>
                    </div><div class="clearfix"></div>
                </li>
            <?php } ?>
        </ul>	
    </div>
    <div class="cl"></div>
</div>

