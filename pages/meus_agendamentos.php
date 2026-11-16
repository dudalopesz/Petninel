<?php
require_once __DIR__ . '/../includes/autenticacao.php';
requireLogin();

$userId = (int) $_SESSION['usuario_id'];
$agendamentos = getAgendamentosUsuario($pdo, $userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('meus_agendamentos.php');
    }

    $acao = $_POST['acao'] ?? '';
    $id = (int) ($_POST['agendamento_id'] ?? 0);

    if ($acao === 'cancelar' && $id > 0) {
        $stmt = $pdo->prepare('SELECT status FROM agendamentos WHERE id = :id AND usuario_id = :usuario_id LIMIT 1');
        $stmt->execute(['id' => $id, 'usuario_id' => $userId]);
        $agendamento = $stmt->fetch();

        if (!$agendamento) {
            setFlash('danger', 'Agendamento não encontrado.');
            redirect('meus_agendamentos.php');
        }

        if (in_array($agendamento['status'], ['cancelado', 'concluido'], true)) {
            setFlash('danger', 'Esse agendamento não pode ser cancelado.');
            redirect('meus_agendamentos.php');
        }

        $update = $pdo->prepare('UPDATE agendamentos SET status = :status WHERE id = :id AND usuario_id = :usuario_id');
        $update->execute(['status' => 'cancelado', 'id' => $id, 'usuario_id' => $userId]);

        setFlash('success', 'Agendamento cancelado com sucesso.');
        redirect('meus_agendamentos.php');
    }
}

$basePath = '../';
$tituloPagina = 'Meus agendamentos';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
?>

<section class="page-header">
    <div class="container">
        <h1>Meus agendamentos</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="panel">
            <?php if (empty($agendamentos)): ?>
                <p class="muted">Você ainda não cadastrou nenhum agendamento.</p>
                <a href="agendamento.php" class="botao">Agendar agora</a>
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
                                <th>Cancelar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($agendamentos as $agendamento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($agendamento['nome_pet']); ?></td>
                                    <td><?php echo htmlspecialchars($agendamento['nome_servico']); ?></td>
                                    <td><?php echo htmlspecialchars(formatarData($agendamento['data_agendamento'])); ?></td>
                                    <td><?php echo htmlspecialchars(formatarHora($agendamento['horario'])); ?></td>
                                    <td><span class="badge badge-<?php echo statusClasse($agendamento['status']); ?>"><?php echo htmlspecialchars(textoStatus($agendamento['status'])); ?></span></td>
                                    <td>
                                        <?php if (!in_array($agendamento['status'], ['cancelado', 'concluido'], true)): ?>
                                            <form method="POST" action="meus_agendamentos.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="acao" value="cancelar">
                                                <input type="hidden" name="agendamento_id" value="<?php echo (int) $agendamento['id']; ?>">
                                                <button type="submit" data-confirm="Deseja cancelar este agendamento?">Cancelar</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="muted">Não disponível</span>
                                        <?php endif; ?>
                                    </td>
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
