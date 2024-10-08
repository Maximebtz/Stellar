# -- Stellar --

![image 1](https://github.com/Maximebtz/Stellar/assets/120190748/bc7fc26c-5566-45f6-9430-fc07c7cf5899)

# Description --
Stellar is my end-of-year project. As the project is not yet complete, the following images are provisional.
The application will involve renting out atypical vacation accommodations, such as cabins, yurts and so on.

This project use Symfony, SCSS, NPM, Stripe, Google maps API...

# Get Started -- 

## -- Need to be intalled before --

## PHP - v.8.2
https://www.php.net/downloads

(For windows you can use https://laragon.org/, https://www.apachefriends.org/fr/index.html or https://www.wampserver.com/ for installing PHP, Apache and MySQL)

## Composer
For Linux/MacOS :
https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos

For Windows :
https://getcomposer.org/doc/00-intro.md#installation-windows

## MySQL
For MacOS:
```
brew install mysql
```

For windows :
https://www.mysql.com/downloads/

## Symfony CLI
For minux/MacOS
```
curl -sS https://get.symfony.com/cli/installer | bash
sudo mv ~/.symfony*/bin/symfony /usr/local/bin/symfony
```

For windows :
https://symfony.com/download


## -- For the project --

## Download the project
``` 
git clone https://github.com/Maximebtz/Stellar.git
```

## Composer
```
composer install
```

## NPM
```
npm install
```

## Symfony
### Create DATABASE
```
symfony console doctrine:database:create
```
### Push the DATABASE
```
symfony console doctrine:schema:update --dump-sql
``` (to check everything that goes into the database)
```
symfony console doctrine:schema:update --force
``` (to push in the database)

## Start the Symfony Server
```
symfony serve -d
```

## or Stop the server
```
symfony serve:stop
```

## SCSS 
You need to know the CSS pre-processor "SCSS", because this projet use it for the style



# -- Some pictures --
## Home Page
![screencapture-127-0-0-1-8000-home-2023-11-07-15_54_14 (2)](https://github.com/Maximebtz/Stellar/assets/120190748/ea2c781e-4a5f-4c91-bd33-2b8ae0279fbb)

## Log In
![image](https://github.com/Maximebtz/Stellar/assets/120190748/b0ba0443-0a4b-4d94-ab05-fc6fb671bc31)

## Sign Up
![image](https://github.com/Maximebtz/Stellar/assets/120190748/c3b2b5ee-9aec-4cb7-b010-5b98e7000120)

## Profil Page
![screencapture-127-0-0-1-8000-user-profil-2023-11-02-17_28_35](https://github.com/Maximebtz/Stellar/assets/120190748/ee217908-7d65-4ebc-a0ad-e163f8aef801)
![screencapture-127-0-0-1-8000-user-profil-2023-11-02-17_30_03](https://github.com/Maximebtz/Stellar/assets/120190748/76075615-ab22-4045-993b-600c99231c9d)

## Advert-Detail Page
![screencapture-127-0-0-1-8000-detail-annonce-Mountain-Cabins-41-2023-11-02-17_33_41](https://github.com/Maximebtz/Stellar/assets/120190748/9100968d-9be1-4026-89a2-6ded82370b4b)

## Advert-Add
![Advert-Add](https://github.com/Maximebtz/Stellar/assets/120190748/9460a534-8270-4c08-9200-f2816c4505d8)
