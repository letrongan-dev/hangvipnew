<?php

namespace app\controllers;

use yii\easyii\modules\shopcart\models\Order;
use Yii;
use yii\easyii\modules\shopcart\models\Good;
use yii\easyii\helpers\Mail;

use yii\easyii\modules\usermoney\models\UserHistoryMoney;
use yii\easyii\modules\usermoney\models\UserMoney;
use yii\easyii\helpers\Globals;


class RepaymoneyController extends \yii\web\Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionOnecomnd() {
        //Start working with repay oncome here
        if (isset($_GET["vpc_SecureHash"]) && isset($_GET["vpc_MerchTxnRef"])) {
            $SECURE_SECRET = "3C27F492A0F05BF49CD60D24AE0A5929";
            $vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
            $vpc_MerchTxnRef = $_GET["vpc_MerchTxnRef"];
            unset($_GET["vpc_SecureHash"]);
            $array_merchtxnref = explode("_", $vpc_MerchTxnRef);
            $querystring = '';
            foreach ($_GET as $key => $value) {
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }
            $upadteOrder = UserHistoryMoney::updateAll(array('data' => $querystring), ['order_id' => $array_merchtxnref[1]]);
            if (count($array_merchtxnref) == 3 && $array_merchtxnref[0] == 'Order' && is_numeric($array_merchtxnref[1]) && is_numeric($array_merchtxnref[2])) {
                $errorExists = false;
                if (strlen($SECURE_SECRET) > 0 && $_GET["vpc_TxnResponseCode"] != "7" && $_GET["vpc_TxnResponseCode"] != "No Value Returned") {
                    ksort($_GET);
                    $md5HashData = "";
                    foreach ($_GET as $key => $value) {
                        if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                            $md5HashData .= $key . "=" . $value . "&";
                        }
                    }
                    $md5HashData = rtrim($md5HashData, "&");
                    if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*', $SECURE_SECRET)))) {
                        $hashValidated = "CORRECT";
                    } else {
                        $hashValidated = "INVALID HASH";
                    }
                } else {
                    $this->redirect($this->goHome());
                    exit();
                }
                $amount = ($_GET["vpc_Amount"]);
                $locale = ($_GET["vpc_Locale"]);
                $command = ($_GET["vpc_Command"]);
                $version = ($_GET["vpc_Version"]);
                $orderInfo = ($_GET["vpc_OrderInfo"]);
                $merchantID = ($_GET["vpc_Merchant"]);
                $merchTxnRef = ($_GET["vpc_MerchTxnRef"]);
                $transactionNo = ($_GET["vpc_TransactionNo"]);
                $txnResponseCode = ($_GET["vpc_TxnResponseCode"]);
                $orderId = $array_merchtxnref[1];
                $order_info = UserHistoryMoney::findOne(['id' => $orderId]);
                $html = '';
                $transStatus = "";
                $returnurl = '';
                $status_oncome = '';
                if ($hashValidated == "CORRECT" && $txnResponseCode == "0") {
                    $status_oncome = 'completed';
                    UserHistoryMoney::updateAll(['status' => 'completed'], ['id' => $array_merchtxnref[1]]);
                    
                    
                    $money_send=$order_info['money_send'];
                    $currency_send=$order_info['currency_send'];
                    if($currency_send=="VND"){                        
                        $mon= Globals::convertVNDUSD($money_send);
                    }elseif($currency_send=="AUD"){
                        $mon= Globals::convertAUDUSD($money_send);
                    }else{
                        $mon=$money_send;
                    }
                    $check=  UserMoney::checkUid(Yii::$app->users->id);
                    if($check){
                        // update tien
                        $usermoney=  UserMoney::find()->where('uid='.$uid)->one();
                        $usermoney->money=$usermoney['money']+$mon;
                        $usermoney->save();
                    }else{
                        $usermoney=new UserMoney();
                        $usermoney->uid=Yii::$app->users->id;
                        $usermoney->money=$mon;
                        $usermoney->currency='USD';
                        $usermoney->save();
                    }
                    
                    $html .='<p style="font-size:14px;font-weight:bold;line-height:20px;">Cám ơn quý khách, quá trình thanh toán đã được hoàn tất !
                             <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "CORRECT" && $txnResponseCode != "0") {
                    $status_oncome = 'canceled';
                    UserHistoryMoney::updateAll(['status' => 'canceled'], ['id' => $array_merchtxnref[1]]);
                    $html.='<p style="font-size:14px;font-weight:bold;line-height:20px;">Quá trình thanh toán không thành công bạn vui lòng thực hiện lại !
                                <br><a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "INVALID HASH") {
                    $status_oncome = 'pending';
                    $html.='Quá trình thanh toán đang trong trạng thái chờ 
                        <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';

                    UserHistoryMoney::updateAll(['status' => 'pending'], ['id' => $array_merchtxnref[1]]);
                }/*
                $bodyMail = "<table cellspacing='0' cellpadding='0' border='0' style='font:11px Verdana,Arial,Helvetica,sans-serif;color:#333'>
                                <tr><td colspan='2'>Thanks for your order</td></tr>
                                <tr><td colspan='2'>&nbsp;</td></tr>
                                <tr><td colspan='2' style='background: #0184C2; color: #fff;'>Purchasing Information:</td></tr>
                                <tr><td width='150'>E-mail Address:</td>
                                    <td width='370'>" . $order_info['email'] . "</td>
                                </tr>
                                <tr><td width='150'>Billing Address:</td>
                                    <td>" . $order_info['address'] . "</td>
                                </tr>
                                <tr><td width='150'>Billing Phone:</td>
                                    <td>" . $order_info['phone'] . "</td>
                                </tr>
                                <tr><td width='150'>Order Grand Total:</td>
                                    <td>" . $amount . "</td>
                                </tr>
                                <tr><td width='150'>Payment Method:</td>
                                    <td>" . $order_info['payment_method'] . "</td>
                                </tr>
                                ";

                if ($status_oncome == 'completed') {
                    $bodyMail .= '<tr><td>Status Order: </td><td>Payment Successful</td></tr>
                                    <tr><td colspan="2"><b>We will send cards in soon</b></td></tr>';
                } else {
                    $bodyMail .= '<tr><td>Status Order: </td><td>Payment Fail</td></tr>
                                    <tr><td colspan="2"><b style="color: red;">You should try to payment again </b></td></tr>';
                }
                $bodyMail .= "<tr><td colspan='2'>&nbsp;</td></tr>
                                  <tr><td colspan='2' style='background: #0184C2; color: #fff;'>Order Summary:</td></tr>
                                  <tr><td width='150'>Order #:</td>
                                  <td>" . $orderId . "</td>
                                  </tr>
                                  <tr><td>Products on order: </td><td></td></tr>
                                  <tr><td colspan='2'>" . Good::getListProduct($orderId) . "</td></tr>
                                  </table>";
                $subject = "Your Order at Easy";
                $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
                Mail::send(
                            $order_info['email'], $subject, $settings['templateOnNewOrder'], [
                            'order' => $bodyMail,
                        ]
                );
                $request = Yii::$app->request->post;*/
                return $this->render('index', array('html' => $html));
            } else {
                $this->redirect($this->goHome());
                exit();
            }
        } else {
            $this->redirect($this->goHome());
            exit();
        }
    }

    public function actionOnecom() {

        if (isset($_GET["vpc_SecureHash"]) && isset($_GET["vpc_MerchTxnRef"]) && isset($_GET["vpc_TxnResponseCode"])) {

            // *********************
            // START OF MAIN PROGRAM
            // *********************
            // Define Constants
            // ----------------
            // This is secret for encoding the MD5 hash
            // This secret will vary from merchant to merchant
            // To not create a secure hash, let SECURE_SECRET be an empty string - ""
            // $SECURE_SECRET = "secure-hash-secret";
            $SECURE_SECRET = "EB27A9161BBB79239D69D9F5A9E02D7D";

            // get and remove the vpc_TxnResponseCode code from the response fields as we
            // do not want to include this field in the hash calculation
            $vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
            $vpc_MerchTxnRef = $_GET["vpc_MerchTxnRef"];
            $vpc_AcqResponseCode = $_GET["vpc_AcqResponseCode"];
            unset($_GET["vpc_SecureHash"]);
            $array_merchtxnref = explode("_", $vpc_MerchTxnRef);

            $querystring = '';
            foreach ($_GET as $key => $value) {
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }
            if (count($array_merchtxnref) == 3 && $array_merchtxnref[0] == 'MeRef' && is_numeric($array_merchtxnref[1]) && is_numeric($array_merchtxnref[2])) {

                $upadteOrder = UserHistoryMoney::updateAll(array('data' => $querystring), ['id' => $array_merchtxnref[1]]);
                $errorExists = false;
                if (strlen($SECURE_SECRET) > 0 && $_GET["vpc_TxnResponseCode"] != "7" && $_GET["vpc_TxnResponseCode"] != "No Value Returned") {
                    ksort($_GET);
                    //$md5HashData = $SECURE_SECRET;
                    //khởi tạo chuỗi mã hóa rỗng
                    $md5HashData = "";
                    // sort all the incoming vpc response fields and leave out any with no value
                    foreach ($_GET as $key => $value) {
                        //        if ($key != "vpc_SecureHash" or strlen($value) > 0) {
                        //            $md5HashData .= $value;
                        //        }
                        //      chỉ lấy các tham số bắt đầu bằng "vpc_" hoặc "user_" và khác trống và không phải chuỗi hash code trả về
                        if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                            $md5HashData .= $key . "=" . $value . "&";
                        }
                    }
                    //  Xóa dấu & thừa cuối chuỗi dữ liệu
                    $md5HashData = rtrim($md5HashData, "&");
                    //    if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper ( md5 ( $md5HashData ) )) {
                    //    Thay hàm tạo chuỗi mã hóa
                    if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*', $SECURE_SECRET)))) {
                        // Secure Hash validation succeeded, add a data field to be displayed
                        // later.
                        $hashValidated = "CORRECT";
                    } else {
                        // Secure Hash validation failed, add a data field to be displayed
                        // later.
                        $hashValidated = "INVALID HASH";
                    }
                } else {
                    // Secure Hash was not validated, add a data field to be displayed later.
                    $this->redirect($this->goHome());
                    exit();
                    // $hashValidated = "INVALID HASH";
                }

                // Define Variables
                // ----------------
                // Extract the available receipt fields from the VPC Response
                // If not present then let the value be equal to 'No Value Returned'
                // Standard Receipt Data
                $amount = ($_GET["vpc_Amount"]);
                $locale = ($_GET["vpc_Locale"]);
                $batchNo = ($_GET["vpc_BatchNo"]);
                $command = ($_GET["vpc_Command"]);
                $message = ($_GET["vpc_Message"]);
                $version = ($_GET["vpc_Version"]);
                $cardType = ($_GET["vpc_Card"]);
                $orderInfo = ($_GET["vpc_OrderInfo"]);
                $receiptNo = ($_GET["vpc_ReceiptNo"]);
                $merchantID = ($_GET["vpc_Merchant"]);
                //$authorizeID = null2unknown($_GET["vpc_AuthorizeId"]);
                $merchTxnRef = ($_GET["vpc_MerchTxnRef"]);
                $transactionNo = ($_GET["vpc_TransactionNo"]);
                $acqResponseCode = ($_GET["vpc_AcqResponseCode"]);
                $txnResponseCode = ($_GET["vpc_TxnResponseCode"]);
                // 3-D Secure Data
                $verType = array_key_exists("vpc_VerType", $_GET) ? $_GET["vpc_VerType"] : "No Value Returned";
                $verStatus = array_key_exists("vpc_VerStatus", $_GET) ? $_GET["vpc_VerStatus"] : "No Value Returned";
                $token = array_key_exists("vpc_VerToken", $_GET) ? $_GET["vpc_VerToken"] : "No Value Returned";
                $verSecurLevel = array_key_exists("vpc_VerSecurityLevel", $_GET) ? $_GET["vpc_VerSecurityLevel"] : "No Value Returned";
                $enrolled = array_key_exists("vpc_3DSenrolled", $_GET) ? $_GET["vpc_3DSenrolled"] : "No Value Returned";
                $xid = array_key_exists("vpc_3DSXID", $_GET) ? $_GET["vpc_3DSXID"] : "No Value Returned";
                $acqECI = array_key_exists("vpc_3DSECI", $_GET) ? $_GET["vpc_3DSECI"] : "No Value Returned";
                $authStatus = array_key_exists("vpc_3DSstatus", $_GET) ? $_GET["vpc_3DSstatus"] : "No Value Returned";

                $orderId = $array_merchtxnref[1];
                $order_info = UserHistoryMoney::findOne(['id' => $orderId]);
                $html = '';
                $transStatus = "";
                $status_oncome = '';
                if ($hashValidated == "CORRECT" && $txnResponseCode == "0") {
                    //$transStatus = "Giao dịch thành công";
                    $status_oncome = 'completed';
                    UserHistoryMoney::updateAll(['status' => $status_oncome], ['id' => $array_merchtxnref[1]]);
                    // cong tiền vào ví
                    $money_send=$order_info['money_send'];
                    $currency_send=$order_info['currency_send'];
                    if($currency_send=="VND"){                        
                        $mon= Globals::convertVNDUSD($money_send);
                    }elseif($currency_send=="AUD"){
                        $mon= Globals::convertAUDUSD($money_send);
                    }else{
                        $mon=$money_send;
                    }
                    $check=  UserMoney::checkUid(Yii::$app->users->id);
                    if($check){
                        // update tien
                        $usermoney=  UserMoney::find()->where('uid='.$uid)->one();
                        $usermoney->money=$usermoney['money']+$mon;
                        $usermoney->save();
                    }else{
                        $usermoney=new UserMoney();
                        $usermoney->uid=Yii::$app->users->id;
                        $usermoney->money=$mon;
                        $usermoney->currency='USD';
                        $usermoney->save();
                    }
                    
                    $html .='<p style="font-size:14px;font-weight:bold;line-height:20px;">Cám ơn quý khách, quá trình thanh toán đã được hoàn tất !
                    <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "CORRECT" && $txnResponseCode != "0") {
                    //$transStatus = "Giao dịch thất bại";
                    $status_oncome = 'canceled';
                    UserHistoryMoney::updateAll(['status' => $status_oncome], ['id' => $array_merchtxnref[1]]);
                    $html.='<p style="font-size:14px;font-weight:bold;line-height:20px;">Quá trình thanh toán không thành công bạn vui lòng thực hiện lại !
                        <br><a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "INVALID HASH") {
                    //$transStatus = "Giao dịch Pendding";
                    $status_oncome = 'pending';
                    $html.='Quá trình thanh toán đang trong trạng thái chờ 
                           <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                    UserHistoryMoney::updateAll(['status' => $status_oncome], ['id' => $array_merchtxnref[1]]);
                }

                /*
                $bodyMail = "<table cellspacing='0' cellpadding='0' border='0' style='font:11px Verdana,Arial,Helvetica,sans-serif;color:#333'>
                            <tr><td colspan='2'>Thanks for your order</td></tr>
                            <tr><td colspan='2'>&nbsp;</td></tr>
                            <tr><td colspan='2' style='background: #0184C2; color: #fff;'>Purchasing Information:</td></tr>
                            <tr><td width='150'>E-mail Address:</td>
                                <td width='370'>" . $order_info['email'] . "</td>
                            </tr>
                            <tr><td width='150'>Billing Address:</td>
                                <td>" . $order_info['address'] . "</td>
                            </tr>
                            <tr><td width='150'>Billing Phone:</td>
                                <td>" . $order_info['phone'] . "</td>
                            </tr>
                            <tr><td width='150'>Order Grand Total:</td>
                                <td>" . $amount . "</td>
                            </tr>
                            <tr><td width='150'>Payment Method:</td>
                                <td>" . $order_info['payment_method'] . "</td>
                            </tr>
                            ";
                if ($status_oncome == 'completed') {
                    $bodyMail .= '<tr><td>Status Order: </td><td>Payment Successful</td></tr>
                        <tr><td colspan="2"><b>We will send cards in soon</b></td></tr>';
                } else {
                    $bodyMail .= '<tr><td>Status Order: </td><td>Payment Fail</td></tr>
                        <tr><td colspan="2"><b style="color: red;">You should try to payment again </b></td></tr>';
                }
                $bodyMail .= "<tr><td colspan='2'>&nbsp;</td></tr>
                        <tr><td colspan='2' style='background: #0184C2; color: #fff;'>Order Summary:</td></tr>
                        <tr><td width='150'>Order #:</td>
                        <td>" . $orderId . "</td>
                        </tr>
                        <tr><td>Products on order: </td><td></td></tr>
                        <tr><td colspan='2'>" . Good::getListProduct($orderId) . "</td></tr>
                        </table>";
                $subject = "Your Order at Trumgame";
                $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
                Mail::send(
                    $order_info['email'], $subject, $settings['templateOnNewOrder'], [
                        'order' => $bodyMail,
                    ]
                );*/
                return $this->render('index', array('html' => $html));
            } else {
                $this->redirect($this->goHome());
                exit();
            }
        }
    }

    public function actionPaypal() { 
        if (isset($_POST["txn_id"]) && isset($_POST["txn_type"]) && isset($_POST['item_name1'])) {
            $transaction_info = $_POST["item_name1"];
            $array_merchtxnref = explode("_", $transaction_info);
            if ($_POST['payment_status'] == 'Completed') {
                $upadteOrder = UserHistoryMoney::model()->updateAll(array('rounding' => 1), $criteria);
                UserHistoryMoney::updateAll(['rounding' => 1], ['id' => $array_merchtxnref[1]]);
            } else if ($_POST['payment_status'] == 'Pending') {
                UserHistoryMoney::updateAll(['rounding' => 2], ['id' => $array_merchtxnref[1]]);
            } else {
                UserHistoryMoney::updateAll(['rounding' => 3], ['id' => $array_merchtxnref[1]]);
            }
        }
        $html.='<p style="font-size:14px;font-weight:bold;line-height:20px;">Cám ơn quý khách, quá trình thanh toán đã được hoàn tất !
            <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
        return $this->render('index', array('html' => $html));        
    }

    public function actionNotifyPaypal() {
        if (isset($_POST["txn_id"]) && isset($_POST["txn_type"]) && isset($_POST['item_name'])) {
            $transaction_info = $_POST["item_name"];
            $array_merchtxnref = explode("_", $transaction_info);
            $querystring = 'trong_';
            foreach ($_POST as $key => $value) {
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }
            UserHistoryMoney::updateAll(['data' => $querystring], ['id' => $array_merchtxnref[1]]);


            if ($array_merchtxnref[0] == 'MeRef' && is_numeric($array_merchtxnref[1]) && is_numeric($array_merchtxnref[2])) {
                if (UserHistoryMoney::checkcomplete_paypal($array_merchtxnref[1], $array_merchtxnref[2])) {

                    $req = 'cmd=_notify-validate';
                    foreach ($_POST as $key => $value) {
                        $value = urlencode(stripslashes($value));
                        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
                        $req .= "&$key=$value";
                    }

                    // assign posted variables to local variables
                    $data = array();
                    $data['item_name'] = $_POST['item_name'];
                    $data['item_number'] = $_POST['item_number'];
                    $data['payment_status'] = $_POST['payment_status'];
                    $data['payment_amount'] = $_POST['mc_gross'];
                    $data['payment_currency'] = $_POST['mc_currency'];
                    $data['txn_id'] = $_POST['txn_id'];
                    $data['receiver_email'] = $_POST['receiver_email'];
                    $data['payer_email'] = $_POST['payer_email'];
                    $data['custom'] = $_POST['custom'];

                    $status_paypal = '';

                    if ($data['payment_status'] == "Completed") {
                        UserHistoryMoney::updateAll(['status' => 'completed'], ['id' => $array_merchtxnref[1]]);
                        $status_paypal = 'completed';
                            // tien
                            $money_send=$order_info['money_send'];
                            $currency_send=$order_info['currency_send'];
                            if($currency_send=="VND"){                        
                                $mon= Globals::convertVNDUSD($money_send);
                            }elseif($currency_send=="AUD"){
                                $mon= Globals::convertAUDUSD($money_send);
                            }else{
                                $mon=$money_send;
                            }
                            $check=  UserMoney::checkUid(Yii::$app->users->id);
                            if($check){
                                // update tien
                                $usermoney=  UserMoney::find()->where('uid='.$uid)->one();
                                $usermoney->money=$usermoney['money']+$mon;
                                $usermoney->save();
                            }else{
                                $usermoney=new UserMoney();
                                $usermoney->uid=Yii::$app->users->id;
                                $usermoney->money=$mon;
                                $usermoney->currency='USD';
                                $usermoney->save();
                            }
                            // end tien
                    } elseif ($data['payment_status'] == "Pending") {
                        UserHistoryMoney::updateAll(['status' => 'paypal_pending'], ['id' => $array_merchtxnref[1]]);
                        $status_paypal = 'pending';
                    } else {
                        UserHistoryMoney::updateAll(['status' => 'canceled'], ['id' => $array_merchtxnref[1]]);
                        $status_paypal = 'canceled';
                    }
                }
                if (UserHistoryMoney::check_paypal_auto_complete($array_merchtxnref[1], $array_merchtxnref[2])) {
                    $data = array();
                    $data['payment_status'] = $_POST['payment_status'];
                    if ($data['payment_status'] == "Completed") {
                        UserHistoryMoney::updateAll(['status' => 'completed'], ['id' => $array_merchtxnref[1]]);
                        $status_paypal = 'completed';
                    }
                }
            }
        }
    }

}
