<?php

namespace app\modules\laythe\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $title
 * @property string $price
 * @property integer $product_id
 * @property double $quantity
 *
 * @property Product $product
 * @property Order $order
 */
class OrderItem extends \yii\easyii\components\ActiveRecord  
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quantity'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'title' => 'Title',
            'price' => 'Price',
            'item_id' => 'Product ID',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'item_d']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    
     public static function getListProduct($orderID) {
        $result = '';
        $lProduct = OrderItem::findAll(['order_id' => $orderID]);
        $order = Order::findOne(['order_id' => $orderID]);
        $result = "<table cellspacing='0' cellpadding='0' border='0' style='font:11px Verdana,Arial,Helvetica,sans-serif;color:#333; padding: 5px 10px;'>";
        foreach ($lProduct as $lp) {

            $result .= '<tr>
                <td style="border-bottom: 1px dotted #ccc;">' . $lp['quantity'] . ' x ' . $lp['title'] . ':</td>
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
}
