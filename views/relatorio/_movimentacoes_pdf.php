<?php

use yii\grid\GridView;

?>

<h1 style="text-align: center;">Relatório de Movimentações</h1>

<div style="margin-bottom: 20px;">
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; background-color: #dff0d8; padding: 10px;">
                <strong>Total de Entradas:</strong> R$ <?= number_format($totais['entradas'], 2, ',', '.') ?>
            </td>
            <td style="width: 50%; background-color: #f2dede; padding: 10px;">
                <strong>Total de Saídas:</strong> R$ <?= number_format($totais['saidas'], 2, ',', '.') ?>
            </td>
        </tr>
    </table>
</div>

<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
        <tr style="background-color: #f5f5f5;">
            <th style="border: 1px solid #ddd; padding: 8px;">Data</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Produto</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Tipo</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Quantidade</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Preço Unit.</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Total</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Número Nota</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Observação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dataProvider->models as $model): ?>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;"><?= Yii::$app->formatter->asDateTime($model->data_movimentacao) ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?= $model->produto->nome ?></td>
            <td style="border: 1px solid #ddd; padding: 8px; <?= $model->tipo === 'entrada' ? 'color: green;' : 'color: red;' ?>">
                <?= ucfirst($model->tipo) ?>
            </td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?= $model->quantidade ?></td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">R$ <?= number_format($model->preco_unitario, 2, ',', '.') ?></td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">R$ <?= number_format($model->quantidade * $model->preco_unitario, 2, ',', '.') ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?= $model->numero_nota ?></td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?= $model->observacao ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="text-align: right; font-size: 12px; color: #666;">
    Gerado em: <?= date('d/m/Y H:i:s') ?>
</div> 