<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $cat->seo('title', $cat->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Shop', 'url' => ['shop/index']];
$this->params['breadcrumbs'][] = $cat->model->title;
?>
<h3 class="titleh3">    
    <span style="position:absolute; background-color:#fff; bottom:-1px; left:0px; border-right:1px solid #4cae4c; padding:0px 10px; color:#2a6a2a; border-left:1px solid #4cae4c; border-top:1px solid #4cae4c;">
    <?= $cat->seo('h1', $cat->title) ?>
    </span>
</h3>
<div class="listrow">
        <?php if(count($items)) : ?>
            <?php $i=1; ?>
            <?php foreach($items as $item) : ?>
                
                <?= $this->render('_item', ['item' => $item,'i' => $i,'addToCartForm' =>$addToCartForm]) ?>
                
            <?php $i++;?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Category is empty</p>
        <?php endif; ?>
    <div class="cl"></div>
    <div style="text-align: center;"><?= $cat->pages() ?></div>
</div>
