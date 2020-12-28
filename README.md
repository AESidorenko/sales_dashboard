# Sales Dashboard Demo Project

Simple dashboard application with the following features:
- time period selection for data query (see top-right page corner).
**Pre-generated data refer to the period from 2020-11-15 to 2020-12-31**. 
- shows total number of customers, revenue and orders for the given period
- shows two charts for statistical data: customers vs orders and revenues vs orders for the given period
- by default the graphs and statistics present data for the last month

## How to install
- go into your local projects directory
- clone project repository:
```bash
git clone https://github.com/AESidorenko/sales_dashboard.git
``` 
- go into project directory
```bash
cd sales_dashboard
```
You have **two options** to run the project:
1) To run the application in docker container (you need docker and docker-compose installed and configured and port 8001 free):
    - run the container:
    ```bash
    docker-compose -f docker-compose.prod.yml up
    ```
    container initialization may take some time, so please be patient during the first start and wait for the message:
    ```
    Project is ready to start. Please, open it in your browser.
    ```
    - after that go to your browser and open the link http://localhost:8001/
    - play!
    
2) To run the application with local web server:

    Please, check system requirements:
    - PHP 7.4, mysqli extension
    - MySQL/MariaDB
    - composer
    - npm

    In the project directory run:
    ```bash
    composer install
    ```
    ... PHP packages will be installed, then install and build JS & CSS with:
    ```bash
    npm install
    ``` 
    and
    ```bash
    npm run build
    ``` 
    In your database client create a database named `sales_dashboard` and configure database connection settings in the file `./config.php`. For example:
    ```php
    ...
       'dbConnectionParameters' => [
           'dbHost'     => 'localhost',
           'dbUsername' => 'root',
           'dbPassword' => 'password',
    ...
    ```
    - next, run the application building script:
    ```bash
    sh bin/build.sh
    ```
    ... it will create database tables and load data fixtures for you
    - start PHP built-in web server:
    ```bash
    php -S 127.0.0.1:8001 -t public
    ```
    - open your browser on http://localhost:8001/
    - play!

___

The application meets the following task requirements:
- is based on MVC architecture:
  - Controllers are in `src/Controller` directory
  - Models are presented by entity and repository classes in `src/Entity` and `src/Repository` directories
  - Views are templates in `/templates` directory
- has interfaces:
  - App\Platform\Database\DatabaseConnectionInterface (implemented by 1 class)
  - App\Platform\Database\ObjectMapper\EntityInterface (implemented by 3 classes)
- has abstract classes:
  - App\Platform\Database\ObjectMapper\AbstractRepository (inherited by 3 classes)
  - App\Exception\HttpProblemJsonException (inherited by 3 classes)
- uses namespaces and follows PSR-4 rules
- built from scratch, without any PHP frameworks like Symfony, Laravel, etc. Only
2 third-party extensions included: for dependency injection and mysql database
- Bootstrap 4 used as html-framework
- jQuery used

## Additional features:
- web API methods for getting statistical data:
  - GET api/v1/statistics/revenues
  - GET api/v1/statistics/customers
  - GET api/v1/statistics/summary
- web API follows the [RFC-7807](https://tools.ietf.org/html/rfc7807) rules for error messaging
- use composer and webpack packages
- common configuration file `config.php` support database connection config and paths-routing config
- automated or custom controllers routing
- support custom database connection interfaces via DatabaseConnectionInterface implementations
- dependency injection into classes constructors
- custom exception classes inherited from App\Exception\HttpProblemJsonException
- data charts on the front-page are special JS-components using async data loading
- cli-scripts in `src/Command` directory
- system platform code is in the `src/Platform` directory
- has dockerized version