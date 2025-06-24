<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Produtos a Vencer';
$this->params['breadcrumbs'][] = ['label' => 'Relatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relatorio-produtos-vencer">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        Este relatório mostra os produtos que vencerão nos próximos 30 dias.
    </div>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'codigo',
                'label' => 'Código',
            ],
            [
                'attribute' => 'nome',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->nome, ['produto/view', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'data_validade',
                'format' => ['date', 'php:d/m/Y'],
                'contentOptions' => function($model) {
                    $diasParaVencer = (strtotime($model->data_validade) - time()) / (60 * 60 * 24);
                    return [
                        'class' => $diasParaVencer <= 7 ? 'text-danger' : 'text-warning',
                    ];
                },
            ],
            [
                'label' => 'Dias para Vencer',
                'value' => function($model) {
                    $diasParaVencer = ceil((strtotime($model->data_validade) - time()) / (60 * 60 * 24));
                    return $diasParaVencer . ' dia(s)';
                },
                'contentOptions' => function($model) {
                    $diasParaVencer = (strtotime($model->data_validade) - time()) / (60 * 60 * 24);
                    return [
                        'class' => $diasParaVencer <= 7 ? 'text-danger' : 'text-warning',
                    ];
                },
            ],
            'quantidade_atual',
            [
                'attribute' => 'preco_venda',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model->preco_venda);
                },
            ],
            [
                'label' => 'Valor Total',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model->quantidade_atual * $model->preco_venda);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', ['produto/view', 'id' => $model->id], [
                            'title' => 'Visualizar',
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

    <?php Pjax::end(); ?>

</div>

<?php
$this->registerCss("
    .btn-sm { margin: 0 2px; }
    .grid-view td { vertical-align: middle; }
    .text-danger { font-weight: bold; }
");
?> 