<?php 
use yii\helpers\Html; 
use yii\widgets\ActiveForm;
use app\models\AddToCartForm;
use yii\helpers\Url;

$addToCartForm = new AddToCartForm();
die("sdf");
?>
<div class="col-md-4 col-xs-6 product-card">
    <div class="col-xs-12">
        <?= Html::a('<img class="col-md-5 col-sm-12 col-xs-5 img-thumbnail" style="padding:3px;height:95px;" src="'.SITE_PATH.$item->image.'" />', ['the/chitiet', 'slug' => $item->slug]) ?>
        <div class="col-md-7 col-sm-12 col-xs-7">
            <h4><a href="<?=SITE_PATH.'/'.$item->slug.'.html'?>"><?= $item->title ?></a></h4>
            <h5><a href="<?=SITE_PATH.'/'.$item->slug.'.html'?>"><?= formatprice($item->price, Yii::$app->session->get('notation')); ?> <?= Yii::$app->session->get('notation');?></a></h5>
            <?php $form = ActiveForm::begin(['action' => Url::to(['/shopcart/buynow', 'id' => $item->id]),'options'=>['style'=>'padding-bottom:5px;']]); ?>
            <div class="col-xs-1" style="padding: 5px 0 0 0;">SL: </div>
            <?= $form->field($addToCartForm, 'count',['options'=>['class'=>'col-lg-5 col-xs-5']])->textInput(['maxlength' => 255,'class'=>'form-control input-sm','style'=>'padding:5px;height:25px;'])->label(false); ?>
            <?=$form->field($addToCartForm, 'item_id',['options'=>['style'=>'height:0;margin:0;']])->hiddenInput(['value'=>$item->id])->label(false)?>
            <?= Html::submitButton('Mua ngay', ['class' => 'btn btn-sm col-lg-6 col-xs-5','style'=>'padding:3px 10px;']) ?>
            <div class="clear"></div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
	<div class="clear"></div>
</div>