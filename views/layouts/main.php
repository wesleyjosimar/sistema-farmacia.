<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?> - Sistema de Farmácia</title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'Sistema de Farmácia',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $role = Yii::$app->user->identity->role;
        $menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];
        if (in_array($role, ['admin', 'operador', 'visualizador'])) {
            $menuItems[] = [
                'label' => 'Cadastros',
                'items' => [
                    ['label' => 'Categorias', 'url' => ['/categoria/index']],
                    ['label' => 'Fabricantes', 'url' => ['/fabricante/index']],
                    ['label' => 'Produtos', 'url' => ['/produto/index']],
                ],
            ];
            $menuItems[] = [
                'label' => 'Movimentações',
                'items' => [
                    ['label' => 'Entrada de Produtos', 'url' => ['/movimentacao/entrada'], 'visible' => in_array($role, ['admin', 'operador'])],
                    ['label' => 'Saída de Produtos', 'url' => ['/movimentacao/saida'], 'visible' => in_array($role, ['admin', 'operador'])],
                    ['label' => 'Histórico', 'url' => ['/movimentacao/index']],
                ],
            ];
            $menuItems[] = [
                'label' => 'Relatórios',
                'items' => [
                    ['label' => 'Estoque Baixo', 'url' => ['/relatorio/estoque-baixo']],
                    ['label' => 'Produtos a Vencer', 'url' => ['/relatorio/produtos-vencer']],
                    ['label' => 'Movimentações', 'url' => ['/relatorio/movimentacoes']],
                ],
            ];
        }
        if ($role === 'admin') {
            $menuItems[] = ['label' => 'Usuários', 'url' => ['/user/index']];
        }
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
            . Html::submitButton(
                'Sair (' . Html::encode(Yii::$app->user->identity->username) . ')',
                ['class' => 'btn btn-link logout', 'style' => 'padding-top:10px; color:#fff;']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; Sistema de Farmácia <?= date('Y') ?></p>
        <p class="float-end">Desenvolvido com Yii Framework</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
