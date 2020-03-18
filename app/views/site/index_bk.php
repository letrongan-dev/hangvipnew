<?php
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\event\api\Event;
use yii\easyii\helpers\Image;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = "Quà tặng online - Hangvip.vn";

$notation =Yii::$app->session->get('notation');
?>
<div class="row margin-top20">
    <?php
    $catalog = Catalog::cats();
    foreach ($catalog as $item) {
        if ($item->depth == 0 && $item->slug != 'qua-tang') {
            ?>
            <div class="col-md-5ths col-sm-6 col-xs-12">
                <a  href="<?= SITE_PATH . '/' . $item->slug ?>.html">
					<?php echo Html::img(Image::thumb($item->image, 190, null), ['alt' => $item->title]) ?>
					<!--<img src="<?php echo SITE_PATH.$item->image;?>" alt="<?php echo $item->title;?>" />-->
				</a>
                <a  href="<?= SITE_PATH . '/' . $item->slug ?>.html">
					<div class="title-catalog">
						<?= $item->title ?>
					</div>
				</a>
            </div>
            <?php
        } else {
            
        }
    }
    ?>
</div> 

<!-- ngay tinh nhan -->
<?php
	$event = Event::item("quoc-te-phu-nu");					
	$item_ids = json_decode($event->model->item_id);
	if(count($item_ids)>0){
?>
<div class="tabbable-panel margin-top20 clear">
    <div class="tabbable-line">
        <?php
            $j = 1;            
            $tabcontent = '<div class="tab-content clear">';                      
                //print_r($items);                        
                    $active = ($j == 1 ? 'active' : '' ); 
                    $htmllistpro = '';
                    $k = 0;
                    $l = 0;
                    $activelis = ($l == 0 ) ? 'active' : '';
                    $list4pro = '';
                    $class = '';
                        foreach($item_ids as $item_id){
                            $itemproduct = Catalog::get($item_id);
                            $price=Catalog::GetPrice($itemproduct->id,$notation);
                            if ($k > 3) {
                                $list4pro = '';
                                $activelis = '';
                                $k = 0;
                            }
                            if ($k == 0) {
                                $class = 'col-md-3';
                            } else if ($k == 1) {
                                $class = 'col-md-3 hidden-xs';
                            } else {
                                $class = 'col-md-3 hidden-sm hidden-xs';
                            }
                            if ($k == 0) {
                                $list4pro .='<div class="item ' . $activelis . '"><div class="row">';
                            }
                            $list4pro .= ''
                                    . '<div class="' . $class . '">'
                                        . '<a href="' . SITE_PATH . '/' . $itemproduct->slug . '.htm" class="thumbnail">' . Html::img(Image::thumb($itemproduct->image, 250, 250), ["alt" => "Images", "style" => "max-width:180px; max-height:170px;"]) . '</a>'
                                        . '<div class="col-xs-7 title nopaddingleft"><p>' . $itemproduct->title . '</p>'
                                        . '<p><b>' . formatprice($price, $notation) . ' ' . $notation . '</b></p></div>'
                                        . '<div class="col-xs-5 text-right nopaddingright">'
                                        . '<form id="w' . $itemproduct->id . '" action="' . SITE_PATH . '/shopcart/buynow/' . $itemproduct->id . '" method="post" >  '
                                        . '<input type="hidden" name="_csrf" value="Q3RTSmxoVWJzRBkfG1sFCikTIAY9LD4lIRwafiVFITpwPzsDGFBtVA==">  '
                                        . '<input type="hidden" id="addtocartform-count-item_id" class="form-control" name="AddToCartForm[count]" value="1">    '
                                        . '<input type="hidden" id="addtocartform-item_id" class="form-control" name="AddToCartForm[item_id]" value="' . $itemproduct->id . '">    '
                                        . '<button type="submit" class="btn btn-primary">Mua ngay</button>       '
                                        . '</form>'
                                        . '</div>'
                                    . '</div>';
                            
                            //$list4pro .= $itemproduct->id;
                            
                            if ($k == 3) {
                                $list4pro .= '</div></div>';
                            }
                            if ($k == 3) {
                                $htmllistpro .= $list4pro;
                            }
                            $k++;
                            $l++;
                        }                                                   
                    $tabcontent .='                                 
                    <div role="tabpanel" class="tab-pane ' . $active . '" id="' . $i . $j . '">
                        <div class="row list-prouct ">
                            <div class="col-md-12 col-xs-12">
                                <div id="Carouselt' . $i . $j . '" class="carousel slide">
                                    <div class="carousel-inner">                                                
                                        ' . $htmllistpro . '
                                    </div>
                                    <a data-slide="prev" href="#Carouselt' . $i . $j . '" class="left carousel-control">	<span class="glyphicon glyphicon-chevron-left"></span></a>
                                    <a data-slide="next" href="#Carouselt' . $i . $j . '" class="right carousel-control"><span class="glyphicon glyphicon-chevron-right"></span></a>
                                </div>

                            </div>
                        </div>
                    </div>'
                    ?>
                    <?php
                    $j++;                    
            $tabcontent.= '</div>';                
        ?>
        <h2 class="pull-left" style="font-size:14px;">
            <a href="<?= SITE_PATH."/su-kien/quoc-te-phu-nu.html";?>">
                Quốc Tế Phụ Nữ
            </a>
        </h2>
        <?= $tabcontent ?>
    </div>
</div>       
<?php }?>
<!-- end le tinh nhan -->


