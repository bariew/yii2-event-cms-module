<?php
Yii::setAlias('@bariew/eventModule', __DIR__.'/../');
return [
    'events'    =>  Yii::$app->has('db') ? (new \bariew\eventModule\models\Item())->moduleEventList() : [],
    'menu'  => [
        'label'    => 'Events',
        'url' => ['/event/item/index']
    ]
];