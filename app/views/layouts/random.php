<?php
use yii\helpers\Html;
use yii\easyii\helpers\Image;
use yii\easyii\modules\catalog\api\Catalog;


?>

<div class="col-xs-12 slider-row random" style="padding:0;margin-top:30px;">

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
        
    }else{

      if($event<>1){
        if(count($items)>0 and $catalog->depth=='1'){
    ?>

            <div class="col-md-3 col-xs-6">
                <div class="col-xs-12" style="padding:0;">
                  <form>
                    <div class="col-xs-12" style="padding:0;">
                        <div class="col-xs-12 eve-title"><a class="col-xs-12" href="<?= SITE_PATH.'/'.$catalog->slug ?>.html"><?= $catalog->title ?></a></div>
                        <div class="col-xs-12 eve-img"><a href="<?= SITE_PATH.'/'.$catalog->slug ?>.html"><?= Html::img(Image::thumb($catalog->image,null,170),['alt'=>$catalog->title]) ?></a></div>
                    </div>
                  </form>
                </div><div class="clearfix"></div>
            </div>
    <?php        
          }else{
            if($catalog->depth=='1' and !empty($catalog->children)){
                $html_transport_item = '';$html_menu_item='';$menu_item_span = '';
                foreach($catalog->children as $children_id){
                    $cata_item = Catalog::cat($children_id);
                    if(!empty($cata_item)){
                        $items =  Catalog::last(20,['category_id'=>$children_id]);
                        if(count($items)>0){
                            $html_transport_item = 1;
                        }
                    }
                }
                if($html_transport_item<>''){
    ?>
                    <div class="col-md-3 col-xs-6">
                        <div class="col-xs-12" style="padding:0;">
                          <form>
                            <div class="col-xs-12" style="padding:0;">
                                <div class="col-xs-12 eve-title"><a class="col-xs-12" href="<?= SITE_PATH.'/'.$catalog->slug ?>.html"><?= $catalog->title ?></a></div>
                                <div class="col-xs-12 eve-img"><a href="<?= SITE_PATH.'/'.$catalog->slug ?>.html"><?= Html::img(Image::thumb($catalog->image,null,170),['alt'=>$catalog->title]) ?></a></div>
                            </div>
                          </form>
                        </div><div class="clearfix"></div>
                    </div>
    <?php
                }
            }
          }
      }
    }
  }
}
?>
    
</div>
<div class="clearfix"></div>