<?php
/**
 * Model de Usuário
 */

class Usuario extends Model {
    protected $table = 'usuarios';

    /**
     * Buscar usuário por email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? AND ativo = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Validar credenciais de login
     */
    public function validarLogin($email, $senha) {
        $usuario = $this->findByEmail($email);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Atualizar último acesso
            $this->update($usuario['id'], ['ultimo_acesso' => date('Y-m-d H:i:s')]);
            return $usuario;
        }
        
        return false;
    }

    /**
     * Criar novo usuário com senha hasheada
     */
    public function criar($data) {
        if (isset($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_HASH_ALGO, PASSWORD_HASH_OPTIONS);
        }
        
        return $this->insert($data);
    }

    /**
     * Atualizar senha
     */
    public function atualizarSenha($id, $nova_senha) {
        $senha_hash = password_hash($nova_senha, PASSWORD_HASH_ALGO, PASSWORD_HASH_OPTIONS);
        return $this->update($id, ['senha' => $senha_hash]);
    }

    /**
     * Listar usuários ativos
     */
    public function listarAtivos() {
        return $this->findAll('ativo = 1', 'nome ASC');
    }

    /**
     * Verificar permissão
     */
    public function temPermissao($usuario_id, $perfil_requerido) {
        $usuario = $this->findById($usuario_id);
        
        if (!$usuario) {
            return false;
        }
        
        // Admin tem acesso a tudo
        if ($usuario['perfil'] === 'admin') {
            return true;
        }
        
        // Verificar se o perfil do usuário é o requerido
        return $usuario['perfil'] === $perfil_requerido;
    }
}
