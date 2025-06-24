<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%produto}}`.
 */
class m240602_000003_create_produto_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%produto}}', [
            'id' => $this->primaryKey(),
            'codigo' => $this->string(50)->notNull()->unique(),
            'nome' => $this->string(200)->notNull(),
            'descricao' => $this->text(),
            'categoria_id' => $this->integer(),
            'fabricante_id' => $this->integer(),
            'preco_custo' => $this->decimal(10, 2),
            'preco_venda' => $this->decimal(10, 2),
            'quantidade_minima' => $this->integer()->defaultValue(1),
            'quantidade_atual' => $this->integer()->defaultValue(0),
            'data_validade' => $this->date(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Criar índices
        $this->createIndex(
            'idx-produto-codigo',
            '{{%produto}}',
            'codigo'
        );

        $this->createIndex(
            'idx-produto-nome',
            '{{%produto}}',
            'nome'
        );

        // Adicionar chaves estrangeiras
        $this->addForeignKey(
            'fk-produto-categoria_id',
            '{{%produto}}',
            'categoria_id',
            '{{%categoria}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-produto-fabricante_id',
            '{{%produto}}',
            'fabricante_id',
            '{{%fabricante}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%produto}}');
    }
} 