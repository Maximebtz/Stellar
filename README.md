# Stellar

## Description

Stellar is my end-of-year project, focusing on the rental of atypical vacation accommodations such as cabins, yurts, and other unique lodgings. As the project is still in development, the images provided are provisional.

This project leverages a robust tech stack including:

- [Symfony](https://symfony.com/doc/current/index.html): A powerful PHP framework for building web applications
- [SCSS](https://sass-lang.com/documentation/): A feature-rich extension of CSS for more maintainable stylesheets
- [NPM](https://docs.npmjs.com/): The package manager for JavaScript, used for managing project dependencies
- [Stripe](https://stripe.com/docs): A comprehensive platform for online payments
- [Google Maps API](https://developers.google.com/maps/documentation): For integrating location-based features

## Getting Started

### Prerequisites

#### [PHP](https://www.php.net/manual/en/) - v.8.2+
PHP 8.2 or higher is required. You can download it from the [official PHP website](https://www.php.net/downloads).

For Windows users, consider using one of these all-in-one packages that include PHP, Apache, and MySQL:
- [Laragon](https://laragon.org/docs/)
- [XAMPP](https://www.apachefriends.org/faq_linux.html)
- [WampServer](https://www.wampserver.com/en/documentation/)

#### [Composer](https://getcomposer.org/doc/)
Composer is a dependency manager for PHP. It's essential for managing Symfony project dependencies.
- For Linux/MacOS: [Installation Instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
- For Windows: [Installation Instructions](https://getcomposer.org/doc/00-intro.md#installation-windows)

#### [MySQL](https://dev.mysql.com/doc/)
MySQL is our chosen database management system.
- For MacOS:
  ```
  brew install mysql
  ```
- For Windows: [MySQL Installer](https://dev.mysql.com/downloads/installer/)
- For Linux: Refer to your distribution's package manager

After installation, make sure to start the MySQL service:
- MacOS: `brew services start mysql`
- Windows: It should start automatically, or you can use the MySQL Workbench
- Linux: `sudo systemctl start mysql`

#### [Symfony CLI](https://symfony.com/doc/current/setup/symfony_server.html)
The Symfony CLI provides useful commands for Symfony development.
- For Linux/MacOS:
  ```
  curl -sS https://get.symfony.com/cli/installer | bash
  sudo mv ~/.symfony*/bin/symfony /usr/local/bin/symfony
  ```
- For Windows: [Symfony CLI Installer](https://symfony.com/download)

### Project Installation

1. Clone the project
   ```
   git clone https://github.com/Maximebtz/Stellar.git
   cd Stellar
   ```

2. Install Composer dependencies
   ```
   composer install
   ```
   This command reads the `composer.json` file and installs all required PHP packages.

3. Install NPM dependencies
   ```
   npm install
   ```
   This installs all JavaScript dependencies defined in `package.json`.

4. Configure the database
   First, ensure your database connection details are correct in the `.env` file.
   Then run:
   ```
   symfony console doctrine:database:create
   symfony console doctrine:schema:update --dump-sql
   symfony console doctrine:schema:update --force
   ```
   These commands create the database, show the SQL that would be executed to update the schema, and then actually update the schema.

5. Start the Symfony development server
   ```
   symfony serve -d
   ```
   The `-d` flag runs the server in the background. You can now access your project at `http://localhost:8000`.

   To stop the server:
   ```
   symfony serve:stop
   ```

### [SCSS](https://sass-lang.com/documentation/)
This project uses SCSS, a CSS preprocessor that allows for more maintainable and feature-rich stylesheets. To compile SCSS to CSS, you'll need to run:

```
npm run watch
```

This command watches for changes in your SCSS files and automatically compiles them to CSS.

### [Google Maps API](https://developers.google.com/maps/documentation)
To integrate Google Maps functionality:

1. Visit the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google Maps JavaScript API
4. Create an API key
5. Add your API key to the `.env` file:

```
GOOGLE_MAPS_API_KEY=your_api_key_here
```

### [Stripe](https://stripe.com/docs)
To enable payment processing with Stripe:

1. Sign up for a [Stripe account](https://dashboard.stripe.com/register)
2. Retrieve your API keys from the Stripe dashboard
3. Add your Stripe secret key to the `.env` file:

```
STRIPE_API_KEY=sk_test_your_secret_key_here
```

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

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open source and available under the [MIT License](LICENSE).