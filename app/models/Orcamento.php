<?php
/**
 * Model de Orçamento
 */

class Orcamento extends Model {
    protected $table = 'orcamentos';

    /**
     * Gerar número único de orçamento
     */
    public function gerarNumero() {
        $ano = date('Y');
        $mes = date('m');
        
        // Buscar último número do mês
        $sql = "SELECT numero FROM {$this->table} 
                WHERE numero LIKE ? 
                ORDER BY id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["{$ano}{$mes}%"]);
        $resultado = $stmt->fetch();
        
        if ($resultado) {
            // Extrair sequência e incrementar
            $numero = intval(substr($resultado['numero'], 6)) + 1;
        } else {
            $numero = 1;
        }
        
        return sprintf("%s%s%05d", $ano, $mes, $numero);
    }

    /**
     * Criar orçamento com itens
     */
    public function criarComItens($dados, $itens) {
        try {
            $this->pdo->beginTransaction();
            
            // Gerar número se não fornecido
            if (!isset($dados['numero'])) {
                $dados['numero'] = $this->gerarNumero();
            }
            
            // Inserir orçamento
            $orcamento_id = $this->insert($dados);
            
            // Inserir itens
            $sql_item = "INSERT INTO orcamento_itens 
                        (orcamento_id, produto_id, descricao, quantidade, preco_unitario, subtotal) 
                        VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_item = $this->pdo->prepare($sql_item);
            
            $subtotal_total = 0;
            foreach ($itens as $item) {
                $subtotal = $item['quantidade'] * $item['preco_unitario'];
                $subtotal_total += $subtotal;
                
                $stmt_item->execute([
                    $orcamento_id,
                    $item['produto_id'] ?? null,
                    $item['descricao'],
                    $item['quantidade'],
                    $item['preco_unitario'],
                    $subtotal
                ]);
            }
            
            // Calcular total com margem de lucro
            $margem = $dados['margem_lucro'] ?? 30;
            $desconto = $dados['desconto'] ?? 0;
            $total = ($subtotal_total - $desconto) * (1 + ($margem / 100));
            
            // Atualizar orçamento com totais
            $this->update($orcamento_id, [
                'subtotal' => $subtotal_total,
                'total' => $total
            ]);
            
            $this->pdo->commit();
            return $orcamento_id;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Buscar orçamento com itens
     */
    public function findComItens($id) {
        $orcamento = $this->findById($id);
        
        if ($orcamento) {
            $sql = "SELECT * FROM orcamento_itens WHERE orcamento_id = ? ORDER BY id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $orcamento['itens'] = $stmt->fetchAll();
        }
        
        return $orcamento;
    }

    /**
     * Listar orçamentos com cliente
     */
    public function listarComCliente() {
        $sql = "SELECT o.*, c.nome as cliente_nome, c.email as cliente_email 
                FROM {$this->table} o 
                LEFT JOIN clientes c ON o.cliente_id = c.id 
                ORDER BY o.data_criacao DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Contar por status
     */
    public function contarPorStatus($status) {
        return $this->count("status = '{$status}'");
    }

    /**
     * Buscar orçamentos aprovados
     */
    public function findAprovados() {
        return $this->findAll("status = 'aprovado'", 'data_criacao DESC');
    }

    /**
     * Atualizar status
     */
    public function atualizarStatus($id, $novo_status) {
        return $this->update($id, ['status' => $novo_status]);
    }

    /**
     * Calcular total de vendas (orçamentos aprovados)
     */
    public function totalVendas($data_inicio = null, $data_fim = null) {
        $sql = "SELECT SUM(total) as total FROM {$this->table} WHERE status = 'aprovado'";
        
        if ($data_inicio && $data_fim) {
            $sql .= " AND DATE(data_criacao) BETWEEN ? AND ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data_inicio, $data_fim]);
        } else {
            $stmt = $this->pdo->query($sql);
        }
        
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }
}
