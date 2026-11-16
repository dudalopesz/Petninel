<?php
require_once __DIR__ . '/../includes/autenticacao.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('promover_admin.php');
    }

    $usuarioId = (int) ($_POST['usuario_id'] ?? 0);
    $senhaAdmin = $_POST['senha_admin'] ?? '';

    if ($usuarioId <= 0 || $senhaAdmin === '') {
        setFlash('danger', 'Selecione um usuário e confirme sua senha de administrador.');
        redirect('promover_admin.php');
    }

    $adminAtual = getUsuarioPorId($pdo, (int) $_SESSION['usuario_id']);
    if (!$adminAtual || !password_verify($senhaAdmin, $adminAtual['senha'])) {
        setFlash('danger', 'Senha do administrador incorreta.');
        redirect('promover_admin.php');
    }

    $usuarioDestino = getUsuarioPorId($pdo, $usuarioId);
    if (!$usuarioDestino) {
        setFlash('danger', 'Usuário não encontrado.');
        redirect('promover_admin.php');
    }

    if ($usuarioDestino['tipo'] === 'admin') {
        setFlash('info', 'Este usuário já é administrador.');
        redirect('promover_admin.php');
    }

    $stmt = $pdo->prepare('UPDATE usuarios SET tipo = :tipo WHERE id = :id');
    $stmt->execute(['tipo' => 'admin', 'id' => $usuarioId]);

    setFlash('success', 'Usuário promovido a administrador com sucesso.');
    redirect('admin.php');
}

$basePath = '../';
$tituloPagina = 'Promover administrador';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
$usuarios = getUsuarios($pdo);
?>

<section class="page-header">
    <div class="container">
        <h1>Promover administrador</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="form-shell">
            <form method="POST" action="promover_admin.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-grid">
                    <div class="form-group full">
                        <label for="usuario_id">Usuário</label>
                        <select id="usuario_id" name="usuario_id" required>
                            <option value="">Selecione um usuário</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <?php if ($usuario['tipo'] !== 'admin'): ?>
                                    <option value="<?php echo (int) $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['nome']) . ' — ' . htmlspecialchars($usuario['email']); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label for="senha_admin">Sua senha de administrador</label>
                        <input type="password" id="senha_admin" name="senha_admin" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Promover</button>
                    <a href="admin.php" class="botao-secundario">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
