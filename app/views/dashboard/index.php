<?php
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Gestão Comunicação Visual</title>
    <link rel="icon" href="<?= APP_URL ?>assets/favicon.ico" sizes="any">
    <link rel="icon" href="<?= APP_URL ?>assets/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="<?= APP_URL ?>assets/apple-touch-icon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap / Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- UI/UX Premium -->
    <style>
        :root {
            --bg-main: #020617;
            --bg-card: rgba(255,255,255,.05);
            --bg-glass: rgba(255,255,255,.07);
            --border-glass: rgba(255,255,255,.08);
            --primary: #3b82f6;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
        }

        /* ===== BASE ===== */
body {
    background: radial-gradient(circle at top, #0f172a, #020617);
    color: #e5e7eb;
}

/* ===== SIDEBAR (compact + premium) ===== */
.sidebar {
    width: 240px;
    min-height: 100vh;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(14px);
    border-right: 1px solid rgba(255,255,255,.06);
}

.sidebar h5 {
    color: #fff;
    letter-spacing: .3px;
}

.sidebar .nav-link {
    color: #9ca3af;
    border-radius: 10px;
    padding: 10px 14px;
    transition: all .25s ease;
}

.sidebar .nav-link:hover {
    background: rgba(37, 99, 235, 0.15);
    color: #fff;
}

.sidebar .nav-link.active {
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color: #fff;
    box-shadow: 0 8px 20px rgba(37,99,235,.35);
}

/* ===== TOPBAR ===== */
main h3, main h5 {
    color: #f9fafb;
}

/* ===== CARDS (GLASS) ===== */
.card {
    background: rgba(17, 25, 40, 0.75);
    backdrop-filter: blur(18px);
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,.08);
    box-shadow: 0 20px 40px rgba(0,0,0,.35);
    transition: transform .2s ease, box-shadow .2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 30px 60px rgba(0,0,0,.45);
}

.card-body {
    padding: 1.25rem 1.5rem;
}

/* ===== TABLE ===== */
.table {
    color: #e5e7eb;
}

