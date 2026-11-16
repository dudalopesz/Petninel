<?php
require_once __DIR__ . '/../includes/autenticacao.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'Usuário não informado.');
    redirect('admin.php');
}

$usuario = getUsuarioPorId($pdo, $id);
if (!$usuario) {
    setFlash('danger', 'Usuário não encontrado.');
    redirect('admin.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('editar_usuario.php?id=' . $id);
    }

    $nome = limparTexto($_POST['nome'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $telefone = limparTexto($_POST['telefone'] ?? '');
    $tipo = $_POST['tipo'] ?? 'cliente';

    if ($nome === '' || $email === '' || $telefone === '') {
        setFlash('danger', 'Preencha nome, e-mail e telefone.');
        redirect('editar_usuario.php?id=' . $id);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('danger', 'Informe um e-mail válido.');
        redirect('editar_usuario.php?id=' . $id);
    }

    $emailExiste = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email AND id != :id LIMIT 1');
    $emailExiste->execute(['email' => $email, 'id' => $id]);
    if ($emailExiste->fetch()) {
        setFlash('danger', 'Este e-mail já pertence a outro usuário.');
        redirect('editar_usuario.php?id=' . $id);
    }

    if ($usuario['tipo'] === 'admin' && $tipo !== 'admin') {
        setFlash('danger', 'Não é permitido alterar administrador para cliente.');
        redirect('editar_usuario.php?id=' . $id);
    }

    $stmt = $pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone, tipo = :tipo WHERE id = :id');
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone,
        'tipo' => $tipo,
        'id' => $id
    ]);

    setFlash('success', 'Usuário atualizado com sucesso.');
    redirect('admin.php');
}

$basePath = '../';
$tituloPagina = 'Editar usuário';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
?>

<section class="page-header">
    <div class="container">
        <h1>Editar usuário</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="form-shell">
            <form method="POST" action="editar_usuario.php?id=<?php echo (int) $id; ?>" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" <?php echo $usuario['tipo'] === 'admin' ? 'disabled' : ''; ?>>
                            <option value="cliente" <?php echo $usuario['tipo'] === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                            <option value="admin" <?php echo $usuario['tipo'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Salvar alterações</button>
                    <a href="admin.php" class="botao-secundario">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
