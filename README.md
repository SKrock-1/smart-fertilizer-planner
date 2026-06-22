# Smart Fertilizer Planner

Smart Fertilizer Planner is a Laravel SaaS-style precision agriculture platform for managing farm parcels, soil test records, crop RDF data, and scientifically computed fertilizer input plans. Farmers can generate NPK-based fertilizer schedules, review seasonal spend, and export recommendations as PDF reports. Admin users manage crop, fertilizer, and user master data.

## Tech Stack

- Laravel 10
- PHP 8.1+
- MySQL 8
- Laravel Breeze authentication
- Blade templates
- Bootstrap 5.3 via CDN
- Chart.js 4 via CDN
- barryvdh/laravel-dompdf
- Vite, Tailwind assets from Breeze

## Installation

```bash
git clone <repo-url>
cd smart-fertilizer-planner
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run build
```

Update `.env`:

```env
APP_NAME="Smart Fertilizer Planner"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_fertilizer_db
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL, then run:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Open the app at `http://127.0.0.1:8000`.

## Default Logins

| Role | Email | Password |
| --- | --- | --- |
| Admin | admin@fertilizer.com | admin123 |
| Farmer | farmer@test.com | farmer123 |

## Core Features

- Farmer registration and login
- Role-based farmer/admin access
- Land parcel CRUD
- Soil test entry with pH and NPK validation
- Crop and fertilizer master data
- Fertilizer recommendation engine
- Seasonal fertilizer plan history
- Chart.js dashboards
- PDF export for fertilizer plans
- Admin management for users, crops, and fertilizers

## Folder Structure

```text
app/Http/Controllers      Web, admin, lookup, and auth controllers
app/Http/Requests         Form request validation classes
app/Models                Eloquent models and relationships
app/Policies              Authorization rules for SaaS data isolation
app/Services              FertilizerRecommendationService computation engine
database/migrations       MySQL schema
database/seeders          Crop, fertilizer, admin, and sample farmer seed data
resources/views           Blade UI, PDF view, and error pages
public/css/app.css        FertiPlan design system
routes/web.php            Browser routes and authenticated JSON lookups
routes/api.php            Sanctum API route
tests                     Feature and unit test coverage
```

## API Endpoints

Authenticated web JSON endpoints:

- `GET /api/parcel/{parcel}/details` - parcel area, location, latest soil test, and NPK values
- `GET /api/crop/{crop}/details` - crop RDF values, season, variety, and duration

Sanctum API endpoint:

- `GET /api/user` - authenticated user payload

## Production Checklist

Use `.env.production.example` as a starting point:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

Before deploying:

```bash
composer install --optimize-autoloader --no-dev
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan migrate --force
```

After changing routes, config, or views in production, clear and rebuild the relevant cache.

## Testing

```bash
php artisan test
```

The suite covers authentication, role access, fertilizer calculations, plan generation, validation, authorization isolation, and PDF export.

## Screenshots

Add screenshots here before publishing:

- Landing page
- Farmer dashboard
- Land parcel management
- Soil test form
- Generated fertilizer plan
- Admin dashboard

## Notes

The recommendation engine uses parcel area conversion, crop RDF demand, soil nutrient availability factors, and fertilizer nutrient percentages for Urea, DAP, and MOP. Treat generated recommendations as decision-support guidance and verify against local agronomist or extension advisories before field application.
