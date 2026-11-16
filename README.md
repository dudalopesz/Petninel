# Petninel

## Como executar no XAMPP

1. Inicie o Apache e o MySQL no painel do XAMPP.
2. Acesse o phpMyAdmin em http://localhost/phpmyadmin.
3. Verifique se o banco `petninel` existe.
4. Se necessário, execute o arquivo `database/atualizacao.sql` para ajustar a estrutura.
5. Acesse o sistema em:
   - http://localhost/petninel
   - ou pela rede local: http://IP-DO-COMPUTADOR/petninel

## Acesso pela rede local

Para acessar pelo IP do computador:

- Descubra o endereço IP do seu Windows com `ipconfig`.
- Exemplo: `http://192.168.0.25/petninel`
- Certifique-se de que o Apache está rodando e que o diretório `C:\xampp\htdocs\petninel` está acessível.

## Estrutura principal

- `index.php` — página inicial
- `pages/` — telas do sistema
- `config/conexao.php` — conexão PDO com MySQL
- `includes/` — header, footer, autenticação e funções utilitárias
- `database/atualizacao.sql` — ajustes e compatibilização do banco

## Observações importantes

- O projeto usa PDO com consultas preparadas.
- A senha do usuário é armazenada com `password_hash()`.
- O login e o acesso às páginas protegidas são controlados por sessão.
- A recuperação de senha possui um fluxo local de teste para ambiente sem SMTP.
- O envio de e-mail real precisa de uma configuração de SMTP no PHP ou servidor de e-mail.
