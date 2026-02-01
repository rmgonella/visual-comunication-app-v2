<?php
/**
 * Controller de Financeiro
 */

class FinanceiroController extends Controller {
    protected $financeiro_model;

    public function __construct() {
        parent::__construct();
        $this->financeiro_model = new Financeiro();
    }

    public function index() {
        $receber = $this->financeiro_model->listarContasReceber();
        $pagar = $this->financeiro_model->listarContasPagar();
        $this->render('financeiro/index', [
            'contas_receber' => $receber,
            'contas_pagar' => $pagar
        ]);
    }

    public function novo() {
        $csrf_token = $this->gerarCsrfToken();
        $this->render('financeiro/form', ['csrf_token' => $csrf_token]);
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('/financeiro');
        $this->validarCsrfToken();
        
        $tipo = $this->getPost('tipo');
        $tabela = ($tipo === 'receita') ? 'contas_receber' : 'contas_pagar';
        
        // Como o modelo Financeiro herda de Model, mas aponta para contas_receber por padrão,
        // vamos usar uma abordagem direta para salvar dependendo do tipo.
        global $pdo;
        $sql = "INSERT INTO $tabela (descricao, valor, data_vencimento, status) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->getPost('descricao'),
            $this->getPost('valor'),
            $this->getPost('data_vencimento'),
            $this->getPost('status')
        ]);
        
        $_SESSION['sucesso'] = 'Lançamento realizado com sucesso';
        $this->redirect('/financeiro');
    }

    public function editar() {
        $id = $this->getQuery('id');
        $tipo = $this->getQuery('tipo');
        if (!$id || !$tipo) $this->redirect('/financeiro');
        
        global $pdo;
        $tabela = ($tipo === 'receita') ? 'contas_receber' : 'contas_pagar';
        $stmt = $pdo->prepare("SELECT * FROM $tabela WHERE id = ?");
        $stmt->execute([$id]);
        $lancamento = $stmt->fetch();
        $lancamento['tipo'] = $tipo;
        
        $csrf_token = $this->gerarCsrfToken();
        $this->render('financeiro/form', [
            'lancamento' => $lancamento,
            'csrf_token' => $csrf_token
        ]);
    }

    public function atualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('/financeiro');
        $this->validarCsrfToken();
        
        $id = $this->getPost('id');
        $tipo = $this->getPost('tipo');
        $tabela = ($tipo === 'receita') ? 'contas_receber' : 'contas_pagar';
        
        global $pdo;
        $sql = "UPDATE $tabela SET descricao = ?, valor = ?, data_vencimento = ?, status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $this->getPost('descricao'),
            $this->getPost('valor'),
            $this->getPost('data_vencimento'),
            $this->getPost('status'),
            $id
        ]);
        
        $_SESSION['sucesso'] = 'Lançamento atualizado';
        $this->redirect('/financeiro');
    }
}
