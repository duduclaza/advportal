-- Portal de Gerenciamento de Processos Jurídicos
-- Schema do Banco de Dados

-- Para servidor remoto (Hostinger), comente a linha abaixo se o banco já existe
-- CREATE DATABASE IF NOT EXISTS u230868210_portaladvmarqu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE u230868210_portaladvmarqu;

-- Tabela de Perfis
CREATE TABLE IF NOT EXISTS perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    descricao VARCHAR(255),
    nivel INT NOT NULL COMMENT '1=Super Admin, 2=Admin, 3=Aux Adm, 4=Advogado, 5=Cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil_id INT NOT NULL,
    telefone VARCHAR(20),
    cpf VARCHAR(14) UNIQUE,
    status ENUM('ativo', 'inativo', 'pendente') DEFAULT 'pendente',
    primeiro_acesso BOOLEAN DEFAULT TRUE,
    codigo_confirmacao VARCHAR(6),
    codigo_expiracao DATETIME,
    ultima_senha_alteracao DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (perfil_id) REFERENCES perfis(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Casos/Processos
CREATE TABLE IF NOT EXISTS casos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_processo VARCHAR(50) UNIQUE,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    cliente_id INT NOT NULL,
    advogado_responsavel_id INT,
    tipo_acao VARCHAR(100),
    valor_causa DECIMAL(15, 2),
    data_abertura DATE NOT NULL,
    data_encerramento DATE,
    status ENUM('aberto', 'em_andamento', 'suspenso', 'encerrado', 'arquivado') DEFAULT 'aberto',
    prioridade ENUM('baixa', 'media', 'alta', 'urgente') DEFAULT 'media',
    tribunal VARCHAR(100),
    comarca VARCHAR(100),
    vara VARCHAR(100),
    observacoes TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (advogado_responsavel_id) REFERENCES usuarios(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Movimentações do Caso
CREATE TABLE IF NOT EXISTS movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caso_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo VARCHAR(50) NOT NULL COMMENT 'audiencia, peticao, sentenca, despacho, etc',
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_movimentacao DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (caso_id) REFERENCES casos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Documentos
CREATE TABLE IF NOT EXISTS documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caso_id INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    tipo VARCHAR(50),
    caminho VARCHAR(500) NOT NULL,
    tamanho INT COMMENT 'em bytes',
    upload_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (caso_id) REFERENCES casos(id) ON DELETE CASCADE,
    FOREIGN KEY (upload_by) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Logs de Acesso
CREATE TABLE IF NOT EXISTS logs_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    acao VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Notificações
CREATE TABLE IF NOT EXISTS notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    caso_id INT,
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo VARCHAR(50),
    lida BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (caso_id) REFERENCES casos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir Perfis Padrão
INSERT INTO perfis (nome, descricao, nivel) VALUES
('Super Admin', 'Acesso total ao sistema', 1),
('Admin', 'Administrador do sistema', 2),
('Auxiliar Administrativo', 'Suporte administrativo', 3),
('Advogado', 'Advogado responsável por casos', 4),
('Cliente', 'Cliente com casos no sistema', 5);

-- Inserir Super Admin padrão (senha: Pandora@1989)
INSERT INTO usuarios (nome, email, senha, perfil_id, status, primeiro_acesso) VALUES
('Clayton Dutra', 'du.claza@gmail.com', '$2y$10$rRxHpHvAXeaCEonfvRImAOGNtDdWyuiCRlEjc4pFf3MKBoC9yDYGO', 1, 'ativo', FALSE);

-- Índices para melhor performance
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_perfil ON usuarios(perfil_id);
CREATE INDEX idx_casos_cliente ON casos(cliente_id);
CREATE INDEX idx_casos_advogado ON casos(advogado_responsavel_id);
CREATE INDEX idx_casos_status ON casos(status);
CREATE INDEX idx_movimentacoes_caso ON movimentacoes(caso_id);
CREATE INDEX idx_documentos_caso ON documentos(caso_id);
CREATE INDEX idx_notificacoes_usuario ON notificacoes(usuario_id);
CREATE INDEX idx_logs_usuario ON logs_acesso(usuario_id);
