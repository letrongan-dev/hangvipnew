<?php

namespace yii\easyii\modules\laythe\models;

use yii\easyii\modules\catalog\models\Item;
use Yii;
use yii\easyii\validators\EscapeValidator;

class Good extends \yii\easyii\components\ActiveRecord {

    public static function tableName() {
        return 'easyii_shopcart_goods';
    }

    public function rules() {
        return [
            [['order_id', 'item_id', 'sku'], 'required'],
            [['order_id', 'item_id', 'count'], 'integer', 'min' => 1],
//            ['price', 'number', 'min' => 0.1],
            ['options', 'trim'],
            ['options', 'string', 'max' => 255],
            ['options', EscapeValidator::className()],
            ['count', 'default', 'value' => 1],
            ['discount', 'default', 'value' => 0],
        ];
    }

    public function attributeLabels() {
        return [];
    }

    public function getItem() {
        return $this->hasOne(Item::className(), ['item_id' => 'item_id']);
    }

    public function getOrder() {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            return true;
        } else {
            return false;
        }
    }

    public static function getListProduct($orderID) {
        $result = '';
        $lProduct = Good::findAll(['order_id' => $orderID]);
        $order = Order::findOne(['order_id' => $orderID]);
        $result = "<table cellspacing='0' cellpadding='0' border='0' style='font:11px Verdana,Arial,Helvetica,sans-serif;color:#333; padding: 5px 10px;'>";
        foreach ($lProduct as $lp) {

            $result .= '<tr>
                                <td style="border-bottom: 1px dotted #ccc;">' . $lp['count'] . ' x ' . $lp['sku'] . ':</td>
				<td style="border-bottom: 1px dotted #ccc;">&nbsp;&nbsp;</td>
                                <td style="border-bottom: 1px dotted #ccc;" width="150" align="right"> <b>&nbsp;' . number_format($lp['price'], 2) . ' ' . $order['sale_currency'] . '</b></td>
                            </tr>';
        }

        $result .= '<tr><td colspan="2" align="right">Total:</td><td align="right"><b>&nbsp;' . number_format($order['order_total'], 2) . ' ' . $order['sale_currency'] . '</b></td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        </table>';

        if ($result)
            return $result;
    }

    public function afterDelete() {
        parent::afterDelete();
    }

}
