<?php
require_once __DIR__ . '/../includes/autenticacao.php';
requireAdmin();

$usuarios = getUsuarios($pdo, $_GET['pesquisa'] ?? null);
$agendamentos = getAgendamentosAdmin($pdo);
$servicos = getServicosAtivos($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('admin.php');
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'alterar_status') {
        $id = (int) ($_POST['agendamento_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($id > 0 && in_array($status, ['pendente', 'confirmado', 'concluido', 'cancelado'], true)) {
            $stmt = $pdo->prepare('UPDATE agendamentos SET status = :status WHERE id = :id');
            $stmt->execute(['status' => $status, 'id' => $id]);
            setFlash('success', 'Status do agendamento atualizado com sucesso.');
        }

        redirect('admin.php');
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'excluir_usuario') {
        $id = (int) ($_POST['usuario_id'] ?? 0);
        if ($id > 0 && $id !== (int) $_SESSION['usuario_id']) {
            $usuario = getUsuarioPorId($pdo, $id);
            if ($usuario && $usuario['tipo'] !== 'admin') {
                $pdo->prepare('DELETE FROM agendamentos WHERE usuario_id = :usuario_id')->execute(['usuario_id' => $id]);
                $pdo->prepare('DELETE FROM pets WHERE usuario_id = :usuario_id')->execute(['usuario_id' => $id]);
                $pdo->prepare('DELETE FROM usuarios WHERE id = :id')->execute(['id' => $id]);
                setFlash('success', 'Usuário removido com sucesso.');
            }
        }
        redirect('admin.php');
    }
}

$basePath = '../';
$tituloPagina = 'Administração';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
?>

<section class="page-header">
    <div class="container">
        <h1>Painel administrativo</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="inline-actions" style="margin-bottom:1.5rem;">
            <a href="promover_admin.php" class="botao-secundario">Promover administrador</a>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <strong><?php echo count($usuarios); ?></strong>
                <span>Usuários</span>
            </div>
            <div class="stat-box">
                <strong><?php echo count($agendamentos); ?></strong>
                <span>Agendamentos</span>
            </div>
            <div class="stat-box">
                <strong><?php echo count($servicos); ?></strong>
                <span>Serviços ativos</span>
            </div>
            <div class="stat-box">
                <strong><?php echo count(array_filter($agendamentos, fn($a) => $a['status'] === 'pendente')); ?></strong>
                <span>Pendentes</span>
            </div>
        </div>

        <div class="panel">
            <h2 class="panel-title">Buscar usuários</h2>
            <form method="GET" action="admin.php" class="search-form">
                <input type="text" name="pesquisa" value="<?php echo htmlspecialchars($_GET['pesquisa'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Pesquisar por nome, e-mail ou telefone">
                <button type="submit">Pesquisar</button>
            </form>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['telefone'] ?? '-'); ?></td>
                                <td><span class="badge badge-<?php echo $usuario['tipo'] === 'admin' ? 'info' : 'warning'; ?>"><?php echo htmlspecialchars($usuario['tipo'] === 'admin' ? 'Admin' : 'Cliente'); ?></span></td>
                                <td class="inline-actions">
                                    <a href="editar_usuario.php?id=<?php echo (int) $usuario['id']; ?>" class="botao-secundario">Editar</a>
                                    <?php if ((int) $usuario['id'] !== (int) $_SESSION['usuario_id'] && $usuario['tipo'] !== 'admin'): ?>
                                        <form method="POST" action="admin.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="acao" value="excluir_usuario">
                                            <input type="hidden" name="usuario_id" value="<?php echo (int) $usuario['id']; ?>">
                                            <button type="submit" data-confirm="Deseja excluir este usuário?">Excluir</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel" style="margin-top: 2rem;">
            <h2 class="panel-title">Agendamentos do sistema</h2>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Pet</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Atualizar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($agendamento['nome_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['nome_pet']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['nome_servico']); ?></td>
                                <td><?php echo htmlspecialchars(formatarData($agendamento['data_agendamento'])); ?></td>
                                <td><span class="badge badge-<?php echo statusClasse($agendamento['status']); ?>"><?php echo htmlspecialchars(textoStatus($agendamento['status'])); ?></span></td>
                                <td>
                                    <form method="POST" action="admin.php" class="inline-actions" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="acao" value="alterar_status">
                                        <input type="hidden" name="agendamento_id" value="<?php echo (int) $agendamento['id']; ?>">
                                        <select name="status">
                                            <option value="pendente" <?php echo $agendamento['status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                                            <option value="confirmado" <?php echo $agendamento['status'] === 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                                            <option value="concluido" <?php echo $agendamento['status'] === 'concluido' ? 'selected' : ''; ?>>Concluído</option>
                                            <option value="cancelado" <?php echo $agendamento['status'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                        </select>
                                        <button type="submit">Salvar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
