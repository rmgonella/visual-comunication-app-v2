<?php
/**
 * Controller de Orçamentos
 */

class OrcamentoController extends Controller {
    protected $orcamento_model;
    protected $cliente_model;
    protected $produto_model;

    public function __construct() {
        parent::__construct();
        $this->orcamento_model = new Orcamento();
        $this->cliente_model = new Cliente();
        $this->produto_model = new Produto();
    }

    /**
     * Listar orçamentos
     */
    public function index() {
        $orcamentos = $this->orcamento_model->listarComCliente();
        $this->render('orcamentos/index', ['orcamentos' => $orcamentos]);
    }

    /**
     * Exibir formulário de novo orçamento
     */
    public function novo() {
        $clientes = $this->cliente_model->listarAtivos();
        $produtos = $this->produto_model->listarComCategoria();
        $csrf_token = $this->gerarCsrfToken();
        
        $this->render('orcamentos/form', [
            'clientes' => $clientes,
            'produtos' => $produtos,
            'csrf_token' => $csrf_token
        ]);
    }

    /**
     * Salvar novo orçamento
     */
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $this->validarCsrfToken();

        $dados = [
            'cliente_id' => $this->getPost('cliente_id'),
            'usuario_id' => $this->usuario_logado['id'],
            'data_validade' => $this->getPost('data_validade'),
            'descricao' => $this->getPost('descricao'),
            'observacoes' => $this->getPost('observacoes'),
            'margem_lucro' => $this->getPost('margem_lucro') ?? 30,
            'desconto' => $this->getPost('desconto') ?? 0
        ];

        // Itens do orçamento
        $itens = [];
        $quantidade_itens = intval($this->getPost('quantidade_itens') ?? 0);

        for ($i = 0; $i < $quantidade_itens; $i++) {
            $item = [
                'produto_id' => $this->getPost("item_{$i}_produto_id"),
                'descricao' => $this->getPost("item_{$i}_descricao"),
                'quantidade' => floatval($this->getPost("item_{$i}_quantidade") ?? 0),
                'preco_unitario' => floatval($this->getPost("item_{$i}_preco_unitario") ?? 0)
            ];

            if (!empty($item['descricao']) && $item['quantidade'] > 0 && $item['preco_unitario'] > 0) {
                $itens[] = $item;
            }
        }

        if (empty($itens)) {
            $_SESSION['erro'] = 'Adicione pelo menos um item ao orçamento';
            $this->redirect('/orcamentos/novo');
        }

