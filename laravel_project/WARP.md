# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview
This is a Laravel 10 e-commerce application with real-time features, featuring a comprehensive product catalog, order management system, payment processing (Stripe), real-time notifications via WebSockets, and multi-language support.

## Key Development Commands

### Environment Setup
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database (if seeders exist)
php artisan db:seed
```

### Development Server
```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (for assets)
npm run dev

# Build assets for production
npm run build
```

### Database Operations
```bash
# Create new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback last migration batch
php artisan migrate:rollback

# Refresh migrations (drop all tables and re-run)
php artisan migrate:refresh

# Reset database and run seeders
php artisan migrate:refresh --seed
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run tests with coverage
php artisan test --coverage
```

### Cache and Optimization
```bash
# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Clear views cache
php artisan view:clear
```

### WebSockets (Real-time Features)
```bash
# Start WebSocket server for real-time features
php artisan websockets:serve

# In production, use supervisor or similar process manager
```

### Code Quality
```bash
# Fix code style with Laravel Pint
./vendor/bin/pint

# Run specific directories with Pint
./vendor/bin/pint app/Http/Controllers
```

## Architecture Overview

### Core Business Models
- **Product**: Central entity with categories, brands, sizes, reviews, and inventory
- **Order**: Order management with status tracking (pending → confirmed → processing → shipped → delivered)
- **Customer**: User management with authentication via JWT
- **Category/ParentCategory**: Hierarchical product organization
- **Payment**: Integration with Stripe payment processing
- **Notification/Message**: Real-time communication system

### Real-time Features Architecture
The application uses Laravel WebSockets with Pusher protocol for real-time features:
- **Order Events**: `OrderCreated`, `OrderSentToKitchen`, `OrderApprovedCash`
- **User Events**: `UserStatusUpdated`
- **Kitchen Events**: `ScanToKitchen`, `CreditCardToKitchen`
- **Broadcasting Channels**: Uses public channels like `ordersItem`, `user-status`

### API Structure
- **Authentication**: JWT-based with refresh tokens
- **Protected Routes**: All main CRUD operations require `jwt.auth` middleware
- **Public Routes**: Product browsing, category listing, brand information
- **File Upload**: Dedicated endpoints for single and multiple file uploads

### Key Integrations
- **Payment Processing**: Stripe with webhook support
- **Translation**: Google Translate API integration (`statickidz/php-google-translate-free`)
- **WebSockets**: `beyondcode/laravel-websockets` for real-time features
- **Database**: MySQL with Docker support

### Frontend Stack
- **Vite**: Asset bundling and hot reload
- **Tailwind CSS**: Utility-first CSS framework
- **Laravel Echo + Pusher**: Client-side WebSocket handling

## Development Patterns

### Model Relationships
- Products belong to Categories, Brands, and Sizes
- Orders have many OrderItems and belong to Customers
- Reviews belong to Products and Customers
- Hierarchical Categories via ParentCategories

### Event Broadcasting
Events are broadcasted to specific channels for real-time updates. Key events include order status changes and user activities.

### API Response Pattern
Controllers return JSON responses for API endpoints with consistent error handling via the Handler class.

### File Organization
- **Controllers**: Organized in `Api` namespace for API endpoints
- **Models**: Eloquent models with relationships and scopes
- **Events**: Broadcastable events for real-time features
- **Middleware**: Custom middleware for localization and authentication

## Docker Development

### Database Setup
```bash
# Start MySQL and phpMyAdmin via Docker
docker-compose up -d

# Access phpMyAdmin at http://localhost:8080
# Database credentials: root/123, database: laravel
```

### Container Management
```bash
# Build and start containers
docker-compose up -d

# View container logs
docker-compose logs -f

# Stop containers
docker-compose down
```

## Testing Approach

### Test Structure
- **Feature Tests**: End-to-end API testing
- **Unit Tests**: Individual component testing
- **Database**: Uses array driver for cache and sessions in testing

### Running Specific Tests
Focus on testing API endpoints, model relationships, and event broadcasting functionality.

## Important Configuration Notes

### WebSocket Configuration
- Default port: 6001 (configurable via `LARAVEL_WEBSOCKETS_PORT`)
- Statistics enabled with 60-second intervals
- Client messages disabled for security

### JWT Authentication
Uses `tymon/jwt-auth` package - ensure JWT secrets are configured in environment.

### File Uploads
Dedicated file upload system with support for multiple file handling through FileController.

### Multi-language Support
Translation system integrated with Google Translate API via TranslationController.
