# SaaS Management System – Visual Communication

## 📋 Description

A complete business management system for **Xavier Design Comunicação Visual**, a company specialized in digital and offset printing, visual communication, commercial façades, metal structures, and custom projects.

The system is robust, scalable, and ready for real commercial use, with a strong focus on financial control, budgeting, production, and reporting.

## 🎯 Main Features

### 1. Authentication and Access Control

* Secure login with email and password authentication
* Password recovery
* User roles with permissions:

  * **Administrator**: Full system access
  * **Finance**: Accounts receivable/payable management
  * **Production**: Production order control
  * **Sales**: Budget and customer management
* Activity logs

### 2. Smart Dashboard

* Key Performance Indicators (KPIs):

  * Total customers
  * Approved/pending budgets
  * Ongoing production orders
  * Overdue accounts
* Interactive charts:

  * Budget status
  * Sales over the last 12 months
* Real-time financial overview
* Latest budgets and production orders

### 3. Customer Module

* Complete customer registration (individuals and companies)
* Storage of contact and address data
* Advanced search and filters
* Transaction history per customer

### 4. Budget Module

* Creation of detailed budgets
* Automatic calculation of:

  * Materials
  * Labor
  * Profit margin
* Budget statuses:

  * Draft
  * Sent
  * Approved
  * Rejected
* **Professional PDF generation** including:

  * Company logo
  * Customer details
  * Service descriptions
  * Detailed pricing
  * Budget validity
  * Signature

### 5. Production Orders Module

* Automatic generation from approved budgets
* Stage control:

  * Created
  * In production
  * Installation
  * Completed
* Assignment of responsible staff
* Dates and technical notes

### 6. Financial Module

* **Accounts Receivable**: Customer payment management
* **Accounts Payable**: Supplier payment management
* Cash flow
* Payment methods
* Financial reports
* Integration with budgets and production orders

### 7. Reports (Exportable to PDF)

* Sales reports
* Financial reports
* Customer-based reports
* Service-type reports
* PDF export for printing

### 8. Additional Registrations

* **Suppliers**: Supplier management with contact details
* **Products and Services**: Categories and pricing
* **Materials**: Material and inventory control

## 🛠️ Technologies Used

### Backend

* **PHP 7.4+**: Programming language
* **MVC Architecture**: Separation of concerns
* **PDO**: Secure database access

### Database

* **MySQL 5.7+**: Relational database
* **Normalized tables**: Referential integrity
* **Optimized indexes**: Performance

### Frontend

* **HTML5**: Semantic markup
* **CSS3**: Responsive styling
* **Vanilla JavaScript**: Interactivity without heavy dependencies
* **Chart.js**: Interactive charts

### Document Generation

* **TCPDF**: Professional PDF generation

## 📁 Folder Structure

```
xavier-design/
├── public/
│   └── index.php              # Application entry point
├── app/
│   ├── controllers/           # Controllers (business logic)
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ClienteController.php
│   │   └── OrcamentoController.php
│   ├── models/                # Models (database access)
│   │   ├── Model.php
│   │   ├── Usuario.php
│   │   ├── Cliente.php
│   │   ├── Orcamento.php
│   │   └── OrdemProducao.php
│   └── views/                 # Views (HTML templates)
│       ├── auth/
│       ├── dashboard/
│       └── clientes/
├── config/
│   ├── app.php               # General configuration
│   └── database.php          # Database configuration
├── database/
│   └── schema.sql            # Database creation script
├── assets/
│   ├── css/
│   │   └── style.css         # CSS styles
│   ├── js/
│   │   └── app.js            # JavaScript scripts
│   └── images/               # Images
├── uploads/                  # File uploads directory
├── logs/                     # Activity logs
└── README.md                 # This file
```

## 🚀 Installation and Setup

### Requirements

* PHP 7.4 or higher
* MySQL 5.7 or higher
* Web server (Apache, Nginx, etc.)

### Installation Steps

1. **Clone or extract the project**

   ```bash
   cd /path/to/xavier-design
   ```

2. **Create the database**

   ```bash
   mysql -u root -p < database/schema.sql
   ```

3. **Configure the database**
   You can configure the database in two ways:

   **Option A: Using a .env file (Recommended)**
   Rename `.env.example` to `.env` and fill in your credentials.

   **Option B: Editing config/database.php**
   Edit `config/database.php` with your credentials:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_user');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'xavier_design');
   ```

4. **Set folder permissions**

   ```bash
   chmod 755 uploads/
   chmod 755 logs/
   ```

5. **Start the server**

   ```bash
   # Using PHP built-in server
   php -S localhost:8000 -t public/

   # Or configure Apache/Nginx
   ```

6. **Access the application**

   * URL: `http://localhost:8000`
   * Email: `admin@xavierdesign.com`
   * Password: `admin123`

## 📊 Database

### Main Tables

| Table             | Description                       |
| ----------------- | --------------------------------- |
| `usuarios`        | System users                      |
| `clientes`        | Customers (individuals/companies) |
| `fornecedores`    | Suppliers                         |
| `produtos`        | Products and services             |
| `materiais`       | Materials and supplies            |
| `orcamentos`      | Budgets                           |
| `orcamento_itens` | Budget items                      |
| `ordens_producao` | Production orders                 |
| `ordem_etapas`    | Order stages                      |
| `contas_receber`  | Accounts receivable               |
| `contas_pagar`    | Accounts payable                  |
| `logs_atividades` | Activity logs                     |
| `configuracoes`   | Company settings                  |

## 🔐 Security

* **Authentication**: Passwords hashed with bcrypt
* **Validation**: Input sanitization
* **CSRF Token**: Protection against CSRF attacks
* **SQL Injection**: Use of prepared statements
* **Logs**: Logging of all activities

## 📈 Scalability

The system is designed to support:

* **Multi-company**: Future support for multiple companies
* **Light/Dark theme**: Theme implementation
* **REST API**: Integration with external systems
* **Advanced reports**: Export to multiple formats

## 🎨 Design and UX

* Modern and professional layout
* Corporate SaaS-style design
* Visual identity focused on visual communication
* Responsive (desktop, tablet, and mobile)
* Clean, intuitive, and elegant interface

## 📝 Code Comments

All code is well-commented and documented to facilitate maintenance and future development.

## 🤝 Support and Maintenance

For support, maintenance, or development of new features, please contact the development team.

## 📄 License

This system is the property of Xavier Design Comunicação Visual.

---

**Developed with ❤️ for Rodrigo Marchi Gonella**
