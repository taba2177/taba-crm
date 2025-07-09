# Taba CRM

A CRM package for Taba.

## Installation

You can install the package via composer:

```bash
composer require taba/crm
```

## Usage

After installing the package, you can access the CRM by visiting the `/admin` URL.

### Publishing Assets

You can publish the package's assets using the following command:

```bash
php artisan vendor:publish --provider="Taba\Crm\Providers\CrmServiceProvider" --tag=public
```

### Publishing Configuration

You can publish the package's configuration file using the following command:

```bash
php artisan vendor:publish --provider="Taba\Crm\Providers\CrmServiceProvider" --tag=config
```

### Publishing Views

You can publish the package's views using the following command:

```bash
php artisan vendor:publish --provider="Taba\Crm\Providers\CrmServiceProvider" --tag=views
```

## License

The Taba CRM is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).