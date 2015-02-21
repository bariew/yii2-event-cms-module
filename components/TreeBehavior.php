<?php

namespace bariew\eventModule\components;

use bariew\eventModule\helpers\ClassCrawler;
use bariew\eventModule\models\Item;
use bariew\nodeTree\ARTreeMenuWidget;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

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

    public function moduleEventList()
    {
        $attributes = ['trigger_class', 'trigger_event', 'handler_class', 'handler_method'];
        $order = array_combine($attributes, array_fill(0, count($attributes), SORT_ASC));
        $allEvents = Item::find()->where(['active'=>1])->select($attributes)->orderBy($order)->asArray()->all();
        $result = [];
        foreach ($allEvents as $data) {
            $result[$data['trigger_class']][$data['trigger_event']][] = [$data['handler_class'], $data['handler_method']];
        }
        return $result;
    }

    public function updateAppEventTree()
    {
        $this->moduleEventList();
    }

    public function createJsonTreeItems($type, $items, $id)
    {
        $result = [];
        $activeId = $this->getActiveNodeId($type);
        foreach ((array) $items as $item => $data) {
            $nodeId = ($id == false) ? $item : $id . "\\" . $item;
            $result[] = [
                "text" => $item,
                "children" => is_array($data),
                "id"    => $nodeId,
                "type"  => is_array($data) ? 'book' : 'file',
                "state" => [
                    "opened"    => strpos($activeId, $nodeId) === 0,
                    //"disabled"  =>
                    "selected"  => $activeId == $nodeId
                ],
                'a_attr'=> [
                    'data-id'   => $nodeId,
                ],
            ];
        }
        return $result;
    }

    public function getActiveNodeId($callback)
    {
        switch ($callback) {
            case 'classEventTree' :
                return implode('\\', [$this->owner->trigger_class, $this->owner->trigger_event]);
            case 'classHandlerTree':
                return implode('\\', [$this->owner->handler_class, $this->owner->handler_method]);
        }
    }

    public function treeWidget($callback, $force = false)
    {
        $options = [
            'view'  => 'simple',
            'behavior'  => $this,
            'items' => $this->$callback(),
            'id'    => $callback,
            'options'   => [
                "core" => ($callback == 'classEventTree')
                ? [
                    "animation" => 0,
                    "check_callback" => true,
                    'data' => [
                        'url' => Url::toRoute(['tree', 'type' => $callback, 'model_id' => $this->owner->id]),
                        'data' => 'function(node) {return { "id" : node.id };}'
                    ]
                ]
                : (new ARTreeMenuWidget())->commonOptions()['core'],
                'plugins' => ["search", "types"]
            ],
            'binds'     => [
                'select_node.jstree'  => 'function(event, data){
                    var el = $(data.event.currentTarget);
                    if (data.node.children_d.length) {
                        return jstree.jstree(true).deselect_node(data.node);
                    }
                    var className = [];
                    el.parents("#'.$callback.' ul li").each(function(){
                        className.unshift($(this).find("a").eq(0).text().replace(/\s/, ""));
                    });
                    var methodName = className.pop();
                    className = className.join(\'\\\\\');
                    $("input.owner.'.$callback.'").val(className);
                    $("input.method.'.$callback.'").val(methodName);
                }',
            ]
        ];
        return ARTreeMenuWidget::widget($options);
    }

    protected function getCacheKey($name)
    {
        return  get_class($this) . '__' . $name;
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


    /* lists for old from dropdowns */

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
}