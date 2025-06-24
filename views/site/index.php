<?php

// página inicial do sistema de farmácia
// mostra dashboard com informações importantes

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $produtosEstoqueBaixo app\models\Produto[] */
/* @var $produtosAVencer app\models\Produto[] */

$this->title = 'Sistema de Farmácia - Dashboard';
?>

<div class="site-index">
    <!-- cabeçalho da página -->
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Sistema de Controle de Farmácia</h1>
            <p class="fs-5 fw-light">Gerencie seu estoque de forma simples e eficiente</p>
        </div>
    </div>

    <div class="body-content">
        <!-- cards de navegação rápida -->
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2 class="card-title">📦 Produtos</h2>
                        <p class="card-text">Cadastre e gerencie todos os produtos da farmácia</p>
                        <a class="btn btn-primary" href="<?= Yii::$app->urlManager->createUrl(['produto/index']) ?>">
                            Ver Produtos
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2 class="card-title">📊 Movimentações</h2>
                        <p class="card-text">Registre entradas e saídas do estoque</p>
                        <a class="btn btn-success" href="<?= Yii::$app->urlManager->createUrl(['movimentacao/index']) ?>">
                            Ver Movimentações
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h2 class="card-title">📈 Relatórios</h2>
                        <p class="card-text">Visualize relatórios e estatísticas</p>
                        <a class="btn btn-info" href="<?= Yii::$app->urlManager->createUrl(['relatorio/index']) ?>">
                            Ver Relatórios
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- alertas importantes -->
        <div class="row mt-4">
            <!-- produtos com estoque baixo -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="card-title mb-0">⚠️ Produtos com Estoque Baixo</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($produtosEstoqueBaixo)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Atual</th>
                                            <th>Mínimo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($produtosEstoqueBaixo as $produto): ?>
                                        <tr class="table-warning">
                                            <td><?= Html::encode($produto->nome) ?></td>
                                            <td><span class="badge bg-danger"><?= Html::encode($produto->quantidade_atual) ?></span></td>
                                            <td><?= Html::encode($produto->quantidade_minima) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <p>🎉 Nenhum produto com estoque baixo!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- produtos a vencer -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3 class="card-title mb-0">⏰ Produtos a Vencer</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($produtosAVencer)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Validade</th>
                                            <th>Dias</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($produtosAVencer as $produto): ?>
                                        <?php
                                            // calcula quantos dias faltam para vencer
                                            $diasRestantes = floor((strtotime($produto->data_validade) - time()) / (60 * 60 * 24));
                                            
                                            // define a cor baseada nos dias restantes
                                            if ($diasRestantes <= 7) {
                                                $classe = 'table-danger';
                                                $badge = 'bg-danger';
                                            } elseif ($diasRestantes <= 15) {
                                                $classe = 'table-warning';
                                                $badge = 'bg-warning';
                                            } else {
                                                $classe = 'table-info';
                                                $badge = 'bg-info';
                                            }
                                        ?>
                                        <tr class="<?= $classe ?>">
                                            <td><?= Html::encode($produto->nome) ?></td>
                                            <td><?= Yii::$app->formatter->asDate($produto->data_validade) ?></td>
                                            <td><span class="badge <?= $badge ?>"><?= $diasRestantes ?> dias</span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <p>✅ Nenhum produto próximo ao vencimento!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- informações do sistema -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>ℹ️ Informações do Sistema</h4>
                        <p class="text-muted">
                            Sistema desenvolvido para controle de estoque de farmácia.<br>
                            Tecnologias: PHP, Yii2 Framework, MySQL, Bootstrap 5
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS personalizado -->
<style>
.card {
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.card-header {
    font-weight: bold;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.badge {
    font-size: 0.8em;
}
</style>
