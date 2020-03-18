<?php
use yii\widgets\ActiveForm;
use yii\easyii\modules\page\api\Page;
use yii\easyii\modules\shopcart\api\Shopcart;
use yii\easyii\modules\catalog\api\Catalog;
use amnah\yii2\user\models\Addresses;
use amnah\yii2\user\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\helpers\Image;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\modules\catalog\models\Price;
$asset = app\assets\AppAsset::register($this);
$page = Page::get('page-shopcart');
$this->title = Yii::t('app','Thanh toán');
$this->params['breadcrumbs'][] = $page->model->title;
$checkcard = 0;
$transpost = 0;
$zingcard = 0;
$address = Addresses::findOne(['uid' => Yii::$app->users->id]);
$notation = Yii::$app->session->get('notation');
?>

<div class="breadcrumbs">
    <a href="<?= SITE_PATH ?>"><?=Yii::t('app', 'Trang chủ')?></a>&nbsp;<span style="left:16px;color:#CCC;margin-left:-10px;font-size:12px;" class="glyphicon glyphicon-play"></span><span style="font-size:12px;color:#bbbaaa;" class="glyphicon glyphicon-play"></span>&nbsp;
    <a href="<?=SITE_PATH?>/thanh-toan.html"><?=Yii::t('app', 'Thanh toán')?></a>    
</div>

