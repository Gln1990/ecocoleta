<?php
// dashboard.php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

verificarAutenticacao();

$usuario = $_SESSION['usuario'];

// Buscar coletas do usuário
if ($usuario['tipo'] == 'morador') {
    $sql = "SELECT c.*, u.nome as coletor_nome 
            FROM coletas c 
            LEFT JOIN usuarios u ON c.coletor_id = u.id 
            WHERE c.morador_id = ? 
            ORDER BY c.data_solicitacao DESC";
} else {
    $sql = "SELECT c.*, u.nome as morador_nome 
            FROM coletas c 
            JOIN usuarios u ON c.morador_id = u.id 
            WHERE c.coletor_id = ? OR c.coletor_id IS NULL
            ORDER BY c.data_solicitacao DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario['id']]);
$coletas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EcoColeta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <h1>Olá, <?php echo $usuario['nome']; ?>!</h1>
            <p>Bem-vindo ao seu painel EcoColeta</p>
        </div>

        <div class="dashboard-actions">
            <?php if ($usuario['tipo'] == 'morador'): ?>
                <a href="solicitar_coleta.php" class="btn btn-primary">Solicitar Coleta</a>
            <?php else: ?>
                <a href="agendar_coleta.php" class="btn btn-primary">Agendar Coletas</a>
            <?php endif; ?>
            <a href="perfil.php" class="btn btn-secondary">Meu Perfil</a>
        </div>

        <div class="coletas-list">
            <h2>Minhas Coletas</h2>
            
            <?php if (empty($coletas)): ?>
                <p>Nenhuma coleta encontrada.</p>
            <?php else: ?>
                <?php foreach ($coletas as $coleta): ?>
                    <div class="coleta-card">
                        <div class="coleta-info">
                            <h3>Coleta #<?php echo $coleta['id']; ?></h3>
                            <p><strong>Endereço:</strong> <?php echo $coleta['endereco']; ?></p>
                            <p><strong>Material:</strong> <?php echo $coleta['material']; ?></p>
                            <p><strong>Status:</strong> 
                                <span class="status-<?php echo $coleta['status']; ?>">
                                    <?php echo ucfirst($coleta['status']); ?>
                                </span>
                            </p>
                            <?php if ($coleta['data_agendada']): ?>
                                <p><strong>Data Agendada:</strong> 
                                    <?php echo date('d/m/Y H:i', strtotime($coleta['data_agendada'])); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script src="js/dashboard.js"></script>
</body>
</html>