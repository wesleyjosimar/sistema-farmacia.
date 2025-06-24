# 🚀 Criar Repositório no GitHub

## ✅ Status Atual
- ✅ Git configurado localmente
- ✅ Repositório local inicializado
- ✅ Primeiro commit realizado (117 arquivos)
- ⏳ **Próximo passo**: Criar repositório no GitHub

## 🌐 Passo 1: Criar Repositório no GitHub

### Opção A: Pelo Site do GitHub (Recomendado)

1. **Acesse**: https://github.com
2. **Faça login** na sua conta
3. **Clique** no botão "+" no canto superior direito
4. **Selecione** "New repository"
5. **Configure o repositório**:
   - **Repository name**: `sistema-farmacia`
   - **Description**: `Sistema de controle de estoque para farmácia - Trabalho da 5ª Fase CC`
   - **Visibility**: Public (ou Private se preferir)
   - **NÃO** marque "Add a README file" (já temos um)
   - **NÃO** marque "Add .gitignore" (já temos um)
   - **NÃO** marque "Choose a license" (já temos um)
6. **Clique** em "Create repository"

### Opção B: Pelo GitHub Desktop
1. **Baixe**: https://desktop.github.com/
2. **Instale** e faça login
3. **Clique** em "File" > "New repository"
4. **Configure**:
   - **Name**: `sistema-farmacia`
   - **Description**: `Sistema de controle de estoque para farmácia`
   - **Local path**: Deixe como está
   - **Git ignore**: None (já temos)
   - **License**: None (já temos)
5. **Clique** em "Create repository"

## 🔗 Passo 2: Conectar com o Repositório Local

### Após criar o repositório no GitHub, execute estes comandos:

```bash
# 1. Adicionar o repositório remoto (SUBSTITUA SEU_USUARIO pelo seu username do GitHub)
& "C:\Program Files\Git\bin\git.exe" remote add origin https://github.com/SEU_USUARIO/sistema-farmacia.git

# 2. Renomear a branch principal para 'main'
& "C:\Program Files\Git\bin\git.exe" branch -M main

# 3. Enviar o código para o GitHub
& "C:\Program Files\Git\bin\git.exe" push -u origin main
```

### Exemplo com username "joaosilva":
```bash
& "C:\Program Files\Git\bin\git.exe" remote add origin https://github.com/joaosilva/sistema-farmacia.git
& "C:\Program Files\Git\bin\git.exe" branch -M main
& "C:\Program Files\Git\bin\git.exe" push -u origin main
```

## 🔑 Passo 3: Autenticação

### Quando solicitado, você precisará:

1. **Username**: Seu username do GitHub
2. **Password**: Use um **Personal Access Token** (não sua senha)

### Como criar um Personal Access Token:

1. **Acesse**: https://github.com/settings/tokens
2. **Clique** em "Generate new token (classic)"
3. **Configure**:
   - **Note**: `Sistema Farmacia`
   - **Expiration**: 90 days (ou escolha)
   - **Scopes**: Marque `repo` (todos os sub-itens)
4. **Clique** em "Generate token"
5. **Copie** o token gerado (você só verá uma vez!)
6. **Use** este token como senha quando solicitado

## 📋 Comandos Completos

### Execute estes comandos em sequência:

```bash
# 1. Verificar status atual
& "C:\Program Files\Git\bin\git.exe" status

# 2. Verificar se o remote foi adicionado
& "C:\Program Files\Git\bin\git.exe" remote -v

# 3. Enviar para o GitHub (substitua SEU_USUARIO)
& "C:\Program Files\Git\bin\git.exe" remote add origin https://github.com/SEU_USUARIO/sistema-farmacia.git
& "C:\Program Files\Git\bin\git.exe" branch -M main
& "C:\Program Files\Git\bin\git.exe" push -u origin main
```

## 🎯 Resultado Esperado

Após executar os comandos, você verá algo como:
```
Enumerating objects: 117, done.
Counting objects: 100% (117/117), done.
Delta compression using up to 8 threads
Compressing objects: 100% (100/100), done.
Writing objects: 100% (117/117), done.
Total 117 (delta 15), reused 0 (delta 0), pack-reused 0
To https://github.com/SEU_USUARIO/sistema-farmacia.git
 * [new branch]      main -> main
Branch 'main' set up to track remote branch 'main' from 'origin'.
```

## 🌐 Acessar o Repositório

Após o upload, você poderá acessar:
- **URL**: `https://github.com/SEU_USUARIO/sistema-farmacia`
- **Clone**: `https://github.com/SEU_USUARIO/sistema-farmacia.git`

## 📁 Estrutura no GitHub

Seu repositório terá:
```
sistema-farmacia/
├── 📄 README.md              # Documentação principal
├── 📄 DOCUMENTACAO.md        # Documentação técnica
├── 📄 COMENTARIOS_CODIGO.md  # Explicação dos comentários
├── 📄 INSTRUCOES_GITHUB.md   # Instruções gerais
├── 📄 CRIAR_REPOSITORIO_GITHUB.md  # Este arquivo
├── 📄 composer.json          # Dependências PHP
├── 📁 config/                # Configurações
├── 📁 controllers/           # Controllers
├── 📁 models/                # Modelos
├── 📁 views/                 # Views
├── 📁 migrations/            # Migrações do banco
└── 📁 web/                   # Arquivos públicos
```

## 🚀 Próximos Passos

Após criar o repositório:

1. **Clone** em outro computador:
   ```bash
   git clone https://github.com/SEU_USUARIO/sistema-farmacia.git
   ```

2. **Instale** as dependências:
   ```bash
   composer install
   ```

3. **Configure** o banco de dados

4. **Execute** as migrações:
   ```bash
   php yii migrate
   ```

## ❓ Problemas Comuns

### Erro: "remote origin already exists"
```bash
# Remover o remote existente
& "C:\Program Files\Git\bin\git.exe" remote remove origin

# Adicionar novamente
& "C:\Program Files\Git\bin\git.exe" remote add origin https://github.com/SEU_USUARIO/sistema-farmacia.git
```

### Erro: "Authentication failed"
- Verifique se está usando o **Personal Access Token** como senha
- Não use sua senha normal do GitHub

### Erro: "Repository not found"
- Verifique se o username está correto
- Verifique se o repositório foi criado no GitHub

## 📞 Ajuda

Se tiver problemas:
- **Git**: https://git-scm.com/doc
- **GitHub**: https://docs.github.com
- **GitHub Desktop**: https://docs.github.com/en/desktop

---

**🎉 Parabéns! Seu projeto estará no GitHub após seguir estes passos!** 