        try {
            $id = $this->orcamento_model->criarComItens($dados, $itens);
            $this->registrarLog('CRIAR', 'orcamentos', $id, "Orçamento criado");
            $_SESSION['sucesso'] = 'Orçamento criado com sucesso';
            $this->redirect("/orcamentos/editar?id={$id}");
        } catch (Exception $e) {
            $_SESSION['erro'] = 'Erro ao criar orçamento: ' . $e->getMessage();
            $this->redirect('/orcamentos/novo');
        }
    }

    /**
     * Editar orçamento
     */
    public function editar($id = null) {
        $id = $id ?? $this->getQuery('id');
        
        if (!$id) {
            $_SESSION['erro'] = 'Orçamento não informado';
            $this->redirect('/orcamentos');
        }

        $orcamento = $this->orcamento_model->findComItens($id);

        if (!$orcamento) {
            $_SESSION['erro'] = 'Orçamento não encontrado';
            $this->redirect('/orcamentos');
        }

        $clientes = $this->cliente_model->listarAtivos();
        $produtos = $this->produto_model->listarComCategoria();
        $csrf_token = $this->gerarCsrfToken();

        $this->render('orcamentos/form', [
            'orcamento' => $orcamento,
            'clientes' => $clientes,
            'produtos' => $produtos,
            'csrf_token' => $csrf_token
        ]);
    }

    /**
     * Atualizar orçamento
     */
    public function atualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $this->validarCsrfToken();

        $id = $this->getPost('id');
        
        if (!$id) {
            $_SESSION['erro'] = 'Orçamento não informado';
            $this->redirect('/orcamentos');
        }

        $dados = [
            'cliente_id' => $this->getPost('cliente_id'),
            'data_validade' => $this->getPost('data_validade'),
            'descricao' => $this->getPost('descricao'),
            'observacoes' => $this->getPost('observacoes'),
            'margem_lucro' => $this->getPost('margem_lucro') ?? 30,
            'desconto' => $this->getPost('desconto') ?? 0
        ];

        if ($this->orcamento_model->update($id, $dados)) {
            $this->registrarLog('ATUALIZAR', 'orcamentos', $id, "Orçamento atualizado");
            $_SESSION['sucesso'] = 'Orçamento atualizado com sucesso';
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar orçamento';
        }

        $this->redirect("/orcamentos/editar?id={$id}");
    }

    /**
     * Atualizar status do orçamento
     */
    public function atualizarStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $id = $this->getPost('id');
        $novo_status = $this->getPost('status');

        if (!in_array($novo_status, ['rascunho', 'enviado', 'aprovado', 'reprovado'])) {
            $_SESSION['erro'] = 'Status inválido';
            $this->redirect("/orcamentos/editar?id={$id}");
        }

        if ($this->orcamento_model->atualizarStatus($id, $novo_status)) {
            $this->registrarLog('ATUALIZAR', 'orcamentos', $id, "Status alterado para {$novo_status}");
            $_SESSION['sucesso'] = 'Status atualizado com sucesso';
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar status';
        }

        $this->redirect("/orcamentos/editar?id={$id}");
    }

    /**
     * Aprovar orçamento e preparar para ordem de produção
     */
    public function aprovar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/orcamentos');
        }

        $id = $this->getPost('id');
        
        if ($this->orcamento_model->atualizarStatus($id, 'aprovado')) {
            $this->registrarLog('ATUALIZAR', 'orcamentos', $id, "Orçamento aprovado pelo usuário");
            $_SESSION['sucesso'] = 'Orçamento aprovado com sucesso! Você já pode criar a Ordem de Produção.';
            $this->redirect('/ordens-producao/novo?orcamento_id=' . $id);
        } else {
            $_SESSION['erro'] = 'Erro ao aprovar orçamento';
            $this->redirect('/orcamentos');
        }
    }

    /**
     * Gerar PDF do orçamento
     */
    public function gerarPdf($id = null) {
        $id = $id ?? $this->getQuery('id');
        
        if (!$id) {
            die('Orçamento não informado');
        }

        $orcamento = $this->orcamento_model->findComItens($id);

        if (!$orcamento) {
            die('Orçamento não encontrado');
        }

        // Buscar dados do cliente
        $cliente = $this->cliente_model->findById($orcamento['cliente_id']);

        // Buscar configurações da empresa
        global $pdo;
        $sql = "SELECT chave, valor FROM configuracoes WHERE chave LIKE 'empresa_%'";
        $stmt = $pdo->query($sql);
        $config = [];
        foreach ($stmt->fetchAll() as $row) {
            $config[$row['chave']] = $row['valor'];
        }

        // Gerar PDF usando TCPDF
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_PAGE_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->AddPage();

        // Cabeçalho
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, $config['empresa_nome'] ?? 'Xavier Design', 0, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'CNPJ: ' . ($config['empresa_cnpj'] ?? ''), 0, 1, 'C');
        $pdf->Cell(0, 5, $config['empresa_endereco'] ?? '', 0, 1, 'C');
        $pdf->Cell(0, 5, $config['empresa_cidade'] ?? '' . ', ' . ($config['empresa_estado'] ?? ''), 0, 1, 'C');

        $pdf->Ln(5);

        // Título
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'ORÇAMENTO', 0, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Número: ' . $orcamento['numero'], 0, 1);
        $pdf->Cell(0, 5, 'Data: ' . date('d/m/Y', strtotime($orcamento['data_criacao'])), 0, 1);
        $pdf->Cell(0, 5, 'Validade: ' . date('d/m/Y', strtotime($orcamento['data_validade'])), 0, 1);

        $pdf->Ln(5);

        // Dados do cliente
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 5, 'CLIENTE', 0, 1);
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Nome: ' . ($cliente['nome'] ?? ''), 0, 1);
        $pdf->Cell(0, 5, 'CPF/CNPJ: ' . ($cliente['cpf_cnpj'] ?? ''), 0, 1);
        $pdf->Cell(0, 5, 'Email: ' . ($cliente['email'] ?? ''), 0, 1);
        $pdf->Cell(0, 5, 'Telefone: ' . ($cliente['telefone'] ?? ''), 0, 1);

        $pdf->Ln(5);

        // Itens
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(80, 7, 'Descrição', 1, 0, 'L', true);
        $pdf->Cell(30, 7, 'Quantidade', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Valor Unit.', 1, 0, 'R', true);
        $pdf->Cell(30, 7, 'Subtotal', 1, 1, 'R', true);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetFillColor(255, 255, 255);

        foreach ($orcamento['itens'] as $item) {
            $pdf->Cell(80, 6, substr($item['descricao'], 0, 40), 1, 0, 'L');
            $pdf->Cell(30, 6, number_format($item['quantidade'], 2, ',', '.'), 1, 0, 'C');
            $pdf->Cell(30, 6, 'R$ ' . number_format($item['preco_unitario'], 2, ',', '.'), 1, 0, 'R');
            $pdf->Cell(30, 6, 'R$ ' . number_format($item['subtotal'], 2, ',', '.'), 1, 1, 'R');
        }

        $pdf->Ln(3);

        // Totais
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(140, 6, 'Subtotal:', 0, 0, 'R');
        $pdf->Cell(30, 6, 'R$ ' . number_format($orcamento['subtotal'], 2, ',', '.'), 0, 1, 'R');

        if ($orcamento['desconto'] > 0) {
            $pdf->Cell(140, 6, 'Desconto:', 0, 0, 'R');
            $pdf->Cell(30, 6, 'R$ ' . number_format($orcamento['desconto'], 2, ',', '.'), 0, 1, 'R');
        }

        $pdf->Cell(140, 6, 'Margem de Lucro (' . $orcamento['margem_lucro'] . '%):', 0, 0, 'R');
        $margem_valor = $orcamento['subtotal'] * ($orcamento['margem_lucro'] / 100);
        $pdf->Cell(30, 6, 'R$ ' . number_format($margem_valor, 2, ',', '.'), 0, 1, 'R');

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(140, 8, 'TOTAL:', 1, 0, 'R', true);
        $pdf->Cell(30, 8, 'R$ ' . number_format($orcamento['total'], 2, ',', '.'), 1, 1, 'R', true);

        // Observações
        if (!empty($orcamento['observacoes'])) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 5, 'Observações:', 0, 1);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->MultiCell(0, 5, $orcamento['observacoes']);
        }

        // Rodapé
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 5, 'Documento gerado em ' . date('d/m/Y H:i:s'), 0, 1, 'C');

        // Enviar PDF
        $filename = 'orcamento_' . $orcamento['numero'] . '.pdf';
        $pdf->Output($filename, 'D');
    }

    /**
     * Deletar orçamento
     */
    public function deletar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Método não permitido');
        }

        $this->validarCsrfToken();

        $id = $this->getPost('id');

        if ($this->orcamento_model->delete($id)) {
            $this->registrarLog('DELETAR', 'orcamentos', $id, 'Orçamento deletado');
            $_SESSION['sucesso'] = 'Orçamento deletado com sucesso';
        } else {
            $_SESSION['erro'] = 'Erro ao deletar orçamento';
        }

        $this->redirect('/orcamentos');
    }
}
