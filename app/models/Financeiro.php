<?php
/**
 * Model de Financeiro
 */

class Financeiro extends Model {
    protected $table = 'contas_receber';

    /**
     * Listar contas a receber
     */
    public function listarContasReceber($status = null) {
        $sql = "SELECT cr.*, c.nome as cliente_nome 
                FROM contas_receber cr 
                LEFT JOIN clientes c ON cr.cliente_id = c.id";
        
        if ($status) {
            $sql .= " WHERE cr.status = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status]);
        } else {
            $stmt = $this->pdo->query($sql);
        }
        
        return $stmt->fetchAll();
    }

    /**
     * Listar contas a pagar
     */
    public function listarContasPagar($status = null) {
        $sql = "SELECT cp.*, f.nome as fornecedor_nome 
                FROM contas_pagar cp 
                LEFT JOIN fornecedores f ON cp.fornecedor_id = f.id";
        
        if ($status) {
            $sql .= " WHERE cp.status = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status]);
        } else {
            $stmt = $this->pdo->query($sql);
        }
        
        return $stmt->fetchAll();
    }

    /**
     * Calcular total a receber
     */
    public function totalAReceber($status = 'pendente') {
        $sql = "SELECT SUM(valor) as total FROM contas_receber WHERE status = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$status]);
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }

    /**
     * Calcular total a pagar
     */
    public function totalAPagar($status = 'pendente') {
        $sql = "SELECT SUM(valor) as total FROM contas_pagar WHERE status = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$status]);
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }

    /**
     * Calcular fluxo de caixa
     */
    public function fluxoCaixa($data_inicio, $data_fim) {
        $sql = "SELECT 
                    SUM(CASE WHEN status = 'pago' THEN valor ELSE 0 END) as recebido,
                    SUM(CASE WHEN status = 'pendente' AND data_vencimento <= ? THEN valor ELSE 0 END) as atrasado_receber
                FROM contas_receber 
                WHERE DATE(data_vencimento) BETWEEN ? AND ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([date('Y-m-d'), $data_inicio, $data_fim]);
        $receber = $stmt->fetch();
        
        $sql = "SELECT 
                    SUM(CASE WHEN status = 'pago' THEN valor ELSE 0 END) as pago,
                    SUM(CASE WHEN status = 'pendente' AND data_vencimento <= ? THEN valor ELSE 0 END) as atrasado_pagar
                FROM contas_pagar 
                WHERE DATE(data_vencimento) BETWEEN ? AND ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([date('Y-m-d'), $data_inicio, $data_fim]);
        $pagar = $stmt->fetch();
        
        return [
            'recebido' => $receber['recebido'] ?? 0,
            'atrasado_receber' => $receber['atrasado_receber'] ?? 0,
            'pago' => $pagar['pago'] ?? 0,
            'atrasado_pagar' => $pagar['atrasado_pagar'] ?? 0,
            'saldo' => ($receber['recebido'] ?? 0) - ($pagar['pago'] ?? 0)
        ];
    }

    /**
     * Registrar pagamento
     */
    public function registrarPagamento($id, $data_pagamento, $forma_pagamento) {
        $dados = [
            'status' => 'pago',
            'data_pagamento' => $data_pagamento,
            'forma_pagamento' => $forma_pagamento
        ];
        
        return $this->update($id, $dados);
    }

    /**
     * Contar contas atrasadas
     */
    public function contarAtrasadas() {
        $sql = "SELECT COUNT(*) as total FROM contas_receber 
                WHERE status = 'pendente' AND data_vencimento < CURDATE()";
        $stmt = $this->pdo->query($sql);
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }
}
