<?php
require_once __DIR__ . '/../includes/autenticacao.php';

if (usuarioLogado()) {
    redirect('../index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('login.php');
    }

    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        setFlash('danger', 'Informe e-mail e senha para continuar.');
        redirect('login.php');
    }

    $usuario = usuarioPorEmail($pdo, $email);
    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        setFlash('danger', 'Credenciais inválidas. Verifique seu e-mail e senha.');
        redirect('login.php');
    }

    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int) $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'];

    setFlash('success', 'Login realizado com sucesso!');

    if ($usuario['tipo'] === 'admin') {
        redirect('admin.php');
    }

    redirect('../index.php');
}

$basePath = '../';
$tituloPagina = 'Login';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
?>

<section class="page-header">
    <div class="container">
        <h1>Entrar no Petninel</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="form-shell">
            <form method="POST" action="login.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-grid">
                    <div class="form-group full">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group full">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Entrar</button>
                    <a href="recuperar_senha.php" class="botao-secundario">Esqueci a senha</a>
                </div>

                <p class="form-meta">Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
