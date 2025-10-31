# 🚀 COMECE AQUI - Configuração Rápida

## ✅ O que foi feito

✔️ **Composer instalado** com `phpdotenv` para gerenciar variáveis de ambiente
✔️ **Sistema refatorado** para usar arquivo `.env`
✔️ **Credenciais do banco** configuradas para seu servidor remoto
✔️ **Bootstrap automático** que carrega as configurações

---

## 📋 Instalação em 3 Passos

### **Passo 1: Instalar Dependências**

Abra o PowerShell/Terminal na pasta do projeto e execute:

```bash
composer install
```

Isso irá baixar e instalar a biblioteca `phpdotenv`.

### **Passo 2: Criar o arquivo .env**

**Opção A - Automática (Windows):**
```powershell
.\criar_env.ps1
```

**Opção B - Manual:**
```bash
# Copiar o exemplo
cp .env.example .env

# Editar o arquivo .env com um editor de texto
```

O arquivo `.env` já está configurado com suas credenciais:
```env
DB_HOST=srv1890.hstgr.io
DB_NAME=u230868210_portaladvmarqu
DB_USER=u230868210_advportal
DB_PASS=Pandora@1989
```

### **Passo 3: Importar Banco de Dados**

Você precisa importar o schema no seu banco MySQL remoto.

**Opção A - Via linha de comando:**
```bash
mysql -h srv1890.hstgr.io -u u230868210_advportal -p u230868210_portaladvmarqu < database/schema.sql
```

**Opção B - Via phpMyAdmin:**
1. Acesse o phpMyAdmin do seu servidor
2. Selecione o banco `u230868210_portaladvmarqu`
3. Vá na aba "Importar"
4. Escolha o arquivo `database/schema.sql`
5. Clique em "Executar"

---

## 🎯 Testar Localmente

```bash
php -S localhost:8000 -t .
```

Acesse: **http://localhost:8000**

**Credenciais de teste:**
- Email: `admin@advportal.com`
- Senha: `Admin@123`

---

## 📁 Estrutura de Arquivos Importantes

```
advportal/
├── .env                          ← Suas credenciais (CRIADO AUTOMATICAMENTE)
├── .env.example                  ← Modelo de exemplo
├── composer.json                 ← Dependências do projeto
├── criar_env.ps1                 ← Script para criar .env (Windows)
├── SETUP_ENV.md                  ← Documentação completa
│
├── api/
│   ├── bootstrap.php             ← Carrega .env e configura constantes
│   ├── index.php                 ← Router principal (atualizado)
│   ├── config/
│   │   └── database_env.php      ← Conexão usando .env
│   └── controllers/              ← Todos atualizados para usar .env
│
└── database/
    └── schema.sql                ← Schema do banco
```

---

## ⚠️ IMPORTANTE

### O arquivo `.env` NÃO será enviado ao Git!

Ele está no `.gitignore` para proteger suas credenciais.

Quando fizer deploy no servidor, você precisará criar o `.env` lá também.

---

## 🌐 Deploy em Servidor de Produção

### 1. Fazer upload dos arquivos

```bash
# Via Git
git clone git@github.com:duduclaza/advportal.git
cd advportal
```

### 2. Instalar Composer no servidor

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Criar .env no servidor

```bash
cp .env.example .env
nano .env  # ou vi .env
```

Configurar com as mesmas credenciais:
```env
DB_HOST=srv1890.hstgr.io
DB_NAME=u230868210_portaladvmarqu
DB_USER=u230868210_advportal
DB_PASS=Pandora@1989
APP_ENV=production
APP_DEBUG=false
```

### 4. Configurar permissões

```bash
chmod 644 .env
chmod -R 755 public
chmod -R 775 uploads
```

### 5. Importar banco (se ainda não foi feito)

```bash
mysql -h srv1890.hstgr.io -u u230868210_advportal -p u230868210_portaladvmarqu < database/schema.sql
```

---

## 🐛 Solução de Problemas

### ❌ Erro: "Class 'Dotenv\Dotenv' not found"

**Solução:**
```bash
composer install
```

### ❌ Erro: "Unable to read .env file"

**Solução:**
```bash
# Verifique se o .env existe
ls -la .env

# Se não existir, crie:
cp .env.example .env
```

### ❌ Erro de conexão com banco de dados

**Solução:**
1. Verifique as credenciais no `.env`
2. Teste a conexão manualmente:
```bash
mysql -h srv1890.hstgr.io -u u230868210_advportal -p
```
3. Certifique-se de que o banco existe
4. Verifique se o firewall permite conexão remota

### ❌ Erro: "vendor/autoload.php not found"

**Solução:**
```bash
composer install
```

---

## ✅ Checklist de Verificação

- [ ] Composer instalado globalmente
- [ ] `composer install` executado com sucesso
- [ ] Arquivo `.env` criado e configurado
- [ ] Credenciais do banco corretas no `.env`
- [ ] Banco de dados existe no servidor MySQL
- [ ] Schema importado (tabelas criadas)
- [ ] Servidor PHP iniciado
- [ ] Página de login carrega
- [ ] Login funciona com admin@advportal.com

---

## 📚 Documentação Completa

Para mais detalhes, consulte:

- **SETUP_ENV.md** - Guia completo de configuração
- **README.md** - Visão geral do projeto
- **INSTALL.md** - Instalação detalhada
- **QUICK_START.md** - Início rápido

---

## 🎉 Está funcionando?

Se o login funcionar, parabéns! Seu portal está configurado corretamente.

**Próximos passos:**
1. Criar novos usuários
2. Cadastrar casos/processos
3. Testar diferentes perfis de acesso
4. Fazer deploy em produção

---

## 📞 Precisando de Ajuda?

1. Leia o **SETUP_ENV.md** para troubleshooting detalhado
2. Verifique se todas as dependências foram instaladas
3. Confirme que o banco de dados está acessível

---

**Desenvolvido com ❤️ para gerenciamento de processos jurídicos**
