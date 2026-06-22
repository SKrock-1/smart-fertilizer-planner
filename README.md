# Smart Fertilizer Planner

A Laravel-based precision agriculture platform for farmers and administrators to manage land parcels, soil tests, crop nutrient demand, fertilizer inventory, and crop-wise fertilizer recommendations.

Smart Fertilizer Planner converts soil test values and crop RDF data into actionable NPK plans, estimated costs, subsidy savings, corrective soil recommendations, charts, history, and printable PDF reports.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Screens and Workflows](#screens-and-workflows)
- [Recommendation Logic](#recommendation-logic)
- [Requirements](#requirements)
- [Installation](#installation)
- [Default Accounts](#default-accounts)
- [Environment Configuration](#environment-configuration)
- [Useful Commands](#useful-commands)
- [Routes and API](#routes-and-api)
- [Testing](#testing)
- [Production Deployment](#production-deployment)
- [Project Structure](#project-structure)
- [Security Notes](#security-notes)
- [License](#license)

## Overview

The application supports two main roles:

- Farmer: manages parcels, records soil tests, generates fertilizer plans, reviews plan history, and downloads PDF recommendations.
- Admin: manages crops, fertilizers, users, and dashboard-level operational data.

The app is built as a server-rendered Laravel application with Blade views, Breeze authentication, role-based access control, Chart.js dashboards, and DomPDF export.

## Features

- Farmer registration and login
- Role-based access for farmer and admin users
- Land parcel CRUD with crop-planning context
- Soil test capture for pH, NPK, organic carbon, zinc, sulfur, and optional lab report uploads
- Crop master data with RDF values, variety, season, and duration
- Fertilizer master data with nutrient percentages, subsidized price, and unsubsidized price
- Least-cost NPK fertilizer recommendation engine
- Corrective recommendations for acidic soil, alkaline soil, low organic carbon, zinc deficiency, and sulfur deficiency
- Fertilizer plan generation with stage-wise application guidance
- Subsidy savings calculation
- Farmer dashboard with charts and recent activity
- Admin dashboard for platform-level management
- Plan history and PDF export
- Authenticated JSON lookup endpoints for dynamic form behavior
- Hindi and English language file structure
- Feature and unit tests for core workflows

## Tech Stack

| Layer | Technology |
| --- | --- |
| Backend | Laravel 10, PHP 8.1+ |
| Auth | Laravel Breeze |
| Database | MySQL 8 |
| Frontend | Blade, Vite, Tailwind CSS assets, Alpine.js |
| UI Helpers | Bootstrap 5.3 CDN, custom CSS |
| Charts | Chart.js 4 CDN |
| PDF | barryvdh/laravel-dompdf |
| API Auth | Laravel Sanctum |
| Testing | PHPUnit 10 |

## Screens and Workflows

### Farmer Flow

1. Register or log in as a farmer.
2. Create a land parcel with area and location details.
3. Add one or more soil tests for the parcel.
4. Generate a fertilizer plan by selecting parcel, crop, and season.
5. Review computed nutrient demand, soil supply, deficit, fertilizer schedule, and cost.
6. Download the plan as a PDF.
7. Track previous plans from history.

### Admin Flow

1. Log in as an administrator.
2. Review dashboard metrics.
3. Manage crop RDF data.
4. Manage fertilizer nutrient percentages and pricing.
5. Review users and update roles.

## Recommendation Logic

The recommendation engine is implemented in `app/Services/FertilizerRecommendationService.php`.

It performs the following steps:

- Converts parcel area from acres to hectares.
- Calculates total crop RDF demand for nitrogen, phosphorus, and potassium.
- Estimates available soil nutrients using standard availability factors.
- Computes nutrient deficits with a zero floor.
- Finds a low-cost active fertilizer combination for NPK requirements.
- Splits urea into base dose and top dressing stages.
- Adds corrective guidance for pH, organic carbon, zinc, and sulfur when test values indicate a deficiency or imbalance.
- Calculates subsidized cost, unsubsidized cost, and estimated subsidy savings.

The output is intended as decision-support guidance. Field application should still be verified against local agronomist or agricultural extension recommendations.

## Requirements

- PHP 8.1 or newer
- Composer
- Node.js 18 or newer
- npm
- MySQL 8 or compatible MariaDB
- Git

Recommended PHP extensions:

- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

## Installation

Clone the repository:

```bash
git clone https://github.com/SKrock-1/smart-fertilizer-planner.git
cd smart-fertilizer-planner
```

Install backend and frontend dependencies:

```bash
composer install
npm install
```

Create the local environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Update database values in `.env`:

```env
APP_NAME="Smart Fertilizer Planner"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_fertilizer_db
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL:

```sql
CREATE DATABASE smart_fertilizer_db;
```

Run migrations and seed demo data:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

Build frontend assets:

```bash
npm run build
```

Start the development server:

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

For active frontend development, run Vite in another terminal:

```bash
npm run dev
```

## Default Accounts

Seeded demo accounts:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@fertilizer.com` | `admin123` |
| Farmer | `farmer@test.com` | `farmer123` |

Change these credentials before using the app in any shared or production environment.

## Environment Configuration

The repository includes:

- `.env.example` for local development
- `.env.production.example` for production deployment planning

Important production values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
FILESYSTEM_DISK=public
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

Never commit a real `.env` file. It is ignored by Git and should stay local to each environment.

## Useful Commands

```bash
# Run the app locally
php artisan serve

# Run Vite dev server
npm run dev

# Build production assets
npm run build

# Reset and seed database
php artisan migrate:fresh --seed

# Create public storage symlink
php artisan storage:link

# Clear Laravel caches
php artisan optimize:clear

# Run tests
php artisan test
```

## Routes and API

### Public Routes

| Method | Path | Purpose |
| --- | --- | --- |
| GET | `/` | Landing page |
| GET | `/locale/{locale}` | Switch language |
| GET | `/login` | Login form |
| GET | `/register` | Registration form |

### Authenticated Routes

| Method | Path | Purpose |
| --- | --- | --- |
| GET | `/dashboard` | Main dashboard |
| GET/PATCH/DELETE | `/profile` | Profile management |
| GET | `/plans/{plan}/pdf` | Download fertilizer plan PDF |

### Farmer Routes

| Method | Path | Purpose |
| --- | --- | --- |
| Resource | `/parcels` | Manage land parcels |
| Resource | `/parcels/{parcel}/soil-tests` | Manage parcel soil tests |
| Resource | `/plans` | Create and review fertilizer plans |
| GET | `/history` | View fertilizer plan history |
| GET | `/api/parcel/{parcel}/details` | Parcel details for dynamic forms |
| GET | `/api/crop/{crop}/details` | Crop RDF details for dynamic forms |

### Admin Routes

| Method | Path | Purpose |
| --- | --- | --- |
| GET | `/admin/dashboard` | Admin dashboard |
| Resource | `/admin/crops` | Manage crops |
| Resource | `/admin/fertilizers` | Manage fertilizers |
| Resource | `/admin/users` | Manage users and roles |

### Sanctum API

| Method | Path | Purpose |
| --- | --- | --- |
| GET | `/api/user` | Authenticated user payload |

## Testing

Run the full test suite:

```bash
php artisan test
```

The suite includes coverage for:

- Authentication
- Registration and profile flows
- Role-based access
- Farmer plan generation
- Fertilizer recommendation calculations
- Production-readiness checks
- Authorization isolation

## Production Deployment

Prepare dependencies and assets:

```bash
composer install --optimize-autoloader --no-dev
npm ci
npm run build
```

Configure `.env` from `.env.production.example`, then run:

```bash
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Recommended server settings:

- Point the web server document root to `public/`.
- Ensure `storage/` and `bootstrap/cache/` are writable by the web server user.
- Set `APP_DEBUG=false`.
- Use HTTPS in production.
- Configure a real mail provider for password reset and notification emails.
- Rotate seeded demo passwords or remove demo users.

After deploying route, config, or view changes, clear and rebuild the relevant Laravel caches.

## Project Structure

```text
app/Http/Controllers      Web, farmer, admin, auth, lookup, and API controllers
app/Http/Middleware       Auth, role, locale, and request middleware
app/Http/Requests         Form request validation classes
app/Models                Eloquent models for users, parcels, soil tests, crops, fertilizers, and plans
app/Policies              Authorization policies for user-owned resources
app/Services              FertilizerRecommendationService calculation engine
database/factories        Model factories for tests and local data
database/migrations       Schema for users, crops, fertilizers, parcels, soil tests, and plans
database/seeders          Demo crops, fertilizers, admin user, and farmer user
lang/en                   English language strings
lang/hi                   Hindi language strings
public/css                Custom application styling
resources/css             Vite/Tailwind entry styles
resources/js              Vite JavaScript entrypoints
resources/views           Blade pages, layouts, admin views, farmer views, auth views, and PDF view
routes/web.php            Browser routes and authenticated lookup endpoints
routes/api.php            Sanctum-protected API route
tests/Feature             Feature tests for user workflows
tests/Unit                Unit tests for recommendation logic
```

## Security Notes

- `.env` is ignored and must not be committed.
- Demo credentials are for local testing only.
- Use strong passwords and a real mail provider in production.
- Keep `APP_DEBUG=false` outside local development.
- Validate file uploads and keep public storage permissions minimal.
- Run dependency updates regularly for Laravel, npm packages, and DomPDF.

## License

This project is open-sourced under the MIT license.
