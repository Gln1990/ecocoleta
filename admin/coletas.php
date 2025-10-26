<?php
// admin/coletas.php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

verificarAutenticacao();
verificarAdmin();

if ($_SESSION['usuario']['tipo'] != 'empresa') {
    header('Location: ../dashboard.php');
    exit;
}

// Filtros
$status = $_GET['status'] ?? '';
$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim = $_GET['data_fim'] ?? '';

// Construir query com filtros
$sql = "SELECT c.*, u.nome as morador_nome, u2.nome as coletor_nome 
        FROM coletas c 
        JOIN usuarios u ON c.morador_id = u.id 
        LEFT JOIN usuarios u2 ON c.coletor_id = u2.id 
        WHERE 1=1";
$params = [];

if ($status) {
    $sql .= " AND c.status = ?";
    $params[] = $status;
}

if ($data_inicio) {
    $sql .= " AND DATE(c.data_solicitacao) >= ?";
    $params[] = $data_inicio;
}

if ($data_fim) {
    $sql .= " AND DATE(c.data_solicitacao) <= ?";
    $params[] = $data_fim;
}

$sql .= " ORDER BY c.data_solicitacao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$coletas = $stmt->fetchAll();

// Ações
if (isset($_GET['action'])) {
    $coleta_id = $_GET['id'] ?? 0;
    
    if ($_GET['action'] == 'delete' && $coleta_id) {
        try {
            $sql = "DELETE FROM coletas WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$coleta_id]);
            $_SESSION['sucesso'] = "Coleta excluída com sucesso!";
            header('Location: coletas.php');
            exit;
        } catch(PDOException $e) {
            $erro = "Erro ao excluir coleta: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Coletas - Admin EcoColeta</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <h1>🗑️ Gerenciar Coletas</h1>
            <p>Gerencie todas as coletas do sistema</p>
        </div>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
        <?php endif; ?>

        <?php if (isset($erro)): ?>
            <div class="alert alert-error"><?php echo $erro; ?></div>
        <?php endif; ?>

        <div class="filtros-section">
            <h3>🔍 Filtros</h3>
            <form method="GET" class="filtro-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">Todos</option>
                            <option value="pendente" <?php echo $status == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                            <option value="agendada" <?php echo $status == 'agendada' ? 'selected' : ''; ?>>Agendada</option>
                            <option value="realizada" <?php echo $status == 'realizada' ? 'selected' : ''; ?>>Realizada</option>
                            <option value="cancelada" <?php echo $status == 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Data Início</label>
                        <input type="date" name="data_inicio" value="<?php echo $data_inicio; ?>">
                    </div>
                    <div class="form-group">
                        <label>Data Fim</label>
                        <input type="date" name="data_fim" value="<?php echo $data_fim; ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                <a href="coletas.php" class="btn btn-secondary">Limpar</a>
            </form>
        </div>

        <div class="coletas-table">
            <div class="table-header">
                <h3>Lista de Coletas (<?php echo count($coletas); ?>)</h3>
            </div>
            
            <?php if (empty($coletas)): ?>
                <div class="alert alert-info">Nenhuma coleta encontrada com os filtros aplicados.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Morador</th>
                                <th>Endereço</th>
                                <th>Material</th>
                                <th>Status</th>
                                <th>Data Solicitação</th>
                                <th>Coletor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($coletas as $coleta): ?>
                                <tr>
                                    <td>#<?php echo $coleta['id']; ?></td>
                                    <td><?php echo htmlspecialchars($coleta['morador_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($coleta['endereco']); ?></td>
                                    <td><?php echo htmlspecialchars($coleta['material']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $coleta['status']; ?>">
                                            <?php echo ucfirst($coleta['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($coleta['data_solicitacao'])); ?></td>
                                    <td><?php echo $coleta['coletor_nome'] ? htmlspecialchars($coleta['coletor_nome']) : '—'; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="../agendar_coleta.php?id=<?php echo $coleta['id']; ?>" 
                                               class="btn btn-sm btn-primary" title="Agendar">
                                               📅
                                            </a>
                                            <a href="coletas.php?action=delete&id=<?php echo $coleta['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Tem certeza que deseja excluir esta coleta?')"
                                               title="Excluir">
                                               🗑️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>