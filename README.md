Laravel 5 Admin Amazing users
======================
after install this packages, you need install base admin
[adminamazing](https://github.com/selfrelianceme/adminamazing)

-----------------
Install via composer
```
composer require selfreliance/iusers
```

Add Service Provider to `config/app.php` in `providers` section
```php
Selfreliance\Iusers\IusersServiceProvider::class,
```


Go to `http://myapp/admin/users` to view admin amazing

**Move public fields** for view customization:

```
php artisan vendor:publish
``` 