<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fabricante}}`.
 */
class m240602_000002_create_fabricante_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fabricante}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(100)->notNull(),
            'cnpj' => $this->string(14)->unique(),
            'telefone' => $this->string(20),
            'email' => $this->string(100),
            'endereco' => $this->text(),
            'contato' => $this->string(100),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Criar índices
        $this->createIndex(
            'idx-fabricante-nome',
            '{{%fabricante}}',
            'nome'
        );

        $this->createIndex(
            'idx-fabricante-cnpj',
            '{{%fabricante}}',
            'cnpj'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fabricante}}');
    }
} 