<div class="col-xs-12 paddingleftright">
    <?php if ($flash = Yii::$app->session->getFlash("Order-danger")): ?>
        <div class="alert alert-danger"><p><?= $flash ?></p></div>
    <?php endif; ?>
    <div class="col-xs-12 paddingleftright">
        <?php if (count($total) and count($products) > 0) { ?>
            <h3 class="title_category_cate"><?=Yii::t('app', 'Thanh toán')?></h3>
            
            <table class="shopcart_table" style="width: 100%;">
                <tr style="background: #e3e3e3;">
                    <th><?=Yii::t('app', 'Tên sản phẩm')?></th>
                    <th><?=Yii::t('app', 'Số lượng')?></th>
                    <th><?=Yii::t('app', 'Giá bán')?></th>
                    <th><?=Yii::t('app', 'Thành tiền')?></th>
                    <th>&nbsp;</th>
                </tr>
                <?php
                $tong=0;
                foreach ($products as $product) {
                    $quantity = $product->getQuantity();
                    $item_info= Item::findOne($product->getId());
                    $get_price= Price::getPriceProduct($product->getId());
                    if($item_info['price']==0){
                        $gia_sp=$get_price;
                    }else{
                        $gia_sp=$item_info['price'];
                    }
                    $giaban=$gia_sp;
                    $thanhtien=$gia_sp*$quantity;
                    $tong=$tong+$thanhtien;
                ?>
				<style>
                    .imgloading<?= $product->getId() ?>{display: none; position: absolute; top:0px; left:0px;}
                </style>
                <tr id="rows<?= $product->getId()?>" class="rows_shopcart">
                    <td>
                        <?=Html::img(Image::thumb($product->image, null, 50))?>
                        <?=Html::a($product->title, ['/the/chitiet', 'slug' => $product->slug])?>
                    </td>
                    <td>
                        <div style="width: 26px; float: left;">
			<span onclick="Updatedesc(<?= $product->getId() ?>)" class="" style="background: #efefef; border: 1px solid #ccc; padding: 5px 10px; border-radius: 2px; cursor: pointer;">-</span>
			</div>
			<div style="width: 26px; float: left; position: relative; text-align: center;">
			<span class="number<?= $product->getId() ?>"><?= $quantity ?></span>
			<img class="imgloading<?= $product->getId() ?>" src="<?=$asset->baseUrl?>/images/24.gif" />
			</div>
			<div style="width: 26px; float: left;">
			<span onclick="Updateasc(<?= $product->getId() ?>)" class="" style="background: #efefef; border: 1px solid #ccc; padding: 5px 10px; border-radius: 2px; cursor: pointer;">+</span>
			</div>
                    </td>
                    <td style="font-weight:bold;">
                        <?php
                            if($item_info['price']==0){
                                echo "<span class='price_no_km'>".number_format($get_price, 0, ',', '.')."</span>";                                
                            }else{
                                echo "<span class='price'>".number_format($get_price, 0, ',', '.')." VND </span>";
                                echo "<br/>";
                                echo "<span class='pricekm'>".number_format($item_info['price'], 0, ',', '.')."</span>";
                            }
                        ?> VND
                    </td>
                    <td>
                        <b id="thanhtien<?= $product->getId() ?>"><?= number_format($thanhtien, 0, ',', '.'); ?> VND</b>
                    </td>
                    <td>
			<span onclick="Removecart(<?= $product->getId() ?>)" class="glyphicon glyphicon-trash" style="color: red; font-size: 15px;">&nbsp;</span>
                    </td>
                </tr>
                <?php }?>
                <tr>
                    <td colspan="3" style="border-bottom: 0px;">
                        &nbsp;
                    </td>
                    <td colspan="2" style="text-align: right;border-bottom: 0px; font-weight:bold;">
                        <b><?=Yii::t('app', 'Thành tiền')?> </b>
                        <span id="total"><?= number_format($tong, 0, ',', '.') ?></span> VND
                    </td>
                </tr>
            </table>
            <br/><br/><br/>
            
            <style>
                .shopcart_table th{padding: 10px;}
                .shopcart_table td{padding: 10px; border-bottom: 1px dashed #ccc;}
                .input_number{width: 50px; border:1px solid #ccc; border-radius: 2px; padding: 3px; text-align: center;}
                .color-red{color: red;}
                .main_cart label{margin-top: 7px;}
            </style>
			<script>
                function Removecart(id) {
                    $.ajax({
                        url: '<?php echo Yii::$app->request->baseUrl . '/shopcart/removecart' ?>',
                        type: 'post',
                        data: {id: id},
                        success: function (data) {
                            $('#total').html("<span id='total'>" + data + "</span>");
                        }
                    });
                    document.getElementById("rows" + id).remove();
                }
                function Updateasc(id) {
					$(".imgloading" + id).css("display", "block");
                    $.ajax({
                        url: '<?php echo Yii::$app->request->baseUrl . '/shopcart/updateasc' ?>',
                        type: 'post',
                        data: {id: id},
                        success: function (data) {
                            var str = data;
                            var res = str.split(":");
                            $('#total').html("<span id='total'>" + res[2] + "</span>");
                            $('#thanhtien' + id).html("<b id='thanhtien" + id + "'>" + res[1] + " VND</b>");
                            $('.number' + id).html(res[0]);
							$(".imgloading" + id).css("display", "none");
                        }
                    });
                }
                function Updatedesc(id) {
					$(".imgloading" + id).css("display", "block");
                    $.ajax({
                        url: '<?php echo Yii::$app->request->baseUrl . '/shopcart/updatedesc' ?>',
                        type: 'post',
                        data: {id: id},
                        success: function (data) {
                            var str = data;
                            var res = str.split(":");
                            if (res[3] == "noremove") {
                                $('.number' + id).html(res[0]);
                                $('#total').html("<span id='total'>" + res[2] + "</span>");
                                $('#thanhtien' + id).html("<b id='thanhtien" + id + "'>" + res[1] + " VND</b>");
                            } else {
                                $('#total').html("<span id='total'>" + res[2] + "</span>");
                                document.getElementById("rows" + id).remove();
                            }
							$(".imgloading" + id).css("display", "none");
                        }
                    });
                }
            </script>
            <?php echo Shopcart::form(['successUrl' => Url::to('/shopcart/success')]) ?>
        <?php } else { ?>
            <?=Yii::t('app','Bạn chưa chọn bất kỳ sản phẩm nào')?>.<?= Html::a('<span class="glyphicon glyphicon-log-in"> </span>'. Yii::t('app','Trang chủ'), ['/'], ['class' => 'btn btn-info', 'style' => 'color:#fafafa;']) ?>
        <?php } ?>
    </div>
</div>