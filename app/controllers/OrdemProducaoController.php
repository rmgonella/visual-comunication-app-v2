<?php
/**
 * Controller de Ordens de Produção
 */

class OrdemProducaoController extends Controller {
    protected $ordem_model;

    public function __construct() {
        parent::__construct();
        $this->ordem_model = new OrdemProducao();
    }

    public function index() {
        $ordens = $this->ordem_model->listarComOrcamento();
        $this->render('ordemproducao/index', ['ordens' => $ordens]);
    }

    public function novo() {
        $orcamento_id_selecionado = $this->getQuery('orcamento_id');
        
        $orcamento_model = new Orcamento();
        // Buscar orçamentos aprovados que ainda não têm ordem de produção
        global $pdo;
        $sql = "SELECT o.*, c.nome as cliente_nome 
                FROM orcamentos o 
                JOIN clientes c ON o.cliente_id = c.id 
                WHERE o.status = 'aprovado' 
                AND o.id NOT IN (SELECT orcamento_id FROM ordens_producao)
                ORDER BY o.data_criacao DESC";
        $stmt = $pdo->query($sql);
        $orcamentos = $stmt->fetchAll();
        
        $csrf_token = $this->gerarCsrfToken();
        $this->render('ordemproducao/form', [
            'orcamentos' => $orcamentos,
            'orcamento_id_selecionado' => $orcamento_id_selecionado,
            'csrf_token' => $csrf_token
        ]);
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('/ordens-producao');
        $this->validarCsrfToken();
        
        $orcamento_id = $this->getPost('orcamento_id');
        $usuario_responsavel = $this->usuario_logado['id'];
        $observacoes = $this->getPost('observacoes');
        
        try {
            $this->ordem_model->criarDeOrcamento($orcamento_id, $usuario_responsavel, $observacoes);
            $_SESSION['sucesso'] = 'Ordem de produção criada com sucesso';
            $this->redirect('/ordens-producao');
        } catch (Exception $e) {
            $_SESSION['erro'] = 'Erro ao criar ordem: ' . $e->getMessage();
            $this->redirect('/ordens-producao/novo');
        }
    }

    public function editar($id = null) {
        $id = $id ?? $this->getQuery('id');
        if (!$id) $this->redirect('/ordens-producao');
        
        $ordem = $this->ordem_model->findComEtapas($id);
        if (!$ordem) $this->redirect('/ordens-producao');

        // Buscar dados do orçamento e cliente
        global $pdo;
        $sql = "SELECT o.*, oc.numero as orcamento_numero, c.nome as cliente_nome 
                FROM ordens_producao o 
                JOIN orcamentos oc ON o.orcamento_id = oc.id 
                JOIN clientes c ON oc.cliente_id = c.id 
                WHERE o.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $dados_extras = $stmt->fetch();
        $ordem = array_merge($ordem, $dados_extras);

        $csrf_token = $this->gerarCsrfToken();
        $this->render('ordemproducao/form', [
            'ordem' => $ordem,
            'csrf_token' => $csrf_token
        ]);
    }

    public function atualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('/ordens-producao');
        $this->validarCsrfToken();
        
        $id = $this->getPost('id');
        $dados = [
            'status' => $this->getPost('status'),
            'data_prevista' => $this->getPost('data_prevista'),
            'observacoes' => $this->getPost('observacoes')
        ];
        
        if ($this->ordem_model->update($id, $dados)) {
            // Atualizar etapas se enviadas
            if (isset($_POST['etapas'])) {
                foreach ($_POST['etapas'] as $etapa_id => $status) {
                    $this->ordem_model->atualizarEtapa($etapa_id, $status);
                }
            }
            $_SESSION['sucesso'] = 'Ordem de produção atualizada com sucesso';
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar ordem';
        }
        $this->redirect('/ordens-producao/editar?id=' . $id);
    }

    public function atualizarEtapa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->json(['erro' => 'Método não permitido'], 405);
        
        $etapa_id = $this->getPost('etapa_id');
        $status = $this->getPost('status');
        
        if ($this->ordem_model->atualizarEtapa($etapa_id, $status, $this->usuario_logado['id'])) {
            $this->json(['sucesso' => true]);
        } else {
            $this->json(['erro' => 'Erro ao atualizar etapa'], 500);
        }
    }
}
