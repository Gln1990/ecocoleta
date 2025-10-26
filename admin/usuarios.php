<?php
// admin/usuarios.php
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
$tipo = $_GET['tipo'] ?? '';
$search = $_GET['search'] ?? '';

// Construir query com filtros
$sql = "SELECT * FROM usuarios WHERE 1=1";
$params = [];

if ($tipo) {
    $sql .= " AND tipo = ?";
    $params[] = $tipo;
}

if ($search) {
    $sql .= " AND (nome LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY data_cadastro DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll();

// Ações
if (isset($_GET['action'])) {
    $usuario_id = $_GET['id'] ?? 0;
    
    if ($_GET['action'] == 'delete' && $usuario_id) {
        // Não permitir excluir a si mesmo
        if ($usuario_id != $_SESSION['usuario']['id']) {
            try {
                // Primeiro excluir coletas associadas
                $sql = "DELETE FROM coletas WHERE morador_id = ? OR coletor_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$usuario_id, $usuario_id]);
                
                // Depois excluir o usuário
                $sql = "DELETE FROM usuarios WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$usuario_id]);
                
                $_SESSION['sucesso'] = "Usuário excluído com sucesso!";
                header('Location: usuarios.php');
                exit;
            } catch(PDOException $e) {
                $erro = "Erro ao excluir usuário: " . $e->getMessage();
            }
        } else {
            $erro = "Você não pode excluir sua própria conta!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - Admin EcoColeta</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <h1>👥 Gerenciar Usuários</h1>
            <p>Gerencie todos os usuários do sistema</p>
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
                        <label>Tipo de Usuário</label>
                        <select name="tipo">
                            <option value="">Todos</option>
                            <option value="morador" <?php echo $tipo == 'morador' ? 'selected' : ''; ?>>Morador</option>
                            <option value="coletor" <?php echo $tipo == 'coletor' ? 'selected' : ''; ?>>Coletor</option>
                            <option value="empresa" <?php echo $tipo == 'empresa' ? 'selected' : ''; ?>>Empresa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Buscar</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Nome ou e-mail...">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                <a href="usuarios.php" class="btn btn-secondary">Limpar</a>
            </form>
        </div>

        <div class="usuarios-table">
            <div class="table-header">
                <h3>Lista de Usuários (<?php echo count($usuarios); ?>)</h3>
            </div>
            
            <?php if (empty($usuarios)): ?>
                <div class="alert alert-info">Nenhum usuário encontrado com os filtros aplicados.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Tipo</th>
                                <th>Telefone</th>
                                <th>Cidade/UF</th>
                                <th>Data Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>#<?php echo $usuario['id']; ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td>
                                        <span class="tipo-badge tipo-<?php echo $usuario['tipo']; ?>">
                                            <?php 
                                            $tipos = [
                                                'morador' => 'Morador',
                                                'coletor' => 'Coletor',
                                                'empresa' => 'Empresa'
                                            ];
                                            echo $tipos[$usuario['tipo']];
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['telefone']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['cidade']); ?>/<?php echo htmlspecialchars($usuario['estado']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="../perfil.php" class="btn btn-sm btn-primary" title="Ver Perfil">
                                                👁️
                                            </a>
                                            <?php if ($usuario['id'] != $_SESSION['usuario']['id']): ?>
                                                <a href="usuarios.php?action=delete&id=<?php echo $usuario['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')"
                                                   title="Excluir">
                                                   🗑️
                                                </a>
                                            <?php else: ?>
                                                <span class="btn btn-sm btn-disabled" title="Você">👤</span>
                                            <?php endif; ?>
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