# Arquitetura do Sistema - Xavier Design

## 🏗️ Padrão de Arquitetura

O sistema utiliza o padrão **MVC (Model-View-Controller)** com separação clara de responsabilidades:

```
┌─────────────────────────────────────────────────────────┐
│                    Camada de Apresentação               │
│                      (Views - HTML/CSS)                 │
└─────────────────────────────────────────────────────────┘
                           ↕
┌─────────────────────────────────────────────────────────┐
│                   Camada de Lógica                      │
│              (Controllers - Processamento)              │
└─────────────────────────────────────────────────────────┘
                           ↕
┌─────────────────────────────────────────────────────────┐
│                 Camada de Dados                         │
│          (Models - Acesso ao Banco de Dados)           │
└─────────────────────────────────────────────────────────┘
                           ↕
┌─────────────────────────────────────────────────────────┐
│                   Banco de Dados                        │
│                      (MySQL)                            │
└─────────────────────────────────────────────────────────┘
```

## 📦 Componentes Principais

### 1. Controllers (app/controllers/)

Responsáveis pela lógica de negócio e processamento de requisições:

- **AuthController**: Autenticação e recuperação de senha
- **DashboardController**: Exibição de indicadores e gráficos
- **ClienteController**: CRUD de clientes
- **OrcamentoController**: CRUD de orçamentos e geração de PDF
- **OrdemProducaoController**: Gestão de ordens de produção
- **FinanceiroController**: Gestão de contas a receber/pagar

Todos herdam de **Controller** (classe base) que fornece:
- Verificação de autenticação
- Renderização de views
- Validação de CSRF
- Registro de logs
- Sanitização de dados

### 2. Models (app/models/)

Responsáveis pelo acesso e manipulação de dados:

- **Model**: Classe base com métodos CRUD genéricos
- **Usuario**: Gerenciamento de usuários e autenticação
- **Cliente**: Operações com clientes
- **Fornecedor**: Operações com fornecedores
- **Produto**: Operações com produtos/serviços
- **Orcamento**: Operações com orçamentos e cálculos
- **OrdemProducao**: Operações com ordens de produção
- **Financeiro**: Operações financeiras

Cada model encapsula a lógica de acesso ao banco de dados.

### 3. Views (app/views/)

Templates HTML que exibem os dados:

```
views/
├── auth/
│   ├── login.php
│   └── recuperar-senha.php
├── dashboard/
│   └── index.php
├── clientes/
│   ├── index.php
│   └── form.php
├── orcamentos/
│   ├── index.php
│   ├── form.php
│   └── pdf.php
└── ...
```

### 4. Configurações (config/)

- **app.php**: Configurações gerais da aplicação
- **database.php**: Conexão com banco de dados

### 5. Banco de Dados (database/)

- **schema.sql**: Script de criação de tabelas e dados iniciais

## 🔄 Fluxo de Requisição

```
1. Usuário acessa URL
   ↓
2. public/index.php (ponto de entrada)
   ↓
3. Roteador identifica a rota
   ↓
4. Controller apropriado é instanciado
   ↓
5. Método do Controller é executado
   ↓
6. Model acessa o banco de dados (se necessário)
   ↓
7. Dados são processados
   ↓
8. View é renderizada com os dados
   ↓
9. HTML é enviado ao navegador
```

## 🗄️ Modelo de Dados

### Tabelas Principais

#### usuarios
```
id (PK)
nome
email (UNIQUE)
senha (bcrypt)
perfil (admin, financeiro, producao, vendas)
ativo
ultimo_acesso
criado_em
atualizado_em
```

#### clientes
```
id (PK)
tipo (PF, PJ)
nome
cpf_cnpj
email
telefone
celular
endereco
numero
complemento
bairro
cidade
estado
cep
ativo
criado_em
atualizado_em
```

#### orcamentos
```
id (PK)
numero (UNIQUE)
cliente_id (FK)
usuario_id (FK)
data_criacao
data_validade
status (rascunho, enviado, aprovado, reprovado)
descricao
observacoes
subtotal
desconto
margem_lucro
total
criado_em
atualizado_em
```

