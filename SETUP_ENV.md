# Configuração com Composer e .env

## 📋 O que foi implementado

✅ **Composer** instalado com `phpdotenv`
✅ **Bootstrap** para carregar .env automaticamente
✅ **database_env.php** - Configuração que lê do .env
✅ **Todos os controllers** atualizados para usar a nova configuração

## 🚀 Instalação Rápida

### 1. Instalar Dependências do Composer

```bash
cd C:\Users\Clayton\Desktop\acadv
composer install
```

Isso irá:
- Instalar `vlucas/phpdotenv`
- Criar a pasta `vendor/`
- Copiar automaticamente `.env.example` para `.env`

### 2. Configurar Credenciais do Banco

Abra o arquivo `.env` e atualize com as credenciais do seu servidor:

```env
# Configuração do Banco de Dados
DB_HOST=srv1890.hstgr.io
DB_NAME=u230868210_portaladvmarqu
DB_USER=u230868210_advportal
DB_PASS=Pandora@1989
DB_CHARSET=utf8mb4

# Configuração JWT
JWT_SECRET_KEY=mude_esta_chave_secreta_em_producao_2024_advportal
JWT_ALGORITHM=HS256
JWT_EXPIRATION=86400

# Configuração da Aplicação
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000
```

### 3. Criar o Banco de Dados no Servidor

Se o banco ainda não existe no servidor remoto:

```bash
# Conectar ao MySQL remoto
mysql -h srv1890.hstgr.io -u u230868210_advportal -p

# Executar (cole o conteúdo do database/schema.sql)
```

Ou se você tem acesso ao cPanel/phpMyAdmin:
1. Acesse o phpMyAdmin
2. Selecione o banco `u230868210_portaladvmarqu`
3. Importe o arquivo `database/schema.sql`

### 4. Testar Localmente

```bash
php -S localhost:8000 -t .
```

Acesse: http://localhost:8000

### 5. Fazer Login

- Email: `admin@advportal.com`
- Senha: `Admin@123`

## 📁 Arquivos Criados/Modificados

### Novos Arquivos:
- ✅ `composer.json` - Dependências do projeto
- ✅ `api/bootstrap.php` - Carrega .env e define constantes
- ✅ `api/config/database_env.php` - Conexão usando .env
- ✅ `.env.example` - Exemplo de configuração

### Arquivos Modificados:
- ✅ `api/index.php` - Agora carrega o bootstrap
- ✅ `api/controllers/AuthController.php` - Usa database_env.php
- ✅ `api/controllers/UsuarioController.php` - Usa database_env.php
- ✅ `api/controllers/CasoController.php` - Usa database_env.php

## 🔒 Segurança

O arquivo `.env` está no `.gitignore` e **NUNCA** será enviado ao Git.

As credenciais reais estão apenas no arquivo `.env` local (não versionado).

## 🌐 Deploy em Produção

### No servidor de hospedagem:

1. **Fazer upload dos arquivos** (via FTP/Git)
   ```bash
   git clone git@github.com:duduclaza/advportal.git
   cd advportal
   ```

2. **Instalar Composer no servidor** (se ainda não tiver)
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Criar o arquivo .env** no servidor
   ```bash
   cp .env.example .env
   nano .env  # ou vi .env
   ```

4. **Configurar .env com credenciais de produção**
   ```env
   DB_HOST=srv1890.hstgr.io
   DB_NAME=u230868210_portaladvmarqu
   DB_USER=u230868210_advportal
   DB_PASS=Pandora@1989
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://seudominio.com
   JWT_SECRET_KEY=gere_uma_chave_secreta_forte_aqui
   ```

5. **Configurar permissões**
   ```bash
   chmod 644 .env
   chmod -R 755 public
   chmod -R 775 uploads
   ```

6. **Importar banco de dados**
   ```bash
   mysql -h srv1890.hstgr.io -u u230868210_advportal -p u230868210_portaladvmarqu < database/schema.sql
   ```

## ⚠️ Importante

### Gerar uma nova chave JWT para produção:

```bash
# Gerar string aleatória de 64 caracteres
php -r "echo bin2hex(random_bytes(32));"
```

Cole o resultado no `.env`:
```env
JWT_SECRET_KEY=resultado_do_comando_acima
```

## 🧪 Testar a API

### Login via cURL:
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@advportal.com","senha":"Admin@123"}'
```

### Resposta esperada:
```json
{
  "status": 200,
  "message": "Login realizado com sucesso",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "usuario": {...}
  }
}
```

## 🐛 Solução de Problemas

### Erro: "Class 'Dotenv\Dotenv' not found"
```bash
composer install
```

### Erro: "Unable to read .env file"
```bash
# Certifique-se de que o .env existe
cp .env.example .env
```

### Erro de conexão com banco
- Verifique as credenciais no `.env`
- Teste a conexão manualmente:
```bash
mysql -h srv1890.hstgr.io -u u230868210_advportal -p
```

### Erro: "vendor/autoload.php not found"
```bash
composer install
```

## ✅ Checklist Final

- [ ] Composer instalado
- [ ] `composer install` executado
- [ ] Arquivo `.env` criado e configurado
- [ ] Credenciais do banco corretas no `.env`
- [ ] Banco de dados criado no servidor
- [ ] Schema importado (database/schema.sql)
- [ ] Servidor PHP rodando
- [ ] Login funciona
- [ ] API responde corretamente

## 📚 Estrutura Final

```
advportal/
├── .env                      ← Suas credenciais (NÃO versionado)
├── .env.example              ← Exemplo (versionado)
├── composer.json             ← Dependências
├── vendor/                   ← Gerado pelo composer (NÃO versionado)
├── api/
│   ├── bootstrap.php         ← Carrega .env
│   ├── config/
│   │   └── database_env.php  ← Usa .env
│   └── index.php             ← Carrega bootstrap
└── ...
```

## 🎉 Pronto!

Seu sistema está configurado para usar variáveis de ambiente com Composer e phpdotenv!

**Próximo passo:** Execute `composer install` e configure seu `.env`
