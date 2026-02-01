<?php
/**
 * Configuração Geral da Aplicação
 * Sistema SaaS de Gestão - Xavier Design Comunicação Visual
 */

// Definições gerais
define('APP_NAME', 'Xavier Design - Sistema de Gestão');
define('APP_VERSION', '1.0.0');
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name === '/' ? '' : $script_name);
define('APP_URL', $base_url . '/');
define('APP_DEBUG', true);

// Configurações de sessão
define('SESSION_TIMEOUT', 3600); // 1 hora
define('SESSION_NAME', 'xavier_design_session');

// Configurações de segurança
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_OPTIONS', ['cost' => 12]);

// Perfis de usuário
define('PERFIS', [
    'admin' => 'Administrador',
    'financeiro' => 'Financeiro',
    'producao' => 'Produção',
    'vendas' => 'Vendas'
]);

// Configurações de arquivo
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Iniciar sessão
session_name(SESSION_NAME);
session_start();
