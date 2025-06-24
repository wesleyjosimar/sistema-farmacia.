<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Fabricantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fabricante-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem certeza que deseja excluir este fabricante?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            [
                'attribute' => 'cnpj',
                'value' => $model->getCnpjFormatado(),
            ],
            'telefone',
            'email:email',
            'endereco:ntext',
            'contato',
            'created_at:datetime',
            'updated_at:datetime',
        ],
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
    ]) ?>

    <?php if (!empty($model->produtos)): ?>
    <div class="produtos-relacionados mt-4">
        <h2>Produtos deste Fabricante</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model->produtos as $produto): ?>
                    <tr>
                        <td><?= Html::encode($produto->codigo) ?></td>
                        <td><?= Html::encode($produto->nome) ?></td>
                        <td><?= Html::encode($produto->categoria->nome) ?></td>
                        <td><?= Html::encode($produto->quantidade_atual) ?></td>
                        <td>R$ <?= Html::encode(number_format($produto->preco_venda, 2, ',', '.')) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div> 