<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use yii\web\Response;
use yii\filters\AccessControl;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !\Yii::$app->user->isGuest && \Yii::$app->user->identity->role === 'admin';
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new User();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->setPassword($model->password_hash);
            $model->generateAuthKey();
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Usuário cadastrado com sucesso!');
                return $this->redirect(['site/login']);
            }
        }
        $model->password_hash = '';
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        $usuarios = User::find()->all();
        return $this->render('index', [
            'usuarios' => $usuarios,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Usuário não encontrado.');
        }
        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            if (!empty($model->password_hash)) {
                $model->setPassword($model->password_hash);
            } else {
                unset($model->password_hash); // não altera se vazio
            }
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Usuário atualizado com sucesso!');
                return $this->redirect(['index']);
            }
        }
        $model->password_hash = '';
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = User::findOne($id);
        if ($model) {
            $model->delete();
            \Yii::$app->session->setFlash('success', 'Usuário excluído com sucesso!');
        }
        return $this->redirect(['index']);
    }
} 