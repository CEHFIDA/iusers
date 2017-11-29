# Laravel 5 Admin Amazing iusers
iusers -  a package that allows you to control users

## Require

- [adminamazing](https://github.com/selfrelianceme/adminamazing)

## How to install

Install via composer
```
composer require selfreliance/iusers
```

### Also you can connect the information block
Edit value blocks in config (config/adminamazing.php)
```
'blocks' => [
    //
    'countUsers' => 'Selfreliance\IUsers\UsersController@registerBlock',
]
```