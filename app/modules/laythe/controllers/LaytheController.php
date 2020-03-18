<?php
namespace app\modules\laythe\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\easyii\helpers\Globals;
use yii\easyii\modules\payment\models\Payment;
use yii\easyii\helpers\Mail;
use amnah\yii2\user\models\Addresses;
use yii\easyii\helpers\Onepay;
use yii\easyii\helpers\OnepayND;
use yii\easyii\helpers\Paypal;

use app\modules\laythe\models\AddToLayTheForm;
use yii\easyii\modules\catalog\api\Catalog;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\modules\catalog\models\Price;
use app\modules\laythe\models\Order;
use app\modules\laythe\models\OrderItem;

class LaytheController extends \yii\web\Controller
{
    public function actionIndex($tag = null)
    {   
        if (!isset(Yii::$app->users->id)) {
            return $this->redirect(["/user/login"]);
        }
        $model = new AddToLayTheForm();
        $modelor = new Order;
        if (isset($_POST['Order'])) {
            $modelor->load(\Yii::$app->request->post());
            if($modelor->validate()){
                $getaddress = Addresses::findOne(['uid' => Yii::$app->users->id]);
                if (!$getaddress) {
                    $address = new Addresses;
                    $address->uid = Yii::$app->users->id;$address->fullname = $_POST['Order']['name'];
                    $address->street = $_POST['Order']['address'];$address->email = Yii::$app->users->email;
                    $address->phone = $_POST['Order']['phone'];$address->city = $_POST['Order']['city'];
                    $address->country = $_POST['Order']['country'];$coutry = Yii::$app->session->get('country');
                    $address->country_code = $coutry->countryCode;$address->created = time();
                    if ($address->validate()) { $address->save();   echo "ad"; die();  }
                }
                $notation = Yii::$app->session->get('notation');
                $modelor->status = "pending";$payment_method = "laythe";
                $payment = Payment::findOne(['name' => $payment_method]);
                $modelor->store_currency = $notation;$modelor->payment_method=$payment_method;
                $modelor->sale_currency = $notation;$modelor->order_fee=0;
                
                $cart = \Yii::$app->cart;
                $products = $cart->getPositions();
                $total = $cart->getCost();

                $modelor->order_total=$total;$modelor->time = time();
                $modelor->user_id = Yii::$app->users->id;$modelor->zipcode = $_POST['Order']['zipcode'];
                $modelor->workflow = 1;$modelor->order_code = time();
                // save Order
                $transaction = $modelor->getDb()->beginTransaction();
                $modelor->save(false);
                foreach ($products as $product) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $modelor->order_id;
                    $orderItem->title = $product->title;
                    $orderItem->price = $product->getPrice();
                    $orderItem->item_id = $product->id;
                    $orderItem->quantity = $product->getQuantity();
                    if (!$orderItem->save(false)) {
                        $transaction->rollBack();
                        \Yii::$app->session->addFlash('error', 'Cannot place your order. Please contact us.');
                        return $this->goBack();
                    }
                }
                if($modelor->type_submit==1){ $typesubmit="Lấy thẻ";}else{ $typesubmit="Đổi thẻ";}
                $bodyMail = "<table cellspacing='0' cellpadding='0' border='0' style='font:11px Verdana,Arial,Helvetica,sans-serif;color:#333'>
                                        <tr><td colspan='2'>Thanks for your order</td></tr>
                                        <tr><td colspan='2'>&nbsp;</td></tr>
                                        <tr><td colspan='2' style='background: #0184C2; color: #fff;'>Purchasing Information:</td></tr>
                                        <tr><td width='150'>E-mail Address:</td>
                                            <td width='370'>" . $modelor->email . "</td>
                                        </tr>
                                        <tr><td width='150'>Billing Address:</td>
                                            <td>" . $modelor->address . "</td>
                                        </tr>
                                        <tr><td width='150'>Billing Phone:</td>
                                            <td>" . $modelor->phone . "</td>
                                        </tr>
                                        <tr><td width='150'>Order Grand Total:</td>
                                            <td>" . number_format(($modelor->order_total + $modelor->order_fee), 2) . " " . $modelor->sale_currency . "</td>
                                        </tr>
                                        <tr><td width='150'>Payment Method:</td>
                                            <td>" . $modelor->payment_method . "</td>
                                        </tr>
                                        <tr><td width='150'>Type submit:</td>
                                            <td>" .$typesubmit. "</td>
                                        </tr>
                                        ";
                        $bodyMail .= "<tr><td colspan='2'>&nbsp;</td></tr>
                                      <tr><td colspan='2' style='background: #0184C2; color: #fff;'>Order Summary:</td></tr>
                                      <tr><td width='150'>Order:</td>
                                          <td>#" . $modelor['order_id'] . "</td>
                                      </tr>
                                      <tr><td>Products on order: </td><td></td></tr>
                                      <tr><td colspan='2'>" . OrderItem::getListProduct($modelor['order_id']) . "</td></tr>
                                      </table>";
                        $subject = "Your Order at Trumgame";
                        $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
                        Mail::send(
                                $modelor->email, $subject, $settings['templateOnNewOrder'], [
                                    'order' => $bodyMail,
                                ]
                        );
                $transaction->commit();
                \Yii::$app->cart->removeAll();
                Yii::$app->session->setFlash("success","Lấy thẻ thành công. Kiểm tra email để xem chi tiết đơn hàng");
                Yii::$app->response->redirect(SITE_PATH);
                Yii::$app->end();
                // end save order
            }// end validate modelor
        }
        return $this->render('index', [
                'model' => $model,
                'modelor' => $modelor,
            ]);
    }
    public function actionThanhtien() {        
        $request = Yii::$app->request;
        if ($request->get()) {
            $item_id = Yii::$app->request->get('id');
            $sl = Yii::$app->request->get('sl');
            $session = Yii::$app->session;        
            $notation = $session->get('notation');
            $total=  Catalog::GetPriceOne($sl, $item_id, $notation);
            $price=  Catalog::GetPrice($item_id, $notation);
            $item=  Item::find()->where('item_id='.$item_id)->one();
            //echo "<input type='text' name='sl' value='".Globals::formatprice($total)." ".$notation."' />:<input type='hidden' value='".Globals::formatprice($price)."' id='hiddenprice' />:<input type='hidden' value='".$item_id."' id='idthe1' />:<input type='hidden' value='".$item['title']."' id='namethe1' />";
			echo "<input type='text' name='sl' value='".Globals::formatprice($total)." ".$notation."' />:<input type='hidden' value='".$price."' id='hiddenprice' />:<input type='hidden' value='".$item_id."' id='idthe1' />:<input type='hidden' value='".$item['title']."' id='namethe1' />";
        }
    }
    public function actionAdd() {        
        $form = new AddToLayTheForm();
        $request = Yii::$app->request;
        $form->load($request->post());
        $form->attributes = $_POST['AddToLayTheForm'];
        $success = 0;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            //$response = Shopcart::add($item->id, $form->count);
            $item = Catalog::get($form->loai_laythe);
            if (!$item) {
                throw new NotFoundHttpException('Item not found');
            }
            \Yii::$app->cart->put($item, $form->sl_laythe);
            $success = 1;
        }
        return $this->redirect(["/lay-the"]);
        //return $this->redirect(Yii::$app->request->referrer . '?' . AddToCartForm::SUCCESS_VAR . '=' . $success);
    }
    public function actionRemove($id) {
        $item = Catalog::get($id);
        if ($item) {
            \Yii::$app->cart->remove($item);            
        }
        return $this->redirect(["/lay-the"]);
    }
    public function actionSend() {
        
        $model = new Order();
        $request = Yii::$app->request;
        $model->load($request->post());
        $model->setAttributes($_POST['Order']);
        $model->validate();
        
        $model->attributes = $_POST['Order'];        
        $getaddress = Addresses::findOne(['uid' => Yii::$app->users->id]);
        if (!$getaddress) {
            $address = new Addresses;
            $address->uid = Yii::$app->users->id;
            $address->fullname = $_POST['Order']['name'];
            $address->street = $_POST['Order']['address'];
            $address->email = Yii::$app->users->email;
            $address->phone = $_POST['Order']['phone'];
            $address->city = $_POST['Order']['city'];
            $address->country = $_POST['Order']['country'];            
            $coutry = Yii::$app->session->get('country');
            $address->country_code = $coutry->countryCode;
            $address->created = time();
            if ($address->validate()) {
                $address->save();
                echo "ad"; die();
            }
        }
        $vpcURL = '';
        $notation = Yii::$app->session->get('notation');
        $model->status = "pending";
        $payment_method = "laythe";
        $payment = Payment::findOne(['name' => $payment_method]);
        $model->store_currency = $notation;
        $model->payment_method=$payment_method;
        $model->sale_currency = $notation;
        $model->order_fee=0;
        
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();
        $total = $cart->getCost();
        
        $model->order_total=$total;
        $model->time = time();
        $model->user_id = Yii::$app->users->id;
        $model->zipcode = $_POST['Order']['zipcode'];//$data['zipcode'];
        $model->workflow = 1;
        $model->order_code = time();      
        
        if ($model->validate()) {        
            echo "a"; die();
            $transaction = $model->getDb()->beginTransaction();
            $model->save(false);
            foreach ($products as $product) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $model->order_id;
                $orderItem->title = $product->title;
                $orderItem->price = $product->getPrice();
                $orderItem->item_id = $product->id;
                $orderItem->quantity = $product->getQuantity();
                if (!$orderItem->save(false)) {
                    $transaction->rollBack();
                    \Yii::$app->session->addFlash('error', 'Cannot place your order. Please contact us.');
                    return $this->goBack();
                }
            }
            if($model->type_submit==1){ $typesubmit="Lấy thẻ";}else{ $typesubmit="Đổi thẻ";}
            $bodyMail = "<table cellspacing='0' cellpadding='0' border='0' style='font:11px Verdana,Arial,Helvetica,sans-serif;color:#333'>
                                    <tr><td colspan='2'>Thanks for your order</td></tr>
                                    <tr><td colspan='2'>&nbsp;</td></tr>
                                    <tr><td colspan='2' style='background: #0184C2; color: #fff;'>Purchasing Information:</td></tr>
                                    <tr><td width='150'>E-mail Address:</td>
                                        <td width='370'>" . $model->email . "</td>
                                    </tr>
                                    <tr><td width='150'>Billing Address:</td>
                                        <td>" . $model->address . "</td>
                                    </tr>
                                    <tr><td width='150'>Billing Phone:</td>
                                        <td>" . $model->phone . "</td>
                                    </tr>
                                    <tr><td width='150'>Order Grand Total:</td>
                                        <td>" . number_format(($model->order_total + $model->order_fee), 2) . " " . $model->sale_currency . "</td>
                                    </tr>
                                    <tr><td width='150'>Payment Method:</td>
                                        <td>" . $model->payment_method . "</td>
                                    </tr>
                                    <tr><td width='150'>Type submit:</td>
                                        <td>" .$typesubmit. "</td>
                                    </tr>
                                    ";
                    $bodyMail .= "<tr><td colspan='2'>&nbsp;</td></tr>
                                  <tr><td colspan='2' style='background: #0184C2; color: #fff;'>Order Summary:</td></tr>
                                  <tr><td width='150'>Order:</td>
                                      <td>#" . $model['order_id'] . "</td>
                                  </tr>
                                  <tr><td>Products on order: </td><td></td></tr>
                                  <tr><td colspan='2'>" . OrderItem::getListProduct($model['order_id']) . "</td></tr>
                                  </table>";
                    $subject = "Your Order at Trumgame";
                    $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
                    Mail::send(
                            $model->email, $subject, $settings['templateOnNewOrder'], [
                                'order' => $bodyMail,
                            ]
                    );
            $transaction->commit();
            \Yii::$app->cart->removeAll();
            Yii::$app->session->setFlash("success","Lấy thẻ thành công. Kiểm tra email để xem chi tiết đơn hàng");
            Yii::$app->response->redirect(SITE_PATH);
            Yii::$app->end();
        }// end validate
       
    }
}
