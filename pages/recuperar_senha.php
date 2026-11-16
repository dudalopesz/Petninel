<?php
require_once __DIR__ . '/../includes/autenticacao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('recuperar_senha.php');
    }

    $email = strtolower(trim($_POST['email'] ?? ''));
    if ($email === '') {
        setFlash('danger', 'Informe o e-mail cadastrado.');
        redirect('recuperar_senha.php');
    }

    $usuario = usuarioPorEmail($pdo, $email);
    if (!$usuario) {
        setFlash('danger', 'Nenhuma conta encontrada com esse e-mail.');
        redirect('recuperar_senha.php');
    }

    $token = gerarTokenRecuperacao();
    $expiracao = date('Y-m-d H:i:s', time() + 3600);

    $stmt = $pdo->prepare('INSERT INTO recuperacao_senha (usuario_id, token, expiracao, utilizado) VALUES (:usuario_id, :token, :expiracao, 0)');
    $stmt->execute([
        'usuario_id' => $usuario['id'],
        'token' => $token,
        'expiracao' => $expiracao
    ]);

    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/petninel/pages/nova_senha.php?token=' . urlencode($token);

    $mensagem = "Use o link a seguir para redefinir sua senha:\n\n{$link}\n\nSe você não solicitou, ignore este e-mail.";

    $modoTeste = true;
    if ($modoTeste) {
        setFlash('info', 'Fluxo de teste ativo: use este token para redefinir sua senha: ' . $token);
        redirect('recuperar_senha.php');
    }

    mail($email, 'Recuperação de senha Petninel', $mensagem);
    setFlash('success', 'E-mail de recuperação enviado com sucesso.');
    redirect('recuperar_senha.php');
}

$basePath = '../';
$tituloPagina = 'Recuperar senha';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
?>

<section class="page-header">
    <div class="container">
        <h1>Recuperar senha</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="form-shell">
            <form method="POST" action="recuperar_senha.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-grid">
                    <div class="form-group full">
                        <label for="email">E-mail cadastrado</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Enviar link de recuperação</button>
                    <a href="login.php" class="botao-secundario">Voltar ao login</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
