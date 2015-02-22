<?php

namespace bariew\eventModule;

class Module extends \yii\base\Module
{
    public $params = [
        'menu'  => [
            'label' => 'Settings',
            'items' => [[
                'label'    => 'Events',
                'url' => ['/event/item/index']
            ]]
        ]
    ];

    /**
     * Checks whether current module is enabled.
     * @return bool
     */
    public static function isEnabled()
    {
        $class = self::className();
        foreach (\Yii::$app->modules as $module => $params) {
            switch (gettype($params)) {
                case 'array' : if ($class == @$params['class']) return true;
                    break;
                case 'object': if ($class == get_class($params)) return true;
                    break;
                default : if ($class == $params) return true;
            }
            if ($module == $class || (isset($module['class']) && $module['class'] == $class)) {
                return true;
            }
        }
        return false;
    }
}
