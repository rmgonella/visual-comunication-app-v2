<?php
/**
 * Controller de Configurações
 */

class ConfiguracoesController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM configuracoes ORDER BY chave");
        $configuracoes = $stmt->fetchAll();
        $this->render('configuracoes/index', ['configuracoes' => $configuracoes]);
    }

    public function editar() {
        $id = $this->getQuery('id');
        if (!$id) $this->redirect('/configuracoes');
        
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM configuracoes WHERE id = ?");
        $stmt->execute([$id]);
        $configuracao = $stmt->fetch();
        
        $csrf_token = $this->gerarCsrfToken();
        $this->render('configuracoes/form', [
            'configuracao' => $configuracao,
            'csrf_token' => $csrf_token
        ]);
    }

    public function atualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('/configuracoes');
        $this->validarCsrfToken();
        
        $id = $this->getPost('id');
        $valor = $this->getPost('valor');
        $descricao = $this->getPost('descricao');
        
        global $pdo;
        $stmt = $pdo->prepare("UPDATE configuracoes SET valor = ?, descricao = ? WHERE id = ?");
        if ($stmt->execute([$valor, $descricao, $id])) {
            $_SESSION['sucesso'] = 'Configuração atualizada com sucesso';
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar configuração';
        }
        $this->redirect('/configuracoes');
    }
}
