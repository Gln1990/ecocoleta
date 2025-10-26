<?php
// perfil.php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

verificarAutenticacao();

$usuario = $_SESSION['usuario'];
$sucesso = '';
$erro = '';

if ($_POST) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $cep = $_POST['cep'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    
    // Verificar se e-mail já existe (exceto para o usuário atual)
    $sql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $usuario['id']]);
    
    if ($stmt->fetch()) {
        $erro = "Este e-mail já está em uso por outro usuário.";
    } else {
        try {
            $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, endereco = ?, cep = ?, cidade = ?, estado = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $email, $telefone, $endereco, $cep, $cidade, $estado, $usuario['id']]);
            
            // Atualizar dados na sessão
            $_SESSION['usuario']['nome'] = $nome;
            $_SESSION['usuario']['email'] = $email;
            $_SESSION['usuario']['telefone'] = $telefone;
            $_SESSION['usuario']['endereco'] = $endereco;
            $_SESSION['usuario']['cep'] = $cep;
            $_SESSION['usuario']['cidade'] = $cidade;
            $_SESSION['usuario']['estado'] = $estado;
            
            $sucesso = "Perfil atualizado com sucesso!";
            $usuario = $_SESSION['usuario']; // Atualizar variável local
            
        } catch(PDOException $e) {
            $erro = "Erro ao atualizar perfil: " . $e->getMessage();
        }
    }
}

// Buscar estatísticas do usuário
if ($usuario['tipo'] == 'morador') {
    $sql = "SELECT 
        COUNT(*) as total_coletas,
        SUM(CASE WHEN status = 'realizada' THEN 1 ELSE 0 END) as coletas_realizadas,
        SUM(CASE WHEN status = 'agendada' THEN 1 ELSE 0 END) as coletas_agendadas
        FROM coletas WHERE morador_id = ?";
} else {
    $sql = "SELECT 
        COUNT(*) as total_coletas,
        SUM(CASE WHEN status = 'realizada' THEN 1 ELSE 0 END) as coletas_realizadas,
        SUM(CASE WHEN status = 'agendada' THEN 1 ELSE 0 END) as coletas_agendadas
        FROM coletas WHERE coletor_id = ?";
}

$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario['id']]);
$estatisticas = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - EcoColeta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <h1>👤 Meu Perfil</h1>
            <p>Gerencie suas informações pessoais</p>
        </div>

        <div class="perfil-layout">
            <div class="perfil-stats">
                <h3>📊 Estatísticas</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $estatisticas['total_coletas']; ?></div>
                        <div class="stat-label">Total de Coletas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $estatisticas['coletas_realizadas']; ?></div>
                        <div class="stat-label">Coletas Realizadas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $estatisticas['coletas_agendadas']; ?></div>
                        <div class="stat-label">Coletas Agendadas</div>
                    </div>
                </div>

                <div class="user-info">
                    <h3>ℹ️ Informações da Conta</h3>
                    <p><strong>Tipo de Usuário:</strong> 
                        <?php 
                        $tipos = [
                            'morador' => 'Morador',
                            'coletor' => 'Coletor Individual', 
                            'empresa' => 'Empresa de Coleta'
                        ];
                        echo $tipos[$usuario['tipo']];
                        ?>
                    </p>
                    <p><strong>Data de Cadastro:</strong> 
                        <?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?>
                    </p>
                </div>
            </div>

            <div class="perfil-form">
                <h3>✏️ Editar Perfil</h3>
                
                <?php if ($sucesso): ?>
                    <div class="alert alert-success"><?php echo $sucesso; ?></div>
                <?php endif; ?>
                
                <?php if ($erro): ?>
                    <div class="alert alert-error"><?php echo $erro; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Endereço</label>
                        <input type="text" name="endereco" value="<?php echo htmlspecialchars($usuario['endereco']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>CEP</label>
                        <input type="text" name="cep" value="<?php echo htmlspecialchars($usuario['cep']); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" name="cidade" value="<?php echo htmlspecialchars($usuario['cidade']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="text" name="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>" maxlength="2" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
                    <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
                </form>

                <div class="profile-actions">
                    <h4>Ações da Conta</h4>
                    <a href="logout.php" class="btn btn-outline">Sair da Conta</a>
                </div>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>