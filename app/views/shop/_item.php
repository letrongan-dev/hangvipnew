<?php 
use yii\helpers\Html; 
use yii\widgets\ActiveForm;
use app\models\AddToCartForm;
use yii\helpers\Url;
?>
<div class="row"<?php if($i%3==0){?> style="border-right:0px;" <?php }?>>
    <?= Html::a($item->title, ['shop/view', 'slug' => $item->slug]) ?>
    <div>
        <img src="<?php echo SITE_PATH.$item->image;?>" />
        <p><?= $item->price .".". Yii::$app->session->get('notation');?></p>
        <?php $form = ActiveForm::begin(['action' => Url::to(['/shopcart/buynow', 'id' => $item->id])]); ?>            
        <?= $form->field($addToCartForm, 'count')->textInput(['maxlength' => 255, 'style' => 'width:50px;','class'=>'textsoluong'])->label(false); ?>
        <?= Html::submitButton('Mua ngay', ['class' => 'button-submit']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>