# Documentação do Desenvolvedor Full Stack – Petninel

## 1. Nome do sistema
Petninel

## 2. Resumo
O Petninel é um sistema web de gestão para pet shop, voltado para clientes e administradores. Ele permite o cadastro de usuários, registro de pets, agendamento de serviços, recuperação de senha e administração completa dos dados do sistema.

## 3. Objetivo
O sistema tem como objetivo facilitar a gestão de serviços e agendamentos de um pet shop, oferecendo uma experiência segura e funcional para clientes e uma área administrativa para os responsáveis.

## 4. Tecnologias utilizadas
- PHP 8+
- MySQL
- PDO
- HTML5
- CSS3
- JavaScript
- XAMPP

## 5. Descrição das sete telas
### Tela 1 – Home
Apresenta a proposta do Petninel, serviços disponíveis e opções de navegação com ou sem login.

### Tela 2 – Cadastro
Permite criar conta do cliente com dados pessoais e senha protegida.

### Tela 3 – Login
Realiza autenticação com validação de e-mail e senha por tipo de usuário.

### Tela 4 – Perfil
Permite visualizar e editar dados, alterar senha e consultar pets/agendamentos do usuário.

### Tela 5 – Agendamento
Permite selecionar pet, serviço, data, horário e observações.

### Tela 6 – Redefinição de Senha
Permite solicitar recuperação e definir nova senha por token válido.

### Tela 7 – Área do Administrador
Permite gerenciar usuários, buscar dados, alterar status de agendamentos e excluir usuários não administradores.

## 6. Requisitos funcionais
- RF-HOM-01: A página inicial deve apresentar os serviços do pet shop.
- RF-HOM-02: Usuários logados devem visualizar boas-vindas personalizadas.
- RF-CAD-01: O cadastro deve validar e-mail, senha e confirmação.
- RF-CAD-02: O sistema deve impedir e-mails duplicados.
- RF-LOG-01: O login deve autenticar usuários com senha protegida.
- RF-LOG-02: O sistema deve redirecionar usuários comuns e administradores corretamente.
- RF-PER-01: O perfil deve permitir edição dos dados pessoais.
- RF-PER-02: O perfil deve validar alteração de senha usando senha atual.
- RF-AGE-01: O agendamento deve validar pet, data, horário e serviço.
- RF-AGE-02: O sistema deve impedir conflitos de horário para o mesmo pet.
- RF-REC-01: O sistema deve gerar token seguro para recuperação.
- RF-REC-02: A nova senha deve ser armazenada com `password_hash()`.
- RF-ADM-01: Apenas administradores podem acessar o painel.
- RF-ADM-02: O administrador deve visualizar e atualizar agendamentos.
- RF-ADM-03: O administrador deve gerenciar usuários com validação e confirmação.

## 7. Papéis do trio
- Desenvolvedor Full Stack: [NOME]
- QA Sênior: [NOME]
- QA Júnior: [NOME]
