<?php

use yii\db\Schema;
use yii\db\Migration;

class m140715_084936_event_item extends Migration
{
    public function up()
    {
        $this->createTable('{{event_item}}', [
            'id'    => Schema::TYPE_PK,
            'trigger_class'  => Schema::TYPE_STRING,
            'trigger_event'  => Schema::TYPE_STRING,
            'handler_class' => Schema::TYPE_STRING,
            'handler_method'=> Schema::TYPE_STRING,
        ]);
    }

    public function down()
    {
        return $this->dropTable('{{event_item}}');
    }
}
