<?php

use yii\helpers\Html;

$this->title = 'Nova Entrada de Produto';
$this->params['breadcrumbs'][] = ['label' => 'Movimentações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimentacao-entrada">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'produtos' => $produtos,
    ]) ?>

</div>

<?php
$this->registerCss("
    .form-group { margin-top: 1rem; }
");

$this->registerJs("
    $('.select2').select2();
");
?> 