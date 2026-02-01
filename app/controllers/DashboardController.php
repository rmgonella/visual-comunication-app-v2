<?php
/**
 * Controller do Dashboard
 */

class DashboardController extends Controller {
    
    /**
     * Exibir dashboard principal
     */
    public function index() {
        // Modelos
        $orcamento_model = new Orcamento();
        $ordem_model = new OrdemProducao();
        $financeiro_model = new Financeiro();
        $cliente_model = new Cliente();

        // Dados para o dashboard
        $dados = [];

        // KPIs
        $dados['total_clientes'] = $cliente_model->count('ativo = 1');
        $dados['orcamentos_pendentes'] = $orcamento_model->contarPorStatus('enviado');
        $dados['orcamentos_aprovados'] = $orcamento_model->contarPorStatus('aprovado');
        $dados['ordens_em_andamento'] = $ordem_model->contarEmAndamento();

        // Financeiro
        $dados['total_a_receber'] = $financeiro_model->totalAReceber('pendente');
        $dados['total_a_pagar'] = $financeiro_model->totalAPagar('pendente');
        $dados['contas_atrasadas'] = $financeiro_model->contarAtrasadas();

        // Vendas do mês
        $data_inicio = date('Y-m-01');
        $data_fim = date('Y-m-t');
        $dados['vendas_mes'] = $orcamento_model->totalVendas($data_inicio, $data_fim);

        // Últimos orçamentos
        $todos_orcamentos = $orcamento_model->listarComCliente();
        $dados['ultimos_orcamentos'] = array_slice($todos_orcamentos, 0, 5);

        // Ordens em produção
        $todas_ordens = $ordem_model->listarComOrcamento();
        $dados['ordens_producao'] = array_filter($todas_ordens, function($ordem) {
            return in_array($ordem['status'], ['criacao', 'producao', 'instalacao']);
        });
        $dados['ordens_producao'] = array_slice($dados['ordens_producao'], 0, 5);

        // Gráfico de vendas (últimos 12 meses)
        $dados['grafico_vendas'] = $this->gerarGraficoVendas();

        // Gráfico de status de orçamentos
        $dados['grafico_orcamentos'] = [
            'rascunho' => $orcamento_model->contarPorStatus('rascunho'),
            'enviado' => $orcamento_model->contarPorStatus('enviado'),
            'aprovado' => $orcamento_model->contarPorStatus('aprovado'),
            'reprovado' => $orcamento_model->contarPorStatus('reprovado')
        ];

        $this->render('dashboard/index', $dados);
    }

    /**
     * Gerar dados para gráfico de vendas
     */
    private function gerarGraficoVendas() {
        $orcamento_model = new Orcamento();
        $dados = [];

        for ($i = 11; $i >= 0; $i--) {
            $data = date('Y-m-01', strtotime("-{$i} months"));
            $mes = date('m/Y', strtotime($data));
            $data_inicio = $data;
            $data_fim = date('Y-m-t', strtotime($data));
            
            $total = $orcamento_model->totalVendas($data_inicio, $data_fim);
            $dados[$mes] = floatval($total);
        }

        return $dados;
    }

    /**
     * Obter dados para gráficos via AJAX
     */
    public function obterDadosGraficos() {
        header('Content-Type: application/json');

        $orcamento_model = new Orcamento();
        $ordem_model = new OrdemProducao();

        $dados = [
            'orcamentos' => [
                'rascunho' => $orcamento_model->contarPorStatus('rascunho'),
                'enviado' => $orcamento_model->contarPorStatus('enviado'),
                'aprovado' => $orcamento_model->contarPorStatus('aprovado'),
                'reprovado' => $orcamento_model->contarPorStatus('reprovado')
            ],
            'ordens' => [
                'criacao' => $ordem_model->contarPorStatus('criacao'),
                'producao' => $ordem_model->contarPorStatus('producao'),
                'instalacao' => $ordem_model->contarPorStatus('instalacao'),
                'finalizado' => $ordem_model->contarPorStatus('finalizado')
            ]
        ];

        echo json_encode($dados);
        exit;
    }
}
