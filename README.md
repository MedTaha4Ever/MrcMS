# MrcMS - Rental Car Management System

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

A comprehensive Rental Car Management System built with Laravel 10, designed to streamline car rental operations with modern web technologies.

## Features

- **Vehicle Management**: Add, edit, and manage your fleet of rental vehicles
- **Customer Management**: Handle customer information and rental history
- **Booking System**: Process rental bookings and reservations
- **Dashboard**: Administrative dashboard for monitoring operations
- **Database Management**: Built-in phpMyAdmin for database administration

## Requirements

- PHP ^8.1
- Composer
- Node.js & npm/yarn
- MySQL 8.0
- Redis (for caching and sessions)

## Installation

### Using Docker (Recommended)

1. Clone the repository:
```bash
git clone https://github.com/MedTaha4Ever/MrcMS.git
cd MrcMS
```

2. Copy the environment file:
```bash
cp .env.example .env
```

3. Install PHP dependencies:
```bash
composer install
```

4. Install JavaScript dependencies:
```bash
npm install
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Start the Docker environment:
```bash
docker-compose up -d
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Build frontend assets:
```bash
npm run build
```

### Manual Installation

If you prefer not to use Docker, ensure you have MySQL and Redis running locally, then follow steps 1-5 and 7-8 above, and configure your `.env` file with your local database credentials.

## Usage

- **Application**: Access the main application at `http://localhost:8000`
- **phpMyAdmin**: Database management interface at `http://localhost:8080`
- **Development**: Use `npm run dev` for hot-reloading during development

## Configuration

The application uses Laravel Sail for Docker development. Key configuration files:

- [`docker-compose.yml`](docker-compose.yml) - Docker services configuration
- [`composer.json`](composer.json) - PHP dependencies
- [`package.json`](package.json) - JavaScript dependencies

## Development

To start development with hot-reloading:

```bash
npm run dev
```

For production builds:

```bash
npm run build
```

## License

This project is built on the Laravel framework, which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
