## About Resource Manager

API for Resource Manager made with **Laravel** (v. 9). Making use of Laravel Repository pattern.

Frontend made with **Vue** (v. 3) - mostly TypeScript and some JavaScript, and **Quasar** Components (v. 2).
Making use of Vue Pinia for State Management. 

Includes a simple docker-compose.yml file to help set up the project quicly with Docker.

## Introduction
The project is built with Laravel 9 and Vue 3 (Quasar 2). The Vue files are in a directory called `resource_spa`.
This is a complete SPA directory that can be extracted from this repo and deployed separately on a node server(with express js and Nginx proxy).

For deployment on a single server, I have set up an instruction in `webpack.mix.js` to copy the compiled vue files into 
laravel public directory and to the `resources/views/app.blade.php` laravel blade file.
This allows laravel to serve the Vue SPA on it's home route, then the Vue's routing will pick up for front-end navigation.
Hence, just one server to do the work.

If you want to avoid this **Manual Setup** Instructions, You can skip to the **Docker Compose** Section Down the Page.

### Manual Setup on local machine/environment
Pull the project from the repo to your local environment.

```bash
git clone https://github.com/jaymoh/resource-manager.git
```

Change into the directory.

```bash
cd resource-manager
```

Copy **.env.example** to **.env** and configure the database credentials as per your environment.

```bash
cp .env.example .env
```

Set up your database credentials appropriately in the `.env` file.

Install the composer dependencies.

```bash
composer install
```

Generate the laravel key.

```bash
php artisan key:generate
```

Run the database migrations.

```bash
php artisan migrate
```

Run the laravel tests:

```bash
php artisan test
```

### Build the Vue SPA
First, install npm dependencies for laravel.

```bash
npm install
```

Install quasar cli on your dev environment.

```bash
npm install -g @quasar/cli
```

Install the npm dependencies for the SPA. Note: you can find a `README.md` for the spa in the `resource_spa` directory.

```bash
npm run install-spa
```
These two lines, defined in the `resource_spa/.env.production` are crucial depending on where the laravel API that we have set up above is running.
`LOCAL_API_URL=http://127.0.0.1:8021/api/`

`PRODUCTION_API_URL=http://127.0.0.1:8021/api/`

The npm scripts are defined in the `resource_spa/package.json` file. 

When running `npm run dev` for hot-code reloading, the `npm run dev` script will use the `LOCAL_API_URL` variable since the app will be in dev mode.
When running `npm run build` for production, the `npm run build` script will use the `PRODUCTION_API_URL` variable since the app will be in production mode.

If running locally and want laravel to serve the Vue SPA,
change the `PRODUCTION_API_URL` variable to match the host on which laravel is running. Usually `PRODUCTION_API_URL` 
could be the public domain serving the laravel API. 

Build the Vue SPA and copy the files to the laravel public directory.

```bash
npm run build-spa
```

Now serve the app and laravel will serve the Vue SPA on its homepage webroot.

Run the laravel server on port `8021`, since it is the port set this in the frontend: `resource_spa/.env.production`. 
You are free to modify the port and rebuild the Vue Spa. 

```bash
php artisan serve --port=8021
```

## Using Docker Compose
You should have Docker and Docker Compose installed on your machine.

The `docker-compose.yml` file includes 3 services:

First service for MySQL database starts the MySQL 8 container.

Second service for Laravel API. It will build the laravel API container based on the `Dockerfile` in the root directory.
It exposes the API using laravel octane on port 8021.
See the `launch.sh` container starter file in the project root folder.

Third service for the Vue SPA. It will build the Vue SPA container based on the `Dockerfile` in the `resource_spa` directory.
It exposes the SPA using express.js on port `8020`, as defined in `resource_spa/server.js`.

#### Configure mysql credentials.

Check the `docker-compose.yml` file under the **MySQL Service** section. The `DB_HOST` variable will be `db` which is defined as a service running
the mysql container, it will be accessible by all containers on the bridge network.

In this case, the `.env` should have the following variables. They are already defined in the `.env.example` file, 
so if you had copied it earlier, you won't have to change anything.  

`DB_CONNECTION=mysql`

`DB_HOST=db`

`DB_PORT=3306`

`DB_DATABASE=resource_db`

Build the containers with docker-compose using the `docker-compose build` command.

```bash
docker-compose build
```

Fire up the containers in detached mode with docker-compose using the `docker-compose up` command.

```bash
docker-compose up -d
```
When the containers are started for the first time, the migration script in `launch.sh` fails, 
I think because the network bridge is not up yet. 
I'm still figuring out how to have the network bridge up before the `api` service. 

For now, you can either start an interactive shell inside the `api` service and run migrations as explained in a section below 
or stop the `api` service and start it again: 

```bash
docker-compose stop api
docker-compose up -d api
```

The app frontend should be accessible on port **8020**. Access it at `http://127.0.0.1:8020/`.
The app backend API should be accessible on port **8021**. Access it at `http://127.0.0.1:8021/api/`.

#### Miscellaneous
If you chose to use a different password and username than the root user, and have defined the details in the `.env` file, 
then you will need to start an interactive shell in the `db` service container and create the user and password:

```bash
docker-compose exec db bash
```

Then login into mysql with `MYSQL_ROOT_PASSWORD` value defined in the `docker-compose.yml` file.

```bash
mysql -u root -p
```

The database is already created by the service, so just create a `user` and set `password` as defined in your `.env` file. 
Modify where appropriate:

```bash
CREATE USER 'resource_user'@'%' IDENTIFIED BY 'resource_user_password';
GRANT ALL PRIVILEGES ON resource_db.* TO 'resource_user'@'%';
FLUSH PRIVILEGES;
```

Exit the `db` container shell when done.

Start a shell in the `api` service container:  

```bash
docker-compose exec api bash
```

Run migrations:

```bash
php artisan migrate --force --no-interaction
```

Optionally run tests within the `api` service:

```bash
php artisan test
```

Stream logs on all the `docker-compose` services: 
    
```bash
docker-compose logs -f
```

Thanks for reading this tutorial!
