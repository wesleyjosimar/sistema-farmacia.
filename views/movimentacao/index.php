<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Movimentações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimentacao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nova Entrada', ['entrada'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Nova Saída', ['saida'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'data_movimentacao',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'produto.nome',
                'label' => 'Produto',
            ],
            [
                'attribute' => 'tipo',
                'value' => function($model) {
                    return ucfirst($model->tipo);
                },
                'contentOptions' => function($model) {
                    return [
                        'class' => $model->tipo === 'entrada' ? 'text-success' : 'text-danger',
                    ];
                },
            ],
            'quantidade',
            [
                'attribute' => 'preco_unitario',
                'value' => function($model) {
                    return 'R$ ' . number_format($model->preco_unitario, 2, ',', '.');
                },
            ],
            [
                'label' => 'Total',
                'value' => function($model) {
                    return 'R$ ' . number_format($model->getValorTotal(), 2, ',', '.');
                },
            ],
            'numero_nota',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'Visualizar',
                            'class' => 'btn btn-primary btn-sm',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Excluir',
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Tem certeza que deseja excluir esta movimentação? Esta ação irá reverter o estoque.',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
        'options' => ['class' => 'table-responsive grid-view'],
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'summaryOptions' => ['class' => 'summary mb-2'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php
$this->registerCss("
    .btn-sm { margin: 0 2px; }
    .grid-view td { vertical-align: middle; }
");
?> 