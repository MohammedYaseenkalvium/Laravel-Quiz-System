# Docker Setup Guide

This guide explains how to build and run the Laravel Quiz System using Docker.

## Prerequisites

- Docker installed (https://www.docker.com/products/docker-desktop)
- Docker Compose installed (usually comes with Docker Desktop)

## Quick Start

### 1. Clone and Setup

```bash
cd /path/to/Laravel-Quiz-system
```

### 2. Create Environment File

```bash
cp .env.example .env
```

Generate an application key:

```bash
docker-compose run --rm app php artisan key:generate
```

### 3. Build and Start Containers

```bash
docker-compose up -d --build
```

This will:
- Build the PHP-FPM application container
- Start Nginx web server
- Start MySQL database
- Install all PHP and npm dependencies
- Compile assets with Vite

### 4. Run Database Migrations

```bash
docker-compose exec app php artisan migrate
```

Optional - Seed the database:

```bash
docker-compose exec app php artisan db:seed
```

### 5. Access the Application

- **Web Application**: http://localhost
- **MySQL Database**: localhost:3306
  - Username: `laravel_user`
  - Password: `laravel_password`
  - Database: `laravel_quiz`

## Useful Commands

### View Logs
```bash
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Execute Artisan Commands
```bash
docker-compose exec app php artisan <command>
```

### Run Tests
```bash
docker-compose exec app php artisan test
```

### Install NPM Packages
```bash
docker-compose exec app npm install
```

### Rebuild Assets
```bash
docker-compose exec app npm run build
```

### Access App Container Shell
```bash
docker-compose exec app sh
```

### Stop Containers
```bash
docker-compose down
```

### Remove Everything (including data)
```bash
docker-compose down -v
```

## File Structure

- `Dockerfile` - Container image configuration for the Laravel app
- `docker-compose.yml` - Multi-container orchestration
- `docker/nginx/conf.d/app.conf` - Nginx configuration
- `.dockerignore` - Files to exclude from Docker build

## Environment Variables

Edit `docker-compose.yml` to customize:
- `DB_DATABASE` - Database name
- `DB_USERNAME` - Database user
- `DB_PASSWORD` - Database password
- `APP_KEY` - Laravel application key (auto-generated)

## Troubleshooting

### Migrations fail
```bash
docker-compose exec app php artisan migrate --force
```

### Clear cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

### Rebuild everything fresh
```bash
docker-compose down -v
docker-compose up -d --build
docker-compose exec app php artisan migrate
```

### Check if containers are running
```bash
docker-compose ps
```

## Production Considerations

For production deployment:

1. Update `.env` with secure values
2. Set `APP_ENV=production`
3. Use a reverse proxy (Nginx/Traefik)
4. Enable HTTPS with SSL certificates
5. Use external database service
6. Configure proper logging
7. Set up backups for database volumes
8. Use environment-specific compose files

For more details, refer to Laravel and Docker documentation.
