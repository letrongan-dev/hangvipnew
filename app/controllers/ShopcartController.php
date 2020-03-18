<?php

namespace app\controllers;

use app\models\AddToCartForm;
use Yii;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\shopcart\api\Shopcart;
use yii\web\NotFoundHttpException;
use yz\shoppingcart\ShoppingCart;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\modules\catalog\models\Price;
use yii\easyii\helpers\Globals;

class ShopcartController extends \yii\web\Controller {

    public $enableCsrfValidation = false;
    
     public function init() {
        $location = Yii::$app->geoip->lookupLocation();
        $currency = Globals::GetNotation($location->countryCode);
//       var_dump($location->countryCode);
//       die();             
        if($location->countryCode!="VN"){
                      
            Yii::$app->language = 'en';
        }
        else{
          
            Yii::$app->language ='vn';  
        }
    }
    
    
    
    public function actionIndex() {
        /*if (!isset(Yii::$app->users->id)) {
            return $this->redirect(["/user/login"]);
        }*/
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();
        $total = $cart->getCost();
        return $this->render('index', [
                    'products' => $products,
                    'total' => $total,
        ]);
    }
    public function actionSuccess() {
        return $this->render('success');
    }
    public function actionRemove($id) {
        $item = Catalog::get($id);
        if ($item) {
            \Yii::$app->cart->remove($item);
            return $this->redirect(SITE_PATH."/thanh-toan.html");
        }
    }
    public function actionBuynow($id) {
        $item = Catalog::get($id);
        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }
        $form = new AddToCartForm();
        $success = 0;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            //$response = Shopcart::add($item->id, $form->count, $form->color);
            \Yii::$app->cart->put($item, $form->count);
            //$success = $response['result'] == 'success' ? 1 : 0;
        }


        return $this->redirect(SITE_PATH."/thanh-toan.html");
    }
    public function actionUpdate() {
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();
        foreach ($products as $product){
            $number=(int)$_POST['number'.$product->getId()];
            if($number<1){
                $item = Catalog::get($product->getId());
                if ($item) {
                    \Yii::$app->cart->remove($item);
                }
            }else{
                $item = Catalog::get($product->getId());
                if ($item) {
                    \Yii::$app->cart->update($item, $number);
                }
            }
        }
        return $this->redirect(SITE_PATH."/thanh-toan.html");           
    }

    public function actionOrder($id, $token) {
        $order = Shopcart::order($id);
        if (!$order || $order->access_token != $token) {
            throw new NotFoundHttpException('Order not found');
        }

        return $this->render('order', ['order' => $order]);
    }
	
	public function actionRemovecart() {
        $id = $_POST['id'];
        $item = Catalog::get($id);
        if ($item) {
            \Yii::$app->cart->remove($item);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $cart = \Yii::$app->cart;
            $products = $cart->getPositions();
            if(count($products)>0){
                $tong=0;
                foreach ($products as $product) {
                    $quantity = $product->getQuantity();
                    $item_info= Item::findOne($product->getId());
                    $get_price= Price::getPriceProduct($product->getId());
                    if($item_info['price']==0){
                        $gia_sp=$get_price;
                    }else{
                        $gia_sp=$item_info['price'];
                    }
                    $giaban=$gia_sp;
                    $thanhtien=$gia_sp*$quantity;
                    $tong=$tong+$thanhtien;
                }
                return number_format($tong, 0, ',', '.');
            }else{
                return $this->redirect(SITE_PATH . "/thanh-toan.html");
            }
        }
    }
    public function actionUpdateasc() {
        $id=$_POST['id'];        
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();
        $sl=0;
        foreach ($products as $pr){
            if($pr->id==$id){
                $sl=$pr->getQuantity();
            }
        }
        $quantity=$sl+1;
        $item = Catalog::get($id);
        if ($item) {            
            \Yii::$app->cart->update($item, $quantity);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $cart = \Yii::$app->cart;            
            $products = $cart->getPositions();
            foreach ($products as $product) {
                $item_info= Item::findOne($product->getId());
                $get_price= Price::getPriceProduct($product->getId());
                if($item_info['price']==0){
                    $gia_sp=$get_price;
                }else{
                    $gia_sp=$item_info['price'];
                }
                $thanhtien=$gia_sp*$product->getQuantity();
                $tong=$tong+$thanhtien;
                if($product->id==$id){
                    $thanhtien_id=$gia_sp*$product->getQuantity();
                }                
            }
            $arr=$quantity.":".number_format($thanhtien_id, 0, ',', '.').":".number_format($tong, 0, ',', '.');
            return $arr;
        }
        
    }
    public function actionUpdatedesc() {
        $id=$_POST['id'];        
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();
        $sl=0;
        foreach ($products as $pr){
            if($pr->id==$id){
                $sl=$pr->getQuantity();
            }
        }
        if($sl==0||$sl==1){ // remove
            $item = Catalog::get($id);
            if ($item) {
                \Yii::$app->cart->remove($item);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $cart = \Yii::$app->cart;
                $products = $cart->getPositions();                
                if(count($products)>0){
                    $tong=0;
                    foreach ($products as $product) {
                        $item_info= Item::findOne($product->getId());
                        $get_price= Price::getPriceProduct($product->getId());
                        if($item_info['price']==0){
                            $gia_sp=$get_price;
                        }else{
                            $gia_sp=$item_info['price'];
                        }
                        $thanhtien=$gia_sp*$product->getQuantity();
                        $tong=$tong+$thanhtien;                               
                    }
                    $arr="0:0:".number_format($tong, 0, ',', '.').":remove";
                    return $arr;
                }else{
                    return $this->redirect(SITE_PATH . "/thanh-toan.html");
                }
            }
        }else{
            $quantity=$sl-1;
            $item = Catalog::get($id);
            if ($item) {            
                \Yii::$app->cart->update($item, $quantity);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $cart = \Yii::$app->cart;            
                $products = $cart->getPositions();
                $tong=0;
                foreach ($products as $product) {
                    $item_info= Item::findOne($product->getId());
                    $get_price= Price::getPriceProduct($product->getId());
                    if($item_info['price']==0){
                        $gia_sp=$get_price;
                    }else{
                        $gia_sp=$item_info['price'];
                    }
                    $thanhtien=$gia_sp*$product->getQuantity();
                    $tong=$tong+$thanhtien;
                    if($product->id==$id){
                        $thanhtien_id=$gia_sp*$product->getQuantity();
                    }                
                }
                $arr=$quantity.":".number_format($thanhtien_id, 0, ',', '.').":".number_format($tong, 0, ',', '.').":noremove";
                return $arr;
            }
        }
    }
}
