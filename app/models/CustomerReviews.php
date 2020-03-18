<?php

namespace app\models;

use Yii;

class CustomerReviews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'user_id'], 'required'],
            ['user_id', 'integer'],
            ['name', 'string', 'max'=>128],
            [['content'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Bí danh (khi bạn muốn ẩn danh)',
            'content' => 'Nội dung',
        ];
    }
}
