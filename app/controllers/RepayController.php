<?php

namespace app\controllers;

use yii\easyii\modules\shopcart\models\Order;
use Yii;
use yii\easyii\modules\shopcart\models\OrderItem;
use yii\easyii\helpers\Mail;

class RepayController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

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
            $array_merchtxnref = explode("_",$vpc_MerchTxnRef);
            $querystring = '';
            foreach ($_GET as $key => $value) {
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }
            $upadteOrder = Order::updateAll(array('data' => $querystring),['order_id' => $array_merchtxnref[1]]);
            if (count($array_merchtxnref) == 3 && $array_merchtxnref[0] == 'Order' && is_numeric($array_merchtxnref[1]) && is_numeric($array_merchtxnref[2])) {
                $errorExists = false;
                if (strlen($SECURE_SECRET) > 0 && $_GET["vpc_TxnResponseCode"] != "7" && $_GET["vpc_TxnResponseCode"] != "No Value Returned") {
                    ksort($_GET);
                    $md5HashData = "";
                    foreach ($_GET as $key => $value) {
                        if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key,0,4) == "vpc_") || (substr($key,0,5) == "user_"))) {
                            $md5HashData .= $key . "=" . $value . "&";
                        }
                    }
                    $md5HashData = rtrim($md5HashData,"&");
                    if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256',$md5HashData,pack('H*',$SECURE_SECRET)))) {
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
                $order_info = Order::findOne(['order_id' => $orderId]);
                $html = '';
                $transStatus = "";
                $returnurl = '';
                $status_oncome = '';
                if ($hashValidated == "CORRECT" && $txnResponseCode == "0") {
                    $status_oncome = 'completed';
                    Order::updateAll(['status' => 'completed'],['order_id' => $array_merchtxnref[1]]);

                    $html .='<p style="font-size:14px;font-weight:bold;line-height:20px;">Cám ơn quý khách, quá trình thanh toán đã được hoàn tất. Chúng tôi sẽ kiểm tra và chuyển hàng sớm cho quý khách!
                             <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "CORRECT" && $txnResponseCode != "0") {
                    $status_oncome = 'canceled';

                    Order::updateAll(['status' => 'canceled'],['order_id' => $array_merchtxnref[1]]);
                    $html.='<p style="font-size:14px;font-weight:bold;line-height:20px;">Quá trình thanh toán không thành công bạn vui lòng thực hiện lại !
                                <br><a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "INVALID HASH") {
                    $status_oncome = 'pending';
                    $html.='Quá trình thanh toán đang trong trạng thái chờ 
                        <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';

                    Order::updateAll(['status' => 'pending'],['order_id' => $array_merchtxnref[1]]);
                }
                $bodyMail = '
                                <h4 style="
                                        font-weight:bold;
                                        background:#ddd;
                                        padding:6px;
                                        text-align:center;
                                        margin-top:0;
                                        border-radius:4px 4px 0 0;
                                        background: #005D9D; /* For browsers that do not support gradients */
                                        background: -webkit-linear-gradient(#0179C1,#084C83); /* For Safari 5.1 to 6.0 */
                                        background: -o-linear-gradient(#0179C1,#084C83); /* For Opera 11.1 to 12.0 */
                                        background: -moz-linear-gradient(#0179C1,#084C83); /* For Firefox 3.6 to 15 */
                                        background: linear-gradient(#0179C1,#084C83);
                                        color:#fafafa;
                                        ">
                                    Purchasing Information:
                                </h4>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">E-mail Address:</span>' . $order_info['email'] . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Billing Address:</span>' . $order_info['address'] . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Billing Phone:</span>' . $order_info['phone'] . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Order Grand Total:</span>' . $amount . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Payment Method:</span>' . $order_info['payment_method'] . '</p>'
                ;

                if ($status_oncome == 'completed') {
                    $bodyMail .= '<p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Status Order:</span>Payment Successful</p>
                                  <p style="padding-left:20px;color:red;"><b>We will send cards in soon</b></p>';
                } else {
                    $bodyMail .= '<p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Status Order:</span>Payment Fail</p>
                                  <p style="padding-left:20px;color:red;"><b>You should try to payment again</b></p>';
                }
                $bodyMail .= '
                                <h4 style="
                                        font-weight:bold;
                                        background:#ddd;
                                        padding:6px;
                                        text-align:center;
                                        margin-top:0;
                                        
                                        background: #005D9D; /* For browsers that do not support gradients */
                                        background: -webkit-linear-gradient(#0179C1,#084C83); /* For Safari 5.1 to 6.0 */
                                        background: -o-linear-gradient(#0179C1,#084C83); /* For Opera 11.1 to 12.0 */
                                        background: -moz-linear-gradient(#0179C1,#084C83); /* For Firefox 3.6 to 15 */
                                        background: linear-gradient(#0179C1,#084C83);
                                        color:#fafafa;
                                        ">
                                    Order Summary:
                                </h4>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Order ID:</span>' . $orderId . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Products on order:</span>' . OrderItem::getListProduct($orderId) . '</p>';
                $subject = "Your order at Vnsupermark";
                $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
                Mail::send(
                        $order_info['email'],$subject,$settings['templateOnNewOrder'],[
                    'order' => $bodyMail,
                        ]
                );
                $request = Yii::$app->request->post();
                //return $this->render('index',array('html' => $html));
				Yii::$app->session->setFlash("successrepay",$html);
                Yii::$app->response->redirect(['/repay/repay']);
                Yii::$app->end();
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
            $array_merchtxnref = explode("_",$vpc_MerchTxnRef);

            $querystring = '';
            foreach ($_GET as $key => $value) {
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }
            if (count($array_merchtxnref) == 3 && $array_merchtxnref[0] == 'MeRef' && is_numeric($array_merchtxnref[1]) && is_numeric($array_merchtxnref[2])) {

                $upadteOrder = Order::updateAll(array('data' => $querystring),['order_id' => $array_merchtxnref[1]]);

                // set a flag to indicate if hash has been validated
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
                        if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key,0,4) == "vpc_") || (substr($key,0,5) == "user_"))) {
                            $md5HashData .= $key . "=" . $value . "&";
                        }
                    }
                    //  Xóa dấu & thừa cuối chuỗi dữ liệu
                    $md5HashData = rtrim($md5HashData,"&");

                    //    if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper ( md5 ( $md5HashData ) )) {
                    //    Thay hàm tạo chuỗi mã hóa
                    if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256',$md5HashData,pack('H*',$SECURE_SECRET)))) {
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
                $verType = array_key_exists("vpc_VerType",$_GET) ? $_GET["vpc_VerType"] : "No Value Returned";
                $verStatus = array_key_exists("vpc_VerStatus",$_GET) ? $_GET["vpc_VerStatus"] : "No Value Returned";
                $token = array_key_exists("vpc_VerToken",$_GET) ? $_GET["vpc_VerToken"] : "No Value Returned";
                $verSecurLevel = array_key_exists("vpc_VerSecurityLevel",$_GET) ? $_GET["vpc_VerSecurityLevel"] : "No Value Returned";
                $enrolled = array_key_exists("vpc_3DSenrolled",$_GET) ? $_GET["vpc_3DSenrolled"] : "No Value Returned";
                $xid = array_key_exists("vpc_3DSXID",$_GET) ? $_GET["vpc_3DSXID"] : "No Value Returned";
                $acqECI = array_key_exists("vpc_3DSECI",$_GET) ? $_GET["vpc_3DSECI"] : "No Value Returned";
                $authStatus = array_key_exists("vpc_3DSstatus",$_GET) ? $_GET["vpc_3DSstatus"] : "No Value Returned";

                $orderId = $array_merchtxnref[1];

                $order_info = Order::findOne(['order_id' => $orderId]);
                $html = '';
                $transStatus = "";

                $status_oncome = '';
                if ($hashValidated == "CORRECT" && $txnResponseCode == "0") {
                    //$transStatus = "Giao dịch thành công";
                    $status_oncome = 'completed';

                    Order::updateAll(['status' => $status_oncome],['order_id' => $array_merchtxnref[1]]);
                    $html .='<p style="font-size:14px;font-weight:bold;line-height:20px;">Cám ơn quý khách, quá trình thanh toán đã được hoàn tất. Chúng tôi sẽ kiểm tra và chuyển hàng sớm cho quý khách!
                                                        <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "CORRECT" && $txnResponseCode != "0") {
                    //$transStatus = "Giao dịch thất bại";
                    $status_oncome = 'canceled';

                    Order::updateAll(['status' => $status_oncome],['order_id' => $array_merchtxnref[1]]);
                    $html.='<p style="font-size:14px;font-weight:bold;line-height:20px;">Quá trình thanh toán không thành công bạn vui lòng thực hiện lại !
                                                         <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "INVALID HASH") {
                    //$transStatus = "Giao dịch Pendding";
                    $status_oncome = 'pending';
                    $html.='Quá trình thanh toán đang trong trạng thái chờ 
                           <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';

                    Order::updateAll(['status' => $status_oncome],['order_id' => $array_merchtxnref[1]]);
                }


                $bodyMail = '
                                <h4 style="
                                        font-weight:bold;
                                        background:#ddd;
                                        padding:6px;
                                        text-align:center;
                                        margin-top:0;
                                        border-radius:4px 4px 0 0;
                                        background: #005D9D; /* For browsers that do not support gradients */
                                        background: -webkit-linear-gradient(#0179C1,#084C83); /* For Safari 5.1 to 6.0 */
                                        background: -o-linear-gradient(#0179C1,#084C83); /* For Opera 11.1 to 12.0 */
                                        background: -moz-linear-gradient(#0179C1,#084C83); /* For Firefox 3.6 to 15 */
                                        background: linear-gradient(#0179C1,#084C83);
                                        color:#fafafa;
                                        ">
                                    Purchasing Information:
                                </h4>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">E-mail Address:</span>' . $order_info['email'] . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Billing Address:</span>' . $order_info['address'] . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Billing Phone:</span>' . $order_info['phone'] . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Order Grand Total:</span>' . $amount . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Payment Method:</span>' . $order_info['payment_method'] . '</p>'
                ;

                if ($status_oncome == 'completed') {
                    $bodyMail .= '<p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Status Order:</span>Payment Successful</p>
                                  <p style="padding-left:20px;color:red;"><b>We will send cards in soon</b></p>';
                } else {
                    $bodyMail .= '<p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Status Order:</span>Payment Fail</p>
                                  <p style="padding-left:20px;color:red;"><b>You should try to payment again</b></p>';
                }

                $bodyMail .= '
                                <h4 style="
                                        font-weight:bold;
                                        background:#ddd;
                                        padding:6px;
                                        text-align:center;
                                        margin-top:0;
                                        
                                        background: #005D9D; /* For browsers that do not support gradients */
                                        background: -webkit-linear-gradient(#0179C1,#084C83); /* For Safari 5.1 to 6.0 */
                                        background: -o-linear-gradient(#0179C1,#084C83); /* For Opera 11.1 to 12.0 */
                                        background: -moz-linear-gradient(#0179C1,#084C83); /* For Firefox 3.6 to 15 */
                                        background: linear-gradient(#0179C1,#084C83);
                                        color:#fafafa;
                                        ">
                                    Thông tin đơn hàng:
                                </h4>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Order ID:</span>' . $orderId . '</p>
                                <p style="padding-left:20px;"><span style="width:200px;display:inline-block;">Products on order:</span>' . OrderItem::getListProduct($orderId) . '</p>';
                $subject = "Đơn hành tại Vnsupermark";

                $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;
                Mail::send(
                        $order_info['email'],$subject,$settings['templateOnNewOrder'],[
                    'order' => $bodyMail,
                        ]
                );
                //return $this->render('index',array('html' => $html));
				Yii::$app->session->setFlash("successrepay",$html);
                Yii::$app->response->redirect(['/repay/repay']);
                Yii::$app->end();
            } else {
                $this->redirect($this->goHome());
                exit();
            }
        }
    }
	public function actionOnecomvnd() {		
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
            $SECURE_SECRET = "202BD0DC106EB249FCDB1D6A784F2176";

            // get and remove the vpc_TxnResponseCode code from the response fields as we
            // do not want to include this field in the hash calculation
            $vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
            $vpc_MerchTxnRef = $_GET["vpc_MerchTxnRef"];
            $vpc_AcqResponseCode = $_GET["vpc_AcqResponseCode"];
            unset($_GET["vpc_SecureHash"]);
            $array_merchtxnref = explode("_", $vpc_MerchTxnRef);			
            $payer_status  = '';
            $querystring = '';
            foreach ($_GET as $key => $value) {
                $value = urlencode(stripslashes($value));	
                $querystring .= "$key=$value&";
            }
			
            if (count($array_merchtxnref) == 3 && $array_merchtxnref[0] == 'MeRef' && is_numeric($array_merchtxnref[1]) && is_numeric($array_merchtxnref[2])) {

                //$upadteOrder = Order::updateAll(array('data' => $querystring), ['order_id' => $array_merchtxnref[1]]);
				$upadteOrder = Order::updateAll(['data' => $querystring], ['order_id' => $array_merchtxnref[1]]);
                // set a flag to indicate if hash has been validated
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

                $order_info = Order::findOne(['order_id' => $orderId]);
                $html = '';
                $transStatus = "";

                $status_oncome = '';
		//echo $hashValidated; die();		
                if ($hashValidated == "CORRECT" && $txnResponseCode == "0") {
                    //$transStatus = "Giao dịch thành công";
                    $status_oncome = 'completed';
                    Order::updateAll(['status' => $status_oncome], ['order_id' => $array_merchtxnref[1]]);
                    $html .='<p style="font-size:14px;font-weight:bold;line-height:35px;">Cám ơn quý khách, quá trình thanh toán đã được hoàn tất. Chúng tôi sẽ kiểm tra và chuyển hàng sớm cho quý khách!
                    <br> <a style="font-weight:bold; text-decoration:underline" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "CORRECT" && $txnResponseCode != "0") {
                    //$transStatus = "Giao dịch thất bại";
                    $status_oncome = 'canceled';
                    Order::updateAll(['status' => $status_oncome], ['order_id' => $array_merchtxnref[1]]);
                    $html.='<p style="font-size:14px;font-weight:bold;line-height:35px;">Quá trình thanh toán không thành công bạn vui lòng thực hiện lại !
                    <br> <a style="font-weight:bold; text-decoration:underline" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
                } elseif ($hashValidated == "INVALID HASH") {
                    //$transStatus = "Giao dịch Pendding";
                    $status_oncome = 'pending';
                    $html.='<p style="font-size:14px;font-weight:bold;line-height:35px;">Quá trình thanh toán đang trong trạng thái chờ 
                           <br> <a style="font-weight:bold; text-decoration:underline" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';

                    Order::updateAll(['status' => $status_oncome], ['order_id' => $array_merchtxnref[1]]);
                }
                //return $this->render('index', array('html' => $html));
                
                Yii::$app->session->setFlash("successrepay",$html);
                Yii::$app->response->redirect(['/repay/repay']);
                Yii::$app->end();
                
                
            } else {
                $this->redirect($this->goHome());
                exit();
            }
        }
    }
	public function actionRepay() {
        return $this->render('repay');
    }

    public function actionPaypalcancel() {
        $html.='<p style="font-size:14px;font-weight:bold;line-height:20px;">Quá trình thanh toán không thành công bạn vui lòng thực hiện lại !
               <br><a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a></p>';
        Yii::$app->session->setFlash("successrepay",$html);
        Yii::$app->response->redirect(['/repay/repay']);
        Yii::$app->end();
    }

    public function actionPaypal() {
        if (isset($_POST["txn_id"]) && isset($_POST["txn_type"]) && isset($_POST['item_name'])) {
            $transaction_info = $_POST["item_name"];
            $array_merchtxnref = explode("_",$transaction_info);

            $querystring = '';
            foreach ($_POST as $key => $value) {
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }
            $upadteOrder = Order::updateAll(array('data' => $querystring),['order_id' => $array_merchtxnref[1]]);
        }
        $html.='<p style="font-size:14px;font-weight:bold;line-height:20px;">Cám ơn quý khách, quá trình thanh toán đã được hoàn tất. Chúng tôi sẽ kiểm tra và chuyển hàng sớm cho quý khách!
                <br> <a style="color:red;" href="' . Yii::$app->getHomeUrl() . '">Trở lại trang chủ</a>.</p>';
        Yii::$app->session->setFlash("successrepay",$html);
        Yii::$app->response->redirect(['/repay/repay']);
        Yii::$app->end();
    }

    public function actionNotifyPaypal() {

        if (isset($_POST["txn_id"]) && isset($_POST["txn_type"]) && isset($_POST['item_name'])) {
            $transaction_info = $_POST["item_name"];
            $array_merchtxnref = explode("_",$transaction_info);
            $querystring = 'trong_';

            if ($array_merchtxnref[0] == 'MeRef' && is_numeric($array_merchtxnref[1]) && is_numeric($array_merchtxnref[2])) {
                if (Order::checkcomplete_paypal($array_merchtxnref[1],$array_merchtxnref[2])) {

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
                        Order::updateAll(['status' => 'completed'],['order_id' => $array_merchtxnref[1]]);
                        $status_paypal = 'completed';
                    } elseif ($data['payment_status'] == "Pending") {
                        Order::updateAll(['status' => 'paypal_pending'],['order_id' => $array_merchtxnref[1]]);
                        $status_paypal = 'pending';
                    } else {
                        Order::updateAll(['status' => 'canceled'],['order_id' => $array_merchtxnref[1]]);
                        $status_paypal = 'canceled';
                    }
                    foreach ($_POST as $key => $value) {
                        $value = urlencode(stripslashes($value));
                        $querystring .= "$key=$value&";
                    }
                    Order::updateAll(['data' => $querystring],['order_id' => $array_merchtxnref[1]]);
                }
                if (Order::check_paypal_auto_complete($array_merchtxnref[1],$array_merchtxnref[2])) {
                    $data = array();
                    $data['payment_status'] = $_POST['payment_status'];
                    if ($data['payment_status'] == "Completed") {
                        Order::updateAll(['status' => 'completed'],['order_id' => $array_merchtxnref[1]]);
                        $status_paypal = 'completed';
                    }
                }
            }
        }
    }

    

}
