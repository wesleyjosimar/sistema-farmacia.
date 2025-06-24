<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use app\models\Produto;
use app\models\Movimentacao;

$this->title = 'Relatório de Movimentações';
$this->params['breadcrumbs'][] = ['label' => 'Relatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Cache da lista de produtos por 1 hora
$produtos = Yii::$app->cache->getOrSet('lista_produtos', function() {
    return ArrayHelper::map(
        Produto::find()
            ->select(['id', 'nome'])
            ->where(['status' => Produto::STATUS_ATIVO])
            ->orderBy('nome')
            ->all(), 
        'id', 'nome'
    );
}, 3600);
?>
<div class="relatorio-movimentacoes">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card mb-4">
        <div class="card-body">
            <?php $form = ActiveForm::begin(['method' => 'get']); ?>
            
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'data_inicial')->widget(MaskedInput::class, [
                        'mask' => '99/99/9999',
                        'value' => Yii::$app->request->get('data_inicial', date('d/m/Y', strtotime('first day of this month'))),
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'data_final')->widget(MaskedInput::class, [
                        'mask' => '99/99/9999',
                        'value' => Yii::$app->request->get('data_final', date('d/m/Y', strtotime('last day of this month'))),
                    ]) ?>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Limpar', ['movimentacoes'], ['class' => 'btn btn-outline-secondary ms-2']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total de Entradas</h5>
                    <h3 class="mb-0">R$ <?= number_format($totais['entradas'], 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Total de Saídas</h5>
                    <h3 class="mb-0">R$ <?= number_format($totais['saidas'], 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'data_movimentacao',
                'format' => ['datetime', 'php:d/m/Y H:i:s'],
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
                    return Yii::$app->formatter->asCurrency($model->preco_unitario);
                },
            ],
            [
                'label' => 'Total',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model->getValorTotal());
                },
            ],
            'numero_nota',
            'observacao',
        ],
        'options' => ['class' => 'table-responsive grid-view'],
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'summaryOptions' => ['class' => 'summary mb-2'],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
        ],
    ]); ?>

    <div class="mt-3">
        <?= Html::a('<i class="fas fa-file-pdf"></i> Exportar PDF', array_merge(['movimentacoes-pdf'], Yii::$app->request->get()), ['class' => 'btn btn-danger']) ?>
        <?= Html::a('<i class="fas fa-file-excel"></i> Exportar Excel', array_merge(['movimentacoes-excel'], Yii::$app->request->get()), ['class' => 'btn btn-success']) ?>
    </div>

</div>

<?php
$this->registerCss("
    .btn-sm { margin: 0 2px; }
    .grid-view td { vertical-align: middle; }
");
?> 