<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m171114_010902_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->comment("收货人"),
            'province'=>$this->string()->comment("省"),
            'city'=>$this->string()->comment('市'),
            'count'=>$this->string()->comment('县/区'),
            'detailed_address'=>$this->string()->comment('详细地址'),
            'phone'=>$this->char(11)->comment("电话号码"),
            'user_id'=>$this->integer()->comment("用户ID"),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
