# Backend TDC

This is a Laravel application that allows you to CRUD users data with authentication.

## Requirements

-   PHP >= 8.1
-   Composer
-   Docker (optional)

## Installation

### Option 1: Using Docker

1. Make sure you have Docker installed on your system.

2. Start the Docker containers:

```
docker-compose up -d

```

This will start the containers defined in `docker-compose.yml` and set up the necessary environment for your Laravel application.

Access the application:

Once the containers are up and running, you can access the application by visiting http://localhost:9000 in your browser.

### Option 2: Local Setup

1. Install dependencies:

```
composer install
```

2. Set up environment variables:
   Rename the .env.example file to .env:

```
cp .env.example .env
```

3. Open the .env file and configure the necessary environment variables, including database connection details.

4. Generate application key:

```
php artisan key:generate
```

5. Generate JWT secret key:

```
php artisan jwt:generate
```

6. Generate JWT public and private keys (optional, for local development):

```
php artisan jwt:generate-certs
```

### Migrate and seed the database:

```
php artisan migrate --seed
```

This will create the necessary database tables and seed the database with initial data.

### Default user credentials:

```
Email: user@example.com
Password: password
```
