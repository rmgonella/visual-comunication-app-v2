# Guia de Instalação - Sistema SaaS Xavier Design

## ⚡ Instalação Rápida

### 1. Preparar o Banco de Dados

```bash
# Conectar ao MySQL
mysql -u root -p

# Executar o script SQL
source /home/ubuntu/xavier-design/database/schema.sql;

# Ou via linha de comando
mysql -u root -p < /home/ubuntu/xavier-design/database/schema.sql
```

### 2. Configurar as Credenciais do Banco

Editar o arquivo `/home/ubuntu/xavier-design/config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Seu usuário MySQL
define('DB_PASS', '');          // Sua senha MySQL
define('DB_NAME', 'xavier_design');
```

### 3. Iniciar o Servidor PHP

```bash
cd /home/ubuntu/xavier-design
php -S localhost:8000 -t public/
```

Ou para outro host/porta:
```bash
php -S 0.0.0.0:8080 -t public/
```

### 4. Acessar a Aplicação

- **URL**: http://localhost:8000
- **Email**: admin@xavierdesign.com
- **Senha**: admin123

## 🔧 Configuração Avançada

### Apache

Criar arquivo `/etc/apache2/sites-available/xavier-design.conf`:

```apache
<VirtualHost *:80>
    ServerName xavier-design.local
    DocumentRoot /home/ubuntu/xavier-design/public

    <Directory /home/ubuntu/xavier-design/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/xavier-design-error.log
    CustomLog ${APACHE_LOG_DIR}/xavier-design-access.log combined
</VirtualHost>
```

Ativar:
```bash
sudo a2ensite xavier-design
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Nginx

Criar arquivo `/etc/nginx/sites-available/xavier-design`:

```nginx
server {
    listen 80;
    server_name xavier-design.local;
    root /home/ubuntu/xavier-design/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

Ativar:
```bash
sudo ln -s /etc/nginx/sites-available/xavier-design /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

## 📋 Estrutura de Pastas - Permissões

```bash
# Dar permissões de escrita
chmod 755 /home/ubuntu/xavier-design/uploads
chmod 755 /home/ubuntu/xavier-design/logs

# Se necessário, ajustar proprietário
sudo chown -R www-data:www-data /home/ubuntu/xavier-design/uploads
sudo chown -R www-data:www-data /home/ubuntu/xavier-design/logs
```

## 🗄️ Banco de Dados - Detalhes

### Usuário Admin Padrão
- **Email**: admin@xavierdesign.com
- **Senha**: admin123 (hash bcrypt)
- **Perfil**: Administrador

### Categorias Pré-cadastradas
- Impressão Digital
- Impressão Offset
- Fachadas Comerciais
- Estruturas Metálicas
- Comunicação Visual
- Letreiros e Totens
- Banners e Adesivos
- Projetos Personalizados

### Produtos Pré-cadastrados
Diversos produtos de exemplo em cada categoria

### Materiais Pré-cadastrados
Lona, ACM, Vinil, Papel, Tinta, Estrutura, LED, Parafusos

## 🔐 Segurança - Checklist

- [ ] Alterar senha do usuário admin
- [ ] Configurar HTTPS em produção
- [ ] Definir permissões corretas de arquivo
- [ ] Configurar firewall
- [ ] Fazer backup regular do banco de dados
- [ ] Manter PHP atualizado
- [ ] Revisar logs regularmente

## 📊 Backup do Banco de Dados

### Fazer Backup
```bash
mysqldump -u root -p xavier_design > backup_xavier_design.sql
```

### Restaurar Backup
```bash
mysql -u root -p xavier_design < backup_xavier_design.sql
```

## 🐛 Troubleshooting

### Erro: "Erro ao conectar ao banco de dados"
- Verificar se MySQL está rodando
- Verificar credenciais em `config/database.php`
- Verificar se o banco `xavier_design` foi criado

### Erro: "View não encontrada"
- Verificar se o arquivo existe em `app/views/`
- Verificar permissões de leitura

### Erro: "Class not found"
- Verificar se o arquivo existe em `app/models/` ou `app/controllers/`
- Verificar se o nome da classe está correto

### Erro 404 em rotas
- Verificar se o `.htaccess` está ativo (Apache)
- Verificar configuração do Nginx
- Verificar se a rota existe em `public/index.php`

## 📞 Suporte

Para dúvidas ou problemas, consulte a documentação em `README.md`.

---

**Última atualização**: 02/01/2026
