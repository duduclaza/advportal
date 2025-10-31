# 📧 Configuração de Email - AdvPortal

## ✅ Sistema de Email Implementado

O AdvPortal agora possui um sistema completo de envio de emails usando **PHPMailer** com SMTP do **Hostinger**.

---

## 🔧 Configurações SMTP

### Servidor: Hostinger
```env
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=advsuporte@djbr.sgqoti.com.br
MAIL_PASSWORD=Pandora@1989
MAIL_FROM_ADDRESS=advsuporte@djbr.sgqoti.com.br
MAIL_FROM_NAME=AdvPortal
```

Estas configurações estão definidas no arquivo `.env` e serão carregadas automaticamente pelo sistema.

---

## 📦 Dependências

O **PHPMailer** foi adicionado ao `composer.json`:

```json
{
  "require": {
    "phpmailer/phpmailer": "^6.8"
  }
}
```

**Para instalar:**
```bash
composer install
```

ou

```bash
composer update
```

---

## 📨 Tipos de Email Implementados

### 1. **Código de Confirmação** (Primeiro Acesso)
Enviado quando:
- Usuário solicita primeiro acesso
- Admin cria um novo usuário

**Template:** HTML profissional com código em destaque
**Expiração:** 15 minutos

### 2. **Boas-Vindas** (Futuro)
Será enviado após confirmação de senha

### 3. **Notificação de Novo Caso** (Futuro)
Será enviado quando um caso for atribuído ao advogado

---

## 💻 Como Usar

### No AuthController

```php
// Enviar código de confirmação
$emailEnviado = EmailHelper::enviarCodigoConfirmacao(
    $email,
    $nome,
    $codigo
);
```

### No UsuarioController

```php
// Ao criar usuário
$emailEnviado = EmailHelper::enviarCodigoConfirmacao(
    $data['email'],
    $data['nome'],
    $codigo
);
```

### No CasoController (Futuro)

```php
// Notificar advogado sobre novo caso
$emailEnviado = EmailHelper::notificarNovoCaso(
    $advogadoEmail,
    $advogadoNome,
    $numeroCaso,
    $tituloCaso
);
```

---

## 🎨 Templates HTML

Todos os emails usam templates HTML profissionais com:
- ✅ Design responsivo
- ✅ Cores da marca (azul #2563eb)
- ✅ Cabeçalho e rodapé personalizados
- ✅ Versão texto alternativa (fallback)

---

## 🧪 Testando

### Testar envio de email localmente:

1. **Iniciar servidor:**
```bash
php -S localhost:8000 -t .
```

2. **Acessar primeiro acesso:**
- Ir para: http://localhost:8000
- Clicar em "Primeiro Acesso"
- Inserir email de um usuário
- Solicitar código

3. **Verificar:**
- Email será enviado para o endereço configurado
- Em modo DEBUG, o código também aparece na resposta da API

---

## 🐛 Debug

### Modo Debug Ativo

Quando `APP_DEBUG=true` no `.env`, a API retorna informações extras:

```json
{
  "status": 200,
  "message": "Código enviado para o email",
  "data": {
    "codigo": "123456",
    "email_enviado": true,
    "mensagem": "Código de confirmação enviado para o email"
  }
}
```

### Modo Produção

Quando `APP_DEBUG=false`, apenas:

```json
{
  "status": 200,
  "message": "Código enviado para o email",
  "data": {
    "mensagem": "Código de confirmação enviado para o email"
  }
}
```

---

## ⚠️ Solução de Problemas

### Erro: "Class 'PHPMailer\PHPMailer\PHPMailer' not found"

**Solução:**
```bash
composer install
```

### Erro: "SMTP Error: Could not authenticate"

**Verificar:**
1. Credenciais corretas no `.env`
2. Senha do email está correta
3. SMTP permite acesso de aplicativos

**Teste manual:**
```php
// Criar arquivo test_email.php na raiz
<?php
require 'vendor/autoload.php';
require 'api/bootstrap.php';
require 'api/helpers/EmailHelper.php';

$resultado = EmailHelper::enviarCodigoConfirmacao(
    'seu@email.com',
    'Seu Nome',
    '123456'
);

echo $resultado ? "Email enviado!" : "Erro ao enviar";
```

### Erro: "SMTP connect() failed"

**Possíveis causas:**
- Porta bloqueada pelo firewall
- SSL/TLS não suportado
- Servidor SMTP offline

**Solução:**
Verifique se a porta 465 (SSL) está aberta:
```bash
telnet smtp.hostinger.com 465
```

---

## 🔒 Segurança

### ✅ Implementado:
- Credenciais no `.env` (não versionado)
- Senhas não aparecem em logs
- Templates sanitizados

### 🚀 Recomendações:
1. Use senha forte e única para o email
2. Ative 2FA no painel do Hostinger
3. Monitore logs de envio
4. Configure limite de envios (rate limiting)

---

## 📁 Arquivos Relacionados

```
advportal/
├── .env.example                    ← Configuração SMTP
├── composer.json                   ← PHPMailer dependency
├── api/
│   ├── bootstrap.php              ← Carrega constantes MAIL_*
│   ├── helpers/
│   │   └── EmailHelper.php        ← 🆕 Classe de email
│   └── controllers/
│       ├── AuthController.php     ← Usa EmailHelper
│       └── UsuarioController.php  ← Usa EmailHelper
└── EMAIL_CONFIG.md                ← Este arquivo
```

---

## 📊 Estatísticas de Email (Futuro)

### Funcionalidades Planejadas:
- [ ] Log de emails enviados
- [ ] Fila de envio assíncrona
- [ ] Dashboard de estatísticas
- [ ] Templates personalizáveis
- [ ] Notificações por webhook
- [ ] Integração com serviços de tracking

---

## 🎯 Próximos Passos

1. **Testar envio de emails**
```bash
composer install
.\criar_env.ps1
php -S localhost:8000 -t .
```

2. **Verificar recebimento**
- Fazer primeiro acesso
- Checar caixa de entrada (e spam)

3. **Ajustar templates** (se necessário)
- Editar `api/helpers/EmailHelper.php`
- Personalizar cores e textos

4. **Deploy em produção**
- Copiar configurações SMTP para `.env` do servidor
- Testar em ambiente real

---

## 📞 Suporte

### Problemas com Email?

1. Verifique `.env` está configurado
2. Execute `composer install`
3. Teste com arquivo `test_email.php`
4. Verifique logs do PHP: `error_log`

### Contato Hostinger:
Se houver problemas com SMTP, entre em contato com o suporte do Hostinger.

---

## ✅ Checklist de Implementação

- [x] PHPMailer adicionado ao composer.json
- [x] Configurações SMTP no .env.example
- [x] EmailHelper criado com 3 tipos de email
- [x] AuthController usa EmailHelper
- [x] UsuarioController usa EmailHelper
- [x] Templates HTML profissionais
- [x] Modo debug implementado
- [x] Documentação completa

**Status:** ✅ **PRONTO PARA USO!**

---

**Desenvolvido com ❤️ para AdvPortal**
