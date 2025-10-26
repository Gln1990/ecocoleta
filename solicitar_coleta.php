<?php
// solicitar_coleta.php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

verificarAutenticacao();

if ($_SESSION['usuario']['tipo'] != 'morador') {
    header('Location: dashboard.php');
    exit;
}

if ($_POST) {
    $morador_id = $_SESSION['usuario']['id'];
    $endereco = $_POST['endereco'];
    $material = $_POST['material'];
    $quantidade = $_POST['quantidade'];
    $observacoes = $_POST['observacoes'];

    try {
        $sql = "INSERT INTO coletas (morador_id, endereco, material, quantidade, observacoes) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$morador_id, $endereco, $material, $quantidade, $observacoes]);
        
        $_SESSION['sucesso'] = "Coleta solicitada com sucesso!";
        header('Location: dashboard.php');
        exit;
    } catch(PDOException $e) {
        $erro = "Erro ao solicitar coleta: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Coleta - EcoColeta</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="form-container">
            <h2>Solicitar Coleta de Recicláveis</h2>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?php echo $erro; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Endereço para Coleta</label>
                    <input type="text" name="endereco" value="<?php echo $_SESSION['usuario']['endereco']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Materiais para Coleta</label>
                    <select name="material" required>
                        <option value="">Selecione os materiais</option>
                        <option value="Plástico">Plástico</option>
                        <option value="Papel">Papel</option>
                        <option value="Vidro">Vidro</option>
                        <option value="Metal">Metal</option>
                        <option value="Eletrônicos">Eletrônicos</option>
                        <option value="Misto">Misto (Vários materiais)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Quantidade Aproximada</label>
                    <select name="quantidade" required>
                        <option value="">Selecione a quantidade</option>
                        <option value="Pequena (até 5kg)">Pequena (até 5kg)</option>
                        <option value="Média (5-15kg)">Média (5-15kg)</option>
                        <option value="Grande (15kg+)">Grande (15kg+)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Observações</label>
                    <textarea name="observacoes" rows="4" placeholder="Informações adicionais..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Solicitar Coleta</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>
