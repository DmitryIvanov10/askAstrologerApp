# nginx-php-symfony-mysql-dockerized-boilerplate
Boilerplate for Symfony projects in docker

## Versions:
**Symfony 4.4**

**PHP 7.4.6**

**MySQL 5.7**

## Install and run:
1. ```git clone git@github.com:DmitryIvanov10/nginx-php-symfony-mysql-dockerized-boilerplate.git```
2. ```cd nginx-php-symfony-mysql-dockerized-boilerplate```
3. ```docker-compose up --build -d```
3. ```docker exec -it php sh```
4. Inside php container: 
 4.1. ```composer update```
 4.2. ```composer install```
 4.3. ```php bin/console doctrine:migrations:migrate```
 4.4. ```php bin/console doctrine:fixtures:load ```
5. Add ```127.0.0.1 app.local``` to `/etc/hosts`
