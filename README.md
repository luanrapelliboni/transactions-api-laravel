# transactions-api-laravel

To run project, in this root project folder:

` $ cd ./src && cp .env.example .env
`

` $ docker-compose up --build
`

```` 
$ docker-compose exec php bash
$ composer install
$ php artisan migrate
````

To generate swagger documentation run:

```
$ docker-compose exec php bash
$ php artisan l5-swagger:generate
```

To access services:

- Mysql external port: 4306
- HTTP external port: 8088

To access Sagger documentation: 
- http://localhost:8088/api/documentation

Postman Collection is in the project root folder.