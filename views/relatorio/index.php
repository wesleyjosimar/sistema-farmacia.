<?php

use yii\helpers\Html;

$this->title = 'Relatórios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relatorio-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Produtos com Estoque Baixo</h5>
                    <p class="card-text">Lista de produtos que estão com quantidade abaixo do mínimo estabelecido.</p>
                    <?= Html::a('Visualizar Relatório', ['estoque-baixo'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Produtos a Vencer</h5>
                    <p class="card-text">Lista de produtos que vencerão nos próximos 30 dias.</p>
                    <?= Html::a('Visualizar Relatório', ['produtos-vencer'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Movimentações</h5>
                    <p class="card-text">Relatório de entradas e saídas de produtos por período.</p>
                    <?= Html::a('Visualizar Relatório', ['movimentacoes'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$this->registerCss("
    .card {
        transition: transform .2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,.1);
    }
");
?> 