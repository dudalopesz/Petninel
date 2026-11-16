<?php
require_once __DIR__ . '/../includes/autenticacao.php';

if (usuarioLogado()) {
    redirect('../index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('cadastro.php');
    }

    $nome = limparTexto($_POST['nome'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $telefone = limparTexto($_POST['telefone'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmacao = $_POST['confirmacao'] ?? '';

    if ($nome === '' || $email === '' || $telefone === '' || $senha === '' || $confirmacao === '') {
        setFlash('danger', 'Preencha todos os campos obrigatórios.');
        redirect('cadastro.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('danger', 'Informe um e-mail válido.');
        redirect('cadastro.php');
    }

    if ($senha !== $confirmacao) {
        setFlash('danger', 'A senha e a confirmação precisam ser iguais.');
        redirect('cadastro.php');
    }

    if (strlen($senha) < 6) {
        setFlash('danger', 'A senha deve conter pelo menos 6 caracteres.');
        redirect('cadastro.php');
    }

    if (usuarioPorEmail($pdo, $email)) {
        setFlash('danger', 'Esse e-mail já está cadastrado no sistema.');
        redirect('cadastro.php');
    }

    $hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO usuarios (nome, email, telefone, senha, tipo) VALUES (:nome, :email, :telefone, :senha, :tipo)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone,
        'senha' => $hash,
        'tipo' => 'cliente'
    ]);

    setFlash('success', 'Cadastro realizado com sucesso! Agora você pode fazer login.');
    redirect('login.php');
}

$basePath = '../';
$tituloPagina = 'Cadastro';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
?>

<section class="page-header">
    <div class="container">
        <h1>Crie sua conta</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="form-shell">
            <form method="POST" action="cadastro.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-grid">
                    <div class="form-group full">
                        <label for="nome">Nome completo</label>
                        <input type="text" id="nome" name="nome" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required maxlength="150">
                    </div>

                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" required maxlength="20">
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="confirmacao">Confirmar senha</label>
                        <input type="password" id="confirmacao" name="confirmacao" required minlength="6">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Cadastrar</button>
                    <a href="login.php" class="botao-secundario">Já tenho conta</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
