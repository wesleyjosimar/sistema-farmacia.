<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Fabricantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fabricante-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Novo Fabricante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'nome',
            [
                'attribute' => 'cnpj',
                'value' => function($model) {
                    return $model->getCnpjFormatado();
                }
            ],
            'telefone',
            'email:email',
            'contato',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'Visualizar',
                            'class' => 'btn btn-primary btn-sm',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => 'Editar',
                            'class' => 'btn btn-info btn-sm',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Excluir',
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Tem certeza que deseja excluir este fabricante?',
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