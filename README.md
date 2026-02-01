# Sistema SaaS de Gestão - Comunicação Visual

## 📋 Descrição

Sistema completo de gestão empresarial para a empresa **Xavier Design Comunicação Visual**, especializada em impressão digital e offset, comunicação visual, fachadas comerciais, estruturas metálicas e projetos personalizados.

O sistema é robusto, escalável e preparado para uso comercial real, com foco em controle financeiro, orçamentos, produção e relatórios.

## 🎯 Funcionalidades Principais

### 1. Autenticação e Controle de Acesso
- Login seguro com autenticação por email e senha
- Recuperação de senha
- Perfis de usuário com permissões:
  - **Administrador**: Acesso total ao sistema
  - **Financeiro**: Gestão de contas a receber/pagar
  - **Produção**: Controle de ordens de produção
  - **Vendas**: Gestão de orçamentos e clientes
- Logs de atividades

### 2. Dashboard Inteligente
- Indicadores principais (KPIs):
  - Total de clientes
  - Orçamentos aprovados/pendentes
  - Ordens em andamento
  - Contas atrasadas
- Gráficos interativos:
  - Status dos orçamentos
  - Vendas dos últimos 12 meses
- Situação financeira em tempo real
- Últimos orçamentos e ordens em produção

### 3. Módulo de Clientes
- Cadastro completo de clientes (PF e PJ)
- Armazenamento de dados de contato e endereço
- Busca e filtros avançados
- Histórico de transações por cliente

### 4. Módulo de Orçamentos
- Criação de orçamentos detalhados
- Cálculo automático de:
  - Materiais
  - Mão de obra
  - Margem de lucro
- Status do orçamento:
  - Rascunho
  - Enviado
  - Aprovado
  - Reprovado
- **Geração de PDF profissional** com:
  - Logo da empresa
  - Dados do cliente
  - Descrição dos serviços
  - Valores detalhados
  - Validade do orçamento
  - Assinatura

### 5. Módulo de Ordens de Produção
- Geração automática a partir de orçamento aprovado
- Controle de etapas:
  - Criação
  - Produção
  - Instalação
  - Finalizado
- Atribuição de responsáveis
- Datas e observações técnicas

### 6. Módulo Financeiro
- **Contas a Receber**: Gestão de pagamentos de clientes
- **Contas a Pagar**: Gestão de pagamentos a fornecedores
- Fluxo de caixa
- Formas de pagamento
- Relatórios financeiros
- Vínculo com orçamentos e ordens

### 7. Relatórios (Exportáveis em PDF)
- Relatório de vendas
- Relatório financeiro
- Relatório por cliente
- Relatório por tipo de serviço
- Exportação em PDF para impressão

### 8. Cadastros Complementares
- **Fornecedores**: Gestão de fornecedores com contatos
- **Produtos e Serviços**: Categorias e preços
- **Materiais**: Controle de materiais e estoque

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 7.4+**: Linguagem de programação
- **Arquitetura MVC**: Separação de responsabilidades
- **PDO**: Acesso seguro ao banco de dados

### Banco de Dados
- **MySQL 5.7+**: Banco de dados relacional
- **Tabelas normalizadas**: Integridade referencial
- **Índices otimizados**: Performance

### Frontend
- **HTML5**: Marcação semântica
- **CSS3**: Estilização responsiva
- **JavaScript Puro**: Interatividade sem dependências pesadas
- **Chart.js**: Gráficos interativos

### Geração de Documentos
- **TCPDF**: Geração de PDFs profissionais

## 📁 Estrutura de Pastas

