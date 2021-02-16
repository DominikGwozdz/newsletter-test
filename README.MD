#Newsletter Test project

###How to run:
1. Install docker
2. Rename `.env-sample` file to `.env`
3. run in root directory `docker-compose up -d`
5. now run `docker container ps` and check your `CONTAINER ID` of `php-app` name
   - take `CONTAINER ID` first 3-4 signs and remember it (for example something like 973b - but your docker will generate it different - in my case it is `973b2b486533`)
   - from now in below commands I will use `973b` as my container alias for docker exec command - it lets to run commands in containers
6. run `docker exec 973b composer install` to install dependencies in vendor
7. run `docker exec -i 973b php bin/console d:m:m` to execute migrations at database
8. Confirm migration by press `RETURN`
9. Ports 3309, 8000 must be free on your host
10. Visit `localhost:8000`

###Requirements:
- Docker

###Additional Information
- Project use PHP 8.0.0-fpm as service and Nginx as web server with mysql 5.7 and all this in 3 different docker containers. Project based on latest Symfony

####PHP Unit
1. run command `docker exec 973b php bin/phpunit`