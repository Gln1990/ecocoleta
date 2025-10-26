<?php
// cadastro.php
session_start();
require_once 'includes/config.php';

if ($_POST) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $cep = $_POST['cep'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    try {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo, telefone, endereco, cep, cidade, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $senha, $tipo, $telefone, $endereco, $cep, $cidade, $estado]);
        
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
        header('Location: login.php');
        exit;
    } catch(PDOException $e) {
        $erro = "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - EcoColeta</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="form-container">
            <h2>Criar Conta</h2>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?php echo $erro; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" required>
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" required>
                </div>

                <div class="form-group">
                    <label>Tipo de Usuário</label>
                    <select name="tipo" required>
                        <option value="morador">Morador</option>
                        <option value="coletor">Coletor Individual</option>
                        <option value="empresa">Empresa de Coleta</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" name="telefone" required>
                </div>

                <div class="form-group">
                    <label>Endereço</label>
                    <input type="text" name="endereco" required>
                </div>

                <div class="form-group">
                    <label>CEP</label>
                    <input type="text" name="cep" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" name="cidade" required>
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" name="estado" maxlength="2" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>

            <p>Já tem conta? <a href="login.php">Faça login</a></p>
        </div>
    </main>
</body>
</html>