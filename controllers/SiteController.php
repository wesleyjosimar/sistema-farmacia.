<?php

// controller principal do sistema
// controla as páginas principais como login, home, etc.

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Produto;

class SiteController extends Controller
{
    /**
     * Configurações de comportamento do controller
     * Define quem pode acessar cada ação
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'about', 'contact', 'logout'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                        'roles' => ['?'], // usuários não logados
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'], // usuários logados
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'], // logout só pode ser POST
                ],
            ],
        ];
    }

    /**
     * Ações padrão do controller
     * Configura ações como captcha e tratamento de erro
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Página inicial do sistema
     * Mostra dashboard com informações importantes
     */
    public function actionIndex()
    {
        // busca produtos com estoque baixo (quantidade atual <= quantidade mínima)
        $produtosEstoqueBaixo = Produto::find()
            ->where('quantidade_atual <= quantidade_minima')
            ->andWhere(['status' => Produto::STATUS_ATIVO])
            ->limit(5) // mostra só os 5 primeiros
            ->all();

        // busca produtos que vencem nos próximos 30 dias
        $produtosAVencer = Produto::find()
            ->where(['status' => Produto::STATUS_ATIVO])
            ->andWhere(['<=', 'data_validade', date('Y-m-d', strtotime('+30 days'))])
            ->andWhere(['>=', 'data_validade', date('Y-m-d')])
            ->limit(5)
            ->all();

        // passa os dados para a view
        return $this->render('index', [
            'produtosEstoqueBaixo' => $produtosEstoqueBaixo,
            'produtosAVencer' => $produtosAVencer,
        ]);
    }

    /**
     * Página de login
     * Permite que o usuário faça login no sistema
     */
    public function actionLogin()
    {
        // se já está logado, vai para a página inicial
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // cria um novo modelo de login
        $model = new LoginForm();
        
        // se recebeu dados do formulário e o login foi bem sucedido
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack(); // volta para a página que tentou acessar
        }

        // limpa a senha para não mostrar na tela
        $model->password = '';
        
        // mostra a página de login
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Faz logout do usuário
     * Só aceita requisições POST por segurança
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Página de contato
     * Permite enviar mensagens para o administrador
     */
    public function actionContact()
    {
        $model = new ContactForm();
        
        // se recebeu dados do formulário e enviou com sucesso
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh(); // recarrega a página
        }
        
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Página sobre o sistema
     * Informações sobre o projeto
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
