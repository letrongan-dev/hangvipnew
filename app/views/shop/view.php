<?php
use app\models\AddToCartForm;
use yii\easyii\modules\catalog\api\Catalog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $item->seo('title', $item->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Shop', 'url' => ['shop/index']];
$this->params['breadcrumbs'][] = ['label' => $item->cat->title, 'url' => ['shop/cat', 'slug' => $item->cat->slug]];
$this->params['breadcrumbs'][] = $item->model->title;

$colors = [];
if(!empty($item->data->color) && is_array($item->data->color)) {
    foreach ($item->data->color as $color) {
        $colors[$color] = $color;
    }
}
?>
<h3 class="titleh3">
    <span style="position:absolute; background-color:#fff; bottom:-1px; left:0px; border-right:1px solid #4cae4c; padding:0px 10px; color:#2a6a2a; border-left:1px solid #4cae4c; border-top:1px solid #4cae4c;">
    Chi tiết thẻ
    </span>            
</h3>
<div class="listrow-news">
    <div class="news-detail">
        <h3 style="padding: 10px 0px 5px 0px; margin: 0px; text-align: center;">
            <?= $item->seo('h1', $item->title) ?>
        </h3>
        <?php if(Yii::$app->request->get(AddToCartForm::SUCCESS_VAR)){ ?>
                <h4 class="text-success"><i class="glyphicon glyphicon-ok"></i> Added to cart successful</h4>
        <?php }?>
        <div style="width:50%; float: left; border-right:10px; border-right:1px solid #ccc;">            
            <div>
                <img src="<?php echo SITE_PATH.$item->image;?>" style="margin-top: -3px; width: 220px; height: 120px; float: left; margin-right: 10px;" />
                <p>
                    <?= $item->price .".".Yii::$app->session->get('notation')?>
                </p>
            </div>
            <div>            
                <?php $form = ActiveForm::begin(['action' => Url::to(['/shopcart/add', 'id' => $item->id])]); ?>            
                <?= $form->field($addToCartForm, 'count')->textInput(['maxlength' => 255, 'style' => 'width:90px;'])->label(false); ?>
                <?= Html::submitButton('Add to cart', ['class' => 'btn btn-warning']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div> 
        <div style="width:49%; float: right;">
            <?= $item->description ?>
        </div>        
    </div>
    <div class="cl"></div>
</div>

