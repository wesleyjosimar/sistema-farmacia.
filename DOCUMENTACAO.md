# Documentação Técnica - Sistema de Farmácia

## 📖 Sobre o Projeto

Este documento descreve o sistema de controle de farmácia desenvolvido para aprendizado.

### Objetivos do Sistema
- Gerenciar produtos da farmácia
- Controlar estoque (entradas e saídas)
- Gerar relatórios básicos
- Sistema de login para usuários

## 🏗️ Arquitetura do Sistema

### Padrão MVC (Model-View-Controller)
O sistema segue o padrão MVC do framework Yii2:

- **Model**: Representa os dados (Produto, Categoria, etc.)
- **View**: Interface com o usuário (formulários, listas)
- **Controller**: Lógica de negócio (validações, processamento)

### Estrutura de Pastas
```
sistema-farmacia/
├── controllers/     # Controllers (lógica)
├── models/         # Modelos (dados)
├── views/          # Views (interface)
├── config/         # Configurações
├── migrations/     # Estrutura do banco
└── web/           # Arquivos públicos
```

## 🗄️ Banco de Dados

### Tabelas Principais

#### 1. produto
```sql
CREATE TABLE produto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(50) UNIQUE,
    nome VARCHAR(200),
    descricao TEXT,
    categoria_id INT,
    fabricante_id INT,
    preco_custo DECIMAL(10,2),
    preco_venda DECIMAL(10,2),
    quantidade_minima INT,
    quantidade_atual INT,
    data_validade DATE,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### 2. categoria
```sql
CREATE TABLE categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    descricao TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### 3. fabricante
```sql
CREATE TABLE fabricante (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    cnpj VARCHAR(20),
    telefone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### 4. movimentacao
```sql
CREATE TABLE movimentacao (
    id INT PRIMARY KEY AUTO_INCREMENT,
    produto_id INT,
    tipo ENUM('entrada', 'saida'),
    quantidade INT,
    data_movimentacao TIMESTAMP,
    observacao TEXT,
    created_at TIMESTAMP
);
```

#### 5. user
```sql
CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    password_hash VARCHAR(255),
    email VARCHAR(100),
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP
);
```

## 🔧 Funcionalidades Implementadas

### 1. Gestão de Produtos
- **Listagem**: Mostra todos os produtos com paginação
- **Cadastro**: Formulário para adicionar novo produto
- **Edição**: Modificar dados de produtos existentes
- **Exclusão**: Remover produtos (com validação)
- **Visualização**: Ver detalhes completos do produto

### 2. Controle de Estoque
- **Entrada**: Registrar chegada de produtos
- **Saída**: Registrar saída de produtos
- **Validação**: Verificar se há estoque suficiente
- **Histórico**: Listar todas as movimentações

### 3. Relatórios
- **Estoque Baixo**: Produtos com quantidade <= mínima
- **Produtos a Vencer**: Produtos próximos ao vencimento
- **Movimentações**: Histórico de entradas e saídas
- **Exportação**: Gerar PDF dos relatórios

### 4. Dashboard
- **Alertas**: Produtos com estoque baixo
- **Vencimento**: Produtos próximos ao vencimento
- **Navegação**: Links rápidos para principais funcionalidades

## 🛡️ Segurança

### Autenticação
- Sistema de login/logout
- Senhas criptografadas com hash
- Controle de acesso por sessão

### Validação de Dados
- Validação no frontend (JavaScript)
- Validação no backend (PHP)
- Sanitização de inputs
- Proteção contra SQL Injection (Active Record)

### Controle de Acesso
- Verificação de usuário logado
- Restrição de ações por tipo de usuário
- Validação de permissões

## 🎨 Interface do Usuário

### Tecnologias Frontend
- **Bootstrap 5**: Framework CSS responsivo
- **jQuery**: Manipulação do DOM
- **Font Awesome**: Ícones

### Características
- **Responsivo**: Funciona em desktop e mobile
- **Intuitivo**: Interface simples e clara
- **Acessível**: Cores contrastantes e textos legíveis
- **Feedback**: Mensagens de sucesso e erro

## 📊 Validações Implementadas

### Produtos
- Código único obrigatório
- Nome obrigatório
- Preço de venda >= preço de custo
- Quantidade mínima > 0
- Data de validade válida

### Movimentações
- Quantidade > 0
- Produto deve existir
- Estoque suficiente para saída
- Data de movimentação válida

### Usuários
- Username único
- Email válido
- Senha com mínimo de caracteres

## 🔍 Queries Importantes

### Produtos com Estoque Baixo
```sql
SELECT * FROM produto 
WHERE quantidade_atual <= quantidade_minima 
AND status = 1
ORDER BY nome;
```

### Produtos a Vencer (30 dias)
```sql
SELECT * FROM produto 
WHERE data_validade BETWEEN CURDATE() 
AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
AND status = 1
ORDER BY data_validade;
```

### Movimentações por Produto
```sql
SELECT p.nome, m.tipo, m.quantidade, m.data_movimentacao
FROM movimentacao m
JOIN produto p ON m.produto_id = p.id
WHERE p.id = ?
ORDER BY m.data_movimentacao DESC;
```

## 🐛 Problemas Conhecidos

### Limitações Atuais
1. **Validação de Data**: Formato de data pode ser melhorado
2. **Relatórios**: Faltam relatórios mais detalhados
3. **Backup**: Não há sistema automático de backup
4. **Mobile**: Interface mobile pode ser melhorada

### Melhorias Futuras
1. **Notificações**: Sistema de alertas por email
2. **Relatórios**: Gráficos e estatísticas
3. **API**: Interface para integração com outros sistemas
4. **Multi-idioma**: Suporte a outros idiomas

## 📚 Aprendizados

### Tecnologias Aprendidas
- **PHP**: Linguagem de programação
- **Yii2**: Framework MVC
- **MySQL**: Banco de dados relacional
- **Bootstrap**: Framework CSS
- **Git**: Controle de versão

### Conceitos Aplicados
- **MVC**: Separação de responsabilidades
- **Active Record**: Mapeamento objeto-relacional
- **Validação**: Verificação de dados
- **Segurança**: Autenticação e autorização
- **Responsividade**: Design adaptativo

## 🚀 Como Executar

### Requisitos
- PHP 7.4+
- MySQL 5.7+
- Composer
- Servidor web (Apache/Nginx)

### Instalação
1. Clone o repositório
2. Execute `composer install`
3. Configure o banco de dados
4. Execute `php yii migrate`
5. Configure as permissões
6. Acesse no navegador

## 📞 Suporte

Para dúvidas ou problemas:
- **Email**: estudante@universidade.edu.br
- **Disciplina**: Desenvolvimento Web
- **Professor**: [Nome do Professor]

---

**Documento criado para documentar o sistema** 