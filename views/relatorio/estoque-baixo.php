<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Relatório de Estoque Baixo';
$this->params['breadcrumbs'][] = ['label' => 'Relatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relatorio-estoque-baixo">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> Este relatório mostra todos os produtos que estão com quantidade em estoque menor ou igual à quantidade mínima estabelecida.
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'codigo',
            'nome',
            [
                'attribute' => 'categoria.nome',
                'label' => 'Categoria',
            ],
            [
                'attribute' => 'fabricante.nome',
                'label' => 'Fabricante',
            ],
            'quantidade_minima',
            [
                'attribute' => 'quantidade_atual',
                'contentOptions' => function($model) {
                    return [
                        'class' => $model->quantidade_atual == 0 ? 'text-danger fw-bold' : 'text-warning',
                    ];
                },
            ],
            [
                'label' => 'Diferença',
                'value' => function($model) {
                    $diff = $model->quantidade_atual - $model->quantidade_minima;
                    return $diff;
                },
                'contentOptions' => ['class' => 'text-danger'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', ['/produto/view', 'id' => $model->id], [
                            'title' => 'Visualizar Produto',
                            'class' => 'btn btn-primary btn-sm',
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

    <div class="mt-3">
        <?= Html::a('<i class="fas fa-file-pdf"></i> Exportar PDF', ['estoque-baixo-pdf'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('<i class="fas fa-file-excel"></i> Exportar Excel', ['estoque-baixo-excel'], ['class' => 'btn btn-success']) ?>
    </div>

</div>

<?php
$this->registerCss("
    .btn-sm { margin: 0 2px; }
    .grid-view td { vertical-align: middle; }
");
?> 