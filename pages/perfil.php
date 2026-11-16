<?php
require_once __DIR__ . '/../includes/autenticacao.php';
requireLogin();

$usuarioId = (int) $_SESSION['usuario_id'];
$usuario = getUsuarioPorId($pdo, $usuarioId);

if (!$usuario) {
    destruirSessao();
    setFlash('danger', 'Usuário não encontrado. Faça login novamente.');
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('perfil.php');
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'atualizar_perfil') {
        $nome = limparTexto($_POST['nome'] ?? '');
        $telefone = limparTexto($_POST['telefone'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));

        if ($nome === '' || $telefone === '' || $email === '') {
            setFlash('danger', 'Preencha nome, telefone e e-mail.');
            redirect('perfil.php');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlash('danger', 'Informe um e-mail válido.');
            redirect('perfil.php');
        }

        $emailExiste = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email AND id != :id LIMIT 1');
        $emailExiste->execute(['email' => $email, 'id' => $usuarioId]);
        if ($emailExiste->fetch()) {
            setFlash('danger', 'Este e-mail já pertence a outro usuário.');
            redirect('perfil.php');
        }

        $stmt = $pdo->prepare('UPDATE usuarios SET nome = :nome, telefone = :telefone, email = :email WHERE id = :id');
        $stmt->execute([
            'nome' => $nome,
            'telefone' => $telefone,
            'email' => $email,
            'id' => $usuarioId
        ]);

        $_SESSION['usuario_nome'] = $nome;
        setFlash('success', 'Dados do perfil atualizados com sucesso.');
        redirect('perfil.php');
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'alterar_senha') {
        $senhaAtual = $_POST['senha_atual'] ?? '';
        $novaSenha = $_POST['nova_senha'] ?? '';
        $confirmacao = $_POST['confirmacao_senha'] ?? '';

        if ($senhaAtual === '' || $novaSenha === '' || $confirmacao === '') {
            setFlash('danger', 'Preencha todos os campos da senha.');
            redirect('perfil.php');
        }

        if (!password_verify($senhaAtual, $usuario['senha'])) {
            setFlash('danger', 'A senha atual está incorreta.');
            redirect('perfil.php');
        }

        if (strlen($novaSenha) < 6) {
            setFlash('danger', 'A nova senha deve ter pelo menos 6 caracteres.');
            redirect('perfil.php');
        }

        if ($novaSenha !== $confirmacao) {
            setFlash('danger', 'A nova senha e a confirmação devem ser iguais.');
            redirect('perfil.php');
        }

        $stmt = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
        $stmt->execute([
            'senha' => password_hash($novaSenha, PASSWORD_DEFAULT),
            'id' => $usuarioId
        ]);

        setFlash('success', 'Senha alterada com sucesso.');
        redirect('perfil.php');
    }
}

$basePath = '../';
$tituloPagina = 'Perfil';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
$meusPets = getPetsUsuario($pdo, $usuarioId);
$meusAgendamentos = getAgendamentosUsuario($pdo, $usuarioId);
?>

<section class="page-header">
    <div class="container">
        <h1>Meu perfil</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="form-shell">
            <h2 class="panel-title">Dados pessoais</h2>
            <form method="POST" action="perfil.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="acao" value="atualizar_perfil">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group full">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Salvar alterações</button>
                </div>
            </form>
        </div>

        <div class="form-shell">
            <h2 class="panel-title">Alterar senha</h2>
            <form method="POST" action="perfil.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="acao" value="alterar_senha">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="senha_atual">Senha atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" required>
                    </div>

                    <div class="form-group">
                        <label for="nova_senha">Nova senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
                    </div>

                    <div class="form-group full">
                        <label for="confirmacao_senha">Confirmar nova senha</label>
                        <input type="password" id="confirmacao_senha" name="confirmacao_senha" required minlength="6">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Alterar senha</button>
                </div>
            </form>
        </div>

        <div class="panel">
            <h2 class="panel-title">Pets cadastrados</h2>
            <?php if (empty($meusPets)): ?>
                <p class="muted">Você ainda não cadastrou nenhum pet.</p>
                <p><a href="agendamento.php" class="botao">Cadastrar pet e agendar</a></p>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Espécie</th>
                                <th>Raça</th>
                                <th>Idade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($meusPets as $pet): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pet['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($pet['especie']); ?></td>
                                    <td><?php echo htmlspecialchars($pet['raca'] ?: 'Não informado'); ?></td>
                                    <td><?php echo (int) $pet['idade']; ?> ano(s)</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="panel" style="margin-top: 2rem;">
            <h2 class="panel-title">Meus agendamentos</h2>
            <?php if (empty($meusAgendamentos)): ?>
                <p class="muted">Você ainda não possui agendamentos.</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pet</th>
                                <th>Serviço</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($meusAgendamentos as $agendamento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($agendamento['nome_pet']); ?></td>
                                    <td><?php echo htmlspecialchars($agendamento['nome_servico']); ?></td>
                                    <td><?php echo htmlspecialchars(formatarData($agendamento['data_agendamento'])); ?></td>
                                    <td><?php echo htmlspecialchars(formatarHora($agendamento['horario'])); ?></td>
                                    <td><span class="badge badge-<?php echo statusClasse($agendamento['status']); ?>"><?php echo htmlspecialchars(textoStatus($agendamento['status'])); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
