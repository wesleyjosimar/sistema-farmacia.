# Sistema de Controle de Farmácia

## 📋 Descrição
Sistema de controle de estoque desenvolvido para gerenciar produtos de farmácia.

Este sistema permite gerenciar produtos, categorias, fabricantes e movimentações de estoque de uma farmácia.

## 🚀 Funcionalidades

### ✅ Implementadas
- **Cadastro de Produtos**: Adicionar, editar e excluir produtos
- **Categorias**: Organizar produtos por categorias
- **Fabricantes**: Cadastrar fabricantes dos produtos
- **Movimentações**: Registrar entradas e saídas do estoque
- **Relatórios**: Visualizar relatórios de estoque
- **Dashboard**: Página inicial com alertas importantes
- **Login/Logout**: Sistema de autenticação

### 📊 Recursos do Dashboard
- Produtos com estoque baixo
- Produtos próximos ao vencimento
- Navegação rápida para as principais funcionalidades

## 🛠️ Tecnologias Utilizadas

- **PHP 7.4+**
- **Yii2 Framework** - Framework MVC
- **MySQL** - Banco de dados
- **Bootstrap 5** - Interface responsiva
- **Composer** - Gerenciador de dependências

## 📦 Instalação

### Pré-requisitos
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Composer
- Servidor web (Apache/Nginx)

### Passos para instalação

1. **Clone o repositório**
```bash
git clone https://github.com/estudante/sistema-farmacia.git
cd sistema-farmacia
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o banco de dados**
   - Crie um banco de dados MySQL
   - Copie o arquivo `config/db.php.example` para `config/db.php`
   - Edite as configurações de conexão

4. **Execute as migrações**
```bash
php yii migrate
```

5. **Configure as permissões**
```bash
chmod 777 runtime/
chmod 777 web/assets/
```

6. **Acesse o sistema**
   - Abra no navegador: `http://localhost/farmacia-sistema`
   - Login padrão: `admin` / `admin123`

## 📁 Estrutura do Projeto

```
sistema-farmacia/
├── controllers/          # Controllers do sistema
├── models/              # Modelos (Produto, Categoria, etc.)
├── views/               # Views (templates)
├── config/              # Configurações
├── migrations/          # Migrações do banco
├── web/                 # Arquivos públicos
└── runtime/             # Arquivos temporários
```

## 🗄️ Banco de Dados

### Tabelas principais:
- **produto**: Informações dos produtos
- **categoria**: Categorias dos produtos
- **fabricante**: Fabricantes
- **movimentacao**: Entradas e saídas
- **user**: Usuários do sistema

## 👤 Usuários Padrão

- **Administrador**: `admin` / `admin123`
- **Usuário**: `user` / `user123`

## 📝 Funcionalidades por Módulo

### Produtos
- Listar todos os produtos
- Adicionar novo produto
- Editar produto existente
- Excluir produto
- Visualizar detalhes

### Movimentações
- Registrar entrada de produtos
- Registrar saída de produtos
- Histórico de movimentações
- Controle de estoque automático

### Relatórios
- Produtos com estoque baixo
- Produtos a vencer
- Relatório de movimentações
- Exportar para PDF

## 🔧 Configurações

### Arquivo de configuração principal: `config/web.php`
- Configurações do banco de dados
- Configurações de URL
- Configurações de formatação

### Variáveis de ambiente: `config/params.php`
- Email do administrador
- Configurações específicas do sistema

## 🐛 Problemas Conhecidos

- [ ] Validação de data de validade pode ser melhorada
- [ ] Relatórios mais detalhados
- [ ] Sistema de backup automático
- [ ] Interface mobile mais responsiva

## 📚 Aprendizados

Este projeto foi desenvolvido para aprender:
- **MVC Pattern** com Yii2
- **Active Record** para banco de dados
- **Validação de dados** em formulários
- **Relacionamentos** entre tabelas
- **Sistema de autenticação**
- **Geração de relatórios**

## 👨‍💻 Autor

**Estudante de Ciência da Computação**
- Universidade: Unoesc - Videira
- Disciplina: Desenvolvimento Web
- Professor: LEANDRO OTAVIO CORDOVA VIEIRA

## 📄 Licença

Este projeto é apenas para fins educacionais.

---

**Desenvolvido com ❤️ para aprendizado** 