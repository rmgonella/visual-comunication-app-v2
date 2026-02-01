<?php
/**
 * Model de Produto
 */

class Produto extends Model {
    protected $table = 'produtos';

    /**
     * Listar produtos ativos
     */
    public function listarAtivos() {
        return $this->findAll('ativo = 1', 'nome ASC');
    }

    /**
     * Buscar produtos por categoria
     */
    public function findByCategoria($categoria_id) {
        $sql = "SELECT * FROM {$this->table} WHERE categoria_id = ? AND ativo = 1 ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$categoria_id]);
        return $stmt->fetchAll();
    }

    /**
     * Buscar produto com categoria
     */
    public function findComCategoria($id) {
        $sql = "SELECT p.*, c.nome as categoria_nome FROM {$this->table} p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Listar todos com categoria
     */
    public function listarComCategoria() {
        $sql = "SELECT p.*, c.nome as categoria_nome FROM {$this->table} p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.ativo = 1 
                ORDER BY c.nome, p.nome";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
