<?php

declare(strict_types=1);

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Modelo de pesquisa para Movimentacao.
 *
 * @property string|null $data_inicial
 * @property string|null $data_final
 */
class MovimentacaoSearch extends Model
{
    public ?string $data_inicial = null;
    public ?string $data_final = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['data_inicial', 'data_final'], 'safe'],
            [['data_inicial', 'data_final'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_inicial' => 'Data Inicial',
            'data_final' => 'Data Final',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * Cria um data provider para pesquisa de movimentações.
     *
     * @param array<string, mixed> $params parâmetros de pesquisa
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $dataInicial = $params['data_inicial'] ?? date('Y-m-01');
        $dataFinal = $params['data_final'] ?? date('Y-m-t');

        $query = Movimentacao::find()
            ->joinWith(['produto'])
            ->where([
                'between',
                'data_movimentacao',
                $dataInicial . ' 00:00:00',
                $dataFinal . ' 23:59:59'
            ])
            ->orderBy(['data_movimentacao' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'data_movimentacao' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
} 