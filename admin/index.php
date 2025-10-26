<?php
// admin/index.php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

verificarAutenticacao();
verificarAdmin(); // Nova verificação específica para admin

// Buscar estatísticas gerais
$sql_coletas = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes,
    SUM(CASE WHEN status = 'agendada' THEN 1 ELSE 0 END) as agendadas,
    SUM(CASE WHEN status = 'realizada' THEN 1 ELSE 0 END) as realizadas
    FROM coletas";
$estatisticas_coletas = $pdo->query($sql_coletas)->fetch();

$sql_usuarios = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN tipo = 'morador' THEN 1 ELSE 0 END) as moradores,
    SUM(CASE WHEN tipo = 'coletor' THEN 1 ELSE 0 END) as coletores,
    SUM(CASE WHEN tipo = 'empresa' THEN 1 ELSE 0 END) as empresas,
    SUM(CASE WHEN is_admin = 1 THEN 1 ELSE 0 END) as administradores
    FROM usuarios";
$estatisticas_usuarios = $pdo->query($sql_usuarios)->fetch();

// Coletas recentes
$sql_recentes = "SELECT c.*, u.nome as morador_nome, u2.nome as coletor_nome 
                 FROM coletas c 
                 JOIN usuarios u ON c.morador_id = u.id 
                 LEFT JOIN usuarios u2 ON c.coletor_id = u2.id 
                 ORDER BY c.data_solicitacao DESC 
                 LIMIT 5";
$coletas_recentes = $pdo->query($sql_recentes)->fetchAll();

// Usuários recentes
$sql_usuarios_recentes = "SELECT * FROM usuarios ORDER BY data_cadastro DESC LIMIT 5";
$usuarios_recentes = $pdo->query($sql_usuarios_recentes)->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - EcoColeta</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <h1>⚙️ Painel Administrativo</h1>
            <p>Bem-vindo, <?php echo $_SESSION['usuario']['nome']; ?>! 👑</p>
            <small>Você está acessando como Administrador do sistema</small>
        </div>

        <div class="admin-stats">
            <div class="stats-grid">
                <div class="stat-card admin">
                    <div class="stat-icon">📊</div>
                    <div class="stat-number"><?php echo $estatisticas_coletas['total']; ?></div>
                    <div class="stat-label">Total de Coletas</div>
                </div>
                <div class="stat-card admin">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number"><?php echo $estatisticas_usuarios['total']; ?></div>
                    <div class="stat-label">Total de Usuários</div>
                </div>
                <div class="stat-card admin">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-number"><?php echo $estatisticas_coletas['pendentes']; ?></div>
                    <div class="stat-label">Coletas Pendentes</div>
                </div>
                <div class="stat-card admin">
                    <div class="stat-icon">✅</div>
                    <div class="stat-number"><?php echo $estatisticas_coletas['realizadas']; ?></div>
                    <div class="stat-label">Coletas Realizadas</div>
                </div>
            </div>
        </div>

        <div class="admin-layout">
            <div class="admin-actions">
                <h3>🔧 Ações Rápidas</h3>
                <div class="action-grid">
                    <a href="usuarios.php" class="action-card">
                        <div class="action-icon">👥</div>
                        <div class="action-text">Gerenciar Usuários</div>
                    </a>
                    <a href="coletas.php" class="action-card">
                        <div class="action-icon">🗑️</div>
                        <div class="action-text">Gerenciar Coletas</div>
                    </a>
                    <a href="../rotas.php" class="action-card">
                        <div class="action-icon">🗺️</div>
                        <div class="action-text">Ver Rotas</div>
                    </a>
                    <a href="configuracoes.php" class="action-card">
                        <div class="action-icon">⚙️</div>
                        <div class="action-text">Configurações</div>
                    </a>
                </div>
            </div>

            <div class="recent-activity">
                <h3>📋 Coletas Recentes</h3>
                <div class="activity-list">
                    <?php if (empty($coletas_recentes)): ?>
                        <p>Nenhuma coleta recente.</p>
                    <?php else: ?>
                        <?php foreach ($coletas_recentes as $coleta): ?>
                            <div class="activity-item">
                                <div class="activity-info">
                                    <strong>Coleta #<?php echo $coleta['id']; ?></strong>
                                    <span class="status-<?php echo $coleta['status']; ?>">
                                        <?php echo ucfirst($coleta['status']); ?>
                                    </span>
                                </div>
                                <div class="activity-details">
                                    <small>Morador: <?php echo htmlspecialchars($coleta['morador_nome']); ?></small>
                                    <small>Data: <?php echo date('d/m/Y H:i', strtotime($coleta['data_solicitacao'])); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <h3 style="margin-top: 2rem;">👥 Usuários Recentes</h3>
                <div class="activity-list">
                    <?php if (empty($usuarios_recentes)): ?>
                        <p>Nenhum usuário recente.</p>
                    <?php else: ?>
                        <?php foreach ($usuarios_recentes as $usuario): ?>
                            <div class="activity-item">
                                <div class="activity-info">
                                    <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong>
                                    <span class="tipo-<?php echo $usuario['tipo']; ?>">
                                        <?php echo getTipoUsuarioTexto($usuario['tipo']); ?>
                                    </span>
                                </div>
                                <div class="activity-details">
                                    <small>Email: <?php echo htmlspecialchars($usuario['email']); ?></small>
                                    <small>Data: <?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="user-stats">
            <h3>👥 Estatísticas de Usuários</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estatisticas_usuarios['moradores']; ?></div>
                    <div class="stat-label">Moradores</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estatisticas_usuarios['coletores']; ?></div>
                    <div class="stat-label">Coletores</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estatisticas_usuarios['empresas']; ?></div>
                    <div class="stat-label">Empresas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estatisticas_usuarios['administradores']; ?></div>
                    <div class="stat-label">Administradores</div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
