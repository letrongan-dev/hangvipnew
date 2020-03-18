<?php
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\file\api\File;
use yii\easyii\modules\page\api\Page;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$addToCartForm = new \app\models\AddToCartForm();

$page = Page::get('page-the-games');

//$this->title = $page->seo('title', $page->model->title);
$this->title = 'Thẻ game';
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
<div class="breadcrumbs">
    <a href="<?php echo SITE_PATH;?>">Trang chủ</a>&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span>&nbsp;&nbsp;
    <a href="#">Thẻ game</a>    
</div>
<div class="col-xs-12" style="padding:0;">
<div class="col-xs-12"><h1 class="title-card"><a style="font-size:20px;" href="#">Thẻ game</a></h1></div>
<?php
$catalog_data = Catalog::cats();
$html_transport = '';$event = '';
foreach($catalog_data as $catalog){
  if($catalog->status<>0){
    if(isset(Catalog::cat(Catalog::cat($catalog->category_id)->tree)->slug)){
        if(Catalog::cat(Catalog::cat($catalog->category_id)->tree)->slug == 'qua-tang')
        {$transpost = 1;}else{$transpost = 0;}
        if(Catalog::cat(Catalog::cat($catalog->category_id)->tree)->slug == 'su-kien')
        {$event = 1;}else{$event = 0;}
    }
    $items =  Catalog::last(8,['category_id'=>$catalog->category_id]);
    if(empty($catalog->children) and $transpost<>1 and $event<>1){
        if(count($items)>0){
            $text_dropdown = [''=>'Mệnh giá'];
            foreach($items as $item){
                $text_dropdown[$item->model->item_id] = $item->title.' ('.formatprice($item->price, Yii::$app->session->get('notation')).' '.Yii::$app->session->get('notation').')';
            }
?>
            <div class="col-lg-3 col-md-4 col-xs-6 buynow_box" style="margin-bottom:50px;">
                <div class="col-xs-12">
                    <?=Html::a(Html::img($catalog->image),SITE_PATH.'/'.$catalog->slug.'.html')?>
                    <div class="col-xs-12">
                        <?php
                        $form = ActiveForm::begin(['action' => Url::to(['/shopcart/buynow'])]);
                        echo $form->field($addToCartForm, 'item_id')->dropDownList($text_dropdown,['class' => 'input-sm form-control chosse_id_index'])->label(false);
                        echo '<div class="col-lg-1 col-xs-1 text-sl">SL: </div>'.$form->field($addToCartForm, 'count',['options' => ['class' => 'col-lg-5 col-xs-6']])->textInput(['maxlength' => 255,'class'=>'form-control input-sm'])->label(false);
                        echo Html::submitButton('Mua ngay', ['class' => 'btn btn-sm col-lg-6 col-xs-5']);
                        echo '<div class="clearfix"></div>';
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
<?php
        }
    }
  }
}
?>
</div>