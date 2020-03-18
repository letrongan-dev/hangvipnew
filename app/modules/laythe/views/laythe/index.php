<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\payment\models\Payment;
use yii\easyii\helpers\Globals;
use app\modules\laythe\models\Order;
use amnah\yii2\user\models\Addresses;
use yii\easyii\models\Setting;$this->title = "Lấy thẻ";
?>
<div class="breadcrumbs">
    <a href="<?php echo SITE_PATH;?>">Trang chủ</a>&nbsp;&nbsp;<span>/</span>&nbsp;&nbsp;
    <a href="#">Lấy thẻ</a>    
</div>
<div style="width: 253px; float: left; margin-bottom: 30px;">   
    <?php $this->beginContent('@app/views/layouts/random.php'); ?><?php $this->endContent(); ?>
    <div style="border-bottom: 1px solid #e4e4e4; height: 1px; margin-top: -1px;">&nbsp;</div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<style>
.laythe-web select {
    padding: 7px 8px;
    width: 237px;
}
.laythe-web input[type='text'] {
    padding: 7px 8px;
    width: 219px;
    margin-bottom: 10px;
}
.thanhtoan-web{padding:0px 10px;}
.thanhtoan-web th{color:#b62e2e; font-weight: bold;}
.table tr th{border-bottom: 1px dotted #ccc; padding-top: 3px; background-color: #e4e4e4; padding-left: 10px;}
.table tr td{border-bottom: 1px dotted #ccc; padding-top: 3px; padding-left: 10px;}
.send-shop input[type='text']{ padding:4px; width:220px;}
.send-shop label{display:inline-block; width: 80px;}
.modal-radio{ width: 100% !important;}
.send-shop h4{ margin: 20px 0px 10px 0px;}
.field-order-comment label{ vertical-align: top;}
.laythesl input,.laythett input{
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 2px;
    padding:5px;
    height:18px;
    width: 207px;
}
.laythesl{ margin-bottom: 8px;}
.sendlaythe{ margin-top: 20px;}
.sendlaythe label{ width: 75px !important;}
.sendlaythe input[type="text"]{
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 2px;
    padding: 5px;
    height: 18px;
    width: 300px;
}
</style>
<div class="laythe-web" style="padding: 0px 10px 20px 10px; margin-bottom: 5px; width:920px; float: right;">
    <h1 class="title-main"><a href="#">Lấy thẻ</a></h1>
    <?php $form = ActiveForm::begin(['action' => Url::to(['/laythe1/laythe/add'])]); ?>
        <div style='width:230px; float: left;'>
            <div style='margin-top: 10px; margin-bottom: 10px;'>
            <?php 
            $session = Yii::$app->session;        
            $notation = $session->get('notation');
            $listDatapayment = Payment::Getpayment($notation);
            $list=  Catalog::GetAll();
            $cata=  Catalog::GetAll1($notation);
            $cataid=  Catalog::GetIDOne($notation);
            $cataname=  Catalog::GetNameOne($notation);
            ?>
            
                <?php echo $form->field($model, 'loai_laythe')->dropDownList($list,
                        [
                            'onchange' => '$.get( "'.Url::toRoute('/laythe1/laythe/thanhtien').'", { 
                                id: document.getElementById("addtolaytheform-loai_laythe").value,
                                sl: document.getElementById("sl").value
                                } )
                                    .done(function( data ) {                                         
                                        var res = data.split(":");
                                        $( ".laythett" ).html(res[0]);
                                        $( "#price" ).html(res[1]);
                                        $( "#idthe" ).html(res[2]);
                                        $( "#namethe" ).html(res[3]);
                                    }
                                );
                            '
                        ]
                    )->label(false);?>
            </div>
            <div id="idthe"><input type="hidden" value="<?= $cataid;?>" id="idthe1" /></div>
            <div id="namethe"><input type="hidden" value="<?= $cataname;?>" id="namethe1" /></div>
            <div id="price"><input type="hidden" value="<?php echo $cata;//Globals::formatprice($cata);?>" id="hiddenprice" /></div>
            <div class="laythesl">
                <input type="text" id="sl" name="AddToLayTheForm[sl_laythe]" value="1" />
            </div>
            <div class="laythett">
                <?php echo "<input type='text' name='tt' value='".Globals::formatprice($cata)." ".$notation."' />";?>
            </div>
        </div>
        <div style='float: left;margin: 52px;text-align: center;width: 80px;'>
            <?php echo Html::submitButton(Yii::t('easyii', '->>'), ['class' => 'btn button_black btn-large btn-primary pull-right']);?>
        </div>
        <?php ActiveForm::end(); ?>
    
    <?php
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();
        $total = $cart->getCost();
    ?>  
    <div style="width: 500px; float: right; border:1px solid #e4e4e4; margin-top: 10px;">
        <table id="table" class="table" cellpadding="0" cellspacing="0" style="border-collapse: separate;">            
            <tr height="35">
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
            <?php 
                foreach ($products as $product){
                $quantity = $product->getQuantity(); ?>
                <tr>
                    <td><?php echo $product->title;?></td>
                    <td><?php echo $quantity;?></td>
                    <td><?php echo Globals::formatprice($product->price)." ".$notation;?></td>
                    <td><?php echo Globals::formatprice($product->price*$quantity)." ".$notation;?></td>
                    <td>
                        <?php 
                        echo Html::a('Remove', 
                                        ['/laythe1/laythe/remove', 
                                            'id' => $product->id
                                        ], 
                                        ['title' => 'Remove item', 
                                            'style' => 'color:red;'
                                        ]);?>
                    </td>
                </tr>
            <?php }?>
        </table>
        <div style="text-align:right;padding-right:10px; padding-top:7px;padding-bottom:7px;">
            Total: <?php echo Globals::formatprice($total).' '.$notation;?>
        </div>
    </div>
    <?php        
        $address = Addresses::findOne(['uid' => Yii::$app->users->id]);
        $session = Yii::$app->session;
        if ($address) {
            $modelor->name = $address->fullname;
            $modelor->address = $address->street;
            $modelor->email = Yii::$app->users->email;
            $modelor->phone = $address->phone;
            $modelor->city = $address->city;
            $modelor->country = $address->country_name;
            $modelor->zipcode = $address->postal_code;
        } else {
            $modelor->email = Yii::$app->users->email;
            $country = $session->get('country');
            $modelor->city = $country->city;
            $modelor->zipcode = $country->zip;
            $modelor->country = $country->country;
        }
        $modelor->scenario = 'confirm';        
        $notation = $session->get('notation');
        $listDatapayment = Payment::Getpayment($notation);
        if($total>0){
        
        $form = ActiveForm::begin([
            'action' => Url::to(['/laythe1/laythe/index']),
            'options' => [
                    'style'=>'clear: both;width: 500px; float: right;'
                ]  
        ]);
        echo "<div class='sendlaythe'>";
        echo "<div class='col-md-6'>";        
        echo $form->field($modelor, 'name');
        echo $form->field($modelor, 'email');
        echo $form->field($modelor, 'address');
        echo $form->field($modelor, 'phone');
        $listtypesubmit=array();
        $listtypesubmit[1]="Lấy thẻ";
        $listtypesubmit[2]="Đổi thẻ";
        echo $form->field($modelor, 'city')->textInput(['readonly' => true]);
        echo $form->field($modelor, 'country')->textInput(['readonly' => true]);
        echo $form->field($modelor, 'zipcode');
        echo "</div>";
        echo "<div>";
        echo $form->field($modelor, 'type_submit')->radioList($listtypesubmit)->label(false);
        echo "</div>";
        
            echo "<div style='margin-top:20px;'>";
            echo Html::submitButton(Yii::t('easyii', 'Mua ngay'), ['class' => 'btn button_black btn-large btn-primary pull-right']);
            echo "</div>";
        
        echo "<div class='cl'>&nbsp;</div>";
        echo "</div>";
        ActiveForm::end();        
        }
    ?>
</div>
