<?php
/**
 * Model de Ordem de Produção
 */

class OrdemProducao extends Model {
    protected $table = 'ordens_producao';

    /**
     * Gerar número único de ordem
     */
    public function gerarNumero() {
        $ano = date('Y');
        
        // Buscar última ordem do ano
        $sql = "SELECT numero FROM {$this->table} 
                WHERE numero LIKE ? 
                ORDER BY id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["OP{$ano}%"]);
        $resultado = $stmt->fetch();
        
        if ($resultado) {
            $numero = intval(substr($resultado['numero'], 4)) + 1;
        } else {
            $numero = 1;
        }
        
        return sprintf("OP%s%05d", $ano, $numero);
    }

    /**
     * Criar ordem a partir de orçamento
     */
    public function criarDeOrcamento($orcamento_id, $usuario_responsavel, $observacoes = '') {
        try {
            $this->pdo->beginTransaction();
            
            $dados = [
                'numero' => $this->gerarNumero(),
                'orcamento_id' => $orcamento_id,
                'usuario_responsavel' => $usuario_responsavel,
                'status' => 'criacao',
                'data_inicio' => date('Y-m-d'),
                'observacoes' => $observacoes
            ];
            
            $ordem_id = $this->insert($dados);
            
            // Criar etapas padrão
            $etapas = ['Criação', 'Produção', 'Instalação', 'Finalizado'];
            $sql_etapa = "INSERT INTO ordem_etapas (ordem_id, etapa, status) VALUES (?, ?, 'pendente')";
            $stmt_etapa = $this->pdo->prepare($sql_etapa);
            
            foreach ($etapas as $etapa) {
                $stmt_etapa->execute([$ordem_id, $etapa]);
            }
            
            $this->pdo->commit();
            return $ordem_id;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Buscar ordem com etapas
     */
    public function findComEtapas($id) {
        $ordem = $this->findById($id);
        
        if ($ordem) {
            $sql = "SELECT * FROM ordem_etapas WHERE ordem_id = ? ORDER BY id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $ordem['etapas'] = $stmt->fetchAll();
        }
        
        return $ordem;
    }

    /**
     * Listar ordens com orçamento
     */
    public function listarComOrcamento() {
        $sql = "SELECT o.*, oc.numero as orcamento_numero, c.nome as cliente_nome 
                FROM {$this->table} o 
                LEFT JOIN orcamentos oc ON o.orcamento_id = oc.id 
                LEFT JOIN clientes c ON oc.cliente_id = c.id 
                ORDER BY o.data_inicio DESC";
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
     * Atualizar status
     */
    public function atualizarStatus($id, $novo_status) {
        return $this->update($id, ['status' => $novo_status]);
    }

    /**
     * Atualizar etapa
     */
    public function atualizarEtapa($etapa_id, $status, $usuario_id = null) {
        $dados = [
            'status' => $status,
            'data_conclusao' => $status === 'concluida' ? date('Y-m-d H:i:s') : null
        ];
        
        if ($usuario_id) {
            $dados['usuario_responsavel'] = $usuario_id;
        }
        
        $sql = "UPDATE ordem_etapas SET " . 
               implode(', ', array_map(function($k) { return "$k = ?"; }, array_keys($dados))) . 
               " WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $valores = array_values($dados);
        $valores[] = $etapa_id;
        
        return $stmt->execute($valores);
    }

    /**
     * Contar ordens em andamento
     */
    public function contarEmAndamento() {
        return $this->count("status IN ('criacao', 'producao', 'instalacao')");
    }
}
