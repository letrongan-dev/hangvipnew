<?php 
use yii\helpers\Html; 
use yii\widgets\ActiveForm;
use app\models\AddToCartForm;
use yii\helpers\Url;
use yii\easyii\helpers\Image;

$addToCartForm = new AddToCartForm();

?>
<div class="col-lg-3 col-md-4 col-xs-6">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin(['action' => Url::to(['/shopcart/buynow', 'id' => $item->item_id]),'options'=>['style'=>'padding-bottom:10px;']]); ?>
        <div class="col-xs-12">
            <div class="col-xs-12 eve-img"><?='<a href="'.SITE_PATH.'/'.$item->slug.'.html">'.Html::img(Image::thumb($item->image,null,170)).'</a>'?></div>
            <div class="col-xs-12 eve-title"><?='<a class="col-xs-12" href="'.SITE_PATH.'/'.$item->slug.'.html">'.$item->title.'</a>'?></div>
                <?= $form->field($addToCartForm, 'count')->hiddenInput(['maxlength' => 255,'value'=>'1'])->label(false); ?>
                <?=$form->field($addToCartForm, 'item_id',['options'=>['style'=>'height:0;margin:0;']])->hiddenInput(['value'=>$item->item_id])->label(false)?>
                <div class="clearfix"></div> 
        </div>
        <?php //echo Html::a(formatprice($item->price)." VND", ['the/chitiet', 'slug' => $item->slug],['class'=>'eve-price']) ?>
		<?php if ($item->giakm != 0) { ?>            
            <?= Html::a('<span style="color:#333;text-decoration: line-through;">'.formatprice($item->giagoc).' VND</span> '.formatprice($item->giakm).' VND', ['the/chitiet', 'slug' => $item->slug], ['class' => 'eve-price']) ?>
        <?php } else { ?>            
            <?= Html::a(formatprice($item->giagoc) . " VND", ['the/chitiet', 'slug' => $item->slug], ['class' => 'eve-price']) ?>
        <?php } ?>
        <?= Html::submitButton('Mua ngay', ['class'=>'eve-button']) ?>
        <?php ActiveForm::end(); ?>   
    </div><div class="clearfix"></div>
</div>