```
xavier-design/
├── public/
│   └── index.php              # Ponto de entrada da aplicação
├── app/
│   ├── controllers/           # Controllers (lógica de negócio)
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ClienteController.php
│   │   └── OrcamentoController.php
│   ├── models/                # Models (acesso ao banco de dados)
│   │   ├── Model.php
│   │   ├── Usuario.php
│   │   ├── Cliente.php
│   │   ├── Orcamento.php
│   │   └── OrdemProducao.php
│   └── views/                 # Views (templates HTML)
│       ├── auth/
│       ├── dashboard/
│       └── clientes/
├── config/
│   ├── app.php               # Configurações gerais
│   └── database.php          # Configuração do banco de dados
├── database/
│   └── schema.sql            # Script de criação do banco de dados
├── assets/
│   ├── css/
│   │   └── style.css         # Estilos CSS
│   ├── js/
│   │   └── app.js            # Scripts JavaScript
│   └── images/               # Imagens
├── uploads/                  # Diretório para uploads de arquivos
├── logs/                     # Logs de atividades
└── README.md                 # Este arquivo
```

## 🚀 Instalação e Configuração

### Pré-requisitos
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache, Nginx, etc.)

### Passos de Instalação

1. **Clonar ou extrair o projeto**
   ```bash
   cd /caminho/para/xavier-design
   ```

2. **Criar o banco de dados**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

3. **Configurar o banco de dados**
   Você pode configurar o banco de dados de duas formas:
   
   **Opção A: Usando arquivo .env (Recomendado)**
   Renomeie o arquivo `.env.example` para `.env` e preencha suas credenciais.
   
   **Opção B: Editando config/database.php**
   Edite o arquivo `config/database.php` com suas credenciais:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'seu_usuario');
   define('DB_PASS', 'sua_senha');
   define('DB_NAME', 'xavier_design');
   ```

4. **Configurar permissões de pastas**
   ```bash
   chmod 755 uploads/
   chmod 755 logs/
   ```

5. **Iniciar o servidor**
   ```bash
   # Usando PHP built-in server
   php -S localhost:8000 -t public/
   
   # Ou configurar no Apache/Nginx
   ```

6. **Acessar a aplicação**
   - URL: `http://localhost:8000`
   - Email: `admin@xavierdesign.com`
   - Senha: `admin123`

## 📊 Banco de Dados

### Tabelas Principais

| Tabela | Descrição |
|--------|-----------|
| `usuarios` | Usuários do sistema |
| `clientes` | Clientes (PF/PJ) |
| `fornecedores` | Fornecedores |
| `produtos` | Produtos e serviços |
| `materiais` | Materiais e insumos |
| `orcamentos` | Orçamentos |
| `orcamento_itens` | Itens dos orçamentos |
| `ordens_producao` | Ordens de produção |
| `ordem_etapas` | Etapas das ordens |
| `contas_receber` | Contas a receber |
| `contas_pagar` | Contas a pagar |
| `logs_atividades` | Log de atividades |
| `configuracoes` | Configurações da empresa |

## 🔐 Segurança

- **Autenticação**: Senhas com hash bcrypt
- **Validação**: Sanitização de inputs
- **CSRF Token**: Proteção contra ataques CSRF
- **SQL Injection**: Uso de prepared statements
- **Logs**: Registro de todas as atividades

## 📈 Escalabilidade

O sistema está preparado para:
- **Multiempresa**: Suporte futuro para múltiplas empresas
- **Tema claro/escuro**: Implementação de temas
- **API REST**: Integração com sistemas externos
- **Relatórios avançados**: Exportação em múltiplos formatos

## 🎨 Design e UX

- Layout moderno e profissional
- Estilo SaaS corporativo
- Identidade visual voltada para comunicação visual
- Responsivo (desktop, tablet e mobile)
- Interface limpa, intuitiva e elegante

## 📝 Comentários no Código

Todo o código está bem comentado e documentado para facilitar manutenção e desenvolvimento futuro.

## 🤝 Suporte e Manutenção

Para suporte, manutenção ou desenvolvimento de novas funcionalidades, entre em contato com a equipe de desenvolvimento.

## 📄 Licença

Este sistema é propriedade da Xavier Design Comunicação Visual.

---

**Desenvolvido com ❤️ para Xavier Design Comunicação Visual**
