<?php

use yii\db\Migration;

/**
 * Class m240229_001000_insert_movimentacoes_teste
 */
class m240229_001000_insert_movimentacoes_teste extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Primeiro, vamos pegar alguns produtos existentes
        $produtos = $this->db->createCommand('SELECT id FROM produto WHERE status = 1 LIMIT 5')->queryAll();
        
        if (empty($produtos)) {
            echo "Nenhum produto encontrado. Criando produtos de teste...\n";
            
            // Inserir alguns produtos de teste
            $this->insert('produto', [
                'codigo' => 'P001',
                'nome' => 'Dipirona 500mg',
                'descricao' => 'Analgésico e antitérmico',
                'quantidade_atual' => 0,
                'quantidade_minima' => 10,
                'preco_custo' => 2.50,
                'preco_venda' => 5.00,
                'status' => 1,
                'data_validade' => date('Y-m-d', strtotime('+1 year')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            $this->insert('produto', [
                'codigo' => 'P002',
                'nome' => 'Paracetamol 750mg',
                'descricao' => 'Analgésico e antitérmico',
                'quantidade_atual' => 0,
                'quantidade_minima' => 15,
                'preco_custo' => 3.00,
                'preco_venda' => 6.00,
                'status' => 1,
                'data_validade' => date('Y-m-d', strtotime('+1 year')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            $produtos = $this->db->createCommand('SELECT id FROM produto WHERE status = 1 LIMIT 5')->queryAll();
        }

        // Data base para as movimentações (mês atual)
        $dataBase = date('Y-m-01');
        
        foreach ($produtos as $produto) {
            // Entrada inicial
            $this->insert('movimentacao', [
                'produto_id' => $produto['id'],
                'tipo' => 'entrada',
                'quantidade' => 100,
                'data_movimentacao' => date('Y-m-d H:i:s', strtotime($dataBase . ' +1 day')),
                'numero_nota' => 'NF-' . rand(1000, 9999),
                'preco_unitario' => rand(5, 50) / 2,
                'observacao' => 'Entrada inicial',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Algumas saídas
            for ($i = 0; $i < 3; $i++) {
                $this->insert('movimentacao', [
                    'produto_id' => $produto['id'],
                    'tipo' => 'saida',
                    'quantidade' => rand(1, 10),
                    'data_movimentacao' => date('Y-m-d H:i:s', strtotime($dataBase . ' +' . ($i + 2) . ' days')),
                    'numero_nota' => 'VD-' . rand(1000, 9999),
                    'preco_unitario' => rand(10, 100) / 2,
                    'observacao' => 'Venda ao cliente',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Mais uma entrada
            $this->insert('movimentacao', [
                'produto_id' => $produto['id'],
                'tipo' => 'entrada',
                'quantidade' => 50,
                'data_movimentacao' => date('Y-m-d H:i:s', strtotime($dataBase . ' +5 days')),
                'numero_nota' => 'NF-' . rand(1000, 9999),
                'preco_unitario' => rand(5, 50) / 2,
                'observacao' => 'Reposição de estoque',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Atualizar quantidade_atual dos produtos
        $this->execute('
            UPDATE produto p
            SET quantidade_atual = (
                SELECT COALESCE(SUM(
                    CASE 
                        WHEN m.tipo = "entrada" THEN m.quantidade
                        WHEN m.tipo = "saida" THEN -m.quantidade
                    END
                ), 0)
                FROM movimentacao m
                WHERE m.produto_id = p.id
            )
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240229_001000_insert_movimentacoes_teste não pode ser revertida.\n";
        return false;
    }
} 