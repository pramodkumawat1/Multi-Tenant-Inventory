# Multi-Tenant Inventory & Order Management System
Below is a complete implementation of the required system using Laravel 12. I've built it from scratch assuming a fresh Laravel installation. 
This project includes:

- Tenant isolation (each user has their own store)
- Policies & Gates for authorization  
- Events & queued listeners (order confirmation)  
- Blade UI (via Laravel Breeze)  
- REST API (secured with Passport)  
- Feature tests  
- Postman collection for API documentation

## Key Decisions

Authentication: Uses Laravel Breeze with Blade for simple web UI and passport for API auth.
Multi-Tenancy: All product and order operations are scoped based on the logged-in user’s store.
Queues: Uses Laravel's queue system with a queued listener for order confirmation (logs to storage/logs/laravel.log).
UI: Simple Blade views for products and orders (list, create, edit).
API: JSON endpoints for CRUD on products and orders.
Tests: 
    Includes multiple feature tests:
        Stock decrement
        Insufficient stock check
        Tenant isolation
        Product creation
        Authorization checks
Database: MySQL (configurable via .env).

## Setup Instructions (from README.md)

### Prerequisites:
PHP 8.2+
Composer
Node.js & npm (for Breeze UI)
MySQL

### Installation:
#### Clone & Install
    Clone the repo: git clone <repo-url>
    cd multi-tenant-inventory
    composer install
    npm install && npm run build
#### Configure Environment
##### Copy the environment file:
    cp .env.example .env
##### Set database credentials in .env:
    DB_DATABASE=multi_tenant
    DB_USERNAME=root
    DB_PASSWORD=
##### Generate the application key:
    php artisan key:generate
##### Migrate & Seed Database
    php artisan migrate --seed
##### Generate passport keys
    php artisan passport:keys
##### This will generate:
    A default admin user
    A demo store
##### Start Queue Worker (Required)
    php artisan queue:work (for queues)
##### Start Application
    php artisan serve
    Visit http://127.0.0.1:8000 for UI. Register a new shops.

### Admin Credentials
Email: admin@admin.com
password: password

### API Usage:
Auth: POST /api/login with email/password to get token. Use Authorization: Bearer <token> for protected routes.
Endpoints: See Postman collection below.

### Running Tests:
php artisan test

### Download Postman Collection
[Download Multi-Tenant Inventory Postman Collection](postman/Multi Tenant.postman_collection.json)