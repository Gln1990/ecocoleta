<?php
// includes/header.php
if (!isset($base_url)) {
    require_once 'config.php';
}

$pagina_atual = basename($_SERVER['PHP_SELF']);
$is_admin = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoColeta - Sistema de Coleta de Recicláveis</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <?php if ($is_admin): ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/dashboard.css">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        .admin-header .nav-links a {
            color: white !important;
        }
        .admin-header .nav-links a:hover {
            color: #f1c40f !important;
        }
    </style>
    <?php endif; ?>
</head>
<body class="<?php echo $is_admin ? 'admin-body' : ''; ?>">
    <header class="<?php echo $is_admin ? 'admin-header' : ''; ?>">
        <nav class="container">
            <?php if ($is_admin): ?>
                <a href="<?php echo BASE_URL; ?>/admin/index.php" class="logo">🌱 EcoColeta Admin</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/index.php" class="logo">🌱 EcoColeta</a>
            <?php endif; ?>
            
            <ul class="nav-links">
                <?php if ($is_admin): ?>
                    <!-- Menu Admin -->
                    <li><a href="<?php echo BASE_URL; ?>/admin/index.php" class="<?php echo $pagina_atual == 'index.php' ? 'active' : ''; ?>">Dashboard</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/usuarios.php" class="<?php echo $pagina_atual == 'usuarios.php' ? 'active' : ''; ?>">Usuários</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/coletas.php" class="<?php echo $pagina_atual == 'coletas.php' ? 'active' : ''; ?>">Coletas</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/configuracoes.php" class="<?php echo $pagina_atual == 'configuracoes.php' ? 'active' : ''; ?>">Configurações</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/dashboard.php">Site Principal</a></li>
                    
                <?php else: ?>
                    <!-- Menu Site Principal -->
                    <li><a href="<?php echo BASE_URL; ?>/index.php" class="<?php echo $pagina_atual == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>/dashboard.php" class="<?php echo $pagina_atual == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
                        
                        <?php if ($_SESSION['usuario']['tipo'] == 'morador'): ?>
                            <li><a href="<?php echo BASE_URL; ?>/solicitar_coleta.php" class="<?php echo $pagina_atual == 'solicitar_coleta.php' ? 'active' : ''; ?>">Solicitar Coleta</a></li>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['usuario']['is_admin']) && $_SESSION['usuario']['is_admin'] == 1): ?>
                            <li><a href="<?php echo BASE_URL; ?>/admin/index.php">Admin</a></li>
                        <?php endif; ?>
                        
                        <li><a href="<?php echo BASE_URL; ?>/rotas.php" class="<?php echo $pagina_atual == 'rotas.php' ? 'active' : ''; ?>">Rotas</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/cadastro.php" class="<?php echo $pagina_atual == 'cadastro.php' ? 'active' : ''; ?>">Cadastrar</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/login.php" class="<?php echo $pagina_atual == 'login.php' ? 'active' : ''; ?>">Login</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>/perfil.php" class="<?php echo $pagina_atual == 'perfil.php' ? 'active' : ''; ?>">
                        👤 <?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?>
                    </a></li>
                    <li><a href="<?php echo BASE_URL; ?>/logout.php" class="btn-logout">Sair</a></li>
                <?php endif; ?>
            </ul>

            <!-- Menu Mobile -->
            <div class="mobile-menu">
                <button class="mobile-menu-toggle">☰</button>
                <div class="mobile-nav-links">
                    <?php if ($is_admin): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/index.php">Dashboard</a>
                        <a href="<?php echo BASE_URL; ?>/admin/usuarios.php">Usuários</a>
                        <a href="<?php echo BASE_URL; ?>/admin/coletas.php">Coletas</a>
                        <a href="<?php echo BASE_URL; ?>/admin/configuracoes.php">Configurações</a>
                        <a href="<?php echo BASE_URL; ?>/dashboard.php">Site Principal</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <a href="<?php echo BASE_URL; ?>/dashboard.php">Dashboard</a>
                            <?php if ($_SESSION['usuario']['tipo'] == 'morador'): ?>
                                <a href="<?php echo BASE_URL; ?>/solicitar_coleta.php">Solicitar Coleta</a>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['usuario']['is_admin']) && $_SESSION['usuario']['is_admin'] == 1): ?>
                                <a href="<?php echo BASE_URL; ?>/admin/index.php">Admin</a>
                            <?php endif; ?>
                            <a href="<?php echo BASE_URL; ?>/rotas.php">Rotas</a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/cadastro.php">Cadastrar</a>
                            <a href="<?php echo BASE_URL; ?>/login.php">Login</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <a href="<?php echo BASE_URL; ?>/perfil.php">Meu Perfil</a>
                        <a href="<?php echo BASE_URL; ?>/logout.php">Sair</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>