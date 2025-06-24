<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Movimentação #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Movimentações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimentacao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem certeza que deseja excluir esta movimentação? Esta ação irá reverter o estoque.',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'produto_id',
                'value' => $model->produto->nome,
                'label' => 'Produto',
            ],
            [
                'attribute' => 'tipo',
                'value' => ucfirst($model->tipo),
                'contentOptions' => [
                    'class' => $model->tipo === 'entrada' ? 'text-success' : 'text-danger',
                ],
            ],
            'quantidade',
            [
                'attribute' => 'preco_unitario',
                'value' => 'R$ ' . number_format($model->preco_unitario, 2, ',', '.'),
            ],
            [
                'label' => 'Valor Total',
                'value' => 'R$ ' . number_format($model->getValorTotal(), 2, ',', '.'),
            ],
            'numero_nota',
            'observacao:ntext',
            'data_movimentacao:datetime',
            'created_at:datetime',
            'updated_at:datetime',
        ],
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
    ]) ?>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Informações do Produto</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Código:</strong> <?= Html::encode($model->produto->codigo) ?></p>
                    <p><strong>Nome:</strong> <?= Html::encode($model->produto->nome) ?></p>
                    <p><strong>Categoria:</strong> <?= Html::encode($model->produto->categoria->nome) ?></p>
                    <p><strong>Fabricante:</strong> <?= Html::encode($model->produto->fabricante->nome) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Quantidade Mínima:</strong> <?= Html::encode($model->produto->quantidade_minima) ?></p>
                    <p><strong>Quantidade Atual:</strong> <?= Html::encode($model->produto->quantidade_atual) ?></p>
                    <p><strong>Preço de Custo:</strong> R$ <?= number_format($model->produto->preco_custo, 2, ',', '.') ?></p>
                    <p><strong>Preço de Venda:</strong> R$ <?= number_format($model->produto->preco_venda, 2, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>

</div> 