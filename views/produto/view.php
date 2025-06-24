<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem certeza que deseja excluir este produto?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codigo',
            'nome',
            [
                'attribute' => 'categoria_id',
                'value' => $model->categoria->nome,
            ],
            [
                'attribute' => 'fabricante_id',
                'value' => $model->fabricante->nome,
            ],
            [
                'attribute' => 'preco_custo',
                'value' => 'R$ ' . number_format($model->preco_custo, 2, ',', '.'),
            ],
            [
                'attribute' => 'preco_venda',
                'value' => 'R$ ' . number_format($model->preco_venda, 2, ',', '.'),
            ],
            'quantidade_minima',
            [
                'attribute' => 'quantidade_atual',
                'contentOptions' => ['class' => $model->isEstoqueBaixo() ? 'text-danger' : ''],
            ],
            'data_validade:date',
            [
                'attribute' => 'status',
                'value' => $model->getStatusText(),
                'contentOptions' => ['class' => $model->status == 1 ? 'text-success' : 'text-danger'],
            ],
            'descricao:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ],
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
    ]) ?>

    <?php if (!empty($model->movimentacoes)): ?>
    <div class="movimentacoes-relacionadas mt-4">
        <h2>Movimentações do Produto</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Preço Unit.</th>
                        <th>Total</th>
                        <th>Nota</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model->movimentacoes as $movimentacao): ?>
                    <tr>
                        <td><?= Yii::$app->formatter->asDateTime($movimentacao->data_movimentacao) ?></td>
                        <td><?= ucfirst($movimentacao->tipo) ?></td>
                        <td><?= $movimentacao->quantidade ?></td>
                        <td>R$ <?= number_format($movimentacao->preco_unitario, 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($movimentacao->getValorTotal(), 2, ',', '.') ?></td>
                        <td><?= Html::encode($movimentacao->numero_nota) ?></td>
                        <td><?= Html::encode($movimentacao->observacao) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div> 