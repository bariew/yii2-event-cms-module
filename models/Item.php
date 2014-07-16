<?php

namespace bariew\eventModule\models;

use bariew\eventModule\components\TreeBehavior;
use bariew\eventModule\helpers\ClassCrawler;
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
 */
class Item extends ActiveRecord
{

    public static function classList()
    {
        $classes = ClassCrawler::getAllClasses();
        return array_combine($classes, $classes);
    }

    public static function eventList($className)
    {
        return ($className)
            ? array_flip(ClassCrawler::getEventNames($className))
            : [];
    }

    public static function methodList($className)
    {
        return ($className)
            ? array_flip(ClassCrawler::getEventHandlerMethodNames($className))
            : [];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trigger_class', 'trigger_event', 'handler_class', 'handler_method'], 'string', 'max' => 255],
            [['trigger_class', 'trigger_event', 'handler_class', 'handler_method'], 'required'],
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
        ];
    }

    public function behaviors()
    {
        return [
            TreeBehavior::className()
        ];
    }
}
