<?php
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\page\api\Page;

$page = Page::get('page-shop-search');

$this->title = $page->seo('title', $page->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Shop', 'url' => ['shop/index']];
$this->params['breadcrumbs'][] = $page->model->title;

?>
<h1 class="title-card"><a><span class="glyphicon glyphicon-play"></span><span class="glyphicon glyphicon-play"></span><?= $page->seo('h1', $page->title) ?></a></h1>
<br/>
<?= $this->render('_search_form', ['text' => $text]) ?>
<br/>
<div class="row">
    <div class="col-xs-12 slider-row">
        <?php if(count($items)) : ?>
            <?php foreach($items as $item) : ?>
                <?= $this->render('_item_tran', ['item' => $item]) ?>
            <?php endforeach; ?>
            <?= Catalog::pages() ?>
        <?php else : ?>
            <p>No items found</p>
        <?php endif; ?>
    </div>
    <div class="col-md-4"></div>
</div>


