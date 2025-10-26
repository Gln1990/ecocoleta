<?php
// admin/login.php
session_start();
require_once '../includes/config.php';

if ($_POST) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ? AND is_admin = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');
        exit;
    } else {
        $erro = "E-mail ou senha inválidos, ou você não tem acesso administrativo!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - EcoColeta</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .admin-login-container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .admin-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .admin-header h1 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .admin-header p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="admin-login">
        <div class="admin-login-container">
            <div class="admin-header">
                <h1>⚙️ Admin EcoColeta</h1>
                <p>Painel Administrativo</p>
            </div>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?php echo $erro; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>E-mail Administrativo</label>
                    <input type="email" name="email" required placeholder="admin@ecocoleta.com">
                </div>

                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" required placeholder="Sua senha">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Acessar Painel</button>
            </form>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="../index.php" class="btn btn-secondary">Voltar ao Site</a>
            </div>

            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #eee; text-align: center;">
                <small style="color: #666;">
                    <strong>Credenciais padrão:</strong><br>
                    E-mail: admin@ecocoleta.com<br>
                    Senha: password
                </small>
            </div>
        </div>
    </div>
</body>
</html>