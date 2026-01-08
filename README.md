# BigBluBox Link Shortener

A personal link shortening service built with Laravel 11. Creates short, memorable URLs for use in social media posts, website content, and other places where clean links matter.

## Features

- **Link Shortening** - Create custom short slugs that redirect to target URLs
- **Click Tracking** - Logs referrer, user agent, and timestamp for each redirect
- **Analytics Dashboard** - View click statistics with Chart.js visualizations
- **Admin Panel** - Full CRUD interface for managing links
- **REST API** - Token-authenticated API for external integrations (e.g., Typefully)

## Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL
- **Authentication**: Laravel Sanctum (session + API tokens)
- **Frontend**: Blade templates with custom CSS (Big Blue Box design system)
- **Charts**: Chart.js

## Local Development

```bash
# Install dependencies
composer install

# Copy environment file and configure
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed admin user
php artisan db:seed --class=AdminUserSeeder

# Start development server
php artisan serve
```

## Environment Configuration

Copy `.env.example` to `.env` and configure:

- Database connection (`DB_*`)
- Application URL (`APP_URL`)
- Admin credentials (`ADMIN_EMAIL`, `ADMIN_PASSWORD`) - used by seeder

## API Endpoints

All API routes require Bearer token authentication.

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/tokens` | Generate API token |
| GET | `/api/links` | List all links |
| POST | `/api/links` | Create new link |
| GET | `/api/links/{slug}` | Get link details |
| PUT | `/api/links/{slug}` | Update link |
| DELETE | `/api/links/{slug}` | Delete link |
| GET | `/api/links/{slug}/stats` | Get click statistics |

## Testing

```bash
php artisan test
```

## Deployment

GitHub Actions workflow included for CI/CD. Configure the following repository secrets:

- `SFTP_HOST` - Deployment server hostname
- `SFTP_USERNAME` - SFTP username
- `SFTP_PASSWORD` - SFTP password
- `DEPLOY_PATH` - Remote path for deployment
- `LARAVEL_ENV` - Production .env file contents

## License

Private project - not for redistribution.
