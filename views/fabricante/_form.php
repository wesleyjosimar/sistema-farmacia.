<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

?>

<div class="fabricante-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj')->widget(MaskedInput::class, [
        'mask' => '99.999.999/9999-99',
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'telefone')->widget(MaskedInput::class, [
        'mask' => '(99) 99999-9999',
    ]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email']) ?>

    <?= $form->field($model, 'endereco')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>

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
?> 