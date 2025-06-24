# Comentários e Decisões de Código

## 📝 Sobre os Comentários

Este documento explica as decisões tomadas durante o desenvolvimento do sistema e como os comentários foram escritos para facilitar o entendimento.

## 🎯 Objetivo dos Comentários

Os comentários foram escritos pensando em:
- **Facilitar o entendimento** para outros desenvolvedores
- **Documentar decisões** importantes
- **Explicar lógicas** complexas
- **Ajudar na manutenção** futura

## 📋 Padrão de Comentários

### 1. Comentários de Arquivo
```php
// modelo para produtos da farmácia
// trabalho da 5ª fase de ciência da computação
```
- **Por que**: Identifica rapidamente o que o arquivo faz
- **Onde**: No início de cada arquivo

### 2. Comentários de Classe
```php
/**
 * Modelo para a tabela "produto".
 * 
 * Este modelo representa um produto da farmácia com todas suas informações
 * como nome, preço, quantidade em estoque, etc.
 */
```
- **Por que**: Explica o propósito da classe
- **Onde**: Antes da declaração da classe

### 3. Comentários de Método
```php
/**
 * Verifica se o produto está com estoque baixo
 * Retorna true se a quantidade atual for menor ou igual à mínima
 */
```
- **Por que**: Explica o que o método faz e como funciona
- **Onde**: Antes de cada método público

### 4. Comentários Inline
```php
$produtosEstoqueBaixo = Produto::find()
    ->where('quantidade_atual <= quantidade_minima')
    ->andWhere(['status' => Produto::STATUS_ATIVO])
    ->limit(5) // mostra só os 5 primeiros
    ->all();
```
- **Por que**: Explica decisões específicas no código
- **Onde**: Ao lado de linhas que precisam de explicação

## 🔧 Decisões de Código

### 1. Nomenclatura de Variáveis
```php
// ✅ Bom - claro e descritivo
$produtosEstoqueBaixo = Produto::find()...

// ❌ Ruim - não fica claro
$prod = Produto::find()...
```

### 2. Estrutura de Condicionais
```php
// ✅ Bom - fácil de entender
if ($tipo === 'entrada') {
    $this->quantidade_atual += $quantidade;
} elseif ($tipo === 'saida') {
    if (!$this->temEstoqueSuficiente($quantidade)) {
        return false; // não tem estoque suficiente
    }
    $this->quantidade_atual -= $quantidade;
}

// ❌ Ruim - difícil de entender
if ($tipo === 'entrada') $this->quantidade_atual += $quantidade;
elseif ($tipo === 'saida' && $this->temEstoqueSuficiente($quantidade)) $this->quantidade_atual -= $quantidade;
```

### 3. Tratamento de Erros
```php
// ✅ Bom - mensagem clara
if ($model->save()) {
    Yii::$app->session->setFlash('success', 'Produto cadastrado com sucesso!');
} else {
    Yii::$app->session->setFlash('error', 'Erro ao salvar o produto. Verifique os dados.');
}

// ❌ Ruim - sem feedback
$model->save();
```

## 🎨 Estilo de Código

### 1. Indentação
- **4 espaços** para indentação (não tab)
- **Consistência** em todo o projeto

### 2. Espaçamento
```php
// ✅ Bom - espaçamento adequado
public function actionIndex(): string
{
    $dataProvider = new ActiveDataProvider([
        'query' => Produto::find()
            ->joinWith(['categoria', 'fabricante'])
            ->orderBy(['nome' => SORT_ASC]),
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);

    return $this->render('index', [
        'dataProvider' => $dataProvider,
    ]);
}
```

### 3. Nomes de Métodos
```php
// ✅ Bom - verbos que descrevem a ação
public function actionCreate() { }
public function actionUpdate() { }
public function actionDelete() { }
public function isEstoqueBaixo() { }
public function temEstoqueSuficiente() { }

// ❌ Ruim - nomes confusos
public function actionNew() { }
public function actionEdit() { }
public function actionRemove() { }
public function checkStock() { }
```

