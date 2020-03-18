<?php
namespace app\models;

use yii\base\Model;

class AddToCartForm extends Model
{
    const SUCCESS_VAR = 'success';
    public $color;
    public $count = 1;
    public $item_id = null;

    public function rules()
    {
        return [
            [['count','item_id'], 'required'],
            ['count', 'integer', 'min' => 1],
            [['color','item_id'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'count' => 'Quantity',
            'item_id' => '',
            'color' => 'Color',
        ];
    }
}