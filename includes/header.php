<?php
// includes/header.php
$pagina_atual = basename($_SERVER['PHP_SELF']);
$is_admin_page = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;

// Função para gerar links corretos baseado na localização
function getLink($caminho) {
    if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
        return '../' . $caminho;
    }
    return $caminho;
}

function getAdminLink($caminho) {
    if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
        return $caminho;
    }
    return 'admin/' . $caminho;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoColeta - Sistema de Coleta de Recicláveis</title>
    <link rel="stylesheet" href="<?php echo $is_admin_page ? '../css/style.css' : 'css/style.css'; ?>">
</head>
<body>
    <header>
        <nav class="container">
            <a href="<?php echo getLink('index.php'); ?>" class="logo">🌱 EcoColeta</a>
            
            <ul class="nav-links">
                <li><a href="<?php echo getLink('index.php'); ?>" class="<?php echo $pagina_atual == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><a href="<?php echo getLink('dashboard.php'); ?>" class="<?php echo $pagina_atual == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
                    
                    <?php if ($_SESSION['usuario']['tipo'] == 'morador'): ?>
                        <li><a href="<?php echo getLink('solicitar_coleta.php'); ?>" class="<?php echo $pagina_atual == 'solicitar_coleta.php' ? 'active' : ''; ?>">Solicitar Coleta</a></li>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['usuario']['is_admin'] == 1): ?>
                        <li><a href="<?php echo getAdminLink('index.php'); ?>" class="<?php echo strpos($pagina_atual, 'admin/') !== false ? 'active' : ''; ?>">Admin</a></li>
                    <?php endif; ?>
                    
                    <li><a href="<?php echo getLink('rotas.php'); ?>" class="<?php echo $pagina_atual == 'rotas.php' ? 'active' : ''; ?>">Rotas</a></li>
                    <li><a href="<?php echo getLink('perfil.php'); ?>" class="<?php echo $pagina_atual == 'perfil.php' ? 'active' : ''; ?>">
                        👤 <?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?>
                    </a></li>
                    <li><a href="<?php echo getLink('logout.php'); ?>" class="btn-logout">Sair</a></li>
                    
                <?php else: ?>
                    <li><a href="<?php echo getLink('cadastro.php'); ?>" class="<?php echo $pagina_atual == 'cadastro.php' ? 'active' : ''; ?>">Cadastrar</a></li>
                    <li><a href="<?php echo getLink('login.php'); ?>" class="<?php echo $pagina_atual == 'login.php' ? 'active' : ''; ?>">Login</a></li>
                <?php endif; ?>
            </ul>

            <!-- Menu Mobile -->
            <div class="mobile-menu">
                <button class="mobile-menu-toggle">☰</button>
                <div class="mobile-nav-links">
                    <a href="<?php echo getLink('index.php'); ?>">Home</a>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <a href="<?php echo getLink('dashboard.php'); ?>">Dashboard</a>
                        <?php if ($_SESSION['usuario']['tipo'] == 'morador'): ?>
                            <a href="<?php echo getLink('solicitar_coleta.php'); ?>">Solicitar Coleta</a>
                        <?php endif; ?>
                        <?php if ($_SESSION['usuario']['is_admin'] == 1): ?>
                            <a href="<?php echo getAdminLink('index.php'); ?>">Admin</a>
                        <?php endif; ?>
                        <a href="<?php echo getLink('rotas.php'); ?>">Rotas</a>
                        <a href="<?php echo getLink('perfil.php'); ?>">Meu Perfil</a>
                        <a href="<?php echo getLink('logout.php'); ?>">Sair</a>
                    <?php else: ?>
                        <a href="<?php echo getLink('cadastro.php'); ?>">Cadastrar</a>
                        <a href="<?php echo getLink('login.php'); ?>">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>







