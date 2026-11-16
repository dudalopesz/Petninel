# Matriz de Rastreabilidade

| Código do caso de teste | Descrição resumida | Código do requisito funcional | Tela relacionada |
| --- | --- | --- | --- |
| CT-HOM-01 | Página inicial para visitante | RF-HOM-01, RF-HOM-03 | Home |
| CT-HOM-02 | Página inicial para usuário autenticado | RF-HOM-02, RF-HOM-04 | Home |
| CT-HOM-03 | Listagem de serviços | RF-HOM-01 | Home |
| CT-HOM-04 | Navegação do menu | RF-HOM-03 | Home |
| CT-HOM-05 | Logout | RF-HOM-04 | Home |
| CT-CAD-01 | Cadastro válido | RF-CAD-01, RF-CAD-05 | Cadastro |
| CT-CAD-02 | E-mail duplicado | RF-CAD-03 | Cadastro |
| CT-CAD-03 | Confirmação de senha | RF-CAD-02 | Cadastro |
| CT-CAD-04 | Senha curta | RF-CAD-02 | Cadastro |
| CT-CAD-05 | E-mail inválido | RF-CAD-01 | Cadastro |
| CT-LOG-01 | Login válido | RF-LOG-01, RF-LOG-04 | Login |
| CT-LOG-02 | Credenciais inválidas | RF-LOG-05 | Login |
| CT-LOG-03 | Login de administrador | RF-LOG-02, RF-LOG-04 | Login |
| CT-LOG-04 | Acesso sem autenticação | RF-LOG-03 | Login |
| CT-LOG-05 | Sessão segura | RF-LOG-03 | Login |
| CT-PER-01 | Visualização do perfil | RF-PER-01 | Perfil |
| CT-PER-02 | Atualização de dados | RF-PER-02 | Perfil |
| CT-PER-03 | E-mail duplicado no perfil | RF-PER-03 | Perfil |
| CT-PER-04 | Alteração de senha | RF-PER-04 | Perfil |
| CT-PER-05 | Visualização de agendamentos | RF-PER-05 | Perfil |
| CT-AGE-01 | Agendamento válido | RF-AGE-01, RF-AGE-06 | Agendamento |
| CT-AGE-02 | Agendamento de pet de outro usuário | RF-AGE-02 | Agendamento |
| CT-AGE-03 | Data passada | RF-AGE-03 | Agendamento |
| CT-AGE-04 | Conflito de horário | RF-AGE-05 | Agendamento |
| CT-AGE-05 | Cancelamento de agendamento | RF-AGE-06 | Agendamento |
| CT-REC-01 | Solicitação de recuperação | RF-REC-01 | Redefinição de Senha |
| CT-REC-02 | E-mail inexistente | RF-REC-01 | Redefinição de Senha |
| CT-REC-03 | Token expirado | RF-REC-03 | Redefinição de Senha |
| CT-REC-04 | Senha curta | RF-REC-05 | Redefinição de Senha |
| CT-REC-05 | Redefinição bem-sucedida | RF-REC-04, RF-REC-05 | Redefinição de Senha |
| CT-ADM-01 | Acesso de administrador | RF-ADM-01 | Administração |
| CT-ADM-02 | Acesso negado para cliente | RF-ADM-01 | Administração |
| CT-ADM-03 | Pesquisa de usuários | RF-ADM-03 | Administração |
| CT-ADM-04 | Alteração de status | RF-ADM-06 | Administração |
| CT-ADM-05 | Exclusão de usuário comum | RF-ADM-05 | Administração |
| CT-BD-01 | Integridade dos relacionamentos | RF-TEC-01 | Banco de Dados |
| CT-BD-02 | Senhas protegidas | RF-TEC-02 | Banco de Dados |
| CT-BD-03 | Persistência e consistência | RF-TEC-03 | Banco de Dados |

## Requisitos técnicos de banco
- RF-TEC-01: Os relacionamentos entre usuários, pets, serviços e agendamentos devem se manter consistentes.
- RF-TEC-02: As senhas devem ser armazenadas em hash protegido.
- RF-TEC-03: Os dados devem persistir de forma consistente no banco.
