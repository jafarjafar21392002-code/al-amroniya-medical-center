# Al Amroniya Medical Center

A web-based medical center management and appointment booking system built with Laravel.

## Technologies

- PHP 8.1+
- Laravel 10
- MySQL
- Filament 3
- Laravel Sanctum
- Vite

## Features

- Medical center homepage.
- Doctor management.
- Doctor schedule management.
- Appointment booking.
- Appointment management through the admin panel.
- Calendar interface for managing appointments.

## Local Installation

### Requirements
- PHP 8.1+
- Composer
- MySQL
- Node.js and npm

### Setup

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd al-omraniya-clinic
   ```

2. Install dependencies:

   ```bash
   composer install
   npm install
   ```

3. Configure environment:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure database credentials in `.env`.

5. Run migrations:

   ```bash
   php artisan migrate
   ```

6. Build frontend assets:

   ```bash
   npm run build
   ```

7. Create storage link:

   ```bash
   php artisan storage:link
   ```

8. Start the application:

   ```bash
   php artisan serve
   ```

Open `http://127.0.0.1:8000`.

## Security

This project is under development. Production deployment requires appropriate security configuration and testing.

Never commit `.env`, application keys, database credentials, or private medical data.

## License

Private project. All rights reserved.
