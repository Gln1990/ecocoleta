<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

verificarAutenticacao();
verificarAdmin();

// Suas consultas ao banco aqui...
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - EcoColeta</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <h1>Painel Administrativo</h1>
        <p>Bem-vindo ao painel de administração!</p>
        
        <div class="admin-actions">
            <a href="<?php echo BASE_URL; ?>/admin/usuarios.php" class="btn btn-primary">Gerenciar Usuários</a>
            <a href="<?php echo BASE_URL; ?>/admin/coletas.php" class="btn btn-primary">Gerenciar Coletas</a>
            <a href="<?php echo BASE_URL; ?>/admin/configuracoes.php" class="btn btn-secondary">Configurações</a>
        </div>
    </main>
</body>
</html>