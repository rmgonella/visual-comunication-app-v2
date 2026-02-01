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
    <title><?= isset($ordem) ? 'Ordem '.$ordem['numero'] : 'Nova Ordem de Produção' ?> | Gestão Comunicação Visual</title>
    <link rel="icon" href="<?= APP_URL ?>assets/favicon.ico" sizes="any">
    <link rel="icon" href="<?= APP_URL ?>assets/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="<?= APP_URL ?>assets/apple-touch-icon.png">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap / Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- 🔥 MESMO CSS DO SISTEMA -->
    <style>
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

    </style>
</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->
    <aside class="sidebar p-3 text-white">
        <h5 class="fw-bold mb-4">Gestão de Comunicação Visual</h5>

        <ul class="nav nav-pills flex-column gap-1">
            <li><a class="nav-link" href="<?= APP_URL ?>dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>clientes"><i class="bi bi-people me-2"></i>Clientes</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>orcamentos"><i class="bi bi-file-text me-2"></i>Orçamentos</a></li>
            <li><a class="nav-link active" href="<?= APP_URL ?>ordens-producao"><i class="bi bi-gear me-2"></i>Ordens de Produção</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>financeiro"><i class="bi bi-cash-coin me-2"></i>Financeiro</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>relatorios"><i class="bi bi-graph-up-arrow me-2"></i>Relatórios</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>configuracoes"><i class="bi bi-sliders me-2"></i>Configurações</a></li>
            <li class="mt-2">
                <a class="nav-link text-danger" href="<?= APP_URL ?>logout">
                    <i class="bi bi-box-arrow-right me-2"></i>Sair
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main -->
    <main class="flex-fill p-4">

        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-semibold mb-0">
                <?= isset($ordem) ? 'Ordem '.$ordem['numero'] : 'Nova Ordem de Produção' ?><br>
                <span class="text-white fs-6">Gestão e acompanhamento</span>
            </h3>

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

        <!-- Alerts -->
        <?php if (!empty($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
            <?php unset($_SESSION['sucesso']) ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
            <?php unset($_SESSION['erro']) ?>
        <?php endif; ?>

        <!-- Card -->
        <div class="card">
            <div class="card-body p-4 p-md-5">

                <form method="POST"
                      action="<?= isset($ordem) ? APP_URL.'ordens-producao/atualizar' : APP_URL.'ordens-producao/salvar' ?>">

                    <?php if (isset($ordem)): ?>
                        <input type="hidden" name="id" value="<?= $ordem['id'] ?>">
                    <?php endif; ?>

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

                    <!-- Informações Gerais -->
                    <h6 class="fw-semibold text-white mb-4">Informações Gerais</h6>

                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <label class="form-label text-white">Orçamento</label>
                            <?php if (isset($ordem)): ?>
                                <input class="form-control" disabled
                                       value="<?= htmlspecialchars($ordem['orcamento_numero'].' - '.$ordem['cliente_nome']) ?>">
                                <input type="hidden" name="orcamento_id" value="<?= $ordem['orcamento_id'] ?>">
                            <?php else: ?>
                                <select name="orcamento_id" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($orcamentos as $orc): ?>
                                        <option value="<?= $orc['id'] ?>">
                                            <?= htmlspecialchars($orc['numero'].' - '.$orc['cliente_nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-white">Status Geral</label>
                            <select name="status" class="form-select" required>
                                <option value="criacao" <?= ($ordem['status'] ?? '')=='criacao'?'selected':'' ?>>Criação</option>
                                <option value="producao" <?= ($ordem['status'] ?? '')=='producao'?'selected':'' ?>>Produção</option>
                                <option value="instalacao" <?= ($ordem['status'] ?? '')=='instalacao'?'selected':'' ?>>Instalação</option>
                                <option value="finalizado" <?= ($ordem['status'] ?? '')=='finalizado'?'selected':'' ?>>Finalizado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Prazos -->
                    <h6 class="fw-semibold text-white mb-4">Prazos</h6>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label text-white">Entrega Prevista</label>
                            <input type="date" name="data_prevista" class="form-control"
                                   value="<?= $ordem['data_prevista'] ?? '' ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-white">Data de Início</label>
                            <input class="form-control" disabled
                                   value="<?= isset($ordem) ? date('d/m/Y',strtotime($ordem['data_inicio'])) : date('d/m/Y') ?>">
                        </div>
                    </div>

                    <!-- Etapas -->
                    <?php if (!empty($ordem['etapas'])): ?>
                        <h6 class="fw-semibold mb-4">Etapas de Produção</h6>

                        <?php foreach ($ordem['etapas'] as $etapa): ?>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <strong><?= htmlspecialchars($etapa['etapa']) ?></strong>
                                <select name="etapas[<?= $etapa['id'] ?>]" class="form-select w-auto">
                                    <option value="pendente" <?= $etapa['status']=='pendente'?'selected':'' ?>>Pendente</option>
                                    <option value="em_andamento" <?= $etapa['status']=='em_andamento'?'selected':'' ?>>Em andamento</option>
                                    <option value="concluida" <?= $etapa['status']=='concluida'?'selected':'' ?>>Concluída</option>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Observações -->
                    <h6 class="fw-semibold mt-5 text-white mb-3">Observações</h6>
                    <textarea name="observacoes" rows="4" class="form-control mb-4"><?= htmlspecialchars($ordem['observacoes'] ?? '') ?></textarea>

                    <!-- Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= APP_URL ?>ordens-producao" class="btn btn-outline-secondary">Cancelar</a>
                        <button class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Salvar
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
