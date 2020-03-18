<?php
use yii\easyii\modules\article\api\Article;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $article->seo('title', $article->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['articles/index']];
$this->params['breadcrumbs'][] = ['label' => $article->cat->title, 'url' => ['articles/cat', 'slug' => $article->cat->slug]];
$this->params['breadcrumbs'][] = $article->model->title;
?>
<h3 class="titleh3">
    <span>
        Chi tiết hướng dẫn
    </span>
</h3>
<div class="listrow-news">
    <div class="news-detail">
        <h2 style="margin: 0px; padding-bottom: 20px; padding-top: 10px; text-align: center;">
            <?php echo $article->seo('h1', $article->title) ?>
        </h2>
        <?php echo $article->text ?>
    </div>
    <?php
    $item=  \yii\easyii\modules\article\models\Item::find()->where('slug = "'.$article->slug.'"')->one();
    $id_cate=$article->category_id;    
    $articlecategory=  Article::ItemOrther($id_cate,$item->item_id);
    if(count($articlecategory)>0){
    ?>
    <div>
        <h3 style="margin:0px; padding-top: 20px; padding-bottom: 10px; color: #4cae4c;">Tin cùng loại</h3>
        <ul>
            <?php
            foreach ($articlecategory as $orther){
            ?>
            <li>
                <img style="float: left; margin-top: 2px;" src="<?php echo SITE_PATH_BANTHE?>/images/next.png" />
                <?= Html::a($orther->title, ['articles/view', 'slug' => $orther->slug]) ?>
            </li>
            <?php }?>
        </ul>        
    </div>
    <?php }?>
    <div class="cl"></div>
</div>