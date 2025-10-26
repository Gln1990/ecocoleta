<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

verificarAutenticacao();
verificarAdmin();

// Buscar coletas
$sql = "SELECT c.*, u.nome as morador_nome FROM coletas c 
        JOIN usuarios u ON c.morador_id = u.id 
        ORDER BY c.data_solicitacao DESC";
$coletas = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coletas - Admin EcoColeta</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <h1>Gerenciar Coletas</h1>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Morador</th>
                        <th>Endereço</th>
                        <th>Material</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coletas as $coleta): ?>
                    <tr>
                        <td><?php echo $coleta['id']; ?></td>
                        <td><?php echo htmlspecialchars($coleta['morador_nome']); ?></td>
                        <td><?php echo htmlspecialchars($coleta['endereco']); ?></td>
                        <td><?php echo htmlspecialchars($coleta['material']); ?></td>
                        <td><?php echo htmlspecialchars($coleta['status']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($coleta['data_solicitacao'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>