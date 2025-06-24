<?php

use yii\db\Migration;

class m240621_000001_add_lote_validade_to_movimentacao extends Migration
{
    public function safeUp()
    {
        $this->addColumn('movimentacao', 'lote', $this->string(50)->null()->after('produto_id'));
        $this->addColumn('movimentacao', 'validade', $this->date()->null()->after('lote'));
    }

    public function safeDown()
    {
        $this->dropColumn('movimentacao', 'lote');
        $this->dropColumn('movimentacao', 'validade');
    }
} 