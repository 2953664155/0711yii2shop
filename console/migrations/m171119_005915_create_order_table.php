<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m171119_005915_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->comment("用户ID"),
            'name' => $this->string(50)->comment("收货人"),
            'province' => $this->string(20)->comment("省"),
            'city' => $this->string(20)->comment("市"),
            'area' => $this->string(20)->comment("县"),
            'address' => $this->string(255)->comment("详细地址"),
            'tel' => $this->char(11)->comment("电话号码"),
            'delivery_name' => $this->string()->comment("配送方式"),
            'delivery_price' => $this->float()->comment("配送价格"),
            'payment_name' => $this->string()->comment("支付方式名称"),
            'total' => $this->decimal()->comment("订单金额"),
            'status' => $this->integer()->comment("订单状态"),
            'trade_no' => $this->string()->comment("第三方支付交易号"),
            'create_time' => $this->integer()->comment("创建时间"),
        ],$tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
