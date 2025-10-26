<?php
// includes/auth.php
function verificarAutenticacao() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../login.php');
        exit;
    }
}

function verificarAdmin() {
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['is_admin'] != 1) {
        header('Location: ../dashboard.php');
        exit;
    }
}

function verificarTipoUsuario($tiposPermitidos) {
    if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['tipo'], $tiposPermitidos)) {
        header('Location: ../dashboard.php');
        exit;
    }
}

function isAdmin() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['is_admin'] == 1;
}

function isMorador() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] == 'morador';
}

function isColetor() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] == 'coletor';
}

function isEmpresa() {
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] == 'empresa';
}

function getUsuarioId() {
    return isset($_SESSION['usuario']) ? $_SESSION['usuario']['id'] : null;
}

function getUsuarioNome() {
    return isset($_SESSION['usuario']) ? $_SESSION['usuario']['nome'] : 'Visitante';
}

function getUsuarioTipo() {
    return isset($_SESSION['usuario']) ? $_SESSION['usuario']['tipo'] : null;
}

function getTipoUsuarioTexto($tipo) {
    $tipos = [
        'morador' => 'Morador',
        'coletor' => 'Coletor Individual',
        'empresa' => 'Empresa de Coleta'
    ];
    return $tipos[$tipo] ?? 'Desconhecido';
}
?>