# logger

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require alphadeltas/logger
```

## Usage

You may use LogMiddleware to log any request to your endpoints you just need to enable it in your routes.

You also may use LoggerServiceProvider to log jobs and commands.
You need to enable it in your config/app.php file (hopefully this will be added by composer automatically). 
Add this line to providers array: 

``` php
'providers' => [
    ...,
    AlphaDeltas\Logger\Providers\LoggerServiceProvider::class,
    ...
],
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email bohdanklochko1@gmail.com instead of using the issue tracker.

## Credits

- [Bohdan Klochko][link-author]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/alphadeltas/logger.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/alphadeltas/logger.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/alphadeltas/logger/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/alphadeltas/logger
[link-downloads]: https://packagist.org/packages/alphadeltas/logger
[link-travis]: https://travis-ci.org/alphadeltas/logger
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/alphadeltas
[link-contributors]: ../../contributors
