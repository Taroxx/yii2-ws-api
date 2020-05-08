<?php
use yii\db\Migration;
use yii\db\Schema;

class m200428_094525_autodbs extends Migration
{
    public function up()
    {
        $tableOptions = null;
        //Опции для mysql
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%api_auto_brand}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'logo' => $this->string(255),
            'slug' => $this->string(255)->notNull(),
            'is_hand' => $this->smallInteger()->defaultValue('0'),
            'is_active_position' => $this->smallInteger()->defaultValue('1'),
            'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP,
        ]
            , $tableOptions
        );


        $this->createTable(
            '{{%api_auto_model}}',
            [
                'id' => Schema::TYPE_PK,
                'brand_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'model_name' => Schema::TYPE_STRING . ' NOT NULL',
                'year_start' => Schema::TYPE_INTEGER . ' NOT NULL',
                'year_end' => Schema::TYPE_INTEGER . ' NOT NULL',
                'body' => Schema::TYPE_STRING . ' NOT NULL',
                'name' => Schema::TYPE_STRING . ' NOT NULL',
                'car' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'center_bore' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'pcd' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'lug_count' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'lug_size' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'lug_type' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'market' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'slug' => $this->string(255)->notNull(),
                'is_hand' => Schema::TYPE_SMALLINT . ' DEFAULT 0',
                'is_active_position' => Schema::TYPE_SMALLINT . ' DEFAULT 1',
                'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => Schema::TYPE_TIMESTAMP,
            ],$tableOptions
        );

        $this->createTable(
            '{{%api_auto_modification}}',
            [
                'id' => Schema::TYPE_PK,
                'brand_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'model_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'name' => Schema::TYPE_STRING . ' NOT NULL',
                'body' => Schema::TYPE_STRING . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'release_year' => Schema::TYPE_INTEGER . ' NOT NULL',
                'engine_displacement' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'power' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'center_bore' => Schema::TYPE_STRING . ' NOT NULL',
                'pcd' => Schema::TYPE_STRING . ' NOT NULL',
                'lug_count' => Schema::TYPE_INTEGER . ' NOT NULL',
                'lug_size' => Schema::TYPE_STRING . ' NOT NULL',
                'lug_type' => Schema::TYPE_INTEGER . ' NOT NULL',
                'market' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'slug' => Schema::TYPE_STRING . ' NOT NULL',
                'is_hand' => Schema::TYPE_SMALLINT . ' DEFAULT 0',
                'is_active_position' => Schema::TYPE_SMALLINT . ' DEFAULT 1',
                'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => Schema::TYPE_TIMESTAMP,
            ],$tableOptions
        );

        $this->createTable(
            '{{%api_auto_modification_tire}}',
            [
                'id' => Schema::TYPE_PK,
                'modification_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'width' => Schema::TYPE_DOUBLE . ' NOT NULL',
                'height' => Schema::TYPE_DOUBLE . ' NOT NULL',
                'diameter' => Schema::TYPE_DOUBLE. ' NOT NULL',
                'load_index' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'speed_rating' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'additional_param' => Schema::TYPE_STRING . ' DEFAULT NULL',
                'is_factory' => Schema::TYPE_INTEGER . ' DEFAULT 1',
                'axle' => Schema::TYPE_INTEGER . ' DEFAULT 0',
                'pair_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'manually_added' => Schema::TYPE_SMALLINT . ' DEFAULT 0',
                'is_active_position' => Schema::TYPE_SMALLINT . ' DEFAULT 1',
                'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => Schema::TYPE_TIMESTAMP,
            ],$tableOptions
        );

        $this->createTable(
            '{{%api_auto_modification_wheel}}',
            [
                'id' => Schema::TYPE_PK,
                'modification_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'width' => Schema::TYPE_DOUBLE . ' NOT NULL',
                'offset' => Schema::TYPE_DOUBLE . ' NOT NULL',
                'diameter' => Schema::TYPE_DOUBLE. ' NOT NULL',
                'is_factory' => Schema::TYPE_INTEGER . ' DEFAULT 1',
                'axle' => Schema::TYPE_INTEGER . ' DEFAULT 0',
                'pair_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'manually_added' => Schema::TYPE_SMALLINT . ' DEFAULT 0',
                'is_active_position' => Schema::TYPE_SMALLINT . ' DEFAULT 1',
                'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => Schema::TYPE_TIMESTAMP,
            ],$tableOptions
        );

    }
    public function safeDown()
    {
        $this->dropTable('{{%api_auto_brand}}');
        $this->dropTable('{{%api_auto_model}}');
        $this->dropTable('{{%api_auto_modification}}');
        $this->dropTable('{{%api_auto_modification_tire}}');
        $this->dropTable('{{%api_auto_modification_wheel}}');
    }

}
