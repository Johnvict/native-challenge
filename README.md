# Native Challenge

  ## Built with

  * PHP - Lumen Framework
  * MySql
  * Docker
  
  <br>
  <br>

## Setup Instructions
 - To get the app running, run the following command in the project root folder
        
        $ docker-compose up --build

 - Install Composer Dependencies for the Lumen project
    
        $ docker-compose exec app composer install
  
 - Migrate and seed database with all provided records as in csv files (located in project directory `./database/files`)

        $ docker-compose exec app php artisan migrate:fresh --seed

<br>
<br>

## Resources
  - Visit https://documenter.getpostman.com/view/9029061/UV5deaGg to get Postman Documentation


<br>
<br>

## Run Test
  - Run the following command to run test

        $ docker-compose exec app vendor/bin/phpunit


<br>
<br>

## Bonuses
 - Connect to database on your local machine via some database client (such as Sequel Pro)
  
          - Host:     localhost
          - username: root
          - password: [leave it empty]
          - port:     3308
          - database: native_challenge
