<?php

// modelo para produtos da farmácia
// trabalho da 5ª fase de ciência da computação

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Modelo para a tabela "produto".
 * 
 * Este modelo representa um produto da farmácia com todas suas informações
 * como nome, preço, quantidade em estoque, etc.
 *
 * @property int $id - ID único do produto
 * @property string $codigo - Código do produto
 * @property string $nome - Nome do produto
 * @property string|null $descricao - Descrição do produto
 * @property int|null $categoria_id - ID da categoria
 * @property int|null $fabricante_id - ID do fabricante
 * @property float|null $preco_custo - Preço de custo
 * @property float|null $preco_venda - Preço de venda
 * @property int|null $quantidade_minima - Quantidade mínima em estoque
 * @property int|null $quantidade_atual - Quantidade atual em estoque
 * @property string|null $data_validade - Data de validade
 * @property int|null $status - Status do produto (1=ativo, 0=inativo)
 * @property string $created_at - Data de criação
 * @property string $updated_at - Data de atualização
 */
class Produto extends ActiveRecord
{
    // constantes para status do produto
    public const STATUS_ATIVO = 1;
    public const STATUS_INATIVO = 0;

    /**
     * Retorna o nome da tabela no banco de dados
     */
    public static function tableName(): string
    {
        return 'produto';
    }

    /**
     * Regras de validação dos campos
     * Aqui definimos como os dados devem ser validados antes de salvar
     */
    public function rules(): array
    {
        return [
            // campos obrigatórios
            [['codigo', 'nome'], 'required', 'message' => 'Este campo é obrigatório!'],
            [['categoria_id', 'fabricante_id', 'preco_custo', 'preco_venda', 'quantidade_minima'], 'required'],
            
            // tipos de dados
            [['descricao'], 'string'],
            [['categoria_id', 'fabricante_id', 'status'], 'integer'],
            [['quantidade_minima', 'quantidade_atual'], 'integer', 'min' => 0],
            [['preco_custo', 'preco_venda'], 'number', 'min' => 0],
            [['data_validade'], 'date', 'format' => 'php:Y-m-d'],
            [['created_at', 'updated_at'], 'safe'],
            
            // tamanhos máximos
            [['codigo'], 'string', 'max' => 50],
            [['nome'], 'string', 'max' => 200],
            
            // validações especiais
            [['codigo'], 'unique', 'message' => 'Este código já existe!'],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['fabricante_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fabricante::class, 'targetAttribute' => ['fabricante_id' => 'id']],
            
            // valores padrão
            [['quantidade_minima'], 'default', 'value' => 1],
            [['quantidade_atual'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_ATIVO],
            
            // validações de negócio
            ['preco_venda', 'compare', 'compareAttribute' => 'preco_custo', 'operator' => '>=', 
             'message' => 'Preço de venda deve ser maior que o custo!'],
            ['quantidade_minima', 'compare', 'compareValue' => 0, 'operator' => '>', 
             'message' => 'Quantidade mínima deve ser maior que zero!'],
        ];
    }

    /**
     * Labels dos campos para exibição na interface
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'codigo' => 'Código',
            'nome' => 'Nome do Produto',
            'descricao' => 'Descrição',
            'categoria_id' => 'Categoria',
            'fabricante_id' => 'Fabricante',
            'preco_custo' => 'Preço de Custo (R$)',
            'preco_venda' => 'Preço de Venda (R$)',
            'quantidade_minima' => 'Quantidade Mínima',
            'quantidade_atual' => 'Quantidade Atual',
            'data_validade' => 'Data de Validade',
            'status' => 'Status',
            'created_at' => 'Data de Criação',
            'updated_at' => 'Última Atualização',
        ];
    }

    /**
     * Relacionamento com a categoria
     * Um produto pertence a uma categoria
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id' => 'categoria_id']);
    }

    /**
     * Relacionamento com o fabricante
     * Um produto pertence a um fabricante
     */
    public function getFabricante()
    {
        return $this->hasOne(Fabricante::class, ['id' => 'fabricante_id']);
    }

    /**
     * Relacionamento com as movimentações
     * Um produto pode ter várias movimentações (entrada/saída)
     */
    public function getMovimentacoes()
    {
        return $this->hasMany(Movimentacao::class, ['produto_id' => 'id']);
    }

    /**
     * Comportamentos do modelo
     * TimestampBehavior adiciona automaticamente created_at e updated_at
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * Verifica se o produto está com estoque baixo
     * Retorna true se a quantidade atual for menor ou igual à mínima
     */
    public function isEstoqueBaixo(): bool
    {
        return $this->quantidade_atual <= $this->quantidade_minima;
    }

    /**
     * Verifica se o produto está vencido
     * Compara a data de validade com a data atual
     */
    public function isVencido(): bool
    {
        if ($this->data_validade === null) {
            return false; // se não tem data de validade, não está vencido
        }
        return strtotime($this->data_validade) < time();
    }

    /**
     * Retorna a lista de status disponíveis
     * Usado para criar dropdowns na interface
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_ATIVO => 'Ativo',
            self::STATUS_INATIVO => 'Inativo',
        ];
    }

    /**
     * Retorna o texto do status atual
     */
    public function getStatusText(): string
    {
        return self::getStatusList()[$this->status];
    }

    /**
     * Formata a data de validade para exibição
     * Converte de Y-m-d para d/m/Y
     */
    public function getDataValidadeFormatada(): string
    {
        if ($this->data_validade === null) {
            return 'Sem validade';
        }
        return date('d/m/Y', strtotime($this->data_validade));
    }

    /**
     * Verifica se tem estoque suficiente para uma quantidade
     * Usado antes de fazer uma saída
     */
    public function temEstoqueSuficiente(int $quantidade): bool
    {
        return $this->quantidade_atual >= $quantidade;
    }

    /**
     * Atualiza a quantidade do produto
     * tipo: 'entrada' ou 'saida'
     */
    public function atualizarQuantidade(int $quantidade, string $tipo): bool
    {
        if ($tipo === 'entrada') {
            $this->quantidade_atual += $quantidade;
        } elseif ($tipo === 'saida') {
            if (!$this->temEstoqueSuficiente($quantidade)) {
                return false; // não tem estoque suficiente
            }
            $this->quantidade_atual -= $quantidade;
        }
        
        return $this->save();
    }

    /**
     * Executa antes de salvar o produto
     * Aqui podemos fazer validações ou ajustes
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            // converte código para maiúsculo
            $this->codigo = strtoupper($this->codigo);
            return true;
        }
        return false;
    }
} 