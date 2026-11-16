<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setFlash(string $tipo, string $mensagem): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
}

function exibirFlash(): void
{
    if (!isset($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];
    $tipo = htmlspecialchars($flash['tipo'], ENT_QUOTES, 'UTF-8');
    $mensagem = htmlspecialchars($flash['mensagem'], ENT_QUOTES, 'UTF-8');

    echo '<div class="alert alert-' . $tipo . '" role="alert">' . $mensagem . '</div>';
    unset($_SESSION['flash']);
}

function redirect(string $destino): void
{
    header('Location: ' . $destino);
    exit;
}

function gerarCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validarCsrf(string $token): bool
{
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function usuarioLogado(): bool
{
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

function usuarioAdmin(): bool
{
    return usuarioLogado() && ($_SESSION['usuario_tipo'] ?? '') === 'admin';
}

function requireLogin(): void
{
    if (!usuarioLogado()) {
        setFlash('danger', 'Você precisa estar logado para acessar esta página.');
        redirect('../pages/login.php');
    }
}

function requireAdmin(): void
{
    if (!usuarioAdmin()) {
        setFlash('danger', 'Acesso negado. Somente administradores podem acessar esta área.');
        redirect('../index.php');
    }
}

function limparTexto(string $valor): string
{
    return trim(strip_tags($valor));
}

function formatarData(string $data): string
{
    if (empty($data)) {
        return '-';
    }

    $dataObj = DateTime::createFromFormat('Y-m-d', $data);
    if ($dataObj === false) {
        return $data;
    }

    return $dataObj->format('d/m/Y');
}

function formatarHora(string $hora): string
{
    if (empty($hora)) {
        return '-';
    }

    $horaObj = DateTime::createFromFormat('H:i:s', $hora);
    if ($horaObj === false) {
        $horaObj = DateTime::createFromFormat('H:i', $hora);
    }

    if ($horaObj === false) {
        return $hora;
    }

    return $horaObj->format('H:i');
}

function getServicosAtivos(PDO $pdo): array
{
    $sql = 'SELECT id, nome, descricao, preco, duracao_minutos, ativo FROM servicos WHERE ativo = 1 ORDER BY nome ASC';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getPetsUsuario(PDO $pdo, int $usuarioId): array
{
    $sql = 'SELECT * FROM pets WHERE usuario_id = :usuario_id ORDER BY criado_em DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario_id' => $usuarioId]);
    return $stmt->fetchAll();
}

function getAgendamentosUsuario(PDO $pdo, int $usuarioId): array
{
    $sql = 'SELECT a.*, s.nome AS nome_servico, p.nome AS nome_pet
            FROM agendamentos a
            INNER JOIN servicos s ON s.id = a.servico_id
            INNER JOIN pets p ON p.id = a.pet_id
            WHERE a.usuario_id = :usuario_id
            ORDER BY a.data_agendamento DESC, a.horario DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario_id' => $usuarioId]);
    return $stmt->fetchAll();
}

function getAgendamentosAdmin(PDO $pdo): array
{
    $sql = 'SELECT a.*, u.nome AS nome_cliente, s.nome AS nome_servico, p.nome AS nome_pet
            FROM agendamentos a
            INNER JOIN usuarios u ON u.id = a.usuario_id
            INNER JOIN servicos s ON s.id = a.servico_id
            INNER JOIN pets p ON p.id = a.pet_id
            ORDER BY a.data_agendamento DESC, a.horario DESC';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getUsuarios(PDO $pdo, ?string $pesquisa = null): array
{
    $sql = 'SELECT * FROM usuarios';
    $params = [];

    if (!empty($pesquisa)) {
        $sql .= ' WHERE nome LIKE :pesquisa OR email LIKE :pesquisa OR telefone LIKE :pesquisa';
        $params['pesquisa'] = '%' . $pesquisa . '%';
    }

    $sql .= ' ORDER BY nome ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getUsuarioPorId(PDO $pdo, int $id): ?array
{
    $sql = 'SELECT * FROM usuarios WHERE id = :id LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $usuario = $stmt->fetch();
    return $usuario ?: null;
}

function gerarTokenRecuperacao(): string
{
    return bin2hex(random_bytes(32));
}

function cidadeBaseUrl(): string
{
    return rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'), '/');
}

function textoStatus(string $status): string
{
    $statusMap = [
        'pendente' => 'Pendente',
        'confirmado' => 'Confirmado',
        'concluido' => 'Concluído',
        'cancelado' => 'Cancelado'
    ];

    return $statusMap[$status] ?? ucfirst($status);
}

function statusClasse(string $status): string
{
    $map = [
        'pendente' => 'warning',
        'confirmado' => 'info',
        'concluido' => 'success',
        'cancelado' => 'danger'
    ];

    return $map[$status] ?? 'secondary';
}

function usuarioPorEmail(PDO $pdo, string $email): ?array
{
    $sql = 'SELECT * FROM usuarios WHERE email = :email LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => strtolower(trim($email))]);
    $usuario = $stmt->fetch();
    return $usuario ?: null;
}

function destruirSessao(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
