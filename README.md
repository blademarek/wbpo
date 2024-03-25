# Intro

- This is an implementation of a Backend functionality in Laravel
- Data is stored in Mysql
- The project is dockerized
- Xdebug is configured inside the container for easier debugging
- Nginx is employed as a proxy
- Basic GitFlow is used for demonstration purposes
- Feature and Unit tests included


# Setup

#### To run this project live, few steps must be followed:

1. Start docker-compose (prerequisite is installed docker)
    ```
    docker-compose up
    ```

2. Enter application docker container

    ```
    docker-compose exec app /bin/bash
    ```
   Following commands will be run inside `app` container.

3. Install composer packages.

   ```
   composer install
   ```

4. Generate APP_KEY and store it in .env file

   ```
   php artisan key:generate
   ```

5. Initiate DB, run migrations

   ```
   php artisan migrate
   ```

Passport credentials can be generated through passport call
   ```
   php artisan passport:client --client
   ```


# Task description

Create simple Laravel API that has following features:
- php 8.2 or 8.3
- add tests for following features (unit and feature tests)
- passport oauth server to server auth
- create payment link api endpoint (create payment service that can be extended with more providers later, at least 2 testing providers)
- creating transaction with fields like amount, currency, provider, user, status
- proper logging of each steps in transaction
- allow only proper transaction statuses (new to processing, processing to completed or failed, cannot go from failed to new
  or completed, cannot go to others from completed etc.)
- DRY/SOLID principes, show the best of your coding skills (events, repositories, abstraction etc)

Delivery as git repository on github (show your git flow).
