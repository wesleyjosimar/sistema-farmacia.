<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Produto;

/**
 * @var yii\web\View $this
 * @var app\models\Produto $model
 * @var array<int, string> $categorias
 * @var array<int, string> $fabricantes
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="produto-form">
    <?php $form = ActiveForm::begin([
        'id' => 'produto-form',
        'enableAjaxValidation' => true,
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'codigo')->textInput([
                'maxlength' => true,
                'required' => true,
                'aria-label' => 'Código do produto'
            ]) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'nome')->textInput([
                'maxlength' => true,
                'required' => true,
                'aria-label' => 'Nome do produto'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'categoria_id')->dropDownList(
                $categorias,
                [
                    'prompt' => 'Selecione uma categoria...',
                    'required' => true,
                    'aria-label' => 'Categoria do produto'
                ]
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'fabricante_id')->dropDownList(
                $fabricantes,
                [
                    'prompt' => 'Selecione um fabricante...',
                    'required' => true,
                    'aria-label' => 'Fabricante do produto'
                ]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'preco_custo')->widget(
                MaskedInput::class,
                [
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'digits' => 2,
                        'digitsOptional' => false,
                        'radixPoint' => ',',
                        'groupSeparator' => '.',
                        'autoGroup' => true,
                        'removeMaskOnSubmit' => true,
                    ],
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => '0,00',
                        'required' => true,
                        'min' => '0',
                        'aria-label' => 'Preço de custo',
                        'value' => number_format((float)($model->preco_custo ?? 0), 2, ',', '.'),
                    ],
                ]
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'preco_venda')->widget(
                MaskedInput::class,
                [
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'digits' => 2,
                        'digitsOptional' => false,
                        'radixPoint' => ',',
                        'groupSeparator' => '.',
                        'autoGroup' => true,
                        'removeMaskOnSubmit' => true,
                    ],
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => '0,00',
                        'required' => true,
                        'min' => '0',
                        'aria-label' => 'Preço de venda',
                        'value' => number_format((float)($model->preco_venda ?? 0), 2, ',', '.'),
                    ],
                ]
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'data_validade')->widget(
                MaskedInput::class,
                [
                    'mask' => '99/99/9999',
                    'value' => $model->getDataValidadeFormatada(),
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'dd/mm/aaaa',
                        'aria-label' => 'Data de validade'
                    ]
                ]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'quantidade_minima')->textInput([
                'type' => 'number',
                'min' => '1',
                'required' => true,
                'value' => $model->isNewRecord ? '1' : $model->quantidade_minima,
                'aria-label' => 'Quantidade mínima'
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'quantidade_atual')->textInput([
                'type' => 'number',
                'min' => '0',
                'value' => $model->isNewRecord ? '0' : $model->quantidade_atual,
                'aria-label' => 'Quantidade atual'
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList(
                Produto::getStatusList(),
                ['aria-label' => 'Status do produto']
            ) ?>
        </div>
    </div>

    <?= $form->field($model, 'descricao')->textarea([
        'rows' => 3,
        'aria-label' => 'Descrição do produto'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(
            'Salvar',
            [
                'class' => 'btn btn-success',
                'aria-label' => 'Salvar produto'
            ]
        ) ?>
        <?= Html::a(
            'Cancelar',
            ['index'],
            [
                'class' => 'btn btn-secondary',
                'aria-label' => 'Cancelar e voltar'
            ]
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerCss("
    .form-group {
        margin-top: 1rem;
    }
");

$this->registerJs("
    $(document).ready(function() {
        // Formatar valores iniciais
        function formatarNumero(valor) {
            return parseFloat(valor || 0).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        $('#produto-form').on('beforeSubmit', function(e) {
            e.preventDefault();
            
            const quantidade = parseInt($('#produto-quantidade_atual').val() || 0);
            const quantidadeMinima = parseInt($('#produto-quantidade_minima').val() || 1);
            const precoCusto = parseFloat(
                ($('#produto-preco_custo').val() || '0').replace(/\./g, '').replace(',', '.')
            );
            const precoVenda = parseFloat(
                ($('#produto-preco_venda').val() || '0').replace(/\./g, '').replace(',', '.')
            );
            
            if (quantidade < 0) {
                alert('A quantidade atual não pode ser negativa.');
                return false;
            }
            
            if (quantidadeMinima < 1) {
                alert('A quantidade mínima deve ser maior que zero.');
                return false;
            }
            
            if (precoCusto < 0 || precoVenda < 0) {
                alert('Os preços não podem ser negativos.');
                return false;
            }
            
            if (precoVenda < precoCusto) {
                alert('O preço de venda deve ser maior ou igual ao preço de custo.');
                return false;
            }

            // Atualizar os valores antes do envio
            $('#produto-preco_custo').val(precoCusto);
            $('#produto-preco_venda').val(precoVenda);
            
            return true;
        });
    });
");
?> 