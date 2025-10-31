# Como fazer Push para o GitHub

## Status Atual
✅ Repositório Git inicializado
✅ Remote configurado: git@github.com:duduclaza/advportal.git
✅ 3 commits prontos
✅ Branch: main

## Pré-requisitos

### 1. Verificar se você tem acesso SSH ao GitHub
```bash
ssh -T git@github.com
```

**Resposta esperada:**
```
Hi duduclaza! You've successfully authenticated, but GitHub does not provide shell access.
```

### 2. Se não tiver acesso SSH configurado:

#### Opção A: Usar HTTPS
```bash
# Remover remote SSH
git remote remove origin

# Adicionar remote HTTPS
git remote add origin https://github.com/duduclaza/advportal.git

# Fazer push (pedirá usuário e senha/token)
git push -u origin main
```

#### Opção B: Configurar chave SSH
1. Gerar chave SSH:
```bash
ssh-keygen -t ed25519 -C "seu_email@example.com"
```

2. Copiar a chave pública:
```bash
# Windows
type %USERPROFILE%\.ssh\id_ed25519.pub

# Linux/Mac
cat ~/.ssh/id_ed25519.pub
```

3. Adicionar no GitHub:
   - Ir em: https://github.com/settings/keys
   - Clicar em "New SSH key"
   - Colar a chave pública
   - Salvar

4. Testar:
```bash
ssh -T git@github.com
```

## Fazer o Push

### Se o repositório no GitHub já existe:
```bash
git push -u origin main
```

### Se o repositório no GitHub NÃO existe ainda:
1. Criar repositório no GitHub:
   - Ir em: https://github.com/new
   - Nome: advportal
   - Descrição: Portal de Gerenciamento de Processos Jurídicos
   - Público ou Privado (sua escolha)
   - **NÃO** marcar "Initialize with README"
   - Criar

2. Fazer push:
```bash
git push -u origin main
```

### Se houver conflitos (repositório já tem conteúdo):
```bash
# Opção 1: Forçar push (CUIDADO: apaga conteúdo remoto)
git push -u origin main --force

# Opção 2: Fazer pull primeiro e resolver conflitos
git pull origin main --allow-unrelated-histories
# Resolver conflitos se houver
git push -u origin main
```

## Verificar Push

Após fazer push, verificar no GitHub:
```
https://github.com/duduclaza/advportal
```

Você deve ver:
- ✅ Todos os arquivos
- ✅ 3 commits
- ✅ README.md renderizado
- ✅ Estrutura de pastas completa

## Comandos Úteis

### Ver status do repositório
```bash
git status
```

### Ver histórico de commits
```bash
git log --oneline --graph
```

### Ver remote configurado
```bash
git remote -v
```

### Ver branch atual
```bash
git branch
```

## Próximos Passos Após o Push

1. **Configurar GitHub Pages** (se quiser hospedar documentação)
2. **Adicionar badges** ao README
3. **Configurar GitHub Actions** (CI/CD)
4. **Adicionar Issues templates**
5. **Criar Pull Request template**
6. **Adicionar CONTRIBUTING.md**
7. **Adicionar LICENSE**

## Trabalhando no Projeto Após Push

### Fazer alterações
```bash
# Fazer mudanças nos arquivos

# Adicionar ao stage
git add .

# Commit
git commit -m "Descrição das mudanças"

# Push
git push origin main
```

### Sincronizar com remoto
```bash
# Buscar atualizações
git fetch origin

# Atualizar branch local
git pull origin main
```

### Criar nova branch (recomendado para features)
```bash
# Criar e mudar para nova branch
git checkout -b feature/nova-funcionalidade

# Fazer alterações e commit
git add .
git commit -m "Adiciona nova funcionalidade"

# Push da branch
git push -u origin feature/nova-funcionalidade

# No GitHub, criar Pull Request
```

## Solução de Problemas

### Erro: "Permission denied (publickey)"
- Configure sua chave SSH corretamente
- Ou use HTTPS ao invés de SSH

### Erro: "Repository not found"
- Verifique se o repositório existe
- Verifique o nome do repositório
- Verifique suas permissões

### Erro: "Updates were rejected"
- Faça pull primeiro: `git pull origin main`
- Ou force push (cuidado): `git push --force`

### Erro: "fatal: refusing to merge unrelated histories"
```bash
git pull origin main --allow-unrelated-histories
```

## Comando Final

```bash
# Se tudo estiver configurado corretamente:
git push -u origin main
```

✅ **Após executar este comando, seu projeto estará no GitHub!**

---

## 🎉 Parabéns!

Seu portal de gerenciamento de processos jurídicos está completo e versionado!

**Próximo passo:** Acessar https://github.com/duduclaza/advportal e ver seu projeto online!
