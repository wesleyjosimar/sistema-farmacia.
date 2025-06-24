<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\Movimentacao;
use app\models\Produto;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\AccessControl;

/**
 * Controlador para gerenciamento de movimentações de estoque.
 */
class MovimentacaoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['entrada', 'saida', 'delete', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return in_array(\Yii::$app->user->identity->role, ['admin', 'operador']);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lista todas as movimentações.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Movimentacao::find()
                ->joinWith(['produto'])
                ->orderBy(['data_movimentacao' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'data_movimentacao' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Registra uma entrada de produto no estoque.
     *
     * @return string|Response
     */
    public function actionEntrada()
    {
        $model = new Movimentacao();
        $model->tipo = Movimentacao::TIPO_ENTRADA;
        $model->data_movimentacao = date('Y-m-d H:i:s');
        
        $produtos = $this->getProdutosAtivos();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Entrada de produto registrada com sucesso.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Erro ao registrar entrada: ' . $e->getMessage());
            }
        }

        return $this->render('entrada', [
            'model' => $model,
            'produtos' => $produtos,
        ]);
    }

    /**
     * Registra uma saída de produto do estoque.
     *
     * @return string|Response
     */
    public function actionSaida()
    {
        $model = new Movimentacao();
        $model->tipo = Movimentacao::TIPO_SAIDA;
        $model->data_movimentacao = date('Y-m-d H:i:s');
        
        $produtos = $this->getProdutosAtivosComEstoque();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Saída de produto registrada com sucesso.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Erro ao registrar saída: ' . $e->getMessage());
            }
        }

        return $this->render('saida', [
            'model' => $model,
            'produtos' => $produtos,
        ]);
    }

    /**
     * Exibe os detalhes de uma movimentação.
     *
     * @param int $id ID da movimentação
     * @return string
     * @throws NotFoundHttpException se a movimentação não for encontrada
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Exclui uma movimentação.
     *
     * @param int $id ID da movimentação
     * @return Response
     * @throws NotFoundHttpException se a movimentação não for encontrada
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $produto = $model->produto;
            if ($produto === null) {
                throw new \Exception('Produto não encontrado.');
            }

            // Reverte a movimentação no estoque
            if ($model->tipo === Movimentacao::TIPO_ENTRADA) {
                $produto->quantidade_atual -= $model->quantidade;
            } else {
                $produto->quantidade_atual += $model->quantidade;
            }
            
            if ($produto->save()) {
                $model->delete();
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Movimentação excluída com sucesso.');
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Erro ao atualizar o estoque.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Erro ao excluir a movimentação: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Localiza uma movimentação pelo ID.
     *
     * @param int $id ID da movimentação
     * @return Movimentacao o modelo da movimentação
     * @throws NotFoundHttpException se a movimentação não for encontrada
     */
    protected function findModel(int $id): Movimentacao
    {
        $model = Movimentacao::findOne($id);
        
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página solicitada não existe.');
    }

    /**
     * Retorna a lista de produtos ativos.
     *
     * @return array<int, string>
     */
    private function getProdutosAtivos(): array
    {
        return ArrayHelper::map(
            Produto::find()
                ->where(['status' => Produto::STATUS_ATIVO])
                ->orderBy('nome')
                ->all(),
            'id',
            function(Produto $model): string {
                return $model->codigo . ' - ' . $model->nome;
            }
        );
    }

    /**
     * Retorna a lista de produtos ativos com informação de estoque.
     *
     * @return array<int, string>
     */
    private function getProdutosAtivosComEstoque(): array
    {
        return ArrayHelper::map(
            Produto::find()
                ->where(['status' => Produto::STATUS_ATIVO])
                ->orderBy('nome')
                ->all(),
            'id',
            function(Produto $model): string {
                return sprintf(
                    '%s - %s (Estoque: %d)',
                    $model->codigo,
                    $model->nome,
                    $model->quantidade_atual
                );
            }
        );
    }
} 