<?php

namespace bariew\eventModule;

class Module extends \yii\base\Module
{
    public $params = [
        'menu'  => [
            'label'    => 'Events',
            'url' => ['/event/item/index']
        ]
    ];
}
