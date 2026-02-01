<?php
/**
 * Model de Cliente
 */

class Cliente extends Model {
    protected $table = 'clientes';

    /**
     * Listar clientes ativos
     */
    public function listarAtivos() {
        return $this->findAll('ativo = 1', 'nome ASC');
    }

    /**
     * Buscar por CPF/CNPJ
     */
    public function findByCpfCnpj($cpf_cnpj) {
        $sql = "SELECT * FROM {$this->table} WHERE cpf_cnpj = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cpf_cnpj]);
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

    /**
     * Contar clientes por tipo
     */
    public function contarPorTipo($tipo) {
        return $this->count("tipo = '{$tipo}' AND ativo = 1");
    }

    /**
     * Buscar clientes por cidade
     */
    public function findByCidade($cidade) {
        return $this->findAll("cidade = '{$cidade}' AND ativo = 1", 'nome ASC');
    }
}
