#Newsletter Test project

###How to run:
1. Install docker
2. Rename `.env-sample` file to `.env`
3. run in root directory `docker-compose build app`
4. run in root directory `docker-compose up -d`
5. now run `docker container ps` and check your `CONTAINER ID` of `newsletter-test_app` image
6. run `docker exec [first 3 or 4 letter of CONTAINER ID from 5.] composer install`
   - for example `docker exec 4f89 composer install`
6. run `docker exec -i [first 3 or 4 letter of CONTAINER ID from 5.] php bin/console d:m:m`
   - for example `docker exec -i 4f89 php bin/console d:m:m`
7. Confirm migration by press `RETURN`
5. Ports 3309, 8000 must be free on your host
6. Visit `localhost:8000`

###Requirements:
- Docker

###Additional Information
- Project use PHP 8.0.0-fpm as service and Nginx as web server with mysql 5.7 and all this in 3 docker containers.