<!-- quà tết 
<?php
$catalog = Catalog::cats();
$i = 1;
foreach ($catalog as $item) {    
    if ($item->category_id == 118) { 
        ?>
        <div class="tabbable-panel margin-top20 clear">
            <div class="tabbable-line">
                <?php
                if ($item->children) {
                    $j = 1;
                    $ul = '';

                    $tabcontent = '<div class="tab-content clear">';
                    foreach ($catalog as $items) {  
                        //print_r($items);
                        if (in_array($items->category_id, $item->children)) {
                            $active = ($j == 1 ? 'active' : '' );
                            $listproduct = Catalog::last(20, ['category_id' => 145]);
                            $htmllistpro = '';
                            $k = 0;
                            $l = 0;
                            $activelis = ($l == 0 ) ? 'active' : '';
                            $list4pro = '';
                            $class = '';

                            if (count($listproduct) > 3) {
                                foreach ($listproduct as $itemproduct) {
									$price=Catalog::GetPrice($itemproduct->id,$notation);
                                    if ($k > 3) {
                                        $list4pro = '';
                                        $activelis = '';
                                        $k = 0;
                                    }
                                    if ($k == 0) {
                                        $class = 'col-md-3';
                                    } else if ($k == 1) {
                                        $class = 'col-md-3 hidden-xs';
                                    } else {
                                        $class = 'col-md-3 hidden-sm hidden-xs';
                                    }
                                    if ($k == 0) {
                                        $list4pro .='<div class="item ' . $activelis . '"><div class="row">';
                                    }
                                    $list4pro .= '<div class="' . $class . '"><a href="' . SITE_PATH . '/' . $itemproduct->slug . '.htm" class="thumbnail">' . Html::img(Image::thumb($itemproduct->image, 250, 250), ["alt" => "Images", "style" => "max-width:180px; max-height:170px;"]) . '</a><div class="col-xs-7 title nopaddingleft"><p>' . $itemproduct->title . '</p><p><b>' . formatprice($price, $notation) . ' ' . $notation . '</b></p></div><div class="col-xs-5 text-right nopaddingright"><form id="w' . $itemproduct->id . '" action="' . SITE_PATH . '/shopcart/buynow/' . $itemproduct->id . '" method="post" >  <input type="hidden" name="_csrf" value="Q3RTSmxoVWJzRBkfG1sFCikTIAY9LD4lIRwafiVFITpwPzsDGFBtVA==">  <input type="hidden" id="addtocartform-count-item_id" class="form-control" name="AddToCartForm[count]" value="1">    <input type="hidden" id="addtocartform-item_id" class="form-control" name="AddToCartForm[item_id]" value="' . $itemproduct->id . '">    <button type="submit" class="btn btn-primary">Mua ngay</button>       </form></div></div>';
                                    if ($k == 3) {
                                        $list4pro .= '</div></div>';
                                    }
                                    if ($k == 3) {
                                        $htmllistpro .= $list4pro;
                                    }

                                    $k++;
                                    $l++;
                                }
                            } else {
                                foreach ($listproduct as $itemproduct) {
									$price=Catalog::GetPrice($itemproduct->id,$notation);
                                    $count = count($listproduct);
                                    if ($k == 0) {
                                        $class = 'col-md-3';
                                    } else if ($k == 1) {
                                        $class = 'col-md-3 hidden-xs';
                                    } else {
                                        $class = 'col-md-3 hidden-sm hidden-xs';
                                    }
                                    if ($k == 0) {
                                        $list4pro .='<div class="item ' . $activelis . '"><div class="row">';
                                    }
                                    $list4pro .= '<div class="' . $class . '"><a href="' . SITE_PATH . '/' . $itemproduct->slug . '.htm" class="thumbnail">' . Html::img(Image::thumb($itemproduct->image, 250, 250), ["alt" => "Images", "style" => "max-width:180px; max-height:170px;"]) . '</a><div class="col-xs-7 title nopaddingleft"><p>' . $itemproduct->title . '</p><p><b>' . formatprice($price, $notation) . ' ' . $notation . '</b></p></div><div class="col-xs-5 text-right nopaddingright"><form id="w' . $itemproduct->id . '" action="/hangvip/shopcart/buynow/' . $itemproduct->id . '" method="post" >  <input type="hidden" name="_csrf" value="Q3RTSmxoVWJzRBkfG1sFCikTIAY9LD4lIRwafiVFITpwPzsDGFBtVA==">  <input type="hidden" id="addtocartform-count-item_id" class="form-control" name="AddToCartForm[count]" value="1">    <input type="hidden" id="addtocartform-item_id" class="form-control" name="AddToCartForm[item_id]" value="' . $itemproduct->id . '">    <button type="submit" class="btn btn-primary">Mua ngay</button>       </form></div></div>';
                                    if ($k == $count - 1) {
                                        $list4pro .= '</div></div>';
                                    }
                                    $k++;
                                    $l++;if(count($listproduct)<1){
                                $category_title="";
                            }else{
                                $category_title=$items->title;
                            }
                                }
                                $htmllistpro .= $list4pro;
                            }
                            if(count($listproduct)<1){
                                $category_title="";
                            }else{
                                $category_title=$items->title;
                            }
                            $ul .=' <li  role="presentation" class="' . $active . '"><a href="#' . $i . $j . '" aria-controls="home" role="tab" data-toggle="tab">'.$category_title . '</a></li> ';
                            $tabcontent .='                                 
                            <div role="tabpanel" class="tab-pane ' . $active . '" id="' . $i . $j . '">
                                <div class="row list-prouct ">
                                    <div class="col-md-12 col-xs-12">
                                        <div id="Carouselt' . $i . $j . '" class="carousel slide">
                                            <div class="carousel-inner">                                                
                                                ' . $htmllistpro . '
                                            </div>
                                            <a data-slide="prev" href="#Carouselt' . $i . $j . '" class="left carousel-control">	<span class="glyphicon glyphicon-chevron-left"></span></a>
                                            <a data-slide="next" href="#Carouselt' . $i . $j . '" class="right carousel-control"><span class="glyphicon glyphicon-chevron-right"></span></a>
                                        </div>

                                    </div>
                                </div>
                            </div>'
                            ?>
                            <?php
                            $j++;
                        }
                    }
                    $tabcontent.= '</div>';
                } else {
                    $ul = '';
                    $active = 'active';
                    $listproduct = Catalog::last(20, ['category_id' => 146]);
                    $htmllistpro = '';
                    $k = 0;
                    $l = 0;
                    $activelis = ($l == 0 ) ? 'active' : '';
                    $list4pro = '';
                    $class = '';
                    foreach ($listproduct as $itemproduct) {
						$price=Catalog::GetPrice($itemproduct->id,$notation);
                        if ($k > 3) {
                            $htmllistpro = '';
                            $activelis = '';
                            $k = 0;
                        }
                        if ($k == 0) {
                            $class = 'col-md-3';
                        } else if ($k == 1) {
                            $class = 'col-md-3 hidden-xs';
                        } else {
                            $class = 'col-md-3 hidden-sm hidden-xs';
                        }
                        if ($k == 0) {
                            $list4pro .='<div class="item ' . $activelis . '"><div class="row">';
                        }
                        $list4pro .= '<div class="' . $class . '"><a href="' . SITE_PATH . '/' . $itemproduct->slug . '.htm" class="thumbnail">' . Html::img(Image::thumb($itemproduct->image, 250, 250), ["alt" => "Images", "style" => "max-width:180px; max-height:170px;"]) . '</a><div class="col-xs-7 title nopaddingleft"><p>' . $itemproduct->title . '</p><p><b>' . formatprice($price, $notation) . ' ' . $notation . '</b></p></div><div class="col-xs-5 text-right nopaddingright"><form id="w' . $itemproduct->id . '" action="/shopcart/buynow/' . $itemproduct->id . '" method="post" >  <input type="hidden" name="_csrf" value="Q3RTSmxoVWJzRBkfG1sFCikTIAY9LD4lIRwafiVFITpwPzsDGFBtVA==">  <input type="hidden" id="addtocartform-count-item_id" class="form-control" name="AddToCartForm[count]" value="1">    <input type="hidden" id="addtocartform-item_id" class="form-control" name="AddToCartForm[item_id]" value="' . $itemproduct->id . '">    <button type="submit" class="btn btn-primary">Mua ngay</button>       </form></div></div>';
                        if ($k == 3) {
                            $list4pro .= '</div></div>';
                        }
                        if ($k == 3) {
                            $htmllistpro .= $list4pro;
                        }
                        $k++;
                        $l++;
                    }
                    $tabcontent = '<div class="tab-content clear">
                            <div role="tabpanel" class="tab-pane active " id="' . $i . 1 . '">
                                <div class="row list-prouct ">
                                    <div class="col-md-12 col-xs-12">
                                        <div id="Carouselt' . $i . 0 . '" class="carousel slide">
                                            <div class="carousel-inner">                                                
                                                ' . $htmllistpro . '
                                            </div>
                                            <a data-slide="prev" href="#Carouselt' . $i . 0 . '" class="left carousel-control">	<span class="glyphicon glyphicon-chevron-left"></span></a>
                                            <a data-slide="next" href="#Carouselt' . $i . 0 . '" class="right carousel-control"><span class="glyphicon glyphicon-chevron-right"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
                ?>

                <h2 class="pull-left" style="font-size:14px;">
                    <a href="<?= SITE_PATH . '/qua-tet-viet.html';?>">
                        Tết Cổ Truyền
                    </a>
                </h2>
                <?= $tabcontent ?>
            </div>
        </div>
        <?php
        $i++;
    }
}
?>
<!-- end quà tết -->


<?php
$catalog = Catalog::cats();
$i = 1;
foreach ($catalog as $item) {
		if ($item->parent == 0) {
			?>
			<div class="tabbable-panel margin-top20 clear">
				<div class="tabbable-line">
					<?php
					if ($item->children) {
						$j = 1;
						$ul = '';

						$tabcontent = '<div class="tab-content clear">';
						foreach ($catalog as $items) {
							
							if (in_array($items->category_id, $item->children)) {
								$active = ($j == 1 ? 'active' : '' );
								$listproduct = Catalog::last(20, ['category_id' => $items->category_id]);
								$htmllistpro = '';
								$k = 0;
								$l = 0;
								$activelis = ($l == 0 ) ? 'active' : '';
								$list4pro = '';
								$class = '';

								if (count($listproduct) > 3) {

									foreach ($listproduct as $itemproduct) {
										$price=Catalog::GetPrice($itemproduct->id,$notation);
										if ($k > 3) {

											$list4pro = '';
											$activelis = '';
											$k = 0;
										}
										if ($k == 0) {
											$class = 'col-lg-3 col-md-3 col-sm-4';
										} else if ($k == 1) {
											$class = 'col-lg-3 col-md-3 col-sm-4 hidden-xs';
										} else {
											$class = 'col-md-3 hidden-sm hidden-xs';
										}
										if ($k == 0) {
											$list4pro .='<div class="item ' . $activelis . '"><div class="row">';
										}
										$list4pro .= '<div class="' . $class . '"><a href="' . SITE_PATH . '/' . $itemproduct->slug . '.htm" class="thumbnail">' . Html::img(Image::thumb($itemproduct->image, 180, 180), ["alt" => "Images", "style" => "max-width:180px; max-height:170px; border:1px solid #ddd;padding:4px; border-radius:3px;"]) . '</a><div class="col-xs-7 title nopaddingright"><p>' . $itemproduct->title . '</p><p><b>' . formatprice($price, $notation) . ' ' . $notation . '</b></p></div><div class="col-xs-5 text-right nopaddingleft"><form id="w' . $itemproduct->id . '" action="/shopcart/buynow/' . $itemproduct->id . '" method="post" >  <input type="hidden" name="_csrf" value="Q3RTSmxoVWJzRBkfG1sFCikTIAY9LD4lIRwafiVFITpwPzsDGFBtVA==">  <input type="hidden" id="addtocartform-count-item_id" class="form-control" name="AddToCartForm[count]" value="1">    <input type="hidden" id="addtocartform-item_id" class="form-control" name="AddToCartForm[item_id]" value="' . $itemproduct->id . '">    <button type="submit" class="btn btn-primary">Mua ngay</button>       </form></div></div>';
										if ($k == 3) {
											$list4pro .= '</div></div>';
										}
										if ($k == 3) {
											$htmllistpro .= $list4pro;
										}

										$k++;
										$l++;
									}
								} else {
									foreach ($listproduct as $itemproduct) {
										$price=Catalog::GetPrice($itemproduct->id,$notation);
										$count = count($listproduct);
									   
										if ($k == 0) {
											$class = 'col-lg-3 col-md-3 col-sm-4';
										} else if ($k == 1) {
											$class = 'col-lg-3 col-md-3 col-sm-4 hidden-xs';
										} else {
											$class = 'col-md-3 hidden-sm hidden-xs';
										}
										if ($k == 0) {
											$list4pro .='<div class="item ' . $activelis . '"><div class="row">';
										}
										$list4pro .= '<div class="' . $class . '"><a href="' . SITE_PATH . '/' . $itemproduct->slug . '.htm" class="thumbnail">' . Html::img(Image::thumb($itemproduct->image, 180, 180), ["alt" => "Images", "style" => "max-width:180px; max-height:170px; border:1px solid #ddd;padding:4px; border-radius:3px;"]) . '</a><div class="col-xs-7 title nopaddingright"><p style="height: 50px; overflow: hidden;">' . $itemproduct->title . '</p><p><b>' . formatprice($price, $notation) . ' ' . $notation . '</b></p></div><div class="col-xs-5 text-right nopaddingleft"><form id="w' . $itemproduct->id . '" action="/hangvip/shopcart/buynow/' . $itemproduct->id . '" method="post" >  <input type="hidden" name="_csrf" value="Q3RTSmxoVWJzRBkfG1sFCikTIAY9LD4lIRwafiVFITpwPzsDGFBtVA==">  <input type="hidden" id="addtocartform-count-item_id" class="form-control" name="AddToCartForm[count]" value="1">    <input type="hidden" id="addtocartform-item_id" class="form-control" name="AddToCartForm[item_id]" value="' . $itemproduct->id . '">    <button type="submit" class="btn btn-primary">Mua ngay</button>       </form></div></div>';
										if ($k == $count-1) {
										   
											$list4pro .= '</div></div>';
										   
										}

										$k++;
										$l++;
									}

									$htmllistpro .= $list4pro;
								}
								if(count($listproduct)<1){
									$category_title="";
									$ul .='';
								}else{
									$category_title=$items->title;
									if($items->status==1){
										$ul .=' <li  role="presentation" class="' . $active . '"><a href="#' . $i . $j . '" aria-controls="home" role="tab" data-toggle="tab">' . $category_title . '</a></li> ';
									}else{
										$ul .='';
									}									
								}
								//$ul .=' <li  role="presentation" class="' . $active . '"><a href="#' . $i . $j . '" aria-controls="home" role="tab" data-toggle="tab">' . $category_title . '</a></li> ';
								$tabcontent .='                                 
								<div role="tabpanel" class="tab-pane ' . $active . '" id="' . $i . $j . '">
									<div class="row list-prouct ">
										<div class="col-md-12 col-xs-12">
											<div id="Carousel' . $i . $j . '" class="carousel slide">
												<div class="carousel-inner">                                                
													' . $htmllistpro . '
												</div>
												<a data-slide="prev" href="#Carousel' . $i . $j . '" class="left carousel-control">	<span class="glyphicon glyphicon-chevron-left"></span></a>
												<a data-slide="next" href="#Carousel' . $i . $j . '" class="right carousel-control"><span class="glyphicon glyphicon-chevron-right"></span></a>
											</div>

										</div>
									</div>
								</div>'
								?>
								<?php
								$j++;
							}
						}
						$tabcontent.= '</div>';
					} else {

						$ul = '';
						$active = 'active';
						$listproduct = Catalog::last(20, ['category_id' => $item->category_id]);
						$htmllistpro = '';
						$k = 0;
						$l = 0;
						$activelis = ($l == 0 ) ? 'active' : '';
						$list4pro = '';
						$class = '';

						foreach ($listproduct as $itemproduct) {
							$price=Catalog::GetPrice($itemproduct->id,$notation);
							if ($k > 3) {
								$htmllistpro = '';
								$activelis = '';
								$k = 0;
							}
							if ($k == 0) {
								$class = 'col-lg-3 col-md-3 col-sm-4';
							} else if ($k == 1) {
								$class = 'col-lg-3 col-md-3 col-sm-4 hidden-xs';
							} else {
								$class = 'col-md-3 hidden-sm hidden-xs';
							}
							if ($k == 0) {
								$list4pro .='<div class="item ' . $activelis . '"><div class="row">';
							}
							$list4pro .= '<div class="' . $class . '"><a href="'. SITE_PATH . '/' . $itemproduct->slug .'.htm" class="thumbnail">'.Html::img(Image::thumb($itemproduct->image,180,180),["alt"=>"Images", "style"=>"max-width:180px; max-height:170px; border:1px solid #ddd;padding:4px; border-radius:3px;"]).'</a><div class="col-xs-7 title nopaddingright"><p>'.$itemproduct->title.'</p><p><b>'.  formatprice($price, $notation).' '.$notation.'</b></p></div><div class="col-xs-5 text-right nopaddingleft"><form id="w'.$itemproduct->id.'" action="/shopcart/buynow/'.$itemproduct->id.'" method="post" >  <input type="hidden" name="_csrf" value="Q3RTSmxoVWJzRBkfG1sFCikTIAY9LD4lIRwafiVFITpwPzsDGFBtVA==">  <input type="hidden" id="addtocartform-count-item_id" class="form-control" name="AddToCartForm[count]" value="1">    <input type="hidden" id="addtocartform-item_id" class="form-control" name="AddToCartForm[item_id]" value="'.$itemproduct->id.'">    <button type="submit" class="btn btn-primary">Mua ngay</button>       </form></div></div>';
							if ($k == 3) {
								$list4pro .= '</div></div>';
							}
							if ($k == 3) {
								$htmllistpro .= $list4pro;
							}

							$k++;
							$l++;
						}
						$tabcontent = '<div class="tab-content clear">
								<div role="tabpanel" class="tab-pane active " id="' . $i . 1 . '">
									<div class="row list-prouct ">
										<div class="col-md-12 col-xs-12">
											<div id="Carousel' . $i . 0 . '" class="carousel slide">
												<div class="carousel-inner">                                                
													' . $htmllistpro . '
												</div>
												<a data-slide="prev" href="#Carousel' . $i . 0 . '" class="left carousel-control">	<span class="glyphicon glyphicon-chevron-left"></span></a>
												<a data-slide="next" href="#Carousel' . $i . 0 . '" class="right carousel-control"><span class="glyphicon glyphicon-chevron-right"></span></a>
											</div>

										</div>
									</div>
								</div>
							</div>';
					}
					?>

					<h2 class=" pull-left "><a href="<?= SITE_PATH . '/' . $item->slug ?>.html"><?= $item->title ?></a></h2>
					<ul class="nav nav-tabs pull-right"  role="tablist">
						<?= $ul; ?>
					</ul>
					<?= $tabcontent ?>
				</div>
			</div>
			<?php
			$i++;
		}
	
}
?>







