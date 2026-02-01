<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestão para Comnunicção visual</title>
    <link rel="icon" href="<?= APP_URL ?>assets/favicon.ico" sizes="any">
    <link rel="icon" href="<?= APP_URL ?>assets/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="<?= APP_URL ?>assets/apple-touch-icon.png">

    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #0d1528;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            margin: 0;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><b>Comunicação Visual</b></h1>
            <p><b>Sistema de Gestão</b></p>
        </div>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['erro']); ?>
            </div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['sucesso']); ?>
            </div>
            <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>

        <form method="POST" action="<?php echo APP_URL; ?>autenticar">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="login-footer">
            <p><a href="<?php echo APP_URL; ?>registrar"><strong>Criar nova conta</strong></a></p>
            <p><a href="<?php echo APP_URL; ?>recuperar-senha">Esqueceu sua senha?</a></p>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #999; font-size: 0.85rem;">
            <p><strong>Credenciais de Teste:</strong></p>
            <p>Email: freelaforever@gmail.com</p>
            <p>Senha: 123456</p>
        </div>
    </div>
</body>
</html>
