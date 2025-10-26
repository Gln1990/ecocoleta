<?php
// rotas.php
session_start();
require_once 'includes/config.php';

$bairro = $_GET['bairro'] ?? '';

// Buscar rotas ativas
$sql = "SELECT r.*, u.nome as coletor_nome, u.telefone 
        FROM rotas r 
        JOIN usuarios u ON r.coletor_id = u.id 
        WHERE r.ativo = true";
        
if (!empty($bairro)) {
    $sql .= " AND r.bairro LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$bairro%"]);
} else {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$rotas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas de Coleta - EcoColeta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/mapa.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="page-header">
            <h1>🗺️ Rotas de Coleta</h1>
            <p>Confira as rotas de coleta na sua região</p>
        </div>

        <div class="mapa-container">
            <div class="filtros">
                <form method="GET" class="filtro-form">
                    <div class="form-group">
                        <label>Buscar por Bairro:</label>
                        <input type="text" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>" 
                               placeholder="Digite o nome do bairro...">
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>

            <div class="mapa-layout">
                <div class="lista-rotas">
                    <h3>Rotas Encontradas</h3>
                    
                    <?php if (empty($rotas)): ?>
                        <div class="alert alert-info">
                            Nenhuma rota encontrada para o bairro "<?php echo htmlspecialchars($bairro); ?>"
                        </div>
                    <?php else: ?>
                        <?php foreach ($rotas as $rota): ?>
                            <div class="rota-card" data-bairro="<?php echo htmlspecialchars($rota['bairro']); ?>"
                                 data-dias="<?php echo htmlspecialchars($rota['dias_semana']); ?>"
                                 data-horario="<?php echo htmlspecialchars($rota['horario']); ?>"
                                 data-coletor="<?php echo htmlspecialchars($rota['coletor_nome']); ?>">
                                <h4><?php echo htmlspecialchars($rota['bairro']); ?></h4>
                                <p><strong>Dias:</strong> <?php echo htmlspecialchars($rota['dias_semana']); ?></p>
                                <p><strong>Horário:</strong> <?php echo htmlspecialchars($rota['horario']); ?></p>
                                <p><strong>Coletor:</strong> <?php echo htmlspecialchars($rota['coletor_nome']); ?></p>
                                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($rota['telefone']); ?></p>
                                <button class="btn btn-sm btn-primary ver-no-mapa" 
                                        data-bairro="<?php echo htmlspecialchars($rota['bairro']); ?>">
                                    Ver no Mapa
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="mapa-wrapper">
                    <div id="mapa"></div>
                    <div class="mapa-legenda">
                        <h4>Legenda:</h4>
                        <div class="legenda-item">
                            <span class="legenda-cor" style="background: #2ecc71;"></span>
                            <span>Rota Ativa</span>
                        </div>
                        <div class="legenda-item">
                            <span class="legenda-cor" style="background: #e74c3c;"></span>
                            <span>Seu Local</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="js/mapa.js"></script>
</body>
</html>