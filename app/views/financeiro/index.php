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
<title>Financeiro | Gestão Comunicação Visual</title>
<link rel="icon" href="<?= APP_URL ?>assets/favicon.ico" sizes="any">
<link rel="icon" href="<?= APP_URL ?>assets/favicon.png" type="image/png">
<link rel="apple-touch-icon" href="<?= APP_URL ?>assets/apple-touch-icon.png">

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* ===== BASE ===== */
body {
    background: radial-gradient(circle at top, #0f172a, #020617);
    color: #e5e7eb;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 240px;
    min-height: 100vh;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(14px);
    border-right: 1px solid rgba(255,255,255,.06);
}

.sidebar .nav-link {
    color: #9ca3af;
    border-radius: 10px;
    padding: 10px 14px;
    transition: .25s;
}

.sidebar .nav-link:hover {
    background: rgba(37, 99, 235, .15);
    color: #fff;
}

.sidebar .nav-link.active {
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color: #fff;
    box-shadow: 0 8px 20px rgba(37,99,235,.35);
}

/* ===== CARDS ===== */
.card {
    background: rgba(17, 25, 40, .75);
    backdrop-filter: blur(18px);
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,.08);
    box-shadow: 0 20px 40px rgba(0,0,0,.35);
}

/* ===== TABLE ===== */
.table {
    color: #e5e7eb;
}

.table thead th {
    background: rgba(255,255,255,.04);
    color: #9ca3af;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.table tbody tr:hover {
    background: rgba(255,255,255,.03);
}

/* ===== BADGES ===== */
.badge {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: .7rem;
}

/* ===== BUTTONS ===== */
.btn-primary {
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    border: none;
    box-shadow: 0 10px 25px rgba(37,99,235,.4);
}

.btn-outline-secondary {
    border-color: rgba(255,255,255,.15);
    color: #e5e7eb;
}

/* ===== SKELETON ===== */
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

@keyframes skeleton {
    0% { background-position: 100% 0; }
    100% { background-position: -100% 0; }
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->
    <aside class="sidebar p-3">
        <h5 class="fw-bold mb-4">Gestão de Comunicação Visual</h5>

        <ul class="nav nav-pills flex-column gap-1">
            <li><a class="nav-link" href="<?= APP_URL ?>dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>clientes"><i class="bi bi-people me-2"></i>Clientes</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>orcamentos"><i class="bi bi-file-text me-2"></i>Orçamentos</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>ordens-producao"><i class="bi bi-gear me-2"></i>Ordens</a></li>
            <li><a class="nav-link active" href="<?= APP_URL ?>financeiro"><i class="bi bi-cash-coin me-2"></i>Financeiro</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>relatorios"><i class="bi bi-graph-up-arrow me-2"></i>Relatórios</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>configuracoes"><i class="bi bi-sliders me-2"></i>Configurações</a></li>
            <li class="mt-2"><a class="nav-link text-danger" href="<?= APP_URL ?>logout"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
        </ul>
    </aside>

    <!-- Main -->
    <main class="flex-fill p-4">

        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-semibold mb-0">Financeiro</h3>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($usuario_logado['nome']) ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= APP_URL ?>configuracoes">Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= APP_URL ?>logout">Sair</a></li>
                </ul>
            </div>
        </div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Gestão Financeira</h5>
            <a href="<?= APP_URL ?>financeiro/novo" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Novo Lançamento
            </a>
        </div>

        <!-- CONTAS A RECEBER -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="text-white mb-3">💸 Contas a Receber</h6>

                <?php if (empty($contas_receber)): ?>
                    <div class="text-white mb-3">Nenhuma conta a receber.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th class="text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($contas_receber as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['descricao'] ?? 'Orçamento '.$c['orcamento_id']) ?></td>
                                    <td>R$ <?= number_format($c['valor'],2,',','.') ?></td>
                                    <td><?= date('d/m/Y', strtotime($c['data_vencimento'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $c['status']=='pago'?'success':'warning' ?>">
                                            <?= ucfirst($c['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="<?= APP_URL ?>financeiro/editar?id=<?= $c['id'] ?>&tipo=receita">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- CONTAS A PAGAR -->
        <div class="card">
            <div class="card-body">
                <h6 class="text-white mb-3">📉 Contas a Pagar</h6>

                <?php if (empty($contas_pagar)): ?>
                    <div class="text-white mb-3">Nenhuma conta a pagar.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th class="text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($contas_pagar as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['descricao']) ?></td>
                                    <td>R$ <?= number_format($c['valor'],2,',','.') ?></td>
                                    <td><?= date('d/m/Y', strtotime($c['data_vencimento'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $c['status']=='pago'?'success':'warning' ?>">
                                            <?= ucfirst($c['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="<?= APP_URL ?>financeiro/editar?id=<?= $c['id'] ?>&tipo=despesa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