## 🔍 Validações

### 1. Validação de Dados
```php
// ✅ Bom - validação clara e específica
[['codigo', 'nome'], 'required', 'message' => 'Este campo é obrigatório!'],
[['codigo'], 'unique', 'message' => 'Este código já existe!'],
['preco_venda', 'compare', 'compareAttribute' => 'preco_custo', 'operator' => '>=', 
 'message' => 'Preço de venda deve ser maior que o custo!'],

// ❌ Ruim - validação genérica
[['codigo', 'nome'], 'required'],
[['codigo'], 'unique'],
```

### 2. Tratamento de Datas
```php
// ✅ Bom - função específica para formatação
private function formatarData(string $data): string
{
    $partes = explode('/', $data);
    if (count($partes) === 3) {
        return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    return $data;
}

// ❌ Ruim - formatação inline
$data = explode('/', $data)[2] . '-' . explode('/', $data)[1] . '-' . explode('/', $data)[0];
```

## 🛡️ Segurança

### 1. Escape de Dados
```php
// ✅ Bom - sempre escapar dados na view
<?= Html::encode($produto->nome) ?>

// ❌ Ruim - dados não escapados
<?= $produto->nome ?>
```

### 2. Validação de Acesso
```php
// ✅ Bom - verificação de permissão
public function behaviors()
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
    ];
}
```

## 📊 Queries

### 1. Queries Simples
```php
// ✅ Bom - query clara e eficiente
$produtosEstoqueBaixo = Produto::find()
    ->where('quantidade_atual <= quantidade_minima')
    ->andWhere(['status' => Produto::STATUS_ATIVO])
    ->limit(5)
    ->all();

// ❌ Ruim - query complexa sem necessidade
$produtosEstoqueBaixo = Produto::find()
    ->select(['*'])
    ->from(['p' => 'produto'])
    ->where(['and', 'p.quantidade_atual <= p.quantidade_minima', 'p.status = 1'])
    ->limit(5)
    ->all();
```

## 🎯 Boas Práticas Aplicadas

### 1. Separação de Responsabilidades
- **Models**: Apenas lógica de dados
- **Controllers**: Apenas lógica de negócio
- **Views**: Apenas apresentação

### 2. Reutilização de Código
```php
// ✅ Bom - método reutilizável
private function getCategoriasList(): array
{
    return ArrayHelper::map(Categoria::find()->orderBy('nome')->all(), 'id', 'nome');
}

// Usado em create e update
$categorias = $this->getCategoriasList();
```

### 3. Constantes para Valores Fixos
```php
// ✅ Bom - constantes para status
public const STATUS_ATIVO = 1;
public const STATUS_INATIVO = 0;

// Usado no código
->andWhere(['status' => Produto::STATUS_ATIVO])
```

## 🐛 Problemas Comuns Evitados

### 1. SQL Injection
- **Solução**: Uso do Active Record do Yii2
- **Exemplo**: `Produto::find()->where(['id' => $id])`

### 2. XSS (Cross-Site Scripting)
- **Solução**: Escape de dados com `Html::encode()`
- **Exemplo**: `<?= Html::encode($produto->nome) ?>`

### 3. CSRF (Cross-Site Request Forgery)
- **Solução**: Tokens CSRF automáticos do Yii2
- **Exemplo**: Formulários já incluem proteção

## 📚 Aprendizados

### 1. Comentários Importantes
- **Sempre comentar** métodos complexos
- **Explicar decisões** importantes
- **Manter comentários** atualizados

### 2. Código Limpo
- **Nomes descritivos** para variáveis e métodos
- **Funções pequenas** com responsabilidade única
- **Estrutura clara** e organizada

### 3. Segurança
- **Sempre validar** dados de entrada
- **Escapar dados** na saída
- **Controlar acesso** às funcionalidades

---

**Este documento foi criado para explicar as decisões de código do trabalho da 5ª fase** 