<?php
// agendar_coleta.php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/email.php';

verificarAutenticacao();

if ($_SESSION['usuario']['tipo'] == 'morador') {
    header('Location: dashboard.php');
    exit;
}

$coleta_id = $_GET['id'] ?? 0;

// Buscar dados da coleta
if ($coleta_id) {
    $sql = "SELECT c.*, u.nome as morador_nome, u.email as morador_email, u.telefone 
            FROM coletas c 
            JOIN usuarios u ON c.morador_id = u.id 
            WHERE c.id = ? AND c.status = 'pendente'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$coleta_id]);
    $coleta = $stmt->fetch();
}

if ($_POST && $coleta) {
    $data_agendada = $_POST['data_agendada'];
    $observacoes_coletor = $_POST['observacoes_coletor'];
    
    try {
        // Atualizar coleta
        $sql = "UPDATE coletas 
                SET coletor_id = ?, data_agendada = ?, observacoes_coletor = ?, status = 'agendada' 
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['usuario']['id'], $data_agendada, $observacoes_coletor, $coleta_id]);
        
        // Enviar e-mail de notificação
        $email = new EmailNotificacao();
        $dadosEmail = [
            'morador_nome' => $coleta['morador_nome'],
            'data_agendada' => date('d/m/Y H:i', strtotime($data_agendada)),
            'endereco' => $coleta['endereco'],
            'material' => $coleta['material'],
            'quantidade' => $coleta['quantidade'],
            'coletor_nome' => $_SESSION['usuario']['nome']
        ];
        
        $email->enviarNotificacaoColetaAgendada(
            $coleta['morador_email'],
            $coleta['morador_nome'],
            $dadosEmail
        );
        
        $_SESSION['sucesso'] = "Coleta agendada com sucesso! E-mail enviado para o morador.";
        header('Location: dashboard.php');
        exit;
        
    } catch(PDOException $e) {
        $erro = "Erro ao agendar coleta: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Coleta - EcoColeta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/mapa.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="form-container">
            <h2>📅 Agendar Coleta</h2>
            
            <?php if (!$coleta): ?>
                <div class="alert alert-error">
                    Coleta não encontrada ou já foi agendada.
                </div>
                <a href="dashboard.php" class="btn btn-primary">Voltar</a>
            <?php else: ?>
                <?php if (isset($erro)): ?>
                    <div class="alert alert-error"><?php echo $erro; ?></div>
                <?php endif; ?>

                <div class="coleta-info">
                    <h3>Informações da Coleta</h3>
                    <p><strong>Morador:</strong> <?php echo htmlspecialchars($coleta['morador_nome']); ?></p>
                    <p><strong>Endereço:</strong> <?php echo htmlspecialchars($coleta['endereco']); ?></p>
                    <p><strong>Materiais:</strong> <?php echo htmlspecialchars($coleta['material']); ?></p>
                    <p><strong>Quantidade:</strong> <?php echo htmlspecialchars($coleta['quantidade']); ?></p>
                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($coleta['telefone']); ?></p>
                    <p><strong>Observações:</strong> <?php echo htmlspecialchars($coleta['observacoes']); ?></p>
                </div>

                <div class="mapa-mini">
                    <div id="mapa-coleta"></div>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label>Data e Horário da Coleta</label>
                        <input type="datetime-local" name="data_agendada" required 
                               min="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Observações para o Morador</label>
                        <textarea name="observacoes_coletor" rows="3" 
                                  placeholder="Instruções, observações, etc..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Confirmar Agendamento</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Mapa para visualização do endereço da coleta
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($coleta): ?>
            const mapa = L.map('mapa-coleta').setView([-23.5505, -46.6333], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapa);
            
            // Simular geocoding do endereço
            const endereco = "<?php echo $coleta['endereco']; ?>";
            // Em implementação real, usar serviço de geocoding como Nominatim
            L.marker([-23.5505, -46.6333])
                .addTo(mapa)
                .bindPopup(`<b>Local da Coleta</b><br>${endereco}`)
                .openPopup();
            <?php endif; ?>
        });
    </script>
</body>
</html>