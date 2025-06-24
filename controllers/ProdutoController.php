<?php

// controller para gerenciar produtos da farmácia
// trabalho da 5ª fase de ciência da computação

namespace app\controllers;

use Yii;
use app\models\Produto;
use app\models\Categoria;
use app\models\Fabricante;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\AccessControl;

/**
 * Controlador para gerenciamento de produtos.
 * Permite criar, editar, excluir e visualizar produtos.
 */
class ProdutoController extends Controller
{
    /**
     * Configurações de comportamento do controller
     * Define quem pode acessar cada ação
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // só usuários logados
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'], // delete só pode ser POST
                ],
            ],
        ];
    }

    /**
     * Lista todos os produtos
     * Página principal do módulo de produtos
     */
    public function actionIndex(): string
    {
        // cria um provedor de dados para paginação
        $dataProvider = new ActiveDataProvider([
            'query' => Produto::find()
                ->joinWith(['categoria', 'fabricante']) // busca categoria e fabricante junto
                ->orderBy(['nome' => SORT_ASC]), // ordena por nome
            'pagination' => [
                'pageSize' => 20, // 20 produtos por página
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Mostra os detalhes de um produto específico
     * @param int $id ID do produto
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Cria um novo produto
     * Formulário para adicionar produto
     */
    public function actionCreate()
    {
        $model = new Produto();
        
        // busca as listas de categorias e fabricantes para o formulário
        $categorias = $this->getCategoriasList();
        $fabricantes = $this->getFabricantesList();

        // se recebeu dados do formulário
        if ($model->load(Yii::$app->request->post())) {
            // trata a data de validade se foi informada
            if ($data = Yii::$app->request->post('Produto')['data_validade']) {
                $model->data_validade = $this->formatarData($data);
            }

            // tenta salvar o produto
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Produto cadastrado com sucesso!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao salvar o produto. Verifique os dados.');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categorias' => $categorias,
            'fabricantes' => $fabricantes,
        ]);
    }

    /**
     * Atualiza um produto existente
     * @param int $id ID do produto
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $categorias = $this->getCategoriasList();
        $fabricantes = $this->getFabricantesList();

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('Produto');
            
            // trata os preços (remove pontos e vírgulas)
            $model->preco_custo = str_replace(['.', ','], ['', '.'], $post['preco_custo']);
            $model->preco_venda = str_replace(['.', ','], ['', '.'], $post['preco_venda']);
            
            // trata a data de validade
            if (!empty($post['data_validade'])) {
                $model->data_validade = $this->formatarData($post['data_validade']);
            }

            // tenta salvar
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Produto atualizado com sucesso!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar o produto.');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'categorias' => $categorias,
            'fabricantes' => $fabricantes,
        ]);
    }

    /**
     * Exclui um produto
     * @param int $id ID do produto
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        
        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não é possível excluir este produto.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Busca um produto pelo ID
     * @param int $id ID do produto
     * @throws NotFoundHttpException se não encontrar
     */
    protected function findModel(int $id): Produto
    {
        $model = Produto::findOne($id);
        
        if ($model === null) {
            throw new NotFoundHttpException('Produto não encontrado.');
        }

        return $model;
    }

    /**
     * Retorna lista de categorias para dropdown
     */
    private function getCategoriasList(): array
    {
        return ArrayHelper::map(Categoria::find()->orderBy('nome')->all(), 'id', 'nome');
    }

    /**
     * Retorna lista de fabricantes para dropdown
     */
    private function getFabricantesList(): array
    {
        return ArrayHelper::map(Fabricante::find()->orderBy('nome')->all(), 'id', 'nome');
    }

    /**
     * Formata data de d/m/Y para Y-m-d
     * @param string $data Data no formato d/m/Y
     * @return string Data no formato Y-m-d
     */
    private function formatarData(string $data): string
    {
        $partes = explode('/', $data);
        if (count($partes) === 3) {
            return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
        }
        return $data;
    }
} 