# Script PowerShell para criar o arquivo .env automaticamente

Write-Host "==================================" -ForegroundColor Cyan
Write-Host "  Configuração do AdvPortal" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se o .env já existe
if (Test-Path ".env") {
    Write-Host "Arquivo .env já existe!" -ForegroundColor Yellow
    $resposta = Read-Host "Deseja sobrescrever? (s/n)"
    if ($resposta -ne "s") {
        Write-Host "Operação cancelada." -ForegroundColor Red
        exit
    }
}

# Criar conteúdo do .env com as credenciais reais
$envContent = @"
# Configuração do Banco de Dados
DB_HOST=srv1890.hstgr.io
DB_NAME=u230868210_portaladvmarqu
DB_USER=u230868210_advportal
DB_PASS=Pandora@1989
DB_CHARSET=utf8mb4

# Configuração JWT
JWT_SECRET_KEY=mude_esta_chave_secreta_em_producao_2024_advportal_$(Get-Random -Maximum 999999)
JWT_ALGORITHM=HS256
JWT_EXPIRATION=86400

# Configuração da Aplicação
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

# Configuração de Email (configure quando implementar)
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@advportal.com
MAIL_FROM_NAME=AdvPortal
"@

# Criar o arquivo .env
Set-Content -Path ".env" -Value $envContent

Write-Host ""
Write-Host "✓ Arquivo .env criado com sucesso!" -ForegroundColor Green
Write-Host ""
Write-Host "Credenciais configuradas:" -ForegroundColor Cyan
Write-Host "  Host: srv1890.hstgr.io" -ForegroundColor White
Write-Host "  Banco: u230868210_portaladvmarqu" -ForegroundColor White
Write-Host "  Usuário: u230868210_advportal" -ForegroundColor White
Write-Host ""
Write-Host "Próximos passos:" -ForegroundColor Yellow
Write-Host "  1. Execute: composer install" -ForegroundColor White
Write-Host "  2. Importe o schema no banco de dados" -ForegroundColor White
Write-Host "  3. Inicie o servidor: php -S localhost:8000 -t ." -ForegroundColor White
Write-Host ""
