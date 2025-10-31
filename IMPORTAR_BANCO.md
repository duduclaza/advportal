# 📊 Como Importar o Banco de Dados

## ⚠️ IMPORTANTE

O banco de dados **já existe** no servidor Hostinger:
- **Nome:** `u230868210_portaladvmarqu`
- **Host:** `srv1890.hstgr.io`
- **Usuário:** `u230868210_advportal`

**NÃO** tente criar um novo banco. Apenas importe as tabelas.

---

## 🎯 Opção 1: Via phpMyAdmin (RECOMENDADO)

### Passo a Passo:

1. **Acesse o phpMyAdmin do Hostinger**
   - Entre no painel do Hostinger
   - Vá em "Banco de Dados" → "phpMyAdmin"

2. **Selecione o banco**
   - Clique em `u230868210_portaladvmarqu` no menu lateral

3. **Importe o schema**
   - Clique na aba "Importar"
   - Clique em "Escolher arquivo"
   - Selecione o arquivo: `database/schema.sql`
   - Role até o final e clique em "Executar"

4. **Aguarde a importação**
   - As tabelas serão criadas
   - O Super Admin será inserido automaticamente

5. **Verifique as tabelas**
   - Clique em "Estrutura"
   - Você deve ver 7 tabelas criadas

---

## 🎯 Opção 2: Via Linha de Comando

### Se você tem acesso SSH ao servidor:

```bash
# Conectar ao servidor remoto
mysql -h srv1890.hstgr.io -u u230868210_advportal -p u230868210_portaladvmarqu < database/schema.sql
```

**Senha:** `Pandora@1989`

---

## 🎯 Opção 3: Via Cliente MySQL (Workbench, HeidiSQL, etc.)

1. **Configurar conexão:**
   - Host: `srv1890.hstgr.io`
   - Porta: `3306`
   - Usuário: `u230868210_advportal`
   - Senha: `Pandora@1989`
   - Banco: `u230868210_portaladvmarqu`

2. **Conectar**

3. **Executar script:**
   - Abrir arquivo `database/schema.sql`
   - Executar todo o script

---

## ✅ Tabelas que serão criadas:

1. **perfis** - 5 perfis de acesso
2. **usuarios** - Cadastro de usuários
3. **casos** - Processos jurídicos
4. **movimentacoes** - Histórico dos casos
5. **documentos** - Arquivos anexados
6. **logs_acesso** - Auditoria
7. **notificacoes** - Alertas

---

## 🔐 Credencial Padrão Criada:

Após importar, você terá um Super Admin:

```
Email: admin@advportal.com
Senha: Admin@123
```

---

## ⚠️ ERROS COMUNS

### Erro: "Banco de dados 'advportal_db' não existe"

**Causa:** Você está tentando usar o nome errado do banco

**Solução:** O arquivo `schema.sql` já foi corrigido para usar `u230868210_portaladvmarqu`

---

### Erro: "Acesso negado para 'u230868210_advportal'@'127.0.0.1'"

**Causa:** Você está tentando conectar localmente ao invés do servidor remoto

**Solução:** Use o host correto: `srv1890.hstgr.io`

---

### Erro: "Tabela já existe"

**Causa:** As tabelas já foram criadas anteriormente

**Solução:** 
1. Você pode pular este erro
2. Ou deletar as tabelas existentes antes de importar
3. Ou usar `DROP TABLE IF EXISTS` antes de cada `CREATE TABLE`

---

## 🧪 Testar Conexão

### Via PHP (criar arquivo test_connection.php):

```php
<?php
$host = 'srv1890.hstgr.io';
$dbname = 'u230868210_portaladvmarqu';
$user = 'u230868210_advportal';
$pass = 'Pandora@1989';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    echo "✅ Conexão bem-sucedida!<br>";
    
    // Listar tabelas
    $stmt = $pdo->query("SHOW TABLES");
    echo "<br>Tabelas encontradas:<br>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "<br>";
    }
    
} catch(PDOException $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>
```

### Via MySQL Client:

```bash
mysql -h srv1890.hstgr.io -u u230868210_advportal -p
# Digite a senha: Pandora@1989

# Depois de conectar:
USE u230868210_portaladvmarqu;
SHOW TABLES;
```

---

## 📋 Checklist Pós-Importação

- [ ] 7 tabelas criadas
- [ ] Verificar se perfis foram inseridos (5 linhas)
- [ ] Verificar se Super Admin foi criado
- [ ] Testar login no sistema
- [ ] Verificar se .env está configurado corretamente

---

## 🎯 Após Importar

1. **Configure o .env no servidor:**
```bash
cp .env.example .env
nano .env
```

2. **Instale dependências:**
```bash
composer install --no-dev
```

3. **Teste o sistema:**
- Acesse https://adv.sgqoti.com.br
- Faça login com: admin@advportal.com / Admin@123

---

## 📞 Problemas?

Se após seguir todos os passos ainda houver erros:

1. Verifique se o banco realmente existe no painel Hostinger
2. Confirme as credenciais de acesso
3. Verifique se o IP está liberado para acesso remoto
4. Contate o suporte do Hostinger se necessário

---

**Boa sorte! 🚀**
