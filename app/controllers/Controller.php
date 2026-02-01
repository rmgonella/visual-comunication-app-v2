<?php
/**
 * Classe Base Controller
 * Fornece métodos comuns para todos os controllers
 */

class Controller {
    protected $view_path = __DIR__ . '/../views/';
    protected $usuario_logado = null;

    public function __construct() {
        // Verificar autenticação
        $this->verificarAutenticacao();
    }

    /**
     * Verificar se usuário está autenticado
     */
    protected function verificarAutenticacao() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . 'login');
            exit;
        }
        
        // Carregar dados do usuário
        $usuario_model = new Usuario();
        $this->usuario_logado = $usuario_model->findById($_SESSION['usuario_id']);
        
        if (!$this->usuario_logado) {
            session_destroy();
            header('Location: ' . APP_URL . 'login');
            exit;
        }
    }

    /**
     * Renderizar view
     */
    protected function render($view, $dados = []) {
        // Passar usuário logado para a view
        $dados['usuario_logado'] = $this->usuario_logado;
        
        // Extrair variáveis
        extract($dados);
        
        // Incluir view
        $arquivo = $this->view_path . $view . '.php';
        
        if (!file_exists($arquivo)) {
            die("View não encontrada: {$arquivo}");
        }
        
        include $arquivo;
    }

    /**
     * Redirecionar
     */
    protected function redirect($url) {
        $target = (strpos($url, 'http') === 0) ? $url : APP_URL . ltrim($url, '/');
        header("Location: {$target}");
        exit;
    }

    /**
     * Retornar JSON
     */
    protected function json($dados, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($dados);
        exit;
    }

    /**
     * Verificar permissão
     */
    protected function verificarPermissao($perfil_requerido) {
        if ($this->usuario_logado['perfil'] !== 'admin' && 
            $this->usuario_logado['perfil'] !== $perfil_requerido) {
            http_response_code(403);
            die('Acesso negado');
        }
    }

    /**
     * Registrar log de atividade
     */
    protected function registrarLog($acao, $tabela = null, $registro_id = null, $descricao = null) {
        $sql = "INSERT INTO logs_atividades 
                (usuario_id, acao, tabela, registro_id, descricao, ip_address) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        global $pdo;
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $this->usuario_logado['id'],
            $acao,
            $tabela,
            $registro_id,
            $descricao,
            $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    }

    /**
     * Obter valor POST com validação
     */
    protected function getPost($chave, $padrao = null) {
        return $_POST[$chave] ?? $padrao;
    }

    /**
     * Obter valor GET com validação
     */
    protected function getQuery($chave, $padrao = null) {
        return $_GET[$chave] ?? $padrao;
    }

    /**
     * Validar CSRF token
     */
    protected function validarCsrfToken() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Token CSRF inválido');
        }
    }

    /**
     * Gerar CSRF token
     */
    protected function gerarCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Sanitizar entrada
     */
    protected function sanitizar($dados) {
        if (is_array($dados)) {
            return array_map([$this, 'sanitizar'], $dados);
        }
        return htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
    }
}
