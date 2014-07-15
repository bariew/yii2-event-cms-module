<?php

namespace bariew\eventModule\helpers;

use \yii\helpers\BaseFileHelper;
use \Yii;

class ClassCrawler
{
    public static function getEventNames($className)
    {
        $result = [];
        $reflection = new \ReflectionClass($className);
        foreach ($reflection->getConstants() as $name => $value) {
            if (!preg_match('/^EVENT/', $name)) {
                continue;
            }
            $result[$name] = $value;
        }
        return $result;
    }

    public static function getAllClasses()
    {
        $result = [];
        foreach (self::getAllAliases() as $alias) {
            $path = \Yii::getAlias($alias);
            $files = BaseFileHelper::findFiles($path);
            foreach ($files as $filePath) {
                if (!preg_match('/.*\/[A-Z]\w+\.php/', $filePath)) {
                    continue;
                }
                $className = str_replace([$path, '.php', '/', '@'], [$alias, '', '\\', ''], $filePath);
                $result[] = $className;
            }
        }
        return $result;
    }

    public static function getAllAliases()
    {
        $result = [];
        foreach (Yii::$aliases as $aliases) {
            foreach (array_keys((array) $aliases) as $alias) {
                if (!$alias) {
                    continue;
                }
                $result[]  = $alias;
            }
        }
        return $result;
    }


}