.table thead th {
    background: rgba(255,255,255,.04);
    color: #9ca3af;
    font-weight: 500;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.table tbody tr {
    transition: background .2s ease;
}

.table tbody tr:hover {
    background: rgba(255,255,255,.03);
}

.table td {
    border-color: rgba(255,255,255,.05);
}

/* ===== BADGES ===== */
.badge {
    font-size: .7rem;
    padding: 6px 12px;
    border-radius: 999px;
    letter-spacing: .4px;
}

/* ===== BUTTONS ===== */
.btn-primary {
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    border: none;
    box-shadow: 0 10px 25px rgba(37,99,235,.4);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 15px 35px rgba(37,99,235,.55);
}

.btn-outline-primary,
.btn-outline-secondary {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

.btn-outline-primary:hover,
.btn-outline-secondary:hover {
    background: rgba(255,255,255,.08);
    border-color: transparent;
}

/* ===== DROPDOWN ===== */
.dropdown-menu {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,.08);
}

.dropdown-item {
    color: #e5e7eb;
}

.dropdown-item:hover {
    background: rgba(37,99,235,.15);
}

/* ===== ALERTS (DARK SAFE) ===== */
.alert {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
    color: #e5e7eb;
}

/* ===== SKELETON (DARK) ===== */
.skeleton {
    height: 18px;
    background: linear-gradient(
        90deg,
        rgba(255,255,255,.06) 25%,
        rgba(255,255,255,.12) 37%,
        rgba(255,255,255,.06) 63%
    );
    background-size: 400% 100%;
    animation: skeleton 1.4s infinite;
    border-radius: 8px;
    margin-bottom: 12px;
}

/* ===== LOADING BUTTON ===== */
.btn-loading::after {
    border: 2px solid rgba(255,255,255,.6);
    border-top-color: transparent;
}
/* Área principal */
.main-content {
    padding: 28px 32px;
}

/* Conteúdo interno */
.content {
    margin-top: 24px;
}

/* Título do dashboard */
.dashboard-header {
    margin-bottom: 28px;
}

/* KPIs */
.kpi-grid,
.row.g-4 {
    margin-bottom: 32px !important;
}

/* Cards em geral */
.card {
    padding: 24px;
}

/* Títulos dentro dos cards */
.card h3,
.card h4,
.card h6 {
    margin-bottom: 8px;
}

.card small {
    display: block;
    margin-bottom: 6px;
}

main {
    padding: 30px;
}


    </style>
</head>
<body>

<div class="d-flex">

    <!-- Sidebar -->
    <aside class="sidebar p-3 text-white">
        <h5 class="fw-bold mb-4">Gestão de Comunicação Visual</h5>

        <ul class="nav nav-pills flex-column gap-1">
            <li><a class="nav-link active" href="<?= APP_URL ?>dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>clientes"><i class="bi bi-people me-2"></i>Clientes</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>orcamentos"><i class="bi bi-file-text me-2"></i>Orçamentos</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>ordens-producao"><i class="bi bi-gear me-2"></i>Ordens de Produção</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>financeiro"><i class="bi bi-cash-coin me-2"></i>Financeiro</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>relatorios"><i class="bi bi-graph-up-arrow me-2"></i>Relatórios</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>configuracoes"><i class="bi bi-sliders me-2"></i>Configurações</a></li>
            <li class="mt-2"><a class="nav-link text-danger" href="<?= APP_URL ?>logout"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
        </ul>
    </aside>

    <!-- Main -->
    <main class="flex-fill mb-3">

        <!-- Topbar -->
        <div class="topbar mb-3">
            <h3 class="fw-semibold mb-3"><br>
                👋 Bom dia, <?= htmlspecialchars($usuario_logado['nome']) ?><br>
                <span>Resumo da operação hoje</span>
            </h3>

            <div class="dropdown">
                <button class="btn dropdown-toggle text-white" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($usuario_logado['nome']) ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= APP_URL ?>configuracoes">Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= APP_URL ?>logout">Sair</a></li>
                </ul>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card kpi-primary p-4">
                    <small class="text-white-50">Clientes</small>
                    <h3 class="fw-bold"><?= $total_clientes ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-success p-4">
                    <small class="text-white-50">Orçamentos Aprovados</small>
                    <h3 class="fw-bold"><?= $orcamentos_aprovados ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-warning p-4">
                    <small class="text-white-50">Ordens em Andamento</small>
                    <h3 class="fw-bold"><?= $ordens_em_andamento ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-danger p-4">
                    <small class="text-white-50">Contas Atrasadas</small>
                    <h3 class="fw-bold"><?= $contas_atrasadas ?></h3>
                </div>
            </div>
        </div>

        <!-- Financeiro -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-4">
                    <small class="text-white-50">A Receber</small>
                    <h4 class="fw-bold text-success">R$ <?= number_format($total_a_receber,2,',','.') ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <small class="text-white-50">A Pagar</small>
                    <h4 class="fw-bold text-danger">R$ <?= number_format($total_a_pagar,2,',','.') ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <small class="text-white-50">Vendas do Mês</small>
                    <h4 class="fw-bold text-primary">R$ <?= number_format($vendas_mes,2,',','.') ?></h4>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <h6 class="text-white-50">Status dos Orçamentos</h6>
                    <canvas id="chartOrcamentos"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <h6 class="text-white-50">Vendas (12 meses)</h6>
                    <canvas id="chartVendas"></canvas>
                </div>
            </div>
        </div>

    </main>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
new Chart(document.getElementById('chartOrcamentos'), {
    type: 'doughnut',
    data: {
        labels: ['Rascunho','Enviado','Aprovado','Reprovado'],
        datasets: [{
            data: <?= json_encode(array_values($grafico_orcamentos)) ?>,
            backgroundColor: ['#6b7280','#3b82f6','#22c55e','#ef4444']
        }]
    },
    options: { plugins:{ legend:{ position:'bottom' } } }
});

new Chart(document.getElementById('chartVendas'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_keys($grafico_vendas)) ?>,
        datasets: [{
            label: 'Vendas (R$)',
            data: <?= json_encode(array_values($grafico_vendas)) ?>,
            tension: .4,
            fill: true,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,.15)'
        }]
    }
});
</script>

</body>
</html>
