<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/funcoes.php';

if (!isset($_SESSION['usuario_id']) && isset($_COOKIE[session_name()])) {
    $_SESSION = [];
}
