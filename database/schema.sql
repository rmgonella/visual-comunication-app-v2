-- ============================================================================
-- Sistema SaaS de Gestão - Xavier Design Comunicação Visual
-- Banco de Dados: xavier_design
-- ============================================================================

CREATE DATABASE IF NOT EXISTS xavier_design CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE xavier_design;

-- ============================================================================
-- 1. TABELA DE USUÁRIOS
-- ============================================================================
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('admin', 'financeiro', 'producao', 'vendas') NOT NULL DEFAULT 'vendas',
    ativo BOOLEAN DEFAULT TRUE,
    ultimo_acesso DATETIME,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_perfil (perfil)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 2. TABELA DE CONFIGURAÇÕES DA EMPRESA
-- ============================================================================
CREATE TABLE configuracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    chave VARCHAR(100) UNIQUE NOT NULL,
    valor LONGTEXT,
    descricao VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_chave (chave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 3. TABELA DE CLIENTES
-- ============================================================================
CREATE TABLE clientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('PF', 'PJ') NOT NULL,
    nome VARCHAR(150) NOT NULL,
    cpf_cnpj VARCHAR(20),
    email VARCHAR(150),
    telefone VARCHAR(20),
    celular VARCHAR(20),
    endereco VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    cep VARCHAR(10),
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_cpf_cnpj (cpf_cnpj),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 4. TABELA DE FORNECEDORES
-- ============================================================================
CREATE TABLE fornecedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    cnpj VARCHAR(20),
    email VARCHAR(150),
    telefone VARCHAR(20),
    celular VARCHAR(20),
    endereco VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    cep VARCHAR(10),
    contato_principal VARCHAR(150),
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_cnpj (cnpj)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 5. TABELA DE CATEGORIAS DE PRODUTOS/SERVIÇOS
-- ============================================================================
CREATE TABLE categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 6. TABELA DE PRODUTOS E SERVIÇOS
-- ============================================================================
CREATE TABLE produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categoria_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco_base DECIMAL(10, 2) NOT NULL,
    unidade VARCHAR(20),
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT,
    INDEX idx_nome (nome),
    INDEX idx_categoria (categoria_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 7. TABELA DE MATERIAIS
-- ============================================================================
CREATE TABLE materiais (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    unidade VARCHAR(20),
    estoque INT DEFAULT 0,
    fornecedor_id INT,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE SET NULL,
    INDEX idx_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 8. TABELA DE ORÇAMENTOS
-- ============================================================================
CREATE TABLE orcamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(50) UNIQUE NOT NULL,
    cliente_id INT NOT NULL,
    usuario_id INT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_validade DATE,
    status ENUM('rascunho', 'enviado', 'aprovado', 'reprovado') DEFAULT 'rascunho',
    descricao TEXT,
    observacoes TEXT,
    subtotal DECIMAL(12, 2) DEFAULT 0,
    desconto DECIMAL(12, 2) DEFAULT 0,
    margem_lucro DECIMAL(5, 2) DEFAULT 30,
    total DECIMAL(12, 2) DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
    INDEX idx_numero (numero),
    INDEX idx_cliente (cliente_id),
    INDEX idx_status (status),
    INDEX idx_data (data_criacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 9. TABELA DE ITENS DO ORÇAMENTO
-- ============================================================================
CREATE TABLE orcamento_itens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    orcamento_id INT NOT NULL,
    produto_id INT,
    descricao VARCHAR(255) NOT NULL,
    quantidade DECIMAL(10, 2) NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE SET NULL,
    INDEX idx_orcamento (orcamento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 10. TABELA DE ORDENS DE PRODUÇÃO
-- ============================================================================
CREATE TABLE ordens_producao (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(50) UNIQUE NOT NULL,
    orcamento_id INT NOT NULL,
    usuario_responsavel INT NOT NULL,
    status ENUM('criacao', 'producao', 'instalacao', 'finalizado') DEFAULT 'criacao',
    data_inicio DATE,
    data_prevista DATE,
    data_conclusao DATE,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_responsavel) REFERENCES usuarios(id) ON DELETE RESTRICT,
    INDEX idx_numero (numero),
    INDEX idx_status (status),
    INDEX idx_orcamento (orcamento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 11. TABELA DE ETAPAS DA ORDEM DE PRODUÇÃO
-- ============================================================================
CREATE TABLE ordem_etapas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ordem_id INT NOT NULL,
    etapa VARCHAR(100) NOT NULL,
    status ENUM('pendente', 'em_andamento', 'concluida') DEFAULT 'pendente',
    usuario_responsavel INT,
    data_inicio DATETIME,
    data_conclusao DATETIME,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ordem_id) REFERENCES ordens_producao(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_responsavel) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_ordem (ordem_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 12. TABELA DE FINANCEIRO - CONTAS A RECEBER
-- ============================================================================
CREATE TABLE contas_receber (
    id INT PRIMARY KEY AUTO_INCREMENT,
    orcamento_id INT,
    cliente_id INT NOT NULL,
    numero_documento VARCHAR(50),
    valor DECIMAL(12, 2) NOT NULL,
    data_vencimento DATE NOT NULL,
    data_pagamento DATE,
    forma_pagamento VARCHAR(50),
    status ENUM('pendente', 'pago', 'atrasado', 'cancelado') DEFAULT 'pendente',
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE SET NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    INDEX idx_cliente (cliente_id),
    INDEX idx_status (status),
    INDEX idx_vencimento (data_vencimento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 13. TABELA DE FINANCEIRO - CONTAS A PAGAR
-- ============================================================================
CREATE TABLE contas_pagar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fornecedor_id INT NOT NULL,
    numero_documento VARCHAR(50),
    valor DECIMAL(12, 2) NOT NULL,
    data_vencimento DATE NOT NULL,
    data_pagamento DATE,
    forma_pagamento VARCHAR(50),
    status ENUM('pendente', 'pago', 'atrasado', 'cancelado') DEFAULT 'pendente',
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE RESTRICT,
    INDEX idx_fornecedor (fornecedor_id),
    INDEX idx_status (status),
    INDEX idx_vencimento (data_vencimento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 14. TABELA DE LOG DE ATIVIDADES
-- ============================================================================
CREATE TABLE logs_atividades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    acao VARCHAR(100) NOT NULL,
    tabela VARCHAR(100),
    registro_id INT,
    descricao TEXT,
    ip_address VARCHAR(45),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_usuario (usuario_id),
    INDEX idx_data (criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- DADOS INICIAIS
-- ============================================================================

-- Categorias padrão
INSERT INTO categorias (nome, descricao) VALUES
('Impressão Digital', 'Serviços de impressão digital'),
('Impressão Offset', 'Serviços de impressão offset'),
('Fachadas Comerciais', 'Projeto e instalação de fachadas'),
('Estruturas Metálicas', 'Fabricação e instalação de estruturas'),
('Comunicação Visual', 'Design e produção de comunicação visual'),
('Letreiros e Totens', 'Letreiros, totens e sinalizações'),
('Banners e Adesivos', 'Banners, adesivos e vinil'),
('Projetos Personalizados', 'Projetos customizados conforme solicitação');

-- Produtos padrão
INSERT INTO produtos (categoria_id, nome, descricao, preco_base, unidade) VALUES
(1, 'Impressão A4 Colorida', 'Impressão digital colorida formato A4', 2.50, 'unidade'),
(1, 'Impressão A3 Colorida', 'Impressão digital colorida formato A3', 5.00, 'unidade'),
(2, 'Cartão de Visita', 'Cartão de visita 250g offset 4x4', 150.00, '500 unidades'),
(3, 'Fachada em ACM', 'Fachada comercial em ACM', 500.00, 'm²'),
(4, 'Estrutura Metálica', 'Estrutura metálica customizada', 800.00, 'm²'),
(5, 'Logo Design', 'Criação de identidade visual completa', 2000.00, 'projeto'),
(6, 'Letreiro Luminoso', 'Letreiro em LED customizado', 1500.00, 'metro'),
(7, 'Banner Lona', 'Banner em lona 440g', 80.00, 'm²'),
(7, 'Adesivo Vinil', 'Adesivo vinil decorativo', 50.00, 'm²');

-- Materiais padrão
INSERT INTO materiais (nome, descricao, preco_unitario, unidade) VALUES
('Lona 440g', 'Lona para banners e adesivos', 85.00, 'm²'),
('ACM 3mm', 'Alumínio composto para fachadas', 450.00, 'm²'),
('Vinil Adesivo', 'Vinil adesivo para impressão', 55.00, 'm²'),
('Papel 240g', 'Papel couché 240g para impressão', 12.00, 'kg'),
('Tinta Pigmentada', 'Tinta pigmentada para impressoras', 150.00, 'litro'),
('Estrutura Alumínio', 'Perfil de alumínio 40x40', 85.00, 'metro'),
('LED RGB', 'Fita LED RGB para letreiros', 120.00, 'metro'),
('Parafuso Inox', 'Parafuso inox 5mm', 0.50, 'unidade');

-- Usuário admin padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, perfil) VALUES
('Administrador', 'admin@xavierdesign.com', '$2y$12$FVAgIR.WXzy4cLx0eQAhDuDdtVqN/q0mFAQZfqvw3CKNrGdmy4cxC', 'admin');

-- Configurações padrão
INSERT INTO configuracoes (chave, valor, descricao) VALUES
('empresa_nome', 'Xavier Design Comunicação Visual', 'Nome da empresa'),
('empresa_cnpj', '00.000.000/0000-00', 'CNPJ da empresa'),
('empresa_email', 'contato@xavierdesign.com', 'Email da empresa'),
('empresa_telefone', '(11) 0000-0000', 'Telefone da empresa'),
('empresa_endereco', 'Rua Exemplo, 123', 'Endereço da empresa'),
('empresa_cidade', 'São Paulo', 'Cidade da empresa'),
('empresa_estado', 'SP', 'Estado da empresa'),
('empresa_cep', '00000-000', 'CEP da empresa'),
('margem_lucro_padrao', '30', 'Margem de lucro padrão em %'),
('dias_validade_orcamento', '30', 'Dias de validade do orçamento'),
('tema_cor_primaria', '#007bff', 'Cor primária do tema'),
('tema_cor_secundaria', '#6c757d', 'Cor secundária do tema');
