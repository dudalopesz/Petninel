<?php
require_once __DIR__ . '/../includes/autenticacao.php';
requireLogin();

$userId = (int) $_SESSION['usuario_id'];
$meusPets = getPetsUsuario($pdo, $userId);
$servicos = getServicosAtivos($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validarCsrf($_POST['csrf_token'])) {
        setFlash('danger', 'Sessão inválida. Tente novamente.');
        redirect('agendamento.php');
    }

    $petId = (int) ($_POST['pet_id'] ?? 0);
    $servicoId = (int) ($_POST['servico_id'] ?? 0);
    $dataAgendamento = $_POST['data_agendamento'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $observacoes = trim($_POST['observacoes'] ?? '');

    if ($petId <= 0 || $servicoId <= 0 || $dataAgendamento === '' || $horario === '') {
        setFlash('danger', 'Preencha todos os campos obrigatórios do agendamento.');
        redirect('agendamento.php');
    }

    $petValido = $pdo->prepare('SELECT id FROM pets WHERE id = :id AND usuario_id = :usuario_id LIMIT 1');
    $petValido->execute(['id' => $petId, 'usuario_id' => $userId]);
    if (!$petValido->fetch()) {
        setFlash('danger', 'Você só pode agendar para seus próprios pets.');
        redirect('agendamento.php');
    }

    $servicoValido = $pdo->prepare('SELECT id FROM servicos WHERE id = :id AND ativo = 1 LIMIT 1');
    $servicoValido->execute(['id' => $servicoId]);
    if (!$servicoValido->fetch()) {
        setFlash('danger', 'Serviço inválido ou indisponível.');
        redirect('agendamento.php');
    }

    $dataAtual = new DateTime('today');
    $dataSolicitada = new DateTime($dataAgendamento);
    if ($dataSolicitada < $dataAtual) {
        setFlash('danger', 'Não é permitido agendar em datas passadas.');
        redirect('agendamento.php');
    }

    $conflito = $pdo->prepare('SELECT id FROM agendamentos WHERE pet_id = :pet_id AND data_agendamento = :data_agendamento AND horario = :horario AND status IN ("pendente", "confirmado") LIMIT 1');
    $conflito->execute([
        'pet_id' => $petId,
        'data_agendamento' => $dataAgendamento,
        'horario' => $horario
    ]);

    if ($conflito->fetch()) {
        setFlash('danger', 'Já existe um agendamento para esse pet no mesmo dia e horário.');
        redirect('agendamento.php');
    }

    $stmt = $pdo->prepare('INSERT INTO agendamentos (usuario_id, pet_id, servico_id, data_agendamento, horario, observacoes, status) VALUES (:usuario_id, :pet_id, :servico_id, :data_agendamento, :horario, :observacoes, :status)');
    $stmt->execute([
        'usuario_id' => $userId,
        'pet_id' => $petId,
        'servico_id' => $servicoId,
        'data_agendamento' => $dataAgendamento,
        'horario' => $horario,
        'observacoes' => $observacoes,
        'status' => 'pendente'
    ]);

    setFlash('success', 'Agendamento realizado com sucesso! O status inicial é pendente.');
    redirect('meus_agendamentos.php');
}

$basePath = '../';
$tituloPagina = 'Agendamento';
include __DIR__ . '/../includes/header.php';
$csrfToken = gerarCsrfToken();
?>

<section class="page-header">
    <div class="container">
        <h1>Agende o cuidado do seu pet</h1>
    </div>
</section>

<?php exibirFlash(); ?>

<section class="section">
    <div class="container">
        <div class="form-shell">
            <form method="POST" action="agendamento.php" data-csrf="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="pet_id">Pet</label>
                        <select id="pet_id" name="pet_id" required>
                            <option value="">Selecione seu pet</option>
                            <?php foreach ($meusPets as $pet): ?>
                                <option value="<?php echo (int) $pet['id']; ?>"><?php echo htmlspecialchars($pet['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="servico_id">Serviço</label>
                        <select id="servico_id" name="servico_id" required>
                            <option value="">Selecione o serviço</option>
                            <?php foreach ($servicos as $servico): ?>
                                <option value="<?php echo (int) $servico['id']; ?>"><?php echo htmlspecialchars($servico['nome']); ?> - R$ <?php echo number_format((float) $servico['preco'], 2, ',', '.'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="data_agendamento">Data</label>
                        <input type="date" id="data_agendamento" name="data_agendamento" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="horario">Horário</label>
                        <input type="time" id="horario" name="horario" required>
                    </div>

                    <div class="form-group full">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" placeholder="Descreva qualquer necessidade especial do seu pet."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Salvar agendamento</button>
                    <a href="meus_agendamentos.php" class="botao-secundario">Ver meus agendamentos</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
