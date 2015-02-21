<?php

namespace bariew\eventModule\models;

use bariew\eventModule\components\TreeBehavior;
use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "event_item".
 *
 * @property integer $id
 * @property string $trigger_class
 * @property string $trigger_event
 * @property string $handler_class
 * @property string $handler_method
 *
 * @method TreeBehavior treeWidget
 */
class Item extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{event_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trigger_class', 'trigger_event', 'handler_class', 'handler_method'], 'string', 'max' => 255],
            [['trigger_class', 'trigger_event', 'handler_class', 'handler_method'], 'required', 'enableClientValidation'=>false],
            [['active'], 'integer'],
            [['active'], 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modules/event', 'ID'),
            'trigger_class' => Yii::t('modules/event', 'Trigger Class'),
            'trigger_event' => Yii::t('modules/event', 'Trigger Event'),
            'handler_class' => Yii::t('modules/event', 'Handler Class'),
            'handler_method' => Yii::t('modules/event', 'Handler Method'),
            'active' => Yii::t('modules/event', 'Active'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TreeBehavior::className()
        ];
    }
}
