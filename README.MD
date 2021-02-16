#Newsletter Test project

###How to run:
1. Install docker
2. copy `.env-sample` file and rename to `.env`
3. run in root directory `docker-compose build app`
4. run in root directory `docker-compose up -d`
5. Ports 3309, 8000 must be free
6. Visit `localhost:8000`

###Requirements:
- Docker

###Additional Information
- Project use PHP 8.0.0-fpm as service and Nginx as web server with mysql 5.7 and all this in 3 docker containers.
