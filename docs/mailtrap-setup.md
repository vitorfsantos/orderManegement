# Configuração do Mailtrap para Desenvolvimento

## Configuração do Mailtrap

Para configurar o envio de emails em desenvolvimento usando o Mailtrap, siga os passos abaixo:

### 1. Criar conta no Mailtrap

1. Acesse [https://mailtrap.io](https://mailtrap.io)
2. Crie uma conta gratuita
3. Acesse o dashboard e vá para "Email Testing" > "Inboxes"
4. Crie um novo inbox ou use o inbox padrão

### 2. Configurar variáveis de ambiente

Adicione as seguintes configurações ao seu arquivo `.env`:

```env
# Mailtrap Configuration for Development
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_username_do_mailtrap
MAIL_PASSWORD=sua_senha_do_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@ordersmanagement.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Obter credenciais do Mailtrap

1. No dashboard do Mailtrap, vá para "Email Testing" > "Inboxes"
2. Clique no inbox que deseja usar
3. Na aba "SMTP Settings", copie:
   - Username
   - Password
4. Cole essas credenciais nas variáveis `MAIL_USERNAME` e `MAIL_PASSWORD` do seu `.env`

### 4. Testar a configuração

Para testar se a configuração está funcionando, você pode:

1. Executar os testes:
```bash
php artisan test tests/Feature/Orders/OrderConfirmationEmailTest.php
```

2. Ou criar um pedido e alterar seu status para "paid" através da interface administrativa.

### 5. Verificar emails no Mailtrap

1. Acesse o dashboard do Mailtrap
2. Vá para "Email Testing" > "Inboxes"
3. Clique no inbox configurado
4. Você verá todos os emails enviados pela aplicação

## Configuração para Produção

Para produção, substitua as configurações do Mailtrap por um provedor real como:

- **SendGrid**
- **Mailgun**
- **Amazon SES**
- **SMTP do seu provedor de hospedagem**

Exemplo para SendGrid:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=sua_api_key_do_sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@seudominio.com"
MAIL_FROM_NAME="Sistema de Pedidos"
```

## Troubleshooting

### Email não está sendo enviado

1. Verifique se as credenciais do Mailtrap estão corretas
2. Verifique se a porta 2525 não está bloqueada pelo firewall
3. Verifique os logs do Laravel: `storage/logs/laravel.log`

### Email não aparece no Mailtrap

1. Verifique se está olhando no inbox correto
2. Verifique se o email está sendo enviado para o endereço correto
3. Verifique se não há erros nos logs da aplicação

### Teste manual

Você pode testar o envio de email criando um comando Artisan:

```bash
php artisan make:command TestEmail
```

E no comando:

```php
use Illuminate\Support\Facades\Mail;
use App\Modules\Orders\Mail\OrderConfirmationMail;

// No método handle()
Mail::to('teste@exemplo.com')->send(new OrderConfirmationMail($order));
```
