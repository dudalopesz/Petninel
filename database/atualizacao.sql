-- Atualização do banco Petninel
-- Mantém os dados atuais e acrescenta ajustes necessários para autenticação, recuperação e gestão de agendamentos.

USE petninel;

ALTER TABLE usuarios
  MODIFY COLUMN tipo ENUM('cliente','admin') NOT NULL DEFAULT 'cliente';

ALTER TABLE recuperacao_senha
  ADD COLUMN IF NOT EXISTS usuario_id INT NOT NULL AFTER id,
  ADD COLUMN IF NOT EXISTS token VARCHAR(255) NOT NULL UNIQUE AFTER usuario_id,
  ADD COLUMN IF NOT EXISTS expiracao DATETIME NOT NULL AFTER token,
  ADD COLUMN IF NOT EXISTS utilizado TINYINT(1) NOT NULL DEFAULT 0 AFTER expiracao;

ALTER TABLE agendamentos
  MODIFY COLUMN status ENUM('pendente','confirmado','concluido','cancelado') NOT NULL DEFAULT 'pendente';

-- Garantia de integridade para relacionamentos
ALTER TABLE pets
  ADD CONSTRAINT fk_pets_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE;

ALTER TABLE agendamentos
  ADD CONSTRAINT fk_agendamentos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_agendamentos_pet FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_agendamentos_servico FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE RESTRICT;

ALTER TABLE recuperacao_senha
  ADD CONSTRAINT fk_recuperacao_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE;

-- Serviço padrão já cadastrado
INSERT INTO servicos (nome, descricao, preco, duracao_minutos, ativo)
SELECT 'Banho', 'Banho completo para o pet', 40.00, 60, 1
WHERE NOT EXISTS (SELECT 1 FROM servicos WHERE nome = 'Banho');

INSERT INTO servicos (nome, descricao, preco, duracao_minutos, ativo)
SELECT 'Tosa', 'Tosa higiênica e estética', 50.00, 60, 1
WHERE NOT EXISTS (SELECT 1 FROM servicos WHERE nome = 'Tosa');

INSERT INTO servicos (nome, descricao, preco, duracao_minutos, ativo)
SELECT 'Banho e Tosa', 'Pacote completo', 80.00, 120, 1
WHERE NOT EXISTS (SELECT 1 FROM servicos WHERE nome = 'Banho e Tosa');

INSERT INTO servicos (nome, descricao, preco, duracao_minutos, ativo)
SELECT 'Corte de Unhas', 'Corte e cuidado das unhas', 20.00, 20, 1
WHERE NOT EXISTS (SELECT 1 FROM servicos WHERE nome = 'Corte de Unhas');

INSERT INTO servicos (nome, descricao, preco, duracao_minutos, ativo)
SELECT 'Hidratação', 'Hidratação dos pelos', 35.00, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM servicos WHERE nome = 'Hidratação');
