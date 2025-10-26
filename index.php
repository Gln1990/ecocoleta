<?php
// index.php
session_start();
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoColeta - Coleta de Reciclagem</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="hero">
            <div class="container">
                <h1>Reciclagem Fácil e Prática</h1>
                <p>Solicite coleta de materiais recicláveis na sua casa</p>
                <div class="cta-buttons">
                    <a href="cadastro.php" class="btn btn-primary">Cadastre-se</a>
                    <a href="login.php" class="btn btn-secondary">Login</a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <div class="feature-grid">
                    <div class="feature">
                        <h3>🗓️ Agende Coletas</h3>
                        <p>Escolha o melhor horário para a coleta dos seus recicláveis</p>
                    </div>
                    <div class="feature">
                        <h3>📍 Roteiro Fixo</h3>
                        <p>Saiba quando o coletor passará na sua rua</p>
                    </div>
                    <div class="feature">
                        <h3>🌱 Impacto Ambiental</h3>
                        <p>Contribua para um planeta mais sustentável</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>