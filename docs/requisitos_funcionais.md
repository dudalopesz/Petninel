# Requisitos Funcionais do Petninel

## Home
- RF-HOM-01: Exibir apresentação do pet shop.
- RF-HOM-02: Exibir serviços disponíveis.
- RF-HOM-03: Exibir opções de cadastro e login para usuários não autenticados.
- RF-HOM-04: Exibir boas-vindas com nome para usuários autenticados.
- RF-HOM-05: Exibir área de perfil e agendamentos do cliente autenticado.

## Cadastro
- RF-CAD-01: O cadastro deve conter nome, e-mail, telefone e senha.
- RF-CAD-02: Validar senha e confirmação.
- RF-CAD-03: Impedir e-mail duplicado.
- RF-CAD-04: Armazenar senha com `password_hash()`.
- RF-CAD-05: Redirecionar para login após cadastro bem-sucedido.

## Login
- RF-LOG-01: Validar e-mail e senha.
- RF-LOG-02: Utilizar `password_verify()`.
- RF-LOG-03: Criar e manter sessão segura.
- RF-LOG-04: Redirecionar cliente e administrador para áreas corretas.
- RF-LOG-05: Exibir mensagens para credenciais inválidas.

## Perfil
- RF-PER-01: Visualizar dados do usuário logado.
- RF-PER-02: Editar nome, telefone e e-mail.
- RF-PER-03: Validar duplicidade de e-mail.
- RF-PER-04: Alterar senha com validação da senha atual.
- RF-PER-05: Consultar pets e agendamentos próprios.

## Agendamento
- RF-AGE-01: Selecionar pet, serviço, data e horário.
- RF-AGE-02: Exibir apenas pets do usuário logado.
- RF-AGE-03: Não permitir datas passadas.
- RF-AGE-04: Validar campos obrigatórios.
- RF-AGE-05: Evitar conflitos de agendamento no mesmo horário.
- RF-AGE-06: Salvar status inicial como pendente.

## Redefinição de Senha
- RF-REC-01: Solicitar e-mail cadastrado.
- RF-REC-02: Gerar token aleatório e seguro.
- RF-REC-03: Validar expiração e reutilização do token.
- RF-REC-04: Permitir redefinição de senha.
- RF-REC-05: Armazenar nova senha com `password_hash()`.

## Administração
- RF-ADM-01: Somente administradores têm acesso.
- RF-ADM-02: Listar usuários cadastrados.
- RF-ADM-03: Pesquisar usuários por dados básicos.
- RF-ADM-04: Editar usuários com validações.
- RF-ADM-05: Excluir usuários não administradores.
- RF-ADM-06: Alterar status dos agendamentos.
- RF-ADM-07: Visualizar serviços ativos.
