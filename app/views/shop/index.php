<?php
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\file\api\File;
use yii\easyii\modules\page\api\Page;
use yii\helpers\Html;

$page = Page::get('page-shop');

$this->title = $page->seo('title', $page->model->title);
$this->params['breadcrumbs'][] = $page->model->title;

function renderNode($node){
    if(!count($node->children)){
        $html = '<li>'.Html::a($node->title, ['/shop/cat', 'slug' => $node->slug]).'</li>';
    } else {
        $html = '<li>'.$node->title.'</li>';
        $html .= '<ul>';
        foreach($node->children as $child) $html .= renderNode($child);
        $html .= '</ul>';
    }
    return $html;
}
?>


<div class="row">
    <div class="col-md-12">
        <h4>Thẻ game</h4>
        <?php foreach(Catalog::last(12) as $item) : ?>
            <p>
                <img src="<?php echo SITE_PATH.$item->image;?>" />             
                <?= Html::a($item->title, ['/shop/view', 'slug' => $item->slug]) ?><br/>
                <span class="label label-warning"><?= $item->price .".". Yii::$app->session->get('notation');?></span>
            </p>
        <?php endforeach; ?>
    </div>
</div>

