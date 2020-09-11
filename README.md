# Blog API Rest
A RESTful web service

* REST API (Routes, controllers, Eloquent, Relationships)
* Database migrations and Database seeders
* Input validation
* User access and Token based authentication and sessions. **JWT Auth**
* Proper 404 pages
* API versioning **Dingo**
* Rate limits/Throttling
* Transformers/Serializers and Meta information **Fractal**

**Requirements**
- [x] PHP 7.4
- [x] MySQL
- [x] Composer

 ### Setup
 Install the dependencies
```
 composer install
```

Create a blog and blog_testing databases

Change the Database configuration
```
cp .env.examples .env
```
Run the migration and seeders
```
php artisan migrate && php artisan db:seed
```
Run the application
```
php -S localhost:8000 -t public
```
