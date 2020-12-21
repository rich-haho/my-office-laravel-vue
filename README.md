<br />
<div align="center">
  <a href="https://github.com/richhaho/zen-office">
    <img src="platform/src/assets/images/logo.png" alt="Logo" width="200" height="130">
  </a>

  <h3 align="center">Zen Office</h3>

  <p align="center">
    Conciergerie d'entreprise
    <br />
    API et PWA
  </p>
</div>

## Table of Contents

* [About the Project](#about-the-project)
  * [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Production](#production)
  * [Super User Creation](#super-user-creation)
* [Contact](#contact)




<!-- ABOUT THE PROJECT -->
## About The Project

### Built With

* [Laravel 6.0](https://laravel.com)
* [VueJS 2.6.10](https://github.com/vuejs/vue)

### Deployment

Deployment is managed with `kubernetes` for all branches except `master` and `develop`  
A specific configuration is made for the `staging` branch : database and storage files will not be deleted for each deployments.

## Getting Started

### Prerequisites

You need the following tools to start this project :
* docker (engine > 18 and compose > 1.23) 

If you are using Mac OSX, please run
```sh
$ cp docker-compose.override.yml.osx docker-compose.override.yml
```

Add to your hosts file :

```sh
127.0.0.1       zen-office.localhost
127.0.0.1       api.zen-office.localhost
```


### Installation

1. Clone the repo
```sh
git clone https://github.com/richhaho/zen-office
cd zen-office
```

#### API & PLATFORM

1. Copy and fill .env files
```sh
cp api/.env.example api/.env
cp .env.example .env
```

2. Start containers
```sh
docker-compose up -d
```

:warning:
To install a new package inside the `platform`, you **MUST** run `yarn` **inside** the bo container

```sh
docker-compose exec bo bash
# Then inside the container, navigate to /usr/app
cd /usr/app/
yarn add my-package
```

:warning:
To install a new package inside the `api`, you **MUST** run `composer` **inside** the api container

```sh
docker-compose exec api bash
# Then inside the container
composer require my-package
```
  
* API is running by default at http://api.zen-office.localhost
* Platform is running by default at http://zen-office.localhost

## Production

### Super User Creation
Super User is a user account that has access to all sites, products and services without any restrictions.

To create this account, you have to run this a special seeder called `SuperUserSeeder` that allows us to create a super user account into the database.

It is **MANDATORY** to have `clients` in the production database, it would fail otherwise.

You can define the email address of this super user by changing `SUPER_USER_MAIL` in the `.env` configuration files, or in your environment variables directly (eg. in docker-compose or kubernetes)

To create the super user account, connect to the api container and seed the database as such:

```sh
docker-compose exec api bash
# Then inside the container
php artisan db:seed --class=SuperUserSeeder
```

After this, the database should have a super user with either the default mail `superuser@yourzenoffice.com` or the one defined in your environment variables. 

 
