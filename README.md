# E-Commerce Shopping Cart

A full-stack Laravel + React shopping cart application demonstrating modern web development practices.

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** React with Inertia.js
- **Styling:** Tailwind CSS
- **Database:** SQLite
- **Queue:** Database driver
- **Authentication:** Laravel Breeze

## Features Implemented

### Core Features
1. **User Authentication** - Registration, login, logout (Laravel Breeze)
2. **Product Browsing** - View all products with stock information
3. **Shopping Cart**
   - Add products to cart
   - Update quantities
   - Remove items
   - Stock validation
4. **Checkout Process**
   - Create orders
   - Update product stock
   - Clear cart after purchase

### Advanced Features
5. **Low Stock Notification (Laravel Jobs/Queues)**
   - Automatically sends email notification when product stock falls below 5
   - Uses database queue
   - Triggered during checkout

6. **Daily Sales Report (Scheduled Job/Cron)**
   - Runs daily at midnight
   - Sends email report with yesterday's sales data
   - Includes order details and total revenue

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM

### Steps

1. **Install dependencies**
```bash
composer install
npm install
```

2. **Environment setup**
```bash
# Copy .env.example if needed (already configured)
# Database is already set up (SQLite)
```

3. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

This will create:
- 2 test users (test@example.com and admin@example.com) - password: "password"
- 8 sample products (some with low stock for testing notifications)

4. **Build frontend assets**
```bash
npm run build
# OR for development with hot reload:
npm run dev
```

5. **Start the application**
```bash
php artisan serve
```

Visit: http://localhost:8000

## Testing the Application

### 1. Basic Shopping Flow
1. Register a new account or login with: `test@example.com` / `password`
2. Navigate to "Products" from the menu
3. Add items to cart (notice stock validation)
4. Click "View Cart"
5. Update quantities or remove items
6. Click "Proceed to Checkout"

### 2. Testing Low Stock Notifications

The system automatically sends notifications when stock ≤ 5 after checkout.

**Option A: Test with seeded products**
- Products "Wireless Mouse" (stock: 3), "USB-C Hub" (stock: 2), and "Headphones" (stock: 1) are already low
- Add these to cart and checkout to trigger notifications

**Option B: Test manually**
1. Add a product to cart multiple times to reduce its stock below 5
2. Complete checkout
3. Check `storage/logs/laravel.log` for the email notification

**Running the queue worker:**
```bash
php artisan queue:work
```

This processes the low stock notification jobs.

### 3. Testing Daily Sales Report

The report is scheduled to run daily at midnight. To test immediately:

**Option A: Dispatch manually**
```bash
php artisan tinker
```
Then run:
```php
App\Jobs\DailySalesReportJob::dispatch();
exit
```

**Option B: Run the scheduler**
```bash
# This runs all scheduled tasks
php artisan schedule:run
```

**Check the email:**
```bash
# View the generated email in the log
tail -f storage/logs/laravel.log
```

### 4. Viewing Email Notifications

Since MAIL_MAILER=log, all emails are logged to:
```
storage/logs/laravel.log
```

Search for "MIME-Version" to find email content, or use:
```bash
grep -A 50 "Low Stock Alert" storage/logs/laravel.log
grep -A 100 "Daily Sales Report" storage/logs/laravel.log
```

## Database Schema

### Products
- id, name, description, price, stock_quantity, image, timestamps

### Cart Items
- id, user_id, product_id, quantity, timestamps
- Foreign keys: user_id → users, product_id → products

### Orders
- id, user_id, total_amount, status, timestamps
- Foreign key: user_id → users

### Order Items
- id, order_id, product_id, quantity, price, timestamps
- Foreign keys: order_id → orders, product_id → products
- Note: Price stored at purchase time (not referenced from products)

## Project Structure

```
app/
├── Http/Controllers/
│   ├── ProductController.php      # Product listing
│   ├── CartController.php         # Cart operations
│   └── CheckoutController.php     # Order processing
├── Models/
│   ├── Product.php
│   ├── CartItem.php
│   ├── Order.php
│   └── OrderItem.php
├── Jobs/
│   ├── CheckLowStockJob.php       # Low stock notification
│   └── DailySalesReportJob.php    # Daily sales report
└── Mail/
    ├── LowStockNotification.php
    └── DailySalesReport.php

resources/
├── js/Pages/
│   ├── Products/Index.jsx         # Product listing page
│   └── Cart/Index.jsx             # Shopping cart page
└── views/emails/
    ├── low-stock.blade.php
    └── daily-sales-report.blade.php

routes/
├── web.php                        # Web routes
└── console.php                    # Scheduled jobs

database/
├── migrations/                    # All database migrations
└── seeders/
    └── ProductSeeder.php          # Sample products
```

## Key Implementation Details

### Laravel Queues
- **Driver:** Database (`QUEUE_CONNECTION=database`)
- **Jobs Table:** Created via migration
- **Processing:** Run `php artisan queue:work` to process jobs
- **Usage:** Low stock notifications dispatched in CheckoutController

### Scheduled Jobs
- **Definition:** routes/console.php
- **Schedule:** Daily at midnight
- **Testing:** `php artisan schedule:run` or `php artisan schedule:work`
- **Production:** Add to cron: `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`

### Email Configuration
- **Development:** MAIL_MAILER=log (writes to laravel.log)
- **Production:** Update .env with SMTP credentials
- **Recipients:** admin@example.com (configurable in job classes)

### Inertia.js Integration
- **Bridge:** Laravel backend ↔ React frontend
- **No API routes needed** - Controllers return Inertia::render()
- **Props:** Data passed from controller to React components
- **Forms:** Using Inertia's router for POST/PATCH/DELETE requests

## Important Notes

1. **Queue Worker:** Must run `php artisan queue:work` for low stock notifications to be sent
2. **Scheduler:** Must run `php artisan schedule:work` (dev) or set up cron (production) for daily reports
3. **Email Viewing:** Check `storage/logs/laravel.log` for email content
4. **Test Credentials:** test@example.com / password OR admin@example.com / password

## Potential Improvements

- Add product images (currently placeholder paths)
- Implement payment gateway integration
- Add order history page for users
- Add admin dashboard for managing products
- Implement real-time stock updates with WebSockets
- Add email verification
- Add pagination for products
- Add product search and filtering
- Add order status tracking
- Implement automated testing

## Development

```bash
# Run development server with hot reload
npm run dev

# In another terminal
php artisan serve

# In another terminal (for queue processing)
php artisan queue:work
```

## Author

**Talha Masood Khan**
Full Stack Developer | Laravel + React Specialist

Portfolio: [talhamasoodkhan.vercel.app](https://talhamasoodkhan.vercel.app)
