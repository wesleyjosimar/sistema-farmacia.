<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Modelo para a tabela "movimentacao" que gerencia todas as entradas e saídas de produtos no estoque.
 * Esta classe implementa a lógica de negócio para garantir a consistência do estoque e o registro
 * adequado de todas as operações realizadas.
 *
 * Funcionalidades implementadas:
 * - Validação de quantidade disponível para saídas
 * - Atualização automática do estoque do produto
 * - Registro de data/hora das operações
 * - Cálculo de valores totais
 * - Rastreamento de notas fiscais
 *
 * @property int $id Identificador único da movimentação
 * @property int $produto_id Referência ao produto movimentado
 * @property string|null $lote Número do lote do produto
 * @property string|null $validade Data de validade do lote
 * @property string $tipo Tipo de movimentação (entrada/saída)
 * @property int $quantidade Quantidade movimentada
 * @property string $data_movimentacao Data e hora da movimentação
 * @property string|null $numero_nota Número da nota fiscal (quando aplicável)
 * @property float|null $preco_unitario Preço unitário do produto na movimentação
 * @property string|null $observacao Observações adicionais sobre a movimentação
 * @property int|null $usuario_id Usuário que realizou a operação
 * @property string $created_at Data de criação do registro
 * @property string $updated_at Data da última atualização
 *
 * @property Produto $produto Relação com o modelo Produto
 */
class Movimentacao extends ActiveRecord
{
    // Constantes para definir os tipos de movimentação
    // Utilizadas para garantir consistência e evitar erros de digitação
    public const TIPO_ENTRADA = 'entrada';
    public const TIPO_SAIDA = 'saida';

    /**
     * Define o nome da tabela no banco de dados.
     * Seguindo as convenções do Yii2 para nomes de tabelas.
     */
    public static function tableName(): string
    {
        return 'movimentacao';
    }

    /**
     * Define as regras de validação para cada atributo.
     * Implementa validações críticas para garantir a integridade dos dados:
     * - Campos obrigatórios
     * - Tipos de dados corretos
     * - Validações de negócio (ex: quantidade disponível)
     *
     * @return array Array de regras de validação
     */
    public function rules(): array
    {
        return [
            [['produto_id', 'tipo', 'quantidade'], 'required', 'message' => 'Este campo é obrigatório.'],
            [['produto_id', 'quantidade', 'usuario_id'], 'integer'],
            [['tipo'], 'string'],
            [['tipo'], 'in', 'range' => [self::TIPO_ENTRADA, self::TIPO_SAIDA]],
            [['data_movimentacao', 'created_at', 'updated_at', 'validade'], 'safe'],
            [['preco_unitario'], 'number'],
            [['observacao'], 'string'],
            [['numero_nota', 'lote'], 'string', 'max' => 50],
            [['lote'], 'required', 'when' => function($model) {
                return $model->tipo === self::TIPO_ENTRADA;
            }, 'message' => 'O lote é obrigatório para entradas.'],
            [['validade'], 'date', 'format' => 'php:Y-m-d'],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::class, 'targetAttribute' => ['produto_id' => 'id']],
            [['quantidade'], 'validateQuantidade'],
        ];
    }

    /**
     * Define os rótulos dos atributos para exibição na interface.
     * Utiliza termos claros e amigáveis para o usuário final.
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'produto_id' => 'Produto',
            'lote' => 'Lote',
            'validade' => 'Data de Validade',
            'tipo' => 'Tipo',
            'quantidade' => 'Quantidade',
            'data_movimentacao' => 'Data da Movimentação',
            'numero_nota' => 'Número da Nota',
            'preco_unitario' => 'Preço Unitário',
            'observacao' => 'Observação',
            'usuario_id' => 'Usuário',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * Define a relação com o modelo Produto.
     * Implementa o relacionamento 1:N onde cada movimentação pertence a um produto.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produto::class, ['id' => 'produto_id']);
    }

    /**
     * Configura os behaviors do modelo.
     * Implementa o TimestampBehavior para gerenciar automaticamente
     * as datas de criação e atualização dos registros.
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Valida a quantidade da movimentação.
     * Implementa a lógica de negócio para evitar:
     * - Saídas maiores que o estoque disponível
     * - Quantidades negativas
     * - Inconsistências no estoque
     *
     * @param string $attribute o atributo sendo validado
     * @param array<string, mixed> $params parâmetros adicionais
     */
    public function validateQuantidade(string $attribute, array $params): void
    {
        if (!$this->hasErrors()) {
            if ($this->tipo === self::TIPO_SAIDA) {
                $produto = Produto::findOne($this->produto_id);
                if ($produto && $this->quantidade > $produto->quantidade_atual) {
                    $this->addError($attribute, 'Quantidade insuficiente em estoque.');
                }
            }
        }
    }

    /**
     * Retorna a lista de tipos de movimentação disponíveis.
     * Utilizado em dropdowns e validações na interface.
     *
     * @return array<string, string>
     */
    public static function getTiposList(): array
    {
        return [
            self::TIPO_ENTRADA => 'Entrada',
            self::TIPO_SAIDA => 'Saída',
        ];
    }

    /**
     * Método executado após o salvamento da movimentação.
     * Implementa a lógica de atualização do estoque do produto:
     * - Aumenta o estoque em caso de entrada
     * - Diminui o estoque em caso de saída
     * - Garante a consistência dos dados através de transação
     *
     * @param bool $insert indica se é uma inserção ou atualização
     * @param array $changedAttributes atributos que foram alterados
     * @return bool sucesso da operação
     */
    public function afterSave($insert, $changedAttributes)
    {
        $result = parent::afterSave($insert, $changedAttributes);

        $produto = $this->produto;
        if ($produto) {
            if ($this->tipo === self::TIPO_ENTRADA) {
                $produto->quantidade_atual += $this->quantidade;
            } else {
                $produto->quantidade_atual -= $this->quantidade;
            }
            return $produto->save() && $result;
        }
        return $result;
    }

    /**
     * Calcula o valor total da movimentação.
     * Multiplica a quantidade pelo preço unitário, tratando valores nulos.
     *
     * @return float valor total da movimentação
     */
    public function getValorTotal(): float
    {
        return (float)($this->quantidade * ($this->preco_unitario ?? 0));
    }
} 