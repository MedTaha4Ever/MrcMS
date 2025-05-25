# MrcMS - Rental Car Management System

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

A comprehensive Rental Car Management System built with Laravel 10, designed to streamline car rental operations with modern web technologies. This system provides a complete solution for managing rental vehicles, customers, and bookings with an intuitive and user-friendly web interface.

> 🚀 **Live Demo**: A working demo is currently running and ready to explore! The application includes sample data for cars, clients, and reservations to help you get started quickly.

## Features

### Core Functionality
- **Vehicle Management**: Add, edit, and manage your fleet of rental vehicles with pricing and availability tracking
- **Customer Management**: Complete CRUD operations for client information, rental history, and statistics
- **Booking System**: Process rental bookings and reservations with real-time availability checking
- **Dashboard**: Administrative dashboard for monitoring operations and key metrics
- **Database Management**: Built-in phpMyAdmin for database administration

### Advanced Features
- **Smart Pricing**: Dynamic pricing calculation based on rental duration and vehicle type
- **Email Notifications**: Automated confirmation emails for reservations using Laravel Mail
- **Search & Filtering**: Advanced search capabilities for vehicles and customers with real-time filtering
- **Responsive Design**: Mobile-friendly interface built with Bootstrap 5
- **Data Validation**: Comprehensive form validation and error handling
- **Real-time Calculations**: Live price updates as users select rental dates

## Quick Start

Want to get MrcMS running immediately? Here's the fastest way:

```bash
git clone https://github.com/MedTaha4Ever/MrcMS.git
cd MrcMS
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

Then visit `http://localhost:8000` to explore the application with sample data!

## Key Features Showcase

### 🚗 **Car Management System**
- Browse available vehicles with real-time pricing at `/cars/available`
- Dynamic price calculation based on rental duration
- Advanced filtering by brand, availability, and date ranges
- Detailed car information with specifications and pricing

### 👥 **Client Management** 
- Complete CRUD operations for customer management at `/admin/clients`
- Client statistics and rental history tracking
- Advanced search and filtering capabilities
- Comprehensive client profiles with contact information

### 📋 **Reservation System**
- Real-time availability checking and booking
- Automated email confirmations for new reservations
- Reservation history and status tracking
- Integration between client and vehicle management

### 🎛️ **Admin Dashboard**
- Centralized management interface at `/admin`
- Vehicle fleet management and pricing control
- Customer relationship management tools
- Reservation monitoring and reporting

## Requirements

- PHP ^8.1
- Composer  
- Node.js & npm/yarn (for frontend assets)
- MySQL 8.0 or SQLite (for database)
- Redis (optional, for caching and sessions)

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

8. Seed the database with sample data (optional):
```bash
php artisan db:seed
```

9. Build frontend assets:
```bash
npm run build
```

### Manual Installation

If you prefer not to use Docker, ensure you have MySQL and Redis running locally, then follow steps 1-5 and 7-9 above, and configure your [`.env`](./.env.example) file with your local database credentials.

### SQLite Setup (Recommended for Quick Testing)

For a simpler setup without Docker or MySQL, use SQLite:

```bash
# Configure for SQLite (simpler setup)
sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
sed -i 's/DB_DATABASE=.*/DB_DATABASE=database.sqlite/' .env
touch database/database.sqlite

# Then run migrations and seeders
php artisan migrate
php artisan db:seed
```

> 🎯 **Note**: The current demo uses SQLite for simplicity and portability.

## Usage

### Accessing the Application
- **Main Application**: Visit `http://localhost:8000` to access the rental management system
- **Admin Dashboard**: Navigate to `/admin` for administrative functions (default credentials: `test@example.com` / `password`)
- **Client Portal**: Browse available cars and make reservations through the main interface
- **Car Availability**: Check real-time car availability and pricing at `/cars/available`
- **phpMyAdmin**: Database management interface available at `http://localhost:8080` (when using Docker)

### Getting Started

1. **Explore Sample Data**: If you ran the database seeder, you'll have sample clients, cars, and reservations ready to explore
2. **Admin Functions**: Use the [admin panel](/admin) to manage vehicles, customers, and view reservations  
3. **Test Reservations**: Try the booking system by browsing [available cars](/cars/available) and making test reservations
4. **Development Mode**: Use `npm run dev` for hot-reloading during development

> 💡 **Tip**: Start by exploring the [admin client management](/admin/clients) section to see the full CRUD functionality in action!

## Configuration

The application uses Laravel Sail for Docker development. Key configuration files:

- [`docker-compose.yml`](./docker-compose.yml) - Docker services configuration
- [`composer.json`](./composer.json) - PHP dependencies and autoloading
- [`package.json`](./package.json) - JavaScript dependencies and build scripts
- [`.env.example`](./.env.example) - Environment configuration template

## Development

### Frontend Development
To start development with hot-reloading:

```bash
npm run dev
```

For production builds:

```bash
npm run build
```

### Backend Development

**Artisan Commands**: Use Laravel's powerful command-line interface:
```bash
php artisan list  # View all available commands
```

**Database Management**: Reset and reseed the database:
```bash
php artisan migrate:fresh --seed
```

**Application Logs**: Monitor application activity:
```bash
tail -f storage/logs/laravel.log
```

**Testing**: Run the test suite:
```bash
php artisan test
```

### Project Structure
- **Models**: Located in [`app/Models/`](./app/Models/) - Contains Car, Client, Reservation, and Marque models
- **Controllers**: Located in [`app/Http/Controllers/`](./app/Http/Controllers/) - Handles application logic
- **Views**: Located in [`resources/views/`](./resources/views/) - Blade templates for the UI
- **Routes**: Defined in [`routes/web.php`](./routes/web.php) - Application routing
- **Migrations**: Located in [`database/migrations/`](./database/migrations/) - Database schema
- **Seeders**: Located in [`database/seeders/`](./database/seeders/) - Sample data generation

## Contributing

We welcome contributions to improve MrcMS! Here's how you can help:

1. **Fork the repository** on GitHub
2. **Create a feature branch**:
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Commit your changes**:
   ```bash
   git commit -m 'Add some amazing feature'
   ```
4. **Push to the branch**:
   ```bash
   git push origin feature/amazing-feature
   ```
5. **Open a Pull Request** with a clear description of your changes

We appreciate all contributions, whether they're bug fixes, new features, documentation improvements, or suggestions!

## Support

If you encounter any issues or have questions:

- 📖 **Documentation**: Check the [Laravel Documentation](https://laravel.com/docs) for framework-specific help
- 🔍 **Debugging**: Review the application logs in [`storage/logs/laravel.log`](./storage/logs/)
- 🐛 **Bug Reports**: Open an issue on [GitHub Issues](https://github.com/MedTaha4Ever/MrcMS/issues) for bug reports or feature requests
- 💬 **Questions**: Feel free to start a discussion for general questions or suggestions

**Common Troubleshooting:**
- **Database Connection**: Ensure your database credentials in [`.env`](./.env) are correct
- **Permission Errors**: Make sure `storage/` and `bootstrap/cache/` directories are writable
- **Missing Dependencies**: Run `composer install` and `npm install` to ensure all dependencies are installed
- **SQLite Issues**: Ensure the `database/database.sqlite` file exists and is writable
- **Email Configuration**: Check your mail settings in `.env` for email notifications to work

## License

This project is built on the Laravel framework, which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
