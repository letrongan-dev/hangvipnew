<?php
namespace app\modules\laythe\models;

use yii\base\Model;

class AddToLayTheForm extends Model
{
    const SUCCESS_VAR = 'success';
    public $loai_laythe;
    public $sl_laythe;
    public $total_laythe;

    public function rules()
    {
        return [
            [['loai_laythe','sl_laythe'], 'required'],
            ['sl_laythe', 'integer', 'min' => 1]
        ];
    }

    public function attributeLabels()
    {
        return [
            'loai_laythe' => 'Thẻ game & Thẻ điện thoại',
            'sl_laythe' => 'Số lượng',
            'total_laythe' => 'Thành tiền',
        ];
    }
}