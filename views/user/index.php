<?php
use yii\helpers\Html;

$this->title = 'Usuários';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->user->identity->role === 'admin'): ?>
<p><?= Html::a('Novo Usuário', ['create'], ['class' => 'btn btn-success']) ?></p>
<?php endif; ?>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Perfil</th>
            <?php if (Yii::$app->user->identity->role === 'admin'): ?><th>Ações</th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= Html::encode($usuario->id) ?></td>
                <td><?= Html::encode($usuario->username) ?></td>
                <td><?= Html::encode(ucfirst($usuario->role)) ?></td>
                <?php if (Yii::$app->user->identity->role === 'admin'): ?>
                <td>
                    <?= Html::a('Editar', ['update', 'id' => $usuario->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?php if ($usuario->id != Yii::$app->user->id): ?>
                        <?= Html::a('Excluir', ['delete', 'id' => $usuario->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Tem certeza que deseja excluir este usuário?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table> 