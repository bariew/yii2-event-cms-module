<?php

namespace bariew\eventModule\components;

use bariew\eventModule\helpers\ClassCrawler;
use bariew\nodeTree\ARTreeMenuWidget;
use yii\base\Behavior;

class TreeBehavior extends Behavior
{
    public function treeWidget($callback)
    {
        $cacheKey = get_class($this) . '__' . $callback;
        //if (!\Yii::$app->cache->exists($cacheKey)) {
//            \Yii::$app->set($cacheKey, ARTreeMenuWidget::widget([
//                'view'  => 'simple',
//                'items' => $this->$callback(),
//                'id'    => $callback,
//                'options'   => [
//                    'plugins' => ["search", "types"]
//                ],
//                'binds'     => [
//                    'select_node.jstree'  => 'function(event, data){return false;}',
//                ]
//            ]));
        //}
        //return \Yii::$app->cache->get($cacheKey);
        return ARTreeMenuWidget::widget([
                'view'  => 'simple',
                'items' => $this->$callback(),
                'id'    => $callback,
                'options'   => [
                    'plugins' => ["search", "types"]
                ],
                'binds'     => [
                    'select_node.jstree'  => 'function(event, data){return false;}',
                ]
            ]);
    }

    public function classEventTree()
    {
        $items = [];
        foreach(ClassCrawler::getAllClasses() as $class) {
            if (!$data = ClassCrawler::getEventNames($class)) {
                continue;
            }
            $items[$class] = $data;
        }
        return $this->createSlashTree($items);
    }

    public function classHandlerTree()
    {
        $items = [];
        foreach(ClassCrawler::getAllClasses() as $class) {
            if (!$data = ClassCrawler::getEventHandlerMethodNames($class)) {
                continue;
            }
            $items[$class] = $data;
        }
        return $this->createSlashTree($items);
    }

    public function appEventTree()
    {
        $items = [];
        $models = $this->owner->find()->all();
        /* @var \bariew\eventModule\models\Item $model */
        foreach ($models as $model) {
            $key = "{$model->handler_class}::{$model->handler_method}";
            if (!$nestedEvents = ClassCrawler::getMethodTriggeredEvents($model->handler_class, $model->handler_method)) {
                $items[$model->trigger_class][$model->trigger_event][$key] = $key;
                continue;
            }
            foreach ($nestedEvents as $eventName) {
                $items[$model->trigger_class][$model->trigger_event][$key][$eventName]
                    = &$items[$model->handler_class][$eventName];
            }
        }
        return $items;
    }

    protected function createSlashTree($items)
    {
        $result = [];
        foreach ($items as $class => $data) {
            $path = explode('\\', $class);
            $insert = &$result;
            foreach ($path as $key => $name) {
                if ($key == (count($path) - 1)) {
                    $insert[$name] = $data;
                    continue;
                }
                $insert = &$insert[$name];
            }
        }
        return $result;
    }
}