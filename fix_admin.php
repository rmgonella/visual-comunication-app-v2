<?php
/**
 * Script para Corrigir/Criar Usuário Administrador
 */

require_once __DIR__ . '/index.php';

echo "<h1>Corrigindo Usuário Administrador</h1>";

$email = 'admin@xavierdesign.com';
$senha = 'admin123';
$nome = 'Administrador';
$perfil = 'admin';

try {
    $usuario_model = new Usuario();
    $usuario = $usuario_model->findByEmail($email);

    if ($usuario) {
        echo "<p>Usuário encontrado. Atualizando senha...</p>";
        $resultado = $usuario_model->atualizarSenha($usuario['id'], $senha);
        if ($resultado) {
            echo "<p style='color: green;'>✅ Senha atualizada com sucesso para: <strong>$senha</strong></p>";
        } else {
            echo "<p style='color: red;'>❌ Erro ao atualizar senha.</p>";
        }
    } else {
        echo "<p>Usuário não encontrado. Criando novo usuário...</p>";
        $id = $usuario_model->criar([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha,
            'perfil' => $perfil,
            'ativo' => 1
        ]);
        
        if ($id) {
            echo "<p style='color: green;'>✅ Usuário criado com sucesso! ID: $id</p>";
        } else {
            echo "<p style='color: red;'>❌ Erro ao criar usuário.</p>";
        }
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='login'>Ir para o Login</a></p>";
?>
