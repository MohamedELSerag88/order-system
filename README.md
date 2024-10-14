# Order System Management API

This project is a Laravel-based RESTful API designed to manage order system , payment and sent notifications.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Postman Collection](#postman-collection)

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/MohamedELSerag88/order-system.git
    cd order-system
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

## Configuration

1. **Environment variables:**

   Copy the `.env.example` file to `.env` and configure your environment variables such as database credentials, mail settings, etc.

    ```bash
    cp .env.example .env
    ```

2. **Database Configuration:**

   Set up your database configuration in the `.env` file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=restaurant
    DB_USERNAME=root
    DB_PASSWORD=
    ```
3. **Generate Api documentation:**

    ```bash
    php artisan l5-swagger:generate
    ```
    then go to link {{host}}/api/documentation

## Database Setup

1. **Migrate the database:**

   Run the migrations to create the required tables and data.

    ```bash
    php artisan migrate
    ```

2. **Seed the database:**

    ```bash
    php artisan db:seed
    ```

## Postman Collection

1. **Import this collection:**

    ```
   can find postman collection file inside base directroy of the project 

    ```