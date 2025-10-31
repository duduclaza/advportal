# Funcionalidades do AdvPortal

## Sistema de Autenticação

### Login
- Login com email e senha
- Validação de credenciais
- Geração de token JWT
- Sessão persistente
- Verificação de status do usuário

### Primeiro Acesso
- Solicitação de código de confirmação via email
- Código de 6 dígitos com expiração de 15 minutos
- Cadastro de senha pelo próprio usuário
- Validação de força da senha

### Segurança
- Senhas criptografadas com bcrypt
- Tokens JWT com expiração de 24 horas
- Proteção contra SQL Injection (PDO prepared statements)
- Middleware de autenticação em todas as rotas protegidas
- Log de acessos com IP e User Agent

## Perfis de Usuário

### 1. Super Admin (Nível 1)
**Permissões:**
- Acesso total ao sistema
- Gerenciar todos os usuários
- Criar, editar e excluir usuários
- Visualizar e gerenciar todos os casos
- Acessar relatórios e estatísticas
- Deletar casos e usuários

**Menu:**
- Dashboard
- Casos
- Novo Caso
- Usuários

### 2. Admin (Nível 2)
**Permissões:**
- Gerenciar usuários (exceto deletar)
- Criar e editar usuários
- Visualizar e gerenciar todos os casos
- Criar e editar casos
- Acessar relatórios e estatísticas

**Menu:**
- Dashboard
- Casos
- Novo Caso
- Usuários

### 3. Auxiliar Administrativo (Nível 3)
**Permissões:**
- Visualizar lista de usuários
- Visualizar todos os casos
- Criar casos
- Editar casos
- Acessar estatísticas

**Menu:**
- Dashboard
- Casos
- Novo Caso

### 4. Advogado (Nível 4)
**Permissões:**
- Visualizar apenas casos onde é responsável
- Criar casos
- Editar casos onde é responsável
- Acessar estatísticas dos seus casos

**Menu:**
- Dashboard
- Casos
- Novo Caso

### 5. Cliente (Nível 5)
**Permissões:**
- Visualizar apenas seus próprios casos
- Acessar estatísticas dos seus casos
- Visualizar movimentações dos seus processos

**Menu:**
- Dashboard
- Casos

## Módulo de Casos

### Criar Novo Caso
**Informações Básicas:**
- Número do Processo (opcional)
- Título do Caso (obrigatório)
- Descrição
- Data de Abertura (obrigatório)
- Cliente (obrigatório)
- Advogado Responsável (opcional)

**Detalhes do Processo:**
- Tipo de Ação (Cobrança, Trabalhista, Civil, etc.)
- Valor da Causa
- Status (Aberto, Em Andamento, Suspenso, Encerrado, Arquivado)
- Prioridade (Baixa, Média, Alta, Urgente)
- Tribunal
- Comarca
- Vara
- Observações

### Listar Casos
**Funcionalidades:**
- Visualização em tabela
- Filtros por Status e Prioridade
- Busca por título, número do processo ou cliente
- Badges coloridos para status e prioridade
- Click na linha para visualizar detalhes
- Ordenação por data de criação (mais recentes primeiro)

**Controle de Acesso:**
- Super Admin/Admin: Visualizam todos os casos
- Auxiliar: Visualiza todos os casos
- Advogado: Visualiza apenas casos onde é responsável
- Cliente: Visualiza apenas seus próprios casos

### Editar Caso
- Atualização de todas as informações
- Controle de permissão por perfil
- Histórico de alterações (em desenvolvimento)

### Excluir Caso
- Apenas Super Admin e Admin
- Confirmação antes de excluir
- Exclusão em cascata (documentos e movimentações)

## Módulo de Usuários

### Criar Usuário (Super Admin / Admin)
**Campos:**
- Nome Completo
- Email (único)
- Perfil
- Telefone
- CPF (único)

**Processo:**
1. Admin cria usuário com dados básicos
2. Sistema gera código de confirmação
3. Código enviado ao email do novo usuário
4. Usuário acessa "Primeiro Acesso"
5. Insere código e cadastra senha
6. Status muda de "Pendente" para "Ativo"

