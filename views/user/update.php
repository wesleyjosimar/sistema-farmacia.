<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Editar Usuário: ' . $model->username;
?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="user-update">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label('Nova Senha (deixe em branco para não alterar)') ?>
    <?= $form->field($model, 'role')->dropDownList([
        'admin' => 'Administrador',
        'operador' => 'Operador',
        'visualizador' => 'Visualizador',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?> 