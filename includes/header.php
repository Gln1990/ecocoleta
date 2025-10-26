<?php
// includes/header.php
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoColeta - Sistema de Coleta de Recicláveis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="container">
            <a href="index.php" class="logo">🌱 EcoColeta</a>
            
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo $pagina_atual == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><a href="dashboard.php" class="<?php echo $pagina_atual == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
                    
                    <?php if ($_SESSION['usuario']['tipo'] == 'morador'): ?>
                        <li><a href="solicitar_coleta.php" class="<?php echo $pagina_atual == 'solicitar_coleta.php' ? 'active' : ''; ?>">Solicitar Coleta</a></li>
                    <?php elseif ($_SESSION['usuario']['tipo'] == 'empresa'): ?>
                        <li><a href="admin/index.php" class="<?php echo strpos($pagina_atual, 'admin/') !== false ? 'active' : ''; ?>">Admin</a></li>
                    <?php endif; ?>
                    
                    <li><a href="rotas.php" class="<?php echo $pagina_atual == 'rotas.php' ? 'active' : ''; ?>">Rotas</a></li>
                    <li><a href="perfil.php" class="<?php echo $pagina_atual == 'perfil.php' ? 'active' : ''; ?>">
                        👤 <?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?>
                    </a></li>
                    <li><a href="logout.php" class="btn-logout">Sair</a></li>
                    
                <?php else: ?>
                    <li><a href="cadastro.php" class="<?php echo $pagina_atual == 'cadastro.php' ? 'active' : ''; ?>">Cadastrar</a></li>
                    <li><a href="login.php" class="<?php echo $pagina_atual == 'login.php' ? 'active' : ''; ?>">Login</a></li>
                <?php endif; ?>
            </ul>

            <!-- Menu Mobile -->
            <div class="mobile-menu">
                <button class="mobile-menu-toggle">☰</button>
                <div class="mobile-nav-links">
                    <a href="index.php">Home</a>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <a href="dashboard.php">Dashboard</a>
                        <?php if ($_SESSION['usuario']['tipo'] == 'morador'): ?>
                            <a href="solicitar_coleta.php">Solicitar Coleta</a>
                        <?php elseif ($_SESSION['usuario']['tipo'] == 'empresa'): ?>
                            <a href="admin/index.php">Admin</a>
                        <?php endif; ?>
                        <a href="rotas.php">Rotas</a>
                        <a href="perfil.php">Meu Perfil</a>
                        <a href="logout.php">Sair</a>
                    <?php else: ?>
                        <a href="cadastro.php">Cadastrar</a>
                        <a href="login.php">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>