<?php
// admin/configuracoes.php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

verificarAutenticacao();
verificarAdmin();

// Lógica para configurações futuras
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Admin EcoColeta</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <h1>⚙️ Configurações do Sistema</h1>
            <p>Configurações administrativas do EcoColeta</p>
        </div>

        <div class="configuracoes-grid">
            <div class="config-card">
                <h3>📧 Configurações de E-mail</h3>
                <p>Configure o servidor de e-mail para notificações</p>
                <button class="btn btn-primary" disabled>Em Breve</button>
            </div>
            
            <div class="config-card">
                <h3>🔐 Segurança</h3>
                <p>Configurações de segurança e permissões</p>
                <button class="btn btn-primary" disabled>Em Breve</button>
            </div>
            
            <div class="config-card">
                <h3>📊 Relatórios</h3>
                <p>Gerar relatórios do sistema</p>
                <button class="btn btn-primary" disabled>Em Breve</button>
            </div>
            
            <div class="config-card">
                <h3>🔄 Backup</h3>
                <p>Backup do banco de dados</p>
                <button class="btn btn-primary" disabled>Em Breve</button>
            </div>
        </div>
    </main>
</body>
</html>