<?php
/**
 * Configuração do Banco de Dados
 * Sistema SaaS de Gestão - Xavier Design Comunicação Visual
 */

// ============================================================================
// CONFIGURAÇÕES MANUAIS (Edite aqui se não usar arquivo .env)
// ============================================================================
$db_config = [
    'host'    => 'localhost',
    'user'    => 'u591057133_visual',
    'pass'    => 'bb/y|uGqHd2=',
    'dbname'  => 'u591057133_visual',
    'charset' => 'utf8mb4'
];

// ============================================================================
// LÓGICA DE CONEXÃO (Não altere abaixo a menos que saiba o que está fazendo)
// ============================================================================

// Tentar carregar do ambiente (prioridade para .env)
$host = getenv('DB_HOST') ?: $db_config['host'];
$user = getenv('DB_USER') ?: $db_config['user'];
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : $db_config['pass'];
$name = getenv('DB_NAME') ?: $db_config['dbname'];
$charset = getenv('DB_CHARSET') ?: $db_config['charset'];

// Limpar aspas se existirem (comum em arquivos .env)
$pass = trim($pass, '"\'');

define('DB_HOST', $host);
define('DB_USER', $user);
define('DB_PASS', $pass);
define('DB_NAME', $name);
define('DB_CHARSET', $charset);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ]
    );
} catch (PDOException $e) {
    // Se estiver no index.php ou test_db.php, o erro será tratado lá ou exibido aqui
    if (basename($_SERVER['PHP_SELF']) == 'test_db.php') {
        throw $e; // Deixa o script de teste tratar
    }
    
    header('Content-Type: text/html; charset=utf-8');
    echo "<div style='font-family: sans-serif; padding: 20px; border: 1px solid #cc0000; background: #fff5f5; color: #990000; border-radius: 5px; margin: 20px;'>";
    echo "<h2>Erro de Conexão com o Banco de Dados</h2>";
    echo "<p>Não foi possível conectar ao banco de dados. Verifique suas credenciais.</p>";
    
    if (defined('APP_DEBUG') && APP_DEBUG) {
        echo "<p><strong>Detalhes do erro:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "<p><strong>Dica:</strong> Você pode testar suas credenciais acessando: <a href='test_db.php'>test_db.php</a></p>";
    echo "</div>";
    exit;
}
