<?php
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /login');
    exit;
}
$isEdit = isset($orcamento);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title><?= $isEdit ? 'Editar' : 'Novo'; ?> Orçamento | Gestão Comunicação Visual</title>
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
/* ===== ITENS DO ORÇAMENTO ===== */
.item-row {
    display: grid;
    grid-template-columns: 1fr 120px 160px 48px;
    gap: 12px;
    align-items: center;
    margin-bottom: 12px;
}

.item-row input {
    background: rgba(255,255,255,.95);
    color: #111827;
}

.item-row input:focus {
    box-shadow: none;
    border-color: #3b82f6;
}

.item-row button {
    height: 42px;
    width: 42px;
    padding: 0;
}

/* Mobile */
@media (max-width: 768px) {
    .item-row {
        grid-template-columns: 1fr;
    }
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
            <li><a class="nav-link active" href="<?= APP_URL ?>orcamentos"><i class="bi bi-file-text me-2"></i>Orçamentos</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>ordens-producao"><i class="bi bi-gear me-2"></i>Produção</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>financeiro"><i class="bi bi-cash-coin me-2"></i>Financeiro</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>relatorios"><i class="bi bi-graph-up-arrow me-2"></i>Relatórios</a></li>
            <li><a class="nav-link" href="<?= APP_URL ?>configuracoes"><i class="bi bi-sliders me-2"></i>Configurações</a></li>
            <li class="mt-2"><a class="nav-link text-danger" href="<?= APP_URL ?>logout"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
        </ul>
    </aside>

    <!-- Main -->
    <main class="flex-fill p-4">

        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-semibold"><?= $isEdit ? 'Editar Orçamento' : 'Novo Orçamento'; ?></h3>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($usuario_logado['nome']); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= APP_URL ?>configuracoes">Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= APP_URL ?>logout">Sair</a></li>
                </ul>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (!empty($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
        <?php endif; ?>

        <!-- Form -->
        <div class="card">
            <div class="card-body">

                <form method="POST"
                      action="<?= $isEdit ? APP_URL.'orcamentos/atualizar' : APP_URL.'orcamentos/salvar'; ?>"
                      onsubmit="salvar(this)">

                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $orcamento['id']; ?>">
                    <?php endif; ?>

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token); ?>">
                    <input type="hidden" id="quantidade_itens" name="quantidade_itens">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-white">Cliente *</label>
                            <select name="cliente_id" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($clientes as $c): ?>
                                    <option value="<?= $c['id']; ?>" <?= $isEdit && $orcamento['cliente_id']==$c['id']?'selected':''; ?>>
                                        <?= htmlspecialchars($c['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-white">Validade *</label>
                            <input type="date" name="data_validade" class="form-control"
                                   value="<?= $orcamento['data_validade'] ?? date('Y-m-d',strtotime('+15 days')); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Descrição do Projeto</label>
                        <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($orcamento['descricao'] ?? ''); ?></textarea>
                    </div>

                    <h6 class="fw-semibold text-white mt-4">Itens</h6>

                    <!-- Cabeçalho dos itens -->
                    <div class="item-row text-white fw-semibold small mb-2 opacity-75">
                        <div>Descrição</div>
                        <div class="text-center">Quantidade</div>
                        <div class="text-center">Preço unitário</div>
                        <div></div>
                    </div>
                    
                    <div id="itens-container" class="mb-3"></div>

                    
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm mb-4"
                            onclick="addItem()">
                        <i class="bi bi-plus-lg me-1"></i>Adicionar Item
                    </button>


                    <div class="totals-box mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-white">Margem (%)</label>
                                <input type="number" id="margem" class="form-control" value="<?= $orcamento['margem_lucro'] ?? 30; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Desconto (R$)</label>
                                <input type="number" id="desconto" class="form-control" value="<?= $orcamento['desconto'] ?? 0; ?>">
                            </div>
                        </div>
                        <p class="mt-3 text-white mb-0">Total:
                            <span class="total-highlight">R$ <span id="total">0,00</span></span>
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Salvar Orçamento
                        </button>
                        <a href="<?= APP_URL ?>orcamentos" class="btn btn-outline-secondary">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ===== ELEMENTOS FIXOS ===== */
const margemInput   = document.getElementById('margem');
const descontoInput = document.getElementById('desconto');

/* ===== ITENS (EDITAR / NOVO) ===== */
let itens = <?= json_encode(
    array_values($orcamento['itens'] ?? [
        ['descricao'=>'','quantidade'=>1,'preco_unitario'=>0]
    ])
); ?>;

/* ===== RENDERIZAÇÃO ===== */
function render() {
    const container = document.getElementById('itens-container');
    container.innerHTML = '';

    itens.forEach((item, idx) => {
        container.insertAdjacentHTML('beforeend', `
            <div class="item-row">
                <input
                    class="form-control"
                    name="item_${idx}_descricao"
                    placeholder="Descrição do item"
                    value="${item.descricao}"
                    oninput="itens[${idx}].descricao=this.value"
                    required
                >

                <input
                    class="form-control"
                    type="number"
                    step="0.01"
                    name="item_${idx}_quantidade"
                    value="${item.quantidade}"
                    oninput="itens[${idx}].quantidade=parseFloat(this.value)||0; calc();"
                >

                <input
                    class="form-control"
                    type="number"
                    step="0.01"
                    name="item_${idx}_preco_unitario"
                    value="${item.preco_unitario}"
                    oninput="itens[${idx}].preco_unitario=parseFloat(this.value)||0; calc();"
                >

                <button
                    type="button"
                    class="btn btn-outline-danger"
                    onclick="rem(${idx})"
                    title="Remover item"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        `);
    });

    document.getElementById('quantidade_itens').value = itens.length;
    calc();
}

/* ===== AÇÕES ===== */
function addItem() {
    itens.push({ descricao:'', quantidade:1, preco_unitario:0 });
    render();
}

function rem(index) {
    if (itens.length === 1) return; // impede remover todos
    itens.splice(index, 1);
    render();
}

/* ===== CÁLCULO ===== */
function calc() {
    let total = itens.reduce((s, i) => {
        return s + (i.quantidade * i.preco_unitario);
    }, 0);

    const margem   = parseFloat(margemInput?.value)   || 0;
    const desconto = parseFloat(descontoInput?.value) || 0;

    total += total * (margem / 100);
    total -= desconto;

    document.getElementById('total').innerText =
        total.toFixed(2).replace('.', ',');
}

/* ===== SUBMIT ===== */
function salvar(form) {
    const btn = form.querySelector('button[type="submit"]');
    btn.classList.add('btn-loading');
}

/* ===== INIT ===== */
render();
</script>

</body>
</html>
