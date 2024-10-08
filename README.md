# Stellar

## Description

Stellar is my end-of-year project. As the project is not yet completed, the following images are provisional. The application will concern the rental of atypical vacation accommodations, such as cabins, yurts, etc.

This project uses Symfony, SCSS, NPM, Stripe, Google Maps API...

## Getting Started

### Prerequisites

#### PHP - v.8.2
https://www.php.net/downloads

(For Windows, you can use [Laragon](https://laragon.org/), [XAMPP](https://www.apachefriends.org/index.html) or [WampServer](https://www.wampserver.com/) to install PHP, Apache and MySQL)

#### Composer
- For Linux/MacOS: [Installation Instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
- For Windows: [Installation Instructions](https://getcomposer.org/doc/00-intro.md#installation-windows)

#### MySQL
- For MacOS:
  ```
  brew install mysql
  ```
- For Windows: [MySQL Download](https://www.mysql.com/downloads/)

#### Symfony CLI
- For Linux/MacOS:
  ```
  curl -sS https://get.symfony.com/cli/installer | bash
  sudo mv ~/.symfony*/bin/symfony /usr/local/bin/symfony
  ```
- For Windows: [Symfony Download](https://symfony.com/download)

### Project Installation

1. Clone the project
   ```
   git clone https://github.com/Maximebtz/Stellar.git
   ```

2. Install Composer dependencies
   ```
   composer install
   ```

3. Install NPM dependencies
   ```
   npm install
   ```

4. Configure the database
   ```
   symfony console doctrine:database:create
   symfony console doctrine:schema:update --dump-sql
   symfony console doctrine:schema:update --force
   ```

5. Start the Symfony server
   ```
   symfony serve -d
   ```

   To stop the server:
   ```
   symfony serve:stop
   ```

### SCSS
This project uses SCSS as a CSS preprocessor. Knowledge of SCSS is necessary to work on the style.

### Google maps API
To use the Google Maps API in this project, you will need to create a Google Maps API key and configure it in the application.

### Stripe
To use Stripe in this project, you will need to create a Stripe API key and configure it in the application.


## Project Preview

### Home Page
![Home Page](https://github.com/Maximebtz/Stellar/assets/120190748/ea2c781e-4a5f-4c91-bd33-2b8ae0279fbb)

### Login
![Login](https://github.com/Maximebtz/Stellar/assets/120190748/b0ba0443-0a4b-4d94-ab05-fc6fb671bc31)

### Registration
![Registration](https://github.com/Maximebtz/Stellar/assets/120190748/c3b2b5ee-9aec-4cb7-b010-5b98e7000120)

### Profile Page
![Profile 1](https://github.com/Maximebtz/Stellar/assets/120190748/ee217908-7d65-4ebc-a0ad-e163f8aef801)
![Profile 2](https://github.com/Maximebtz/Stellar/assets/120190748/76075615-ab22-4045-993b-600c99231c9d)

### Listing Detail Page
![Listing Detail](https://github.com/Maximebtz/Stellar/assets/120190748/9100968d-9be1-4026-89a2-6ded82370b4b)

### Add Listing
![Add Listing](https://github.com/Maximebtz/Stellar/assets/120190748/9460a534-8270-4c08-9200-f2816c4505d8)
