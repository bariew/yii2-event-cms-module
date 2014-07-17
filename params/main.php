<?php
Yii::setAlias('@bariew/eventModule', __DIR__.'/../');
return [
    'events'    =>  (new \bariew\eventModule\models\Item())->getCached('moduleEventList'),
    'menu'  => [
        'label'    => 'Events',
        'url' => ['/event/item/index']
    ]
];