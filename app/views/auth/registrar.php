<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - <?php echo APP_NAME; ?></title>
    <link rel="icon" href="<?= APP_URL ?>assets/favicon.ico" sizes="any">
    <link rel="icon" href="<?= APP_URL ?>assets/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="<?= APP_URL ?>assets/apple-touch-icon.png">
    
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-header h1 {
            color: #007bff;
            margin-bottom: 5px;
        }
        .login-header p {
            color: #666;
            margin-bottom: 30px;
        }
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 16px;
        }
        .btn-login:hover {
            background: #0056b3;
        }
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .login-footer {
            margin-top: 25px;
            font-size: 14px;
        }
        .login-footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Xavier Design</h1>
            <p>Criar nova conta no sistema</p>
        </div>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['erro']; 
                    unset($_SESSION['erro']);
                ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo APP_URL; ?>processar-registro" method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" required placeholder="Seu nome">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Sua senha">
            </div>
            <div class="form-group">
                <label for="perfil">Perfil de Acesso</label>
                <select id="perfil" name="perfil" required>
                    <option value="admin">Administrador</option>
                    <option value="vendas">Vendas</option>
                    <option value="financeiro">Financeiro</option>
                    <option value="producao">Produção</option>
                </select>
            </div>
            <button type="submit" class="btn-login">Criar Conta</button>
        </form>

        <div class="login-footer">
            <p>Já tem uma conta? <a href="<?php echo APP_URL; ?>login">Fazer Login</a></p>
        </div>
    </div>
</body>
</html>
