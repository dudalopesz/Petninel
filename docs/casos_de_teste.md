# Casos de Teste – Petninel

## Home
- CT-HOM-01: Projeto; Tela: Home; Objetivo: Validar apresentação da página inicial quando o usuário não está logado; Pré-condição: Usuário não autenticado; Passo a passo: Acessar a página inicial; Resultado esperado: Exibir apresentação e botões de cadastro/login; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-HOM-02: Projeto; Tela: Home; Objetivo: Validar página inicial para usuário autenticado; Pré-condição: Usuário logado; Passo a passo: Acessar a página inicial; Resultado esperado: Exibir mensagem personaliza e links de perfil/agendamento; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-HOM-03: Projeto; Tela: Home; Objetivo: Verificar listagem de serviços; Pré-condição: Banco com serviços cadastrados; Passo a passo: Carregar a home; Resultado esperado: Exibir os cinco serviços; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-HOM-04: Projeto; Tela: Home; Objetivo: Verificar navegação do menu; Pré-condição: Sistema acessível; Passo a passo: Clicar em links do menu; Resultado esperado: Redirecionar para páginas corretas; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-HOM-05: Projeto; Tela: Home; Objetivo: Verificar comportamento de logout; Pré-condição: Usuário logado; Passo a passo: Clicar em sair; Resultado esperado: Encerrar sessão e voltar para home pública; Resultado obtido: ; Situação: ; Comentário: ; Print: ;

## Cadastro
- CT-CAD-01: Projeto; Tela: Cadastro; Objetivo: Validar cadastro com dados válidos; Pré-condição: Usuário não cadastrado; Passo a passo: preencher nome, e-mail, telefone, senha e confirmação; Resultado esperado: Cadastro realizado com sucesso; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-CAD-02: Projeto; Tela: Cadastro; Objetivo: Validar e-mail duplicado; Pré-condição: Usuário já cadastrado; Passo a passo: cadastrar com e-mail repetido; Resultado esperado: Exibir erro e impedir cadastro; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-CAD-03: Projeto; Tela: Cadastro; Objetivo: Validar senha divergente; Pré-condição: Formulário aberto; Passo a passo: informar senhas diferentes; Resultado esperado: Exibir erro de confirmação; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-CAD-04: Projeto; Tela: Cadastro; Objetivo: Validar senha curta; Pré-condição: Formulário aberto; Passo a passo: informar senha menor que 6 caracteres; Resultado esperado: Exibir mensagem de validação; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-CAD-05: Projeto; Tela: Cadastro; Objetivo: Validar e-mail inválido; Pré-condição: Formulário aberto; Passo a passo: inserir e-mail sem formato válido; Resultado esperado: Exibir erro; Resultado obtido: ; Situação: ; Comentário: ; Print: ;

## Login
- CT-LOG-01: Projeto; Tela: Login; Objetivo: Validar login com usuário válido; Pré-condição: Usuário cadastrado; Passo a passo: informar e-mail e senha; Resultado esperado: Redirecionar para home logada; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-LOG-02: Projeto; Tela: Login; Objetivo: Validar credenciais inválidas; Pré-condição: Usuário não cadastrado ou senha incorreta; Passo a passo: enviar dados errados; Resultado esperado: Exibir mensagem de erro; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-LOG-03: Projeto; Tela: Login; Objetivo: Verificar redirecionamento de administrador; Pré-condição: Usuário admin cadastrado; Passo a passo: fazer login; Resultado esperado: acessar painel administrativo; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-LOG-04: Projeto; Tela: Login; Objetivo: Validar acesso sem autenticação; Pré-condição: Usuário fora; Passo a passo: tentar acessar página protegida; Resultado esperado: redirecionar para login; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-LOG-05: Projeto; Tela: Login; Objetivo: Verificar sessão segura; Pré-condição: Login realizado; Passo a passo: manter uso e navegar entre páginas; Resultado esperado: sessão preservada e acesso adequado; Resultado obtido: ; Situação: ; Comentário: ; Print: ;

## Perfil
- CT-PER-01: Projeto; Tela: Perfil; Objetivo: Validar visualização dos dados do usuário; Pré-condição: Usuário logado; Passo a passo: acessar perfil; Resultado esperado: mostrar nome, e-mail, telefone e pets; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-PER-02: Projeto; Tela: Perfil; Objetivo: Validar atualização de nome e telefone; Pré-condição: Usuário autenticado; Passo a passo: alterar dados e salvar; Resultado esperado: dados atualizados com sucesso; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-PER-03: Projeto; Tela: Perfil; Objetivo: Validar e-mail duplicado; Pré-condição: Outro usuário com e-mail equivalente; Passo a passo: alterar e-mail para já cadastrado; Resultado esperado: erro e bloqueio; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-PER-04: Projeto; Tela: Perfil; Objetivo: Validar alteração de senha com senha atual incorreta; Pré-condição: Usuário logado; Passo a passo: inserir senha atual errada; Resultado esperado: mensagem de erro; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-PER-05: Projeto; Tela: Perfil; Objetivo: Validar visualização de agendamentos; Pré-condição: usuário com agendamentos; Passo a passo: acessar perfil; Resultado esperado: listagem correta dos agendamentos; Resultado obtido: ; Situação: ; Comentário: ; Print: ;

