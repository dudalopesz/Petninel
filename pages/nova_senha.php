<?php
require_once __DIR__ . '/../includes/autenticacao.php';

$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('nova_senha.php?token=' . urlencode($_POST['token'] ?? ''));
    }

    $token = $_POST['token'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmacao = $_POST['confirmacao_senha'] ?? '';

    if ($token === '' || $novaSenha === '' || $confirmacao === '') {
        setFlash('danger', 'Token e senha são obrigatórios.');
        redirect('nova_senha.php?token=' . urlencode($token));
    }

    $stmt = $pdo->prepare('SELECT * FROM recuperacao_senha WHERE token = :token AND utilizado = 0 AND expiracao > NOW() LIMIT 1');
    $stmt->execute(['token' => $token]);
    $solicitacao = $stmt->fetch();

    if (!$solicitacao) {
        setFlash('danger', 'Token inválido ou expirado. Solicite uma nova recuperação.');
        redirect('recuperar_senha.php');
    }

    if (strlen($novaSenha) < 6) {
        setFlash('danger', 'A nova senha deve ter pelo menos 6 caracteres.');
        redirect('nova_senha.php?token=' . urlencode($token));
    }

    if ($novaSenha !== $confirmacao) {
        setFlash('danger', 'A nova senha e a confirmação devem ser iguais.');
        redirect('nova_senha.php?token=' . urlencode($token));
    }

    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $updateUsuario = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
    $updateUsuario->execute(['senha' => $hash, 'id' => $solicitacao['usuario_id']]);

    $updateToken = $pdo->prepare('UPDATE recuperacao_senha SET utilizado = 1 WHERE id = :id');
    $updateToken->execute(['id' => $solicitacao['id']]);

    setFlash('success', 'Senha redefinida com sucesso! Faça login com a nova senha.');
    redirect('login.php');
}

$basePath = '../';
$tituloPagina = 'Nova senha';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
?>

<section class="page-header">
    <div class="container">
        <h1>Defina uma nova senha</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="form-shell">
            <?php if (empty($token)): ?>
                <p class="muted">Token de recuperação não informado.</p>
                <a href="recuperar_senha.php" class="botao">Solicitar nova recuperação</a>
            <?php else: ?>
                <form method="POST" action="nova_senha.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="nova_senha">Nova senha</label>
                            <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
                        </div>

                        <div class="form-group full">
                            <label for="confirmacao_senha">Confirmar nova senha</label>
                            <input type="password" id="confirmacao_senha" name="confirmacao_senha" required minlength="6">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit">Salvar nova senha</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
