-- database/ecocoleta.sql
CREATE DATABASE ecocoleta;
USE ecocoleta;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('morador', 'coletor', 'empresa') NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT,
    cep VARCHAR(10),
    cidade VARCHAR(50),
    estado VARCHAR(2),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de coletas
CREATE TABLE coletas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    morador_id INT,
    coletor_id INT,
    endereco TEXT NOT NULL,
    material TEXT NOT NULL,
    quantidade VARCHAR(50),
    status ENUM('pendente', 'agendada', 'realizada', 'cancelada') DEFAULT 'pendente',
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_agendada DATETIME,
    observacoes TEXT,
    FOREIGN KEY (morador_id) REFERENCES usuarios(id),
    FOREIGN KEY (coletor_id) REFERENCES usuarios(id)
);

-- Tabela de rotas
CREATE TABLE rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coletor_id INT,
    bairro VARCHAR(100),
    dias_semana VARCHAR(50),
    horario TIME,
    ativo BOOLEAN DEFAULT true,
    FOREIGN KEY (coletor_id) REFERENCES usuarios(id)
);

-- Adicionar estas tabelas ao banco de dados

-- Tabela de notificações
CREATE TABLE notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    tipo VARCHAR(50) NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT,
    lida BOOLEAN DEFAULT FALSE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de configurações de e-mail
CREATE TABLE config_email (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(50) UNIQUE NOT NULL,
    valor TEXT,
    descricao TEXT
);

-- Inserir configurações padrão
INSERT INTO config_email (chave, valor, descricao) VALUES
('smtp_host', 'smtp.seuprovedor.com', 'Host do servidor SMTP'),
('smtp_port', '587', 'Porta do servidor SMTP'),
('smtp_username', 'notificacoes@ecocoleta.com', 'Usuário SMTP'),
('smtp_password', 'sua_senha', 'Senha SMTP'),
('from_email', 'notificacoes@ecocoleta.com', 'E-mail remetente'),
('from_name', 'EcoColeta', 'Nome do remetente');