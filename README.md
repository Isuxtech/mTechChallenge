MTECH CHALLENGE REPOSITORY README

AUTHOR: Omokpo Gabriel

Email: omokpogabriel@gmail.com

Language used: Php

Framework: Laravel

Database: MySQL


PLEASE perform the following steps after cloning this repository:

1. run composer install -  this will get all the required dependency
2. rename the  .env.example to .env file, 
3. in the new .env file, replace DB_DATABASE=laravel with DB_DATABASE=mTechdb
4.  Create a new database with the name "mTechdb"
5. run php artisan key:generate


DIRECTORY STRUCTURE

The main code for this implementation is in: app/Http/Controllers/newsController

The "Welcome.blade.php" found in resource/views dierctory shows the response returned to the client after each 
request is made to the endpoint

The .env file contains the database connection as well as several other vital configurations

Feel free to contact the Author  for any addition information