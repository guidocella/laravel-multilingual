# Laravel Multilingual Models

Make Eloquent model attributes translatable without separate database tables for translation values.

Simply access `$country->name` and you get a value based on your application's current locale.

`$country->nameTranslations->en` will be the value of a specific locale.

You can get all the translations of a given attributes with `$country->nameTranslations->toArray()`.

## Installation

Install the package through Composer:

```sh
composer require guidocella/laravel-multilingual
```

Then publish the config file:

```sh
php artisan vendor:publish
```

# Usage

First make sure that the translatable attributes' field type is `text` or `json`. If you are building the database from a migration file you may do this:

```php
<?php

Schema::create('countries', function (Blueprint $table)
{
	$table->increments('id');
	$table->json('name');
});
```

Now that you have the database ready to save a JSON string, add the `Translatable` trait to your models and a public array property `$translatable` that holds the names of the translatable fields.

```php
<?php

class Country extends Model
{
    use GuidoCella\Multilingual\Translatable;

    public $translatable = ['name'];
}
```

The trait will override the `getCasts` method to instruct Eloquent to cast all `$translatable` attributes to `array` without having to specify them again in `$casts`.

Now that our model's `name` attribute is translatable, when creating a new model you may specify the name field as follows:

```php
<?php

Country::create([
	'name' => [
		'en' => 'Spain',
		'es' => 'España'
	]
]);
```

It will be automatically converted to a JSON string and saved in the name field of the database. You can later retrieve the name like this:

```php
$country->name
```

This will return the country name based on the current locale. If the translation in the current locale doesn't have a non-null value then the `fallback_locale` defined in the config file will be used.

In case nothing can be found `null` will be returned.

You may also want to return the value for a specific locale; you can do it using the following syntax:

```php
$country->nameTranslations->en
```

This will return the English name of the country.

To return an array of all the available translations you may use:

```php
$country->nameTranslations->toArray()
```

You can update the translation in a single locale with Eloquent's arrow syntax for JSON fields:

```php
$country->update(['name->'.App::getLocale() => 'Spain']);
```

# Validation

You can validate the presence of specific locales like so:

```php
<?php

$validator = validator(
    ['name' => ['en' => 'One', 'es' => 'Uno']],
    ['name.en' => 'required']
);
```

However, this package includes the `translatable_required` validation rule for requiring that the translations are provided in every locale:

```php
<?php

$validator = validator(
    ['name' => ['en' => 'One', 'es' => 'Uno']],
    ['name' => 'translatable_required']
);
```

You may define the available locales as well as the `fallback_locale` from the package config file.

Now you only need to add the translated message of our new validation rule: add this to the `validation.php` translation file:

```php
'translatable_required' => 'The :attribute translations must be provided.',
```

# Queries

Laravel lets you query JSON columns with the `->` operator:

```php
Company::where('name->en', 'Monsters Inc.')->first();

Country::orderBy('name->'.App::getLocale())->get();
```
