# nginx-php-symfony-mysql-dockerized-boilerplate
Boilerplate for Symfony projects in docker

## Versions:
**Symfony 4.4**

**PHP 7.4.6**

**MySQL 5.7**

## Install and run:
1. ```git clone git@github.com:DmitryIvanov10/nginx-php-symfony-mysql-dockerized-boilerplate.git```
2. ```docker-compose up --build -d```
3. ```composer update``` / ```composer install``` (on host machine or inside php container)
4. Add ```127.0.0.1 app.local``` to `/etc/hosts`
5. The app is available at `app.local:81`
6. Check if everything works and get `phpinfo()` at `app.local:81/php_info`
