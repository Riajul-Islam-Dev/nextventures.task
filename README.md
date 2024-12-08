# Next Ventures Task!

## Overview

This project is a **Simple Product Purchase System** designed to demonstrate my skills in **Laravel 11**, database design, API development, and payment gateway integration using **Stripe**.  
It includes the following core features:

-   **User authentication** with role-based access control using Laravel Sanctum.
-   **Product management** for admins with CRUD operations.
-   **Order creation** with stock validation for authenticated users.
-   **Payment gateway integration** to handle fixed-amount payments ($50.00).
-   **RESTful APIs** for various operations.
-   **Feature testing** to ensure code reliability.

## Features

### Authentication

-   User registration and login with Laravel Sanctum.
-   Role-based access control for distinguishing between admin and regular users.

### Product Management

-   Admins can create, read, update, and delete products.
-   Product table fields: `id`, `name`, `description`, `price`, `stock`, `created_at`, `updated_at`.
-   Seeded database with sample product entries.

### Orders Management

-   Authenticated users can place orders.
-   Orders table fields: `id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `created_at`, `updated_at`.
-   Stock availability is checked before order creation.

### Payment Integration

-   Integrated Stripe payment gateway for processing payments.
-   Handles both payment success and failure scenarios.
-   Payments table fields: `id`, `product_name`, `amount`, `status`, `created_at`, `updated_at`.

### RESTful APIs

-   **Authentication APIs**: `/register`, `/login`
-   **Product APIs**: `/products`, CRUD operations (Admin only)
-   **Order APIs**: `/orders`, place and fetch orders
-   **Payment APIs**: `/pay`, `/payment-success`, `/payment-failure`

## Installation and Setup

### Prerequisites

-   PHP 8.1 or higher
-   Composer
-   Node.js (optional)
-   MySQL or a compatible database

### Setup Steps

1.  **Clone the repository**:

    ```bash
    git clone https://github.com/Riajul-Islam-Dev/nextventures.task.git
    cd nextventures.task

    ```

2.  **Install dependencies**:

    ```bash
    composer install

    ```

3.  **Copy the environment file**:

    ```bash
    cp .env.example .env

    ```

4.  **Generate an application key**:

    ```bash
    php artisan key:generate

    ```

5.  **Configure your database** in `.env`:

    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

    ```

6.  **Set up Stripe keys** in `.env`:

    ```ini
    STRIPE_SECRET=your_secret_key
    STRIPE_PUBLIC=your_public_key

    ```

7.  **Run migrations**:

    ```bash
    php artisan migrate

    ```

8.  **Seed the database**:

    ```bash
    php artisan migrate:refresh --seed

    ```

9.  **Serve the application**:

    ```bash
    php artisan serve

    ```

    The app will be available at [http://localhost:8000](http://localhost:8000/).

## Running Tests

### Execute all tests:

```bash
php artisan test

```

### Login Credentials:

```bash
Admin Login:
Email: admin@example.com
Password: password123

User Login:
Email: user@example.com
Password: password123
```

Admin users log in with a role as **Admin users**. They have the following permissions:

-   CRUD permissions for **Roles**, **Permissions**, and **Users**.
-   CRUD permissions for **Products** and **Orders** menus.
    -   Admins can create, edit, and delete products.
    -   Admins can edit and delete orders with a status of "pending."

Regular users have the following features:

-   Place orders.
-   Access the **Orders** menu to proceed to checkout using the **Stripe Payment Gateway**.

## Additional Information

-   **Code Style**: Adheres to PSR-12 standards.
-   **Architecture**: Utilizes the **Repository Pattern** for clean and maintainable code.
-   **Testing**: Comprehensive coverage of core functionalities.
-   **Frontend**: Laravel Blade templates for product and order UI.

## About Me

**Riajul Islam**

-   🌍 [riajul.islam.softkit.io](https://riajul.islam.softkit.io/)
-   ✉️ [riajul.islam.dev@gmail.com](mailto:riajul.islam.dev@gmail.com)
-   🔗 [LinkedIn Profile](https://linkedin.com/in/riajul-islam-dev/)
-   📞 [WhatsApp: +8801722787007](https://wa.me/8801722787007)
