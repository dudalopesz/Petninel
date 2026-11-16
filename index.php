<?php
$basePath = '';
$tituloPagina = 'Home';
require_once __DIR__ . '/includes/autenticacao.php';

$servicos = getServicosAtivos($pdo);
$agendamentos = [];
if (usuarioLogado()) {
    $agendamentos = getAgendamentosUsuario($pdo, (int) $_SESSION['usuario_id']);
}

include __DIR__ . '/includes/header.php';
?>

<section class="banner">
    <div class="banner-texto">
        <?php if (usuarioLogado()): ?>
            <h1>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8'); ?>!</h1>
            <p>Que bom ter você no Petninel. Vamos cuidar do seu melhor amigo com carinho e atenção?</p>
            <div class="inline-actions">
                <a href="/petninel/pages/agendamento.php" class="botao">Agendar serviço</a>
                <a href="/petninel/pages/perfil.php" class="botao-secundario">Meu perfil</a>
            </div>
        <?php else: ?>
            <h1>Seu pet merece todo amor e cuidado!</h1>
            <p>No Petninel, seu melhor amigo recebe banho, tosa, hidratação e atendimento com carinho, higiene e qualidade.</p>
            <div class="inline-actions">
                <a href="/petninel/pages/cadastro.php" class="botao">Comece agora</a>
                <a href="/petninel/pages/login.php" class="botao-secundario">Entrar</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="banner-imagem">
        <img src="assets/img/cachorro-banner.png" alt="Pet recebendo carinho no Petninel">
    </div>
</section>

<section class="section">
    <div class="container">
        <h2>Sobre o Petninel</h2>
        <p class="section-subtitulo">Somos um pet shop dedicado ao bem-estar animal, com atendimento acolhedor, serviços de qualidade e cuidados personalizados para cachorros e gatos.</p>
    </div>
</section>

<section class="section" id="servicos">
    <div class="container">
        <h2>Nossos serviços</h2>
        <p class="section-subtitulo">Escolha o cuidado ideal para seu pet e agende com facilidade.</p>

        <div class="cards">
            <?php foreach ($servicos as $servico): ?>
                <div class="card">
                    <div class="card-icon">🐾</div>
                    <h3><?php echo htmlspecialchars($servico['nome']); ?></h3>
                    <p><?php echo htmlspecialchars($servico['descricao']); ?></p>
                    <p class="muted"><strong>R$ <?php echo number_format((float) $servico['preco'], 2, ',', '.'); ?></strong></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (usuarioLogado()): ?>
    <section class="section">
        <div class="container">
            <h2>Minha área</h2>
            <div class="stats-grid">
                <div class="stat-box">
                    <strong><?php echo count($agendamentos); ?></strong>
                    <span>Agendamentos</span>
                </div>
                <div class="stat-box">
                    <strong><?php echo count(getPetsUsuario($pdo, (int) $_SESSION['usuario_id'])); ?></strong>
                    <span>Pets cadastrados</span>
                </div>
                <div class="stat-box">
                    <strong><?php echo htmlspecialchars($_SESSION['usuario_tipo'] === 'admin' ? 'Admin' : 'Cliente'); ?></strong>
                    <span>Perfil</span>
                </div>
                <div class="stat-box">
                    <strong><?php echo htmlspecialchars(date('d/m')); ?></strong>
                    <span>Hoje</span>
                </div>
            </div>

            <?php if (!empty($agendamentos)): ?>
                <div class="panel">
                    <h3 class="panel-title">Próximos agendamentos</h3>
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
                                <?php foreach (array_slice($agendamentos, 0, 5) as $agendamento): ?>
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
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<section class="chamada">
    <div class="container">
        <h2>Cuide de quem sempre está ao seu lado!</h2>
        <p>Cadastre-se e agende um atendimento com conforto, segurança e muito carinho.</p>
        <?php if (usuarioLogado()): ?>
            <a href="/petninel/pages/agendamento.php" class="botao">Fazer agendamento</a>
        <?php else: ?>
            <a href="/petninel/pages/cadastro.php" class="botao">Criar minha conta</a>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
