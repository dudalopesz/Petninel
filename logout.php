<?php
require_once __DIR__ . '/includes/autenticacao.php';

destruirSessao();
setFlash('success', 'Sessão encerrada com sucesso.');
header('Location: index.php');
exit;
