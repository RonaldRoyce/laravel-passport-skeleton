<p align="left"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p> 

## Passport Skeleton

## About Passport Skeleton

Passport skeleton is a laravel framework skeleton that provides out-of-the-box configuration for Laravel Passport
functionality.

## License

The Passport Skeleton skeleton is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Installation

<ul>
  <li>composer create-project https://github.com/ronaldroyce/laravel-passport-skeleton [dirname]</li>
  <li>cp .env.example .env
  <li>php artisan key:generate</li>
  <li>php artisan passport:client</li>
  <li>Edit the .env file and specify the values displayed in above step for:
    <ul>
      <li>PASSPORT_CLIENT_ID</li>
      <li>PASSPORT_SECRET</li>
      <li>PASSPORT_CERT_DIR   (Directory to store the oauth cert key files</li>
    </ul>
  </li>
</ul>
