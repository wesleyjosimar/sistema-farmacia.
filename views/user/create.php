<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Cadastrar Usuário';
?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="user-create">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label('Senha') ?>
    <?= $form->field($model, 'role')->dropDownList([
        'admin' => 'Administrador',
        'operador' => 'Operador',
        'visualizador' => 'Visualizador',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Cadastrar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div> 