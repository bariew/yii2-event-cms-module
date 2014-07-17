<?php

namespace bariew\eventModule\components;

use bariew\eventModule\helpers\ClassCrawler;
use bariew\nodeTree\ARTreeMenuWidget;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class TreeBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT    => 'updateAppEventTree',
            ActiveRecord::EVENT_AFTER_UPDATE    => 'updateAppEventTree',
            ActiveRecord::EVENT_AFTER_DELETE    => 'updateAppEventTree',
        ];
    }

    public function updateAppEventTree()
    {
        $this->treeWidget('appEventTree', true);
    }

    public function treeWidget($callback, $force = false)
    {
        $cacheKey = $this->getCacheKey($callback);
        $options = [
            'view'  => 'simple',
            'items' => $this->$callback(),//$this->getCached($callback),
            'id'    => $callback,
            'options'   => [
                'plugins' => ["search", "types", 'state']
            ],
            'binds'     => [
                'select_node.jstree'  => 'function(event, data){
                    var el = $(data.event.currentTarget);
                    if (data.node.children_d.length) {
                        return alert("Can not select parent node!");
                    }
                    var className = [];
                    var container = $("#'.$callback.'");
                    el.parents("#'.$callback.' ul li").each(function(){
                        className.unshift($(this).find("a").eq(0).text().replace(/\s/, ""));
                    });
                    var methodName = className.pop();
                    className = className.join(\'\\\\\');
                    container.parent().find("input.owner").val(className);
                    container.parent().find("input.method").val(methodName);
                }',
            ]
        ];

        if (!\Yii::$app->cache->exists($cacheKey) || $force) {
            $data = ARTreeMenuWidget::widget($options);
            \Yii::$app->cache->set($cacheKey, $data);
        } else {
            (new ARTreeMenuWidget($options))->registerScripts();
        }
        return \Yii::$app->cache->get($cacheKey);
    }

    protected function getCacheKey($name)
    {
        return  get_class($this) . '__' . $name;
    }

    protected function getCached($methodName)
    {
        $cacheKey = $this->getCacheKey($methodName);
        if (!\Yii::$app->cache->exists($cacheKey)) {
            \Yii::$app->cache->set($cacheKey, serialize($this->$methodName()));
        }
        return unserialize(\Yii::$app->cache->get($cacheKey));
    }

    public function classEventTree()
    {
        $items = [];
        foreach(ClassCrawler::getAllClasses() as $class) {
            if (!$data = ClassCrawler::getEventNames($class)) {
                continue;
            }
            $items[$class] = array_flip($data);
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