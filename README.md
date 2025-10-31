# Portal de Gerenciamento de Processos Jurídicos

Sistema completo de gerenciamento de casos jurídicos com 5 níveis de perfil.

## Perfis de Usuário
1. **Super Admin** - Acesso total ao sistema
2. **Admin** - Gerenciamento de casos e usuários
3. **Auxiliar Administrativo** - Suporte administrativo
4. **Advogados** - Gerenciamento de casos
5. **Cliente** - Visualização de casos próprios

## Stack Tecnológica
- **Backend:** PHP 8.x
- **Banco de Dados:** MySQL 8.x
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Autenticação:** JWT

## Estrutura do Projeto
```
advportal/
├── api/                    # Backend PHP
│   ├── config/            # Configurações
│   ├── controllers/       # Controladores
│   ├── models/            # Modelos
│   ├── middleware/        # Middleware de autenticação
│   └── routes/            # Rotas da API
├── database/              # Scripts SQL
├── public/                # Frontend
│   ├── css/              
│   ├── js/               
│   ├── assets/           
│   └── pages/            
└── .gitignore

```

## Instalação

### 1. Clonar o repositório
```bash
git clone git@github.com:duduclaza/advportal.git
cd advportal
```

### 2. Instalar dependências do Composer
```bash
composer install
```

### 3. Configurar variáveis de ambiente
```bash
# Copiar arquivo de exemplo
cp .env.example .env

# Editar .env com suas credenciais
# Ou no Windows PowerShell:
.\criar_env.ps1
```

### 4. Configurar banco de dados
```bash
mysql -u root -p < database/schema.sql
```

### 5. Iniciar servidor
```bash
php -S localhost:8000 -t .
```

## Funcionalidades

### Autenticação
- Login com email e senha
- Primeiro acesso com código de confirmação
- Recuperação de senha
- Sessões seguras com JWT

### Módulos
- **Cadastro de Usuários** (Super Admin)
- **Gerenciamento de Casos**
- **Dashboard personalizado por perfil**
- **Notificações**
- **Relatórios**

## Segurança
- Senhas criptografadas (bcrypt)
- Proteção contra SQL Injection
- Validação de dados
- Controle de acesso baseado em perfis (RBAC)

## Contribuição
Para contribuir, crie uma branch, faça suas alterações e envie um pull request.

## Licença
Proprietário