### Listar Usuários
- Filtro por perfil
- Busca por nome ou email
- Badges de status (Ativo, Inativo, Pendente)
- Ações de Editar e Excluir

### Editar Usuário
- Super Admin/Admin pode editar qualquer usuário
- Usuário pode editar apenas seus próprios dados
- Não pode alterar o próprio perfil
- Campos editáveis: Nome, Telefone, Status (admin)

### Excluir Usuário
- Apenas Super Admin
- Não pode excluir a si mesmo
- Confirmação antes de excluir

## Dashboard

### Estatísticas em Cards
- Total de Casos
- Casos Abertos
- Casos Em Andamento
- Casos Urgentes

**Personalizado por Perfil:**
- Admin: Estatísticas de todos os casos
- Advogado: Estatísticas dos seus casos
- Cliente: Estatísticas dos seus casos

### Casos Recentes
- Tabela com os 5 casos mais recentes
- Informações: Nº Processo, Título, Cliente, Status, Prioridade, Data
- Click para visualizar detalhes
- Link para ver todos os casos

### Informações do Usuário
- Avatar com iniciais do nome
- Nome completo
- Perfil
- Botão de Logout

## Banco de Dados

### Tabelas Principais
1. **perfis** - Perfis de acesso do sistema
2. **usuarios** - Cadastro de usuários
3. **casos** - Processos jurídicos
4. **movimentacoes** - Histórico de movimentações dos casos
5. **documentos** - Arquivos anexados aos casos
6. **logs_acesso** - Log de acessos ao sistema
7. **notificacoes** - Notificações para usuários

### Relacionamentos
- Usuario -> Perfil (N:1)
- Caso -> Cliente (Usuario) (N:1)
- Caso -> Advogado (Usuario) (N:1)
- Movimentacao -> Caso (N:1)
- Documento -> Caso (N:1)
- Notificacao -> Usuario (N:1)
- Notificacao -> Caso (N:1)

### Índices
Índices criados para otimizar consultas frequentes:
- Email de usuário
- Perfil de usuário
- Cliente do caso
- Advogado do caso
- Status do caso
- Caso das movimentações
- Usuário das notificações

## Interface do Usuário

### Design Moderno
- Cores profissionais (azul primário)
- Sidebar fixa com menu de navegação
- Layout responsivo
- Cards e tabelas estilizadas
- Badges coloridos para status
- Formulários intuitivos

### Componentes
- **Sidebar:** Menu lateral com navegação
- **Header:** Título da página e ações principais
- **Cards:** Exibição de estatísticas e conteúdo
- **Tabelas:** Listagem de dados com hover
- **Modais:** Formulários em popup
- **Notificações:** Alertas de sucesso/erro
- **Badges:** Indicadores visuais de status

### Responsividade
- Layout adaptável para mobile
- Grid system flexível
- Sidebar colapsável em mobile
- Tabelas com scroll horizontal

## API REST

### Formato de Resposta Padrão
```json
{
  "status": 200,
  "message": "Mensagem descritiva",
  "data": { ... }
}
```

### Autenticação
Header obrigatório para rotas protegidas:
```
Authorization: Bearer {token}
```

### Códigos HTTP
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 500: Internal Server Error

## Funcionalidades Futuras

### Em Desenvolvimento
- Módulo de Movimentações
- Upload de Documentos
- Sistema de Notificações em tempo real
- Calendário de Audiências
- Relatórios em PDF
- Gráficos e Analytics
- Histórico de alterações
- Comentários nos casos
- Tags e categorias
- Busca avançada
- Exportação de dados
- Backup automático
- API para integração externa
- App Mobile

### Melhorias Planejadas
- Envio real de emails (atualmente apenas simulado)
- Reset de senha
- 2FA (Autenticação de dois fatores)
- Temas (Light/Dark mode)
- Personalização de perfis
- Webhooks
- Auditoria completa
- Cache de consultas
- Rate limiting
- Logs estruturados
