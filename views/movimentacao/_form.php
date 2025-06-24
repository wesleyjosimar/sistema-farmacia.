<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

?>

<div class="movimentacao-form">
    <?php $form = ActiveForm::begin([
        'id' => 'movimentacao-form',
        'enableAjaxValidation' => false,
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'produto_id')->dropDownList($produtos, [
                'prompt' => 'Selecione um produto...',
                'class' => 'form-control select2',
                'required' => true
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'data_movimentacao')->widget(MaskedInput::class, [
                'mask' => '99/99/9999 99:99',
                'value' => date('d/m/Y H:i'),
                'options' => ['required' => true]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'quantidade')->textInput([
                'type' => 'number',
                'min' => '1',
                'required' => true
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'preco_unitario')->widget(MaskedInput::class, [
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
                    'required' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'numero_nota')->textInput([
                'maxlength' => true,
                'required' => true
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'observacao')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerCss("
    .form-group { margin-top: 1rem; }
");

$this->registerJs("
    $('.select2').select2();
");
?> 