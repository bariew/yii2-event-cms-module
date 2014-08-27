<?php

use yii\db\Schema;
use yii\db\Migration;
use bariew\eventModule\models\Item;

class m140715_084936_event_item extends Migration
{
    public function up()
    {
        return $this->createTable(Item::tableName(), [
            'id'    => Schema::TYPE_PK,
            'trigger_class'  => Schema::TYPE_STRING,
            'trigger_event'  => Schema::TYPE_STRING,
            'handler_class' => Schema::TYPE_STRING,
            'handler_method'=> Schema::TYPE_STRING,
            'active'        => Schema::TYPE_SMALLINT,
        ]);
    }

    public function down()
    {
        return $this->dropTable(Item::tableName());
    }
}
