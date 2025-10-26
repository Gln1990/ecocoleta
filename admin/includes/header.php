<?php
// admin/includes/header.php
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - EcoColeta</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .admin-header .logo {
            color: white;
        }
        .admin-header .nav-links a {
            color: white;
        }
        .admin-header .nav-links a:hover {
            color: #f1c40f;
        }
        .admin-header .nav-links a.active {
            color: #f1c40f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <nav class="container">
            <a href="index.php" class="logo">🌱 EcoColeta Admin</a>
            
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo $pagina_atual == 'index.php' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="usuarios.php" class="<?php echo $pagina_atual == 'usuarios.php' ? 'active' : ''; ?>">Usuários</a></li>
                <li><a href="coletas.php" class="<?php echo $pagina_atual == 'coletas.php' ? 'active' : ''; ?>">Coletas</a></li>
                <li><a href="configuracoes.php" class="<?php echo $pagina_atual == 'configuracoes.php' ? 'active' : ''; ?>">Configurações</a></li>
                <li><a href="../dashboard.php" class="">Site Principal</a></li>
                <li><a href="../perfil.php" class="">
                    👤 <?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']['nome']) : 'Admin'; ?>
                </a></li>
                <li><a href="../logout.php" class="btn-logout">Sair</a></li>
            </ul>

            <!-- Menu Mobile -->
            <div class="mobile-menu">
                <button class="mobile-menu-toggle">☰</button>
                <div class="mobile-nav-links">
                    <a href="index.php">Dashboard</a>
                    <a href="usuarios.php">Usuários</a>
                    <a href="coletas.php">Coletas</a>
                    <a href="configuracoes.php">Configurações</a>
                    <a href="../dashboard.php">Site Principal</a>
                    <a href="../perfil.php">Meu Perfil</a>
                    <a href="../logout.php">Sair</a>
                </div>
            </div>
        </nav>
    </header>