#### orcamento_itens
```
id (PK)
orcamento_id (FK)
produto_id (FK)
descricao
quantidade
preco_unitario
subtotal
criado_em
```

#### ordens_producao
```
id (PK)
numero (UNIQUE)
orcamento_id (FK)
usuario_responsavel (FK)
status (criacao, producao, instalacao, finalizado)
data_inicio
data_prevista
data_conclusao
observacoes
criado_em
atualizado_em
```

#### ordem_etapas
```
id (PK)
ordem_id (FK)
etapa
status (pendente, em_andamento, concluida)
usuario_responsavel (FK)
data_inicio
data_conclusao
observacoes
criado_em
```

#### contas_receber
```
id (PK)
orcamento_id (FK)
cliente_id (FK)
numero_documento
valor
data_vencimento
data_pagamento
forma_pagamento
status (pendente, pago, atrasado, cancelado)
observacoes
criado_em
atualizado_em
```

#### contas_pagar
```
id (PK)
fornecedor_id (FK)
numero_documento
valor
data_vencimento
data_pagamento
forma_pagamento
status (pendente, pago, atrasado, cancelado)
observacoes
criado_em
atualizado_em
```

#### logs_atividades
```
id (PK)
usuario_id (FK)
acao
tabela
registro_id
descricao
ip_address
criado_em
```

## 🔐 Segurança

### Autenticação
- Senhas armazenadas com hash bcrypt (custo 12)
- Sessões PHP com timeout configurável
- Verificação de autenticação em todos os controllers

### Validação
- Sanitização de inputs com `htmlspecialchars()`
- Prepared statements para prevenir SQL injection
- Validação de CSRF token em formulários

### Autorização
- Controle de acesso por perfil de usuário
- Método `verificarPermissao()` em controllers
- Admin tem acesso a tudo

### Logs
- Registro de todas as ações de usuários
- Armazenamento de IP address
- Rastreabilidade completa

## 📈 Escalabilidade

### Preparado para:
- **Multiempresa**: Adicionar coluna `empresa_id` nas tabelas
- **API REST**: Criar endpoints JSON
- **Cache**: Implementar Redis para performance
- **Fila de Jobs**: Processar PDFs e emails em background
- **Microserviços**: Separar módulos em serviços independentes

### Performance
- Índices nas colunas frequentemente consultadas
- Prepared statements reutilizáveis
- Lazy loading de dados relacionados
- Paginação em listas grandes

## 🎨 Frontend

### Estrutura CSS
- Variáveis CSS para temas
- Mobile-first responsive design
- Grid system flexível
- Componentes reutilizáveis

### JavaScript
- Vanilla JS (sem dependências pesadas)
- Chart.js para gráficos
- Validação de formulários
- AJAX para operações assíncronas

## 📝 Padrões de Código

### Nomenclatura
- **Classes**: PascalCase (Usuario, ClienteController)
- **Métodos**: camelCase (findById, criarComItens)
- **Constantes**: UPPER_SNAKE_CASE (DB_HOST, MAX_UPLOAD_SIZE)
- **Variáveis**: snake_case (usuario_id, total_clientes)

### Comentários
- Comentários em português
- Documentação de métodos públicos
- Explicação de lógica complexa

### Estrutura de Métodos
```php
/**
 * Descrição do método
 * 
 * @param tipo $parametro Descrição
 * @return tipo Descrição do retorno
 */
public function metodo($parametro) {
    // Implementação
}
```

## 🚀 Deployment

### Pré-requisitos
- PHP 7.4+
- MySQL 5.7+
- Servidor web (Apache/Nginx)

### Passos
1. Clonar repositório
2. Executar `database/schema.sql`
3. Configurar `config/database.php`
4. Configurar permissões de pasta
5. Acessar via navegador

### Produção
- Desabilitar `APP_DEBUG`
- Usar HTTPS
- Configurar backups automáticos
- Monitorar logs
- Implementar WAF

## 📚 Documentação Adicional

- **README.md**: Descrição geral do projeto
- **INSTALACAO.md**: Guia passo a passo de instalação
- **Comentários no código**: Explicações detalhadas

---

**Arquitetura versão 1.0 - 02/01/2026**
