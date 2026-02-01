<?php
/**
 * Controller de Autenticação
 */

class AuthController {
    protected $usuario_model;

    public function __construct() {
        $this->usuario_model = new Usuario();
    }

    /**
     * Exibir formulário de login
     */
    public function login() {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . 'dashboard');
            exit;
        }
        
        include __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Processar login
     */
    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $_SESSION['erro'] = 'Email e senha são obrigatórios';
            header('Location: ' . APP_URL . 'login');
            exit;
        }

        $usuario = $this->usuario_model->validarLogin($email, $senha);

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_perfil'] = $usuario['perfil'];
            
            // Registrar log
            $sql = "INSERT INTO logs_atividades 
                    (usuario_id, acao, descricao, ip_address) 
                    VALUES (?, ?, ?, ?)";
            global $pdo;
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $usuario['id'],
                'LOGIN',
                'Usuário realizou login',
                $_SERVER['REMOTE_ADDR'] ?? null
            ]);

            header('Location: ' . APP_URL . 'dashboard');
            exit;
        } else {
            $_SESSION['erro'] = 'Email ou senha inválidos';
            header('Location: ' . APP_URL . 'login');
            exit;
        }
    }

    /**
     * Fazer logout
     */
    public function logout() {
        if (isset($_SESSION['usuario_id'])) {
            // Registrar log
            $sql = "INSERT INTO logs_atividades 
                    (usuario_id, acao, descricao, ip_address) 
                    VALUES (?, ?, ?, ?)";
            global $pdo;
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_SESSION['usuario_id'],
                'LOGOUT',
                'Usuário realizou logout',
                $_SERVER['REMOTE_ADDR'] ?? null
            ]);
        }

        session_destroy();
        header('Location: ' . APP_URL . 'login');
        exit;
    }

    /**
     * Exibir formulário de recuperação de senha
     */
    public function recuperarSenha() {
        include __DIR__ . '/../views/auth/recuperar-senha.php';
    }

    /**
     * Processar recuperação de senha
     */
    public function processarRecuperacao() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            $_SESSION['erro'] = 'Email é obrigatório';
            header('Location: ' . APP_URL . 'recuperar-senha');
            exit;
        }

        $usuario = $this->usuario_model->findByEmail($email);

        if ($usuario) {
            // Gerar token de recuperação
            $token = bin2hex(random_bytes(32));
            
            // Salvar token em sessão (em produção, usar banco de dados)
            $_SESSION['reset_token'] = $token;
            $_SESSION['reset_usuario_id'] = $usuario['id'];
            $_SESSION['reset_expira'] = time() + 3600; // 1 hora

            $_SESSION['sucesso'] = 'Instruções de recuperação foram enviadas para seu email';
        } else {
            // Não revelar se email existe ou não por segurança
            $_SESSION['sucesso'] = 'Se o email existe, você receberá instruções';
        }

        header('Location: ' . APP_URL . 'recuperar-senha');
        exit;
    }

    /**
     * Exibir formulário de registro
     */
    public function registrar() {
        include __DIR__ . '/../views/auth/registrar.php';
    }

    /**
     * Processar registro de novo usuário
     */
    public function processarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $perfil = $_POST['perfil'] ?? 'vendas';

        if (empty($nome) || empty($email) || empty($senha)) {
            $_SESSION['erro'] = 'Todos os campos são obrigatórios';
            header('Location: ' . APP_URL . 'registrar');
            exit;
        }

        // Verificar se email já existe
        if ($this->usuario_model->findByEmail($email)) {
            $_SESSION['erro'] = 'Este email já está cadastrado';
            header('Location: ' . APP_URL . 'registrar');
            exit;
        }

        // Criar usuário
        $id = $this->usuario_model->criar([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha,
            'perfil' => $perfil,
            'ativo' => 1
        ]);

        if ($id) {
            $_SESSION['sucesso'] = 'Conta criada com sucesso! Faça login agora.';
            header('Location: ' . APP_URL . 'login');
            exit;
        } else {
            $_SESSION['erro'] = 'Erro ao criar conta. Tente novamente.';
            header('Location: ' . APP_URL . 'registrar');
            exit;
        }
    }

    /**
     * Testar conexão com o banco de dados
     */
    public function testarConexao() {
        include ROOT_DIR . '/test_db.php';
    }
}
