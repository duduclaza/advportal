# Guia de Instalação - AdvPortal

## Pré-requisitos

- PHP 8.0 ou superior
- MySQL 8.0 ou superior
- Servidor web (Apache ou Nginx) ou PHP built-in server
- Git

## Passo a Passo

### 1. Clonar o Repositório

```bash
git clone git@github.com:duduclaza/advportal.git
cd advportal
```

### 2. Configurar o Banco de Dados

#### Criar o banco de dados
```bash
mysql -u root -p
```

```sql
CREATE DATABASE advportal_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

#### Importar o schema
```bash
mysql -u root -p advportal_db < database/schema.sql
```

#### (Opcional) Importar dados de exemplo
```bash
mysql -u root -p advportal_db < database/sample_data.sql
```

### 3. Configurar Credenciais do Banco

Copie o arquivo de exemplo e configure suas credenciais:

```bash
cp api/config/database.example.php api/config/database.php
```

Edite `api/config/database.php` com suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'advportal_db');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

### 4. Configurar Permissões

```bash
# Linux/Mac
chmod -R 755 public
chmod -R 775 uploads

# Windows (PowerShell como administrador)
icacls uploads /grant Users:F /T
```

### 5. Iniciar o Servidor

#### Opção 1: PHP Built-in Server (Desenvolvimento)
```bash
php -S localhost:8000 -t .
```

#### Opção 2: Apache
Configure o DocumentRoot para a pasta `public/`

#### Opção 3: Nginx
Configure o root para a pasta `public/`

### 6. Acessar o Sistema

Abra o navegador e acesse:
```
http://localhost:8000
```

### 7. Login Padrão

**Super Admin:**
- Email: `admin@advportal.com`
- Senha: `Admin@123`

## Estrutura de URLs

- Login: `http://localhost:8000`
- Dashboard: `http://localhost:8000/pages/dashboard.html`
- API: `http://localhost:8000/api/*`

## API Endpoints

### Autenticação
- `POST /api/auth/login` - Login
- `POST /api/auth/solicitar-codigo` - Primeiro acesso
- `POST /api/auth/confirmar-codigo` - Confirmar código

### Usuários (Requer autenticação)
- `GET /api/usuarios` - Listar usuários
- `POST /api/usuarios` - Criar usuário
- `GET /api/usuarios/{id}` - Buscar usuário
- `PUT /api/usuarios/{id}` - Atualizar usuário
- `DELETE /api/usuarios/{id}` - Deletar usuário

### Casos (Requer autenticação)
- `GET /api/casos` - Listar casos
- `POST /api/casos` - Criar caso
- `GET /api/casos/{id}` - Buscar caso
- `PUT /api/casos/{id}` - Atualizar caso
- `DELETE /api/casos/{id}` - Deletar caso
- `GET /api/casos/estatisticas` - Estatísticas

### Perfis (Requer autenticação)
- `GET /api/perfis` - Listar perfis

## Solução de Problemas

### Erro de conexão com banco de dados
- Verifique se o MySQL está rodando
- Confirme as credenciais em `api/config/database.php`
- Verifique se o banco foi criado corretamente

### CORS Error
- Certifique-se de que o arquivo `api/config/cors.php` está sendo carregado
- Em produção, configure os domínios permitidos

### Token inválido
- Verifique se a chave JWT em `api/config/jwt.php` está configurada
- Limpe o localStorage do navegador

## Configurações de Produção

### Segurança
1. Altere a chave JWT em `api/config/jwt.php`
2. Use HTTPS
3. Configure CORS adequadamente
4. Remova dados de exemplo
5. Configure logs de erro

### Performance
1. Ative OPcache do PHP
2. Configure cache do navegador
3. Use CDN para assets estáticos
4. Otimize queries do banco

## Suporte

Para problemas ou dúvidas, abra uma issue no repositório do GitHub.
