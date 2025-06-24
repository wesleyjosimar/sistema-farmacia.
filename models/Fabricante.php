<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "fabricante".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $cnpj
 * @property string|null $telefone
 * @property string|null $email
 * @property string|null $endereco
 * @property string|null $contato
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Produto[] $produtos
 */
class Fabricante extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fabricante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required', 'message' => 'O nome do fabricante é obrigatório'],
            [['endereco'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['nome', 'email', 'contato'], 'string', 'max' => 100],
            [['cnpj'], 'string', 'max' => 14],
            [['telefone'], 'string', 'max' => 20],
            [['cnpj'], 'unique', 'message' => 'Este CNPJ já está cadastrado'],
            [['email'], 'email', 'message' => 'E-mail inválido'],
            [['cnpj'], 'match', 'pattern' => '/^\d{14}$/', 'message' => 'CNPJ deve conter 14 dígitos'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'cnpj' => 'CNPJ',
            'telefone' => 'Telefone',
            'email' => 'E-mail',
            'endereco' => 'Endereço',
            'contato' => 'Contato',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * Gets query for [[Produtos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasMany(Produto::class, ['fabricante_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Formata o CNPJ para exibição
     * @return string
     */
    public function getCnpjFormatado()
    {
        if (strlen($this->cnpj) === 14) {
            return sprintf(
                '%s.%s.%s/%s-%s',
                substr($this->cnpj, 0, 2),
                substr($this->cnpj, 2, 3),
                substr($this->cnpj, 5, 3),
                substr($this->cnpj, 8, 4),
                substr($this->cnpj, 12, 2)
            );
        }
        return $this->cnpj;
    }
} 