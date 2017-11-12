<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171111_102834_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('名称'),
            'route'=>$this->string()->comment('路由'),
            'parent_id'=>$this->integer()->comment('上级菜单'),
            'sort'=>$this->integer()->comment('排序'),
            'tier'=>$this->integer()->comment('层级'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
