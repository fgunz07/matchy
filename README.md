## About
This is a proof of concept for a dating style website I called ```matchy```. A light weight web platform where users can send like to other users and send direct message.

## Tech stacks
- **Laravel**
- **Docker**
- **Mysql**
- **Nuxt**
- **TailwindCSS**
- **DaisyUI**
- **Firebase Auth**

## Achitecture
- CRUDDY
- Single Action Class
- Observer
- Scope
- Form Request
- Resources
- DTO (Data Transfer Objects)

## Packages used
- **sanctum:** simple api token base authentication
- **socialite:** enable third party authentication
- **laravel-data:** for data transfer objects
- **query-builder:** build Eloquent queries from API requests
- **queueable-action:** enable actions classes to be pushed to queue

## Firebase auth
Leveraging third party 0auth enable login using google accounts set:
```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT="${APP_URL}/api/auth/social/google/callback"
```

## Docker
Docker environment setup using laravel sail
```bash
// try running composer install
docker compose run laravel.test composer install
// try running npm install
docker compose run app npm install
// If want to have a dummy data user the seeder
docker compose run laravel.test php artisan db:seed

docker compose build
docker compose up -d
```

## Services
- **laravel.test:** the main laravel application
- **mysql:** the database
- **redis:** the cache database but is not being used just keep it running if ever this will be needed in future
- **meilisearch:** service like elasticsearch this is by default keep it running if ever this will be needed in future
- **mailplit:** the smtp email server for email testing this is currently in used for mailing specially for ```email verification``` and ```forget password```
- **selenium:** the chromium browser usually used when running test this is by default keep it running if ever this will be needed in future
- **queue:** this holds the processing of the queue
- **scheduler:** this runs all the schedules jobs that will be process by queue
- **app:** this is the frontend application running

## Trouble shoot
Sometimes queue service fails or stop running due to database connection just restart the service.
```bash
docker composer restart queue
```

## API Documentation
As the backend is an API. A full api documentation ```matchy.postman_collection.json``` can be imported to ```postman```.
