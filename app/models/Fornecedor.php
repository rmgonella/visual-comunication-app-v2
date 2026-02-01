<?php
/**
 * Model de Fornecedor
 */

class Fornecedor extends Model {
    protected $table = 'fornecedores';

    /**
     * Listar fornecedores ativos
     */
    public function listarAtivos() {
        return $this->findAll('ativo = 1', 'nome ASC');
    }

    /**
     * Buscar por CNPJ
     */
    public function findByCnpj($cnpj) {
        $sql = "SELECT * FROM {$this->table} WHERE cnpj = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cnpj]);
        return $stmt->fetch();
    }

    /**
     * Buscar por email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}
