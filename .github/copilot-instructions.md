# Copilot Instructions for ecommerce-admin-dashboard

## Project Overview
This is a Laravel-based ecommerce admin dashboard. The codebase follows standard Laravel conventions but includes custom models, controllers, and workflows tailored for ecommerce management.

## Architecture & Key Components
- **app/Models/**: Contains Eloquent models for core entities (Product, Category, User, Order, Cart, Inventory, etc.).
- **app/Http/Controllers/**: Houses controllers for handling HTTP requests and business logic.
- **app/Http/Middleware/**: Custom and Laravel middleware for request filtering and authentication.
- **resources/views/**: Blade templates for admin UI.
- **routes/**: Defines API (`api.php`) and web (`web.php`) routes. Most business logic is routed through controllers.
- **database/migrations/**: Schema definitions for all entities. Migration filenames indicate creation order and entity type.
- **database/seeders/**: Seed data for development/testing. Use `php artisan db:seed` to populate sample data.

## Developer Workflows
- **Start local server**: `php artisan serve`
- **Run migrations**: `php artisan migrate`
- **Seed database**: `php artisan db:seed`
- **Run tests**: `php artisan test` (tests in `tests/Feature` and `tests/Unit`)
- **Build frontend assets**: `npm run build` (uses Vite)
- **Hot reload frontend**: `npm run dev`

## Conventions & Patterns
- **Models**: Use Eloquent ORM. Relationships (hasMany, belongsTo, etc.) are defined in model classes.
- **Controllers**: Grouped by domain (e.g., ProductController, OrderController). Business logic is kept in controllers, not routes.
- **Validation**: Request validation is handled via Laravel Form Requests or inline in controllers.
- **Authentication**: Uses Laravel Sanctum (see `config/sanctum.php`).
- **Frontend**: Blade templates, with assets managed via Vite (`vite.config.js`).
- **Environment**: Sensitive config in `.env` (not committed).

## Integration Points
- **External packages**: Managed via Composer (`composer.json`).
- **Frontend dependencies**: Managed via npm (`package.json`).
- **API**: RESTful endpoints in `routes/api.php`, typically returning JSON.

## Examples
- To add a new model: create in `app/Models/`, add migration in `database/migrations/`, update relevant controller in `app/Http/Controllers/`, and add routes as needed.
- To add a new route: define in `routes/web.php` or `routes/api.php`, point to a controller method.

## References
- See `README.md` for a high-level project summary.
- See `config/` for environment and service configuration.
- See `tests/` for test structure and examples.

---
_If any conventions or workflows are unclear, please request clarification or provide feedback to improve these instructions._
