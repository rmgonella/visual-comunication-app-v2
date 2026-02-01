<?php
/**
 * Script de Teste de Conexão com o Banco de Dados
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste de Conexão com Banco de Dados</h1>";

// Carregar .env se existir
if (file_exists(__DIR__ . '/.env')) {
    echo "<p style='color: green;'>✅ Arquivo .env encontrado.</p>";
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || !strpos($line, '=')) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Remover aspas se existirem
        if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
            $value = substr($value, 1, -1);
        }
        
        putenv("$name=$value");
    }
} else {
    echo "<p style='color: orange;'>⚠️ Arquivo .env NÃO encontrado. Usando padrões ou variáveis de ambiente do servidor.</p>";
}

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$name = getenv('DB_NAME') ?: 'xavier_design';

echo "<h3>Configurações Detectadas:</h3>";
echo "<ul>";
echo "<li><strong>Host:</strong> $host</li>";
echo "<li><strong>Usuário:</strong> $user</li>";
echo "<li><strong>Senha:</strong> " . ($pass ? "********" : "(vazia)") . "</li>";
echo "<li><strong>Banco:</strong> $name</li>";
echo "</ul>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h2 style='color: green;'>✅ Conexão realizada com SUCESSO!</h2>";
    
    // Testar se a tabela usuarios existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Tabela 'usuarios' encontrada.</p>";
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
        $count = $stmt->fetchColumn();
        echo "<p>Total de usuários cadastrados: <strong>$count</strong></p>";
    } else {
        echo "<p style='color: red;'>❌ Tabela 'usuarios' NÃO encontrada. Você importou o arquivo database/schema.sql?</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Erro na Conexão:</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<p><strong>Dica:</strong> Verifique se o usuário e a senha estão corretos no seu arquivo .env ou no painel da sua hospedagem.</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>Voltar para o Sistema</a></p>";
?>
