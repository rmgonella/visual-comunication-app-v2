<?php
/**
 * Arquivo Principal - Sistema SaaS de Gestão Xavier Design
 * Ponto de entrada da aplicação
 */

// Definir diretório raiz
define('ROOT_DIR', __DIR__);

// Carregar variáveis de ambiente se existir .env
if (file_exists(ROOT_DIR . '/.env')) {
    $lines = file(ROOT_DIR . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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
        
        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

// Carregar configurações
require_once ROOT_DIR . '/config/app.php';
require_once ROOT_DIR . '/config/database.php';

// Autoloader de classes
spl_autoload_register(function($class) {
    $paths = [
        ROOT_DIR . '/app/models/',
        ROOT_DIR . '/app/controllers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Rotear requisição
$base_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remover o base_path da URI se estiver presente
if ($base_path !== '/' && strpos($uri, $base_path) === 0) {
    $uri = substr($uri, strlen($base_path));
}

$uri = str_replace('/public', '', $uri);
$uri = trim($uri, '/');

// Rotas
$rotas = [
    // Autenticação
    'login' => ['controller' => 'AuthController', 'action' => 'login'],
    'autenticar' => ['controller' => 'AuthController', 'action' => 'autenticar'],
    'logout' => ['controller' => 'AuthController', 'action' => 'logout'],
    'recuperar-senha' => ['controller' => 'AuthController', 'action' => 'recuperarSenha'],
    'processar-recuperacao' => ['controller' => 'AuthController', 'action' => 'processarRecuperacao'],
    'registrar' => ['controller' => 'AuthController', 'action' => 'registrar'],
    'processar-registro' => ['controller' => 'AuthController', 'action' => 'processarRegistro'],
    
    // Dashboard
    'dashboard' => ['controller' => 'DashboardController', 'action' => 'index'],
    'dashboard/dados-graficos' => ['controller' => 'DashboardController', 'action' => 'obterDadosGraficos'],
    
    // Clientes
    'clientes' => ['controller' => 'ClienteController', 'action' => 'index'],
    'clientes/novo' => ['controller' => 'ClienteController', 'action' => 'novo'],
    'clientes/salvar' => ['controller' => 'ClienteController', 'action' => 'salvar'],
    'clientes/editar' => ['controller' => 'ClienteController', 'action' => 'editar'],
    'clientes/atualizar' => ['controller' => 'ClienteController', 'action' => 'atualizar'],
    'clientes/deletar' => ['controller' => 'ClienteController', 'action' => 'deletar'],
    'clientes/buscar' => ['controller' => 'ClienteController', 'action' => 'buscar'],
    
    // Orçamentos
    'orcamentos' => ['controller' => 'OrcamentoController', 'action' => 'index'],
    'orcamentos/novo' => ['controller' => 'OrcamentoController', 'action' => 'novo'],
    'orcamentos/salvar' => ['controller' => 'OrcamentoController', 'action' => 'salvar'],
    'orcamentos/editar' => ['controller' => 'OrcamentoController', 'action' => 'editar'],
    'orcamentos/atualizar' => ['controller' => 'OrcamentoController', 'action' => 'atualizar'],
    'orcamentos/atualizar-status' => ['controller' => 'OrcamentoController', 'action' => 'atualizarStatus'],
    'orcamentos/aprovar' => ['controller' => 'OrcamentoController', 'action' => 'aprovar'],
    'orcamentos/gerar-pdf' => ['controller' => 'OrcamentoController', 'action' => 'gerarPdf'],
    'orcamentos/deletar' => ['controller' => 'OrcamentoController', 'action' => 'deletar'],
    'testar-conexao' => ['controller' => 'AuthController', 'action' => 'testarConexao'],
    
    // Ordens de Produção
    'ordens-producao' => ['controller' => 'OrdemProducaoController', 'action' => 'index'],
    'ordens-producao/novo' => ['controller' => 'OrdemProducaoController', 'action' => 'novo'],
    'ordens-producao/salvar' => ['controller' => 'OrdemProducaoController', 'action' => 'salvar'],
    'ordens-producao/editar' => ['controller' => 'OrdemProducaoController', 'action' => 'editar'],
    'ordens-producao/atualizar' => ['controller' => 'OrdemProducaoController', 'action' => 'atualizar'],
    
    // Financeiro
    'financeiro' => ['controller' => 'FinanceiroController', 'action' => 'index'],
    'financeiro/novo' => ['controller' => 'FinanceiroController', 'action' => 'novo'],
    'financeiro/salvar' => ['controller' => 'FinanceiroController', 'action' => 'salvar'],
    'financeiro/editar' => ['controller' => 'FinanceiroController', 'action' => 'editar'],
    'financeiro/atualizar' => ['controller' => 'FinanceiroController', 'action' => 'atualizar'],
    
    // Relatórios
    'relatorios' => ['controller' => 'RelatoriosController', 'action' => 'index'],
    
    // Configurações
    'configuracoes' => ['controller' => 'ConfiguracoesController', 'action' => 'index'],
    'configuracoes/editar' => ['controller' => 'ConfiguracoesController', 'action' => 'editar'],
    'configuracoes/atualizar' => ['controller' => 'ConfiguracoesController', 'action' => 'atualizar'],
];

// Processar rota
if (empty($uri) || $uri === 'index.php') {
    $redirect_url = ($base_path === '/') ? '/dashboard' : $base_path . '/dashboard';
    header("Location: $redirect_url");
    exit;
}

if (isset($rotas[$uri])) {
    $rota = $rotas[$uri];
    $controller_class = $rota['controller'];
    $action = $rota['action'];
    
    if (class_exists($controller_class)) {
        $controller = new $controller_class();
        
        if (method_exists($controller, $action)) {
            call_user_func([$controller, $action]);
        } else {
            http_response_code(404);
            die("Ação não encontrada: {$action}");
        }
    } else {
        http_response_code(404);
        die("Controller não encontrado: {$controller_class}");
    }
} else {
    http_response_code(404);
    die("Rota não encontrada: {$uri}");
}
