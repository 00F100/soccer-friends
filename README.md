# Soccer Friends

This project uses [Docker](https://www.docker.com/) for infrastructure and [Laravel Framework](https://laravel.com/) for manipulate players and soccer matches.

> Not needs install PHP, Nodejs, Apache2, Nginx, etc. Just Docker.

## Online view

View this project online on [https://soccer-friends.zcode.app/](https://soccer-friends.zcode.app/)

## Features

- Validate all input data
- Validate relation restrict data
- Use Eloquent ORM relation
- Migrations and Seeds
- Routes HTTP and Controllers
- Helpers for manipulate data
- Sort team by level for auto balance

## Directories and files structure

### Directories

| Directory | Description |
| -- | --- |
| /soccer-friends | Laravel Project, routes, controllers, etc |
| /soccer-friends/app/Http/Controllers | Controllers for HTTP Routes |
| /soccer-friends/app/Helpers | Helpers for Controller |
| /soccer-friends/app/Models | Models of data |
| /soccer-friends/app/Repositories | Repositories |
| /soccer-friends/app/Providers | Providers of instances, services, etc |

### Files

| Directory | Description |
| -- | --- |
| /bin/host/client | Start a Docker container with bash terminal. INcludes PHP and NPM |
| /docker/client/Dockerfile | Dockerfile for create client and web containers |
| /soccer-friends/Dockerfile | Dockerfile for Render pipeline create image |
| /docker-compose.yml | Docker containers configuration with PHP, PostgreSQL and PgMyAdmin |
| /soccer-friends/routes/web.php | HTTP Routes |
| /soccer-friends/storage/logs/laravel.logs | Logs of application |
| /soccer-friends/resources/js/app.js | Javascript for view |
| /soccer-friends/resources/scss/app.scss | Sass for CSS view |

## Development

### Outside container

Start a container with Bash, PHP, Npm, etc

```sh
bin/host/client
```

Start a container for browser for view application using [localhost](http://localhost).

```sh
bin/host/web
```

### Inside container

Install Composer and Nodejs dependencies

```sh
bin/client/install
```

Execute Laravel Eloquent Migrations

```sh
bin/client/migrate
```

Execute Laravel Eloquent Seeds

```sh
bin/client/seed
```

## Production

This project uses pipeline [Render](https://render.com/) for create Docker image and Kubernetes infrastructure.

Example for generate Docker image Production

```sh
cd soccer-friends
docker build -t name-production .
```

## Environment Variables

| Name | Description |
| --- | --- |
| APP_NAME | Application name |
| APP_ENV | Env for application |
| APP_KEY | Application key for encryption |
| APP_DEBUG | Enable debugger |
| APP_URL | Application URL |
| DB_CONNECTION | Driver for datasource connection |
| DB_HOST | Host datasource |
| DB_PORT | Datasource port |
| DB_DATABASE | Datasource database |
| DB_USERNAME | Datasource username |
| DB_PASSWORD | Datasource password |