-- Dados de Exemplo para Testes
USE advportal_db;

-- Usuários de Exemplo (senha para todos: Senha@123)
INSERT INTO usuarios (nome, email, senha, perfil_id, status, primeiro_acesso, telefone, cpf) VALUES
('João Silva', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'ativo', FALSE, '(11) 98765-4321', '123.456.789-00'),
('Maria Santos', 'auxiliar@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'ativo', FALSE, '(11) 98765-4322', '123.456.789-01'),
('Dr. Carlos Oliveira', 'advogado@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'ativo', FALSE, '(11) 98765-4323', '123.456.789-02'),
('Ana Paula Costa', 'cliente@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 5, 'ativo', FALSE, '(11) 98765-4324', '123.456.789-03');

-- Casos de Exemplo
INSERT INTO casos (numero_processo, titulo, descricao, cliente_id, advogado_responsavel_id, tipo_acao, valor_causa, data_abertura, status, prioridade, tribunal, comarca, vara, created_by) VALUES
('0001234-56.2024.8.26.0100', 'Ação de Cobrança - Empresa XYZ', 'Cobrança de valores não pagos referentes ao contrato 12345', 5, 4, 'Cobrança', 50000.00, '2024-01-15', 'em_andamento', 'alta', 'TJSP', 'São Paulo', '1ª Vara Cível', 1),
('0001235-56.2024.8.26.0100', 'Ação Trabalhista - Rescisão', 'Contestação de rescisão contratual', 5, 4, 'Trabalhista', 75000.00, '2024-02-20', 'aberto', 'media', 'TRT', 'São Paulo', '2ª Vara do Trabalho', 1);

-- Movimentações de Exemplo
INSERT INTO movimentacoes (caso_id, usuario_id, tipo, titulo, descricao, data_movimentacao) VALUES
(1, 4, 'peticao', 'Petição Inicial', 'Petição inicial protocolada', '2024-01-15 10:00:00'),
(1, 4, 'despacho', 'Despacho do Juiz', 'Juiz determinou citação da parte contrária', '2024-01-20 14:30:00'),
(2, 4, 'audiencia', 'Audiência de Conciliação', 'Primeira audiência agendada', '2024-03-10 09:00:00');

-- Notificações de Exemplo
INSERT INTO notificacoes (usuario_id, caso_id, titulo, mensagem, tipo) VALUES
(5, 1, 'Novo Despacho', 'Foi publicado um novo despacho no processo 0001234-56.2024.8.26.0100', 'despacho'),
(4, 2, 'Audiência Agendada', 'Audiência de conciliação agendada para 10/03/2024', 'audiencia');
