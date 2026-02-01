# 🚀 COMECE AQUI - Sistema Xavier Design

## ⚡ Inicialização Rápida (5 minutos)

### Passo 1: Criar o Banco de Dados

```bash
# Abrir MySQL
mysql -u root -p

# Executar o script SQL
source /home/ubuntu/xavier-design/database/schema.sql;

# Ou em uma linha
mysql -u root -p < /home/ubuntu/xavier-design/database/schema.sql
```

### Passo 2: Iniciar o Servidor

```bash
cd /home/ubuntu/xavier-design
php -S localhost:8000 -t public/
```

### Passo 3: Acessar a Aplicação

Abra seu navegador e acesse:
```
http://localhost:8000
```

### Passo 4: Fazer Login

- **Email**: admin@xavierdesign.com
- **Senha**: admin123

**Pronto! 🎉 O sistema está funcionando!**

---

## 📚 Documentação Completa

Após a instalação, leia a documentação:

1. **README.md** - Descrição geral do sistema
2. **INSTALACAO.md** - Guia detalhado de instalação
3. **ARQUITETURA.md** - Detalhes técnicos
4. **TESTES.md** - Checklist de testes

---

## 🎯 Próximos Passos

### 1. Explorar o Dashboard
- Veja os KPIs em tempo real
- Analise os gráficos interativos

### 2. Criar um Cliente
- Vá para "Clientes"
- Clique em "+ Novo Cliente"
- Preencha os dados

### 3. Criar um Orçamento
- Vá para "Orçamentos"
- Clique em "+ Novo Orçamento"
- Selecione o cliente
- Adicione itens
- Gere o PDF

### 4. Criar uma Ordem de Produção
- Aprove um orçamento
- Crie uma ordem de produção
- Acompanhe as etapas

### 5. Gerenciar Financeiro
- Registre contas a receber
- Acompanhe pagamentos
- Veja o fluxo de caixa

---

## 🔧 Configurações Importantes

### Alterar Dados da Empresa

1. Faça login como admin
2. Vá para "Configurações"
3. Edite os dados da empresa:
   - Nome
   - CNPJ
   - Email
   - Telefone
   - Endereço
   - Logo (opcional)

### Adicionar Novos Usuários

1. Vá para "Configurações" → "Usuários"
2. Clique em "+ Novo Usuário"
3. Preencha os dados
4. Selecione o perfil:
   - **Admin**: Acesso total
   - **Financeiro**: Gestão financeira
   - **Produção**: Ordens de produção
   - **Vendas**: Orçamentos e clientes

### Personalizar Produtos

1. Vá para "Configurações" → "Produtos"
2. Adicione seus produtos
3. Defina preços
4. Organize por categoria

---

## 💡 Dicas de Uso

### Orçamentos
- Use a margem de lucro padrão (30%) ou customize por orçamento
- O PDF é gerado automaticamente com logo e dados da empresa
- Números são gerados automaticamente (YYYYMM00001)

### Ordens de Produção
- São criadas automaticamente quando orçamento é aprovado
- Etapas padrão: Criação → Produção → Instalação → Finalizado
- Atribua responsáveis para rastreabilidade

### Financeiro
- Contas a receber são criadas automaticamente com orçamentos
- Registre pagamentos para atualizar status
- Acompanhe contas atrasadas no dashboard

### Relatórios
- Gere relatórios de vendas por período
- Exporte em PDF para impressão
- Analise performance por cliente ou tipo de serviço

---

## 🔐 Segurança

### Alterar Senha do Admin

1. Faça login como admin
2. Vá para "Configurações" → "Minha Conta"
3. Clique em "Alterar Senha"
4. Digite a nova senha
5. Salve as alterações

### Fazer Backup

```bash
# Backup do banco de dados
mysqldump -u root -p xavier_design > backup.sql

# Restaurar backup
mysql -u root -p xavier_design < backup.sql
```

---

## ❓ Troubleshooting

### Erro: "Erro ao conectar ao banco de dados"
- Verifique se MySQL está rodando
- Verifique credenciais em `config/database.php`
- Verifique se o banco `xavier_design` foi criado

### Erro: "Rota não encontrada"
- Verifique a URL digitada
- Verifique se o servidor está rodando
- Limpe o cache do navegador

### Erro: "Permissão negada"
- Verifique seu perfil de usuário
- Peça ao admin para aumentar suas permissões
- Faça logout e login novamente

---

## 📞 Suporte

Para dúvidas ou problemas:

1. Consulte a documentação (README.md, INSTALACAO.md)
2. Revise os comentários no código
3. Verifique os logs em `/logs/`
4. Execute os testes em TESTES.md

---

## 🎉 Parabéns!

Você agora tem um sistema SaaS profissional e funcional!

**Aproveite e bom uso! 🚀**

---

**Desenvolvido com ❤️ para Xavier Design Comunicação Visual**
