**PROJECT FRANKHOOD v2**

This project is used to manage the customers and the membership renewal campaign of a small brotherhood in a small 
lost country.
This repository include two branches:
* Current version (2.4) based on Laravel 11, Bootstrap 5.3 and Vite. PHP 8.2 required
* Version 2.3 based on Laravel 8, Boostrap 5.3 and Laravel Mix. PHP 7.4 required

Installation procedure:
1. git clone 
2. cp .env.default .env 
3. composer install 
4. npm install 
5. touch database/database.sqlite 
6. php artisan migrate
7. php artisan key:generate
8. npm run build

