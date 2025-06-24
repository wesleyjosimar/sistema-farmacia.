<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movimentacao}}`.
 */
class m240602_000004_create_movimentacao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movimentacao}}', [
            'id' => $this->primaryKey(),
            'produto_id' => $this->integer()->notNull(),
            'tipo' => "ENUM('entrada', 'saida') NOT NULL",
            'quantidade' => $this->integer()->notNull(),
            'data_movimentacao' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'numero_nota' => $this->string(50),
            'preco_unitario' => $this->decimal(10, 2),
            'observacao' => $this->text(),
            'usuario_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Criar índices
        $this->createIndex(
            'idx-movimentacao-produto_id',
            '{{%movimentacao}}',
            'produto_id'
        );

        $this->createIndex(
            'idx-movimentacao-data',
            '{{%movimentacao}}',
            'data_movimentacao'
        );

        // Adicionar chave estrangeira
        $this->addForeignKey(
            'fk-movimentacao-produto_id',
            '{{%movimentacao}}',
            'produto_id',
            '{{%produto}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%movimentacao}}');
    }
} 