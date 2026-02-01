<?php
/**
 * Controller de Clientes
 */

class ClienteController extends Controller {
    protected $cliente_model;

    public function __construct() {
        parent::__construct();
        $this->cliente_model = new Cliente();
    }

    /**
     * Listar clientes
     */
    public function index() {
        $clientes = $this->cliente_model->listarAtivos();
        $this->render('clientes/index', ['clientes' => $clientes]);
    }

    /**
     * Exibir formulário de novo cliente
     */
    public function novo() {
        $csrf_token = $this->gerarCsrfToken();
        $this->render('clientes/form', ['csrf_token' => $csrf_token]);
    }

    /**
     * Salvar novo cliente
     */
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $this->validarCsrfToken();

        $dados = [
            'tipo' => $this->getPost('tipo'),
            'nome' => $this->getPost('nome'),
            'cpf_cnpj' => $this->getPost('cpf_cnpj'),
            'email' => $this->getPost('email'),
            'telefone' => $this->getPost('telefone'),
            'celular' => $this->getPost('celular'),
            'endereco' => $this->getPost('endereco'),
            'numero' => $this->getPost('numero'),
            'complemento' => $this->getPost('complemento'),
            'bairro' => $this->getPost('bairro'),
            'cidade' => $this->getPost('cidade'),
            'estado' => $this->getPost('estado'),
            'cep' => $this->getPost('cep')
        ];

        // Validações
        if (empty($dados['nome']) || empty($dados['tipo'])) {
            $_SESSION['erro'] = 'Nome e tipo são obrigatórios';
            $this->redirect('/clientes/novo');
        }

        // Verificar duplicata
        if (!empty($dados['cpf_cnpj'])) {
            $existente = $this->cliente_model->findByCpfCnpj($dados['cpf_cnpj']);
            if ($existente) {
                $_SESSION['erro'] = 'Cliente com este CPF/CNPJ já existe';
                $this->redirect('/clientes/novo');
            }
        }

        $id = $this->cliente_model->insert($dados);

        if ($id) {
            $this->registrarLog('CRIAR', 'clientes', $id, "Cliente criado: {$dados['nome']}");
            $_SESSION['sucesso'] = 'Cliente criado com sucesso';
            $this->redirect('/clientes');
        } else {
            $_SESSION['erro'] = 'Erro ao criar cliente';
            $this->redirect('/clientes/novo');
        }
    }

    /**
     * Editar cliente
     */
    public function editar($id = null) {
        $id = $id ?? $this->getQuery('id');
        
        if (!$id) {
            $_SESSION['erro'] = 'Cliente não informado';
            $this->redirect('/clientes');
        }

        $cliente = $this->cliente_model->findById($id);

        if (!$cliente) {
            $_SESSION['erro'] = 'Cliente não encontrado';
            $this->redirect('/clientes');
        }

        $csrf_token = $this->gerarCsrfToken();
        $this->render('clientes/form', ['cliente' => $cliente, 'csrf_token' => $csrf_token]);
    }

    /**
     * Atualizar cliente
     */
    public function atualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $this->validarCsrfToken();

        $id = $this->getPost('id');
        
        if (!$id) {
            $_SESSION['erro'] = 'Cliente não informado';
            $this->redirect('/clientes');
        }

        $dados = [
            'tipo' => $this->getPost('tipo'),
            'nome' => $this->getPost('nome'),
            'cpf_cnpj' => $this->getPost('cpf_cnpj'),
            'email' => $this->getPost('email'),
            'telefone' => $this->getPost('telefone'),
            'celular' => $this->getPost('celular'),
            'endereco' => $this->getPost('endereco'),
            'numero' => $this->getPost('numero'),
            'complemento' => $this->getPost('complemento'),
            'bairro' => $this->getPost('bairro'),
            'cidade' => $this->getPost('cidade'),
            'estado' => $this->getPost('estado'),
            'cep' => $this->getPost('cep')
        ];

        if ($this->cliente_model->update($id, $dados)) {
            $this->registrarLog('ATUALIZAR', 'clientes', $id, "Cliente atualizado: {$dados['nome']}");
            $_SESSION['sucesso'] = 'Cliente atualizado com sucesso';
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar cliente';
        }

        $this->redirect('/clientes');
    }

    /**
     * Deletar cliente
     */
    public function deletar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $this->validarCsrfToken();

        $id = $this->getPost('id');

        if ($this->cliente_model->delete($id)) {
            $this->registrarLog('DELETAR', 'clientes', $id, 'Cliente deletado');
            $_SESSION['sucesso'] = 'Cliente deletado com sucesso';
        } else {
            $_SESSION['erro'] = 'Erro ao deletar cliente';
        }

        $this->redirect('/clientes');
    }

    /**
     * Buscar cliente via AJAX
     */
    public function buscar() {
        $termo = $_GET['termo'] ?? '';
        
        if (strlen($termo) < 2) {
            echo json_encode([]);
            exit;
        }

        $sql = "SELECT id, nome, email, telefone FROM clientes 
                WHERE (nome LIKE ? OR email LIKE ? OR cpf_cnpj LIKE ?) 
                AND ativo = 1 
                LIMIT 10";
        
        global $pdo;
        $stmt = $pdo->prepare($sql);
        $termo_busca = "%{$termo}%";
        $stmt->execute([$termo_busca, $termo_busca, $termo_busca]);
        
        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll());
        exit;
    }
}
