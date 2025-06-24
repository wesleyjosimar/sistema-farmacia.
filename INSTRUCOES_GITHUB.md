# Instruções para Criar Repositório no GitHub

## 🔧 Passo 1: Instalar o Git

### Windows
1. **Baixe o Git**: Acesse https://git-scm.com/download/win
2. **Instale**: Execute o arquivo baixado e siga as instruções
3. **Configure**: Após instalar, abra o PowerShell e configure:
   ```bash
   git config --global user.name "Seu Nome"
   git config --global user.email "seu.email@exemplo.com"
   ```

### Alternativa: Git Bash
- Se preferir, você pode usar o Git Bash que vem com o Git
- Abra o Git Bash e use os mesmos comandos

## 🌐 Passo 2: Criar Repositório no GitHub

### Opção 1: Pelo Site do GitHub
1. **Acesse**: https://github.com
2. **Faça login** na sua conta
3. **Clique** no botão "+" no canto superior direito
4. **Selecione** "New repository"
5. **Configure**:
   - **Repository name**: `sistema-farmacia`
   - **Description**: `Sistema de controle de estoque para farmácia - Trabalho da 5ª fase`
   - **Public** (ou Private se preferir)
   - **NÃO** marque "Add a README file" (já temos um)
6. **Clique** em "Create repository"

### Opção 2: Pelo GitHub Desktop (mais fácil)
1. **Baixe**: https://desktop.github.com/
2. **Instale** e faça login
3. **Clique** em "File" > "New repository"
4. **Configure** o repositório
5. **Publique** no GitHub

## 📁 Passo 3: Subir o Código

### Após instalar o Git, execute estes comandos no PowerShell:

```bash
# 1. Inicializar o repositório Git local
git init

# 2. Adicionar todos os arquivos
git add .

# 3. Fazer o primeiro commit
git commit -m "Primeiro commit: Sistema de Farmácia - 5ª Fase CC"

# 4. Adicionar o repositório remoto (substitua SEU_USUARIO pelo seu username do GitHub)
git remote add origin https://github.com/SEU_USUARIO/sistema-farmacia.git

# 5. Enviar para o GitHub
git branch -M main
git push -u origin main
```

## 🔑 Passo 4: Configurar Autenticação

### Token de Acesso Pessoal (Recomendado)
1. **Acesse**: https://github.com/settings/tokens
2. **Clique** em "Generate new token"
3. **Selecione** os escopos: `repo`, `workflow`
4. **Copie** o token gerado
5. **Use** o token como senha quando solicitado

### Ou usar GitHub CLI
```bash
# Instalar GitHub CLI
winget install GitHub.cli

# Fazer login
gh auth login
```

## 📋 Comandos Úteis

### Verificar status
```bash
git status
```

### Ver histórico de commits
```bash
git log --oneline
```

### Fazer alterações e enviar
```bash
git add .
git commit -m "Descrição das alterações"
git push
```

### Baixar alterações do GitHub
```bash
git pull
```

## 🎯 Estrutura Final no GitHub

Seu repositório terá esta estrutura:
```
sistema-farmacia/
├── README.md              # Documentação principal
├── DOCUMENTACAO.md        # Documentação técnica
├── COMENTARIOS_CODIGO.md  # Explicação dos comentários
├── INSTRUCOES_GITHUB.md   # Este arquivo
├── composer.json          # Dependências PHP
├── config/                # Configurações
├── controllers/           # Controllers
├── models/                # Modelos
├── views/                 # Views
├── migrations/            # Migrações do banco
└── web/                   # Arquivos públicos
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

## 📞 Ajuda

Se tiver problemas:
- **Git**: https://git-scm.com/doc
- **GitHub**: https://docs.github.com
- **GitHub Desktop**: https://docs.github.com/en/desktop

---

**Criado para facilitar o upload do projeto para o GitHub** 