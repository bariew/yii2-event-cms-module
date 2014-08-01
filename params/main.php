<?php
use bariew\eventModule\models\Item;
Yii::setAlias('@bariew/eventModule', __DIR__.'/../');
return [
    'events'    =>  Yii::$app->has('db') && isset(Yii::$app->db->schema->tableSchemas[Item::tableName()])
        ? (new \bariew\eventModule\models\Item())->getCached('moduleEventList') : [],
    'menu'  => [
        'label'    => 'Events',
        'url' => ['/event/item/index']
    ]
];