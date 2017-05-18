<?php

use yii\db\Migration;
use common\models\User;
use common\models\Handler;
use common\models\Category;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Crear user table
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'nickname' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string(32)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Initialize the user admin
        $admin = new User();
        $admin->username = 'admin';
        $admin->nickname = '管理员';
        $admin->setPassword($admin->username);
        $admin->generateAuthKey();
        $admin->enable();
        $admin->created_at = $admin->updated_at = time();

        $this->insert('{{%user}}',$admin->toArray());

        // Crear handler table
        $this->createTable('{{%handler}}', [
            'id' => $this->primaryKey(),
            'handler_name' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'last_editor' => $this->integer()->notNull(),
        ], $tableOptions);

        // Initialize the handler Default
        $defaultCategory = new Handler();
        $defaultCategory->handler_name = '经手人';
        $defaultCategory->created_at = $defaultCategory->updated_at = time();
        $defaultCategory->last_editor = 1;

        $this->insert('{{%handler}}',$defaultCategory->toArray());

        // Crear category table
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'category_name' => $this->string(32)->notNull(),
            'category_sequence' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'last_editor' => $this->integer()->notNull(),
        ], $tableOptions);

        // Initialize the category Default
        $defaultCategory = new Category();
        $defaultCategory->category_name = '默认分类';
        $defaultCategory->category_sequence = 1;
        $defaultCategory->created_at = $defaultCategory->updated_at = time();
        $defaultCategory->last_editor = 1;

        $this->insert('{{%category}}',$defaultCategory->toArray());
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%handler}}');
        $this->dropTable('{{%category}}');
    }
}
