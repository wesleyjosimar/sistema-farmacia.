<?php

namespace app\controllers;

use Yii;
use app\models\Produto;
use app\models\Movimentacao;
use app\models\MovimentacaoSearch;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\caching\TagDependency;
use kartik\mpdf\Pdf;
use yii\web\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\filters\AccessControl;

class RelatorioController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['estoque-baixo', 'produtos-vencer', 'movimentacoes', 'movimentacoes-pdf', 'movimentacoes-excel'],
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionEstoqueBaixo()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Produto::find()
                ->where('quantidade_atual <= quantidade_minima')
                ->andWhere(['status' => Produto::STATUS_ATIVO])
                ->orderBy(['quantidade_atual' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('estoque-baixo', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProdutosVencer()
    {
        // Data atual
        $hoje = date('Y-m-d');
        // Data limite (30 dias a partir de hoje)
        $dataLimite = date('Y-m-d', strtotime('+30 days'));

        $query = Produto::find()
            ->where(['status' => Produto::STATUS_ATIVO])
            ->andWhere(['not', ['data_validade' => null]]) // Excluir produtos sem data de validade
            ->andWhere(['>=', 'data_validade', $hoje]) // Apenas produtos que ainda não venceram
            ->andWhere(['<=', 'data_validade', $dataLimite]) // Produtos que vencerão em até 30 dias
            ->andWhere(['>', 'quantidade_atual', 0]) // Apenas produtos com estoque
            ->orderBy(['data_validade' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'data_validade' => SORT_ASC,
                ],
                'attributes' => [
                    'codigo',
                    'nome',
                    'data_validade',
                    'quantidade_atual',
                    'preco_venda',
                ],
            ],
        ]);

        return $this->render('produtos-vencer', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMovimentacoes()
    {
        $searchModel = new MovimentacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Calcular totais
        $totais = [
            'entradas' => Movimentacao::find()
                ->where(['tipo' => Movimentacao::TIPO_ENTRADA])
                ->andWhere(['between', 'data_movimentacao', 
                    Yii::$app->request->get('data_inicial', date('Y-m-01')) . ' 00:00:00', 
                    Yii::$app->request->get('data_final', date('Y-m-t')) . ' 23:59:59'
                ])
                ->sum('quantidade * preco_unitario') ?? 0,
            'saidas' => Movimentacao::find()
                ->where(['tipo' => Movimentacao::TIPO_SAIDA])
                ->andWhere(['between', 'data_movimentacao', 
                    Yii::$app->request->get('data_inicial', date('Y-m-01')) . ' 00:00:00', 
                    Yii::$app->request->get('data_final', date('Y-m-t')) . ' 23:59:59'
                ])
                ->sum('quantidade * preco_unitario') ?? 0,
        ];

        return $this->render('movimentacoes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totais' => $totais,
        ]);
    }

    public function actionMovimentacoesPdf()
    {
        $searchModel = new MovimentacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false; // Desabilita paginação para exportar todos os registros

        // Calcular totais
        $totais = [
            'entradas' => Movimentacao::find()
                ->where(['tipo' => Movimentacao::TIPO_ENTRADA])
                ->andWhere(['between', 'data_movimentacao', 
                    Yii::$app->request->get('data_inicial', date('Y-m-01')) . ' 00:00:00', 
                    Yii::$app->request->get('data_final', date('Y-m-t')) . ' 23:59:59'
                ])
                ->sum('quantidade * preco_unitario') ?? 0,
            'saidas' => Movimentacao::find()
                ->where(['tipo' => Movimentacao::TIPO_SAIDA])
                ->andWhere(['between', 'data_movimentacao', 
                    Yii::$app->request->get('data_inicial', date('Y-m-01')) . ' 00:00:00', 
                    Yii::$app->request->get('data_final', date('Y-m-t')) . ' 23:59:59'
                ])
                ->sum('quantidade * preco_unitario') ?? 0,
        ];

        $content = $this->renderPartial('_movimentacoes_pdf', [
            'dataProvider' => $dataProvider,
            'totais' => $totais,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@webroot/css/pdf.css',
            'options' => ['title' => 'Relatório de Movimentações'],
            'methods' => [
                'SetHeader' => ['Relatório de Movimentações - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    public function actionMovimentacoesExcel()
    {
        $searchModel = new MovimentacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false; // Desabilita paginação para exportar todos os registros

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cabeçalho
        $sheet->setCellValue('A1', 'Data');
        $sheet->setCellValue('B1', 'Produto');
        $sheet->setCellValue('C1', 'Tipo');
        $sheet->setCellValue('D1', 'Quantidade');
        $sheet->setCellValue('E1', 'Preço Unitário');
        $sheet->setCellValue('F1', 'Total');
        $sheet->setCellValue('G1', 'Número Nota');
        $sheet->setCellValue('H1', 'Observação');

        // Estilo do cabeçalho
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');

        // Dados
        $row = 2;
        foreach ($dataProvider->models as $model) {
            $sheet->setCellValue('A' . $row, Yii::$app->formatter->asDateTime($model->data_movimentacao));
            $sheet->setCellValue('B' . $row, $model->produto->nome);
            $sheet->setCellValue('C' . $row, ucfirst($model->tipo));
            $sheet->setCellValue('D' . $row, $model->quantidade);
            $sheet->setCellValue('E' . $row, $model->preco_unitario);
            $sheet->setCellValue('F' . $row, $model->quantidade * $model->preco_unitario);
            $sheet->setCellValue('G' . $row, $model->numero_nota);
            $sheet->setCellValue('H' . $row, $model->observacao);
            $row++;
        }

        // Autosize colunas
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Formatar células de moeda
        $sheet->getStyle('E2:F' . ($row-1))->getNumberFormat()->setFormatCode('R$ #,##0.00');

        // Configurar resposta
        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $headers->add('Content-Disposition', 'attachment;filename="movimentacoes.xlsx"');

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }
} 