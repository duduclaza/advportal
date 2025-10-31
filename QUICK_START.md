# Guia Rápido - AdvPortal

## Início Rápido (5 minutos)

### 1. Configurar Banco de Dados
```bash
# Criar banco
mysql -u root -p -e "CREATE DATABASE advportal_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importar schema
mysql -u root -p advportal_db < database/schema.sql

# (Opcional) Dados de exemplo
mysql -u root -p advportal_db < database/sample_data.sql
```

### 2. Configurar Credenciais
```bash
# Copiar arquivo de exemplo
cp api/config/database.example.php api/config/database.php

# Editar com suas credenciais
# Alterar: DB_USER e DB_PASS
```

### 3. Iniciar Servidor
```bash
php -S localhost:8000 -t .
```

### 4. Acessar Sistema
```
URL: http://localhost:8000
Email: admin@advportal.com
Senha: Admin@123
```

## Testando o Sistema

### Criar um Novo Usuário (Cliente)
1. Login como admin
2. Ir em "Usuários" → "Novo Usuário"
3. Preencher dados:
   - Nome: João Silva
   - Email: joao@cliente.com
   - Perfil: Cliente
   - Telefone: (11) 98765-4321
   - CPF: 123.456.789-00
4. Clicar em "Criar Usuário"
5. Anotar o código de confirmação mostrado

### Primeiro Acesso do Novo Usuário
1. Fazer logout
2. Clicar em "Primeiro Acesso"
3. Inserir email: joao@cliente.com
4. Clicar em "Solicitar Código"
5. Inserir o código mostrado
6. Cadastrar nova senha (mínimo 6 caracteres)
7. Fazer login com as novas credenciais

### Criar um Novo Caso
1. Login como admin ou advogado
2. Ir em "Novo Caso"
3. Preencher dados mínimos:
   - Título: Ação de Cobrança - Empresa ABC
   - Data de Abertura: (hoje)
   - Cliente: João Silva
4. (Opcional) Preencher outros campos
5. Clicar em "Criar Caso"

### Visualizar como Cliente
1. Fazer logout
2. Login com o cliente criado (joao@cliente.com)
3. Ver dashboard com estatísticas do cliente
4. Ir em "Casos" - verá apenas seus casos
5. Notar que não há opção "Novo Caso"

## Estrutura de Testes

### Usuários de Exemplo (após importar sample_data.sql)
```
Admin:
- Email: admin@example.com
- Senha: Senha@123
- Perfil: Admin

Auxiliar:
- Email: auxiliar@example.com  
- Senha: Senha@123
- Perfil: Auxiliar Administrativo

Advogado:
- Email: advogado@example.com
- Senha: Senha@123
- Perfil: Advogado

Cliente:
- Email: cliente@example.com
- Senha: Senha@123
- Perfil: Cliente
```

## Testando a API com cURL

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@advportal.com","senha":"Admin@123"}'
```

### Listar Casos (substitua {TOKEN} pelo token recebido)
```bash
curl http://localhost:8000/api/casos \
  -H "Authorization: Bearer {TOKEN}"
```

### Criar Usuário
```bash
curl -X POST http://localhost:8000/api/usuarios \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {TOKEN}" \
  -d '{
    "nome": "Maria Santos",
    "email": "maria@example.com",
    "perfil_id": 5,
    "telefone": "(11) 98888-8888"
  }'
```

### Criar Caso
```bash
curl -X POST http://localhost:8000/api/casos \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {TOKEN}" \
  -d '{
    "titulo": "Ação Civil - Teste",
    "cliente_id": 5,
    "data_abertura": "2024-01-15",
    "status": "aberto",
    "prioridade": "media"
  }'
```

## Resolução de Problemas Comuns

### Erro: "Erro de conexão"
```bash
# Verificar se MySQL está rodando
mysql -u root -p -e "SELECT 1"

# Verificar credenciais em api/config/database.php
```

### Erro: "Token inválido"
```javascript
// Limpar localStorage do navegador
localStorage.clear()
// Fazer login novamente
```

### Erro: "Permissão negada"
```bash
# Windows: Dar permissão na pasta uploads
icacls uploads /grant Users:F /T

# Linux/Mac:
chmod -R 775 uploads
```

### Página em branco
```bash
# Verificar logs de erro do PHP
tail -f /path/to/php-error.log

# Ou iniciar servidor com exibição de erros
php -S localhost:8000 -t . -d display_errors=1
```

## Próximos Passos

1. **Personalizar:**
   - Alterar logo e cores em `public/css/style.css`
   - Modificar nome do sistema em todos os HTMLs

2. **Configurar Email:**
   - Implementar envio real de emails em `AuthController.php`
   - Configurar SMTP

3. **Deploy:**
   - Configurar servidor web (Apache/Nginx)
   - Configurar SSL/HTTPS
   - Alterar JWT_SECRET_KEY em `api/config/jwt.php`
   - Configurar CORS adequadamente

4. **Backup:**
   - Configurar backup automático do banco
   - Versionar uploads

5. **Monitoramento:**
   - Configurar logs
   - Monitorar uso da API
   - Alertas de erro

## Comandos Úteis

```bash
# Ver estrutura do banco
mysql -u root -p advportal_db -e "SHOW TABLES"

# Backup do banco
mysqldump -u root -p advportal_db > backup.sql

# Restaurar backup
mysql -u root -p advportal_db < backup.sql

# Ver logs do servidor PHP
# (nos terminais onde o servidor está rodando)

# Verificar sintaxe de arquivo PHP
php -l api/index.php

# Limpar cache (se necessário)
rm -rf cache/*
```

## Suporte

- Documentação completa: `README.md`
- Funcionalidades: `FEATURES.md`
- Instalação detalhada: `INSTALL.md`
- Issues: https://github.com/duduclaza/advportal/issues