## Agendamento
- CT-AGE-01: Projeto; Tela: Agendamento; Objetivo: Validar agendamento com dados válidos; Pré-condição: Usuário logado com pets cadastrados; Passo a passo: selecionar pet, serviço, data e horário; Resultado esperado: agendamento salvo com status pendente; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-AGE-02: Projeto; Tela: Agendamento; Objetivo: Validar seleção de pet de outro usuário; Pré-condição: usuário sem acesso ao pet; Passo a passo: tentar forçar pet externo; Resultado esperado: bloqueio do agendamento; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-AGE-03: Projeto; Tela: Agendamento; Objetivo: Validar data passada; Pré-condição: formulário aberto; Passo a passo: informar data anterior à data atual; Resultado esperado: erro e bloqueio; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-AGE-04: Projeto; Tela: Agendamento; Objetivo: Validar conflito de horário; Pré-condição: agendamento existente para o mesmo pet; Passo a passo: repetir mesmo horário; Resultado esperado: impedir cadastro duplicado; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-AGE-05: Projeto; Tela: Agendamento; Objetivo: Validar cancelamento de agendamento; Pré-condição: agendamento pendente; Passo a passo: acessar Meus agendamentos e cancelar; Resultado esperado: status alterado para cancelado; Resultado obtido: ; Situação: ; Comentário: ; Print: ;

## Redefinição de senha
- CT-REC-01: Projeto; Tela: Redefinição de senha; Objetivo: Validar solicitação com e-mail cadastrado; Pré-condição: usuário existente; Passo a passo: informar e-mail e solicitar recuperação; Resultado esperado: gerar token e informar fluxo de teste; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-REC-02: Projeto; Tela: Redefinição de senha; Objetivo: Validar e-mail inexistente; Pré-condição: e-mail não cadastrado; Passo a passo: informar e-mail inválido; Resultado esperado: exibir mensagem de conta não localizada; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-REC-03: Projeto; Tela: Redefinição de senha; Objetivo: Validar token expirado; Pré-condição: token vencido; Passo a passo: acessar nova_senha com token inválido; Resultado esperado: rejeitar redefinição; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-REC-04: Projeto; Tela: Redefinição de senha; Objetivo: Validar senha curta; Pré-condição: token válido; Passo a passo: tentar definir senha com menos de 6 caracteres; Resultado esperado: bloqueio com mensagem de validação; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-REC-05: Projeto; Tela: Redefinição de senha; Objetivo: Validar redefinição bem-sucedida; Pré-condição: token válido e não utilizado; Passo a passo: definir nova senha e confirmar; Resultado esperado: senha alterada e usuário redirecionado para login; Resultado obtido: ; Situação: ; Comentário: ; Print: ;

## Administração
- CT-ADM-01: Projeto; Tela: Administração; Objetivo: Validar acesso do admin; Pré-condição: usuário admin autenticado; Passo a passo: acessar painel; Resultado esperado: painel abre com dados do sistema; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-ADM-02: Projeto; Tela: Administração; Objetivo: Validar acesso negado para cliente; Pré-condição: usuário comum autenticado; Passo a passo: acessar admin.php; Resultado esperado: redirecionado para home; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-ADM-03: Projeto; Tela: Administração; Objetivo: Validar pesquisa de usuários; Pré-condição: painel acessível; Passo a passo: pesquisar por nome ou e-mail; Resultado esperado: listar usuários correspondentes; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-ADM-04: Projeto; Tela: Administração; Objetivo: Validar alteração de status de agendamento; Pré-condição: agendamento existente; Passo a passo: alterar de pendente para confirmado; Resultado esperado: atualização persistida no sistema; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-ADM-05: Projeto; Tela: Administração; Objetivo: Validar exclusão de usuário comum; Pré-condição: usuário comum registrado; Passo a passo: excluir usuário; Resultado esperado: remover dados e confirmação; Resultado obtido: ; Situação: ; Comentário: ; Print: ;

## Banco de Dados
- CT-BD-01: Projeto; Tela: Banco de Dados; Objetivo: Verificar integridade dos relacionamentos; Pré-condição: dados cadastrados; Passo a passo: consultar tabelas e relacionamento de registros; Resultado esperado: usuários, pets e agendamentos conectados corretamente; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-BD-02: Projeto; Tela: Banco de Dados; Objetivo: Verificar armazenamento de senhas protegidas; Pré-condição: usuários cadastrados; Passo a passo: consultar a coluna senha no banco; Resultado esperado: senhas armazenadas em hash; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
- CT-BD-03: Projeto; Tela: Banco de Dados; Objetivo: Verificar persistência e consistência de dados; Pré-condição: agendamentos e usuários cadastrados; Passo a passo: inserir, consultar e atualizar registros; Resultado esperado: dados preservados sem inconsistência; Resultado obtido: ; Situação: ; Comentário: ; Print: ;
