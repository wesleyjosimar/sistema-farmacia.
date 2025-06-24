<?php

use yii\helpers\Html;

$this->title = 'Nova Saída de Produto';
$this->params['breadcrumbs'][] = ['label' => 'Movimentações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimentacao-saida">

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

    function atualizarPrecoVenda(produtoId) {
        if (produtoId) {
            $.get('/produto/get-preco-venda', {id: produtoId}, function(data) {
                $('#movimentacao-preco_unitario').val(data);
            });
        }
    }

    function validarQuantidade(quantidade) {
        var produtoId = $('#movimentacao-produto_id').val();
        if (produtoId && quantidade) {
            $.get('/produto/get-quantidade-atual', {id: produtoId}, function(data) {
                if (parseInt(quantidade) > parseInt(data)) {
                    alert('Quantidade indisponível em estoque. Quantidade máxima: ' + data);
                    $('#movimentacao-quantidade').val('');
                }
            });
        }
    }
");
?> 