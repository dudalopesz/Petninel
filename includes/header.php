<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = usuarioLogado();
$usuarioNome = $logado ? htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8') : '';
$usuarioTipo = $logado ? ($_SESSION['usuario_tipo'] ?? 'cliente') : 'cliente';
$baseUrl = '/petninel/';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') . ' | Petninel' : 'Petninel'; ?></title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/style.css">
    <script src="<?php echo $baseUrl; ?>assets/js/app.js" defer></script>
</head>
<body>
<header class="topo">
    <div class="logo-wrap">
        <div class="logo-icon">🐾</div>
        <div class="logo-text">Petninel</div>
    </div>

    <nav class="menu-navegacao" aria-label="Menu principal">
        <a href="<?php echo $baseUrl; ?>index.php">Início</a>
        <a href="<?php echo $baseUrl; ?>index.php#servicos">Serviços</a>
        <?php if ($logado): ?>
            <a href="<?php echo $baseUrl; ?>pages/perfil.php">Perfil</a>
            <a href="<?php echo $baseUrl; ?>pages/agendamento.php">Agendar</a>
            <a href="<?php echo $baseUrl; ?>pages/meus_agendamentos.php">Meus agendamentos</a>
            <?php if ($usuarioTipo === 'admin'): ?>
                <a href="<?php echo $baseUrl; ?>pages/admin.php">Administração</a>
            <?php endif; ?>
            <a href="<?php echo $baseUrl; ?>logout.php" class="btn-login">Sair</a>
        <?php else: ?>
            <a href="<?php echo $baseUrl; ?>pages/login.php">Entrar</a>
            <a href="<?php echo $baseUrl; ?>pages/cadastro.php" class="btn-login">Cadastrar</a>
        <?php endif; ?>
    </nav>
</header>
<main class="site-main">
