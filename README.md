# Indonesian Bank Mutation

[![Latest Stable Version](https://poser.pugx.org/lugasdev/mutasi/v/stable)](https://packagist.org/packages/lugasdev/mutasi)
[![Total Downloads](https://poser.pugx.org/lugasdev/mutasi/downloads)](https://packagist.org/packages/lugasdev/mutasi)
[![Latest Unstable Version](https://poser.pugx.org/lugasdev/mutasi/v/unstable)](https://packagist.org/packages/lugasdev/mutasi)
[![License](https://poser.pugx.org/lugasdev/mutasi/license)](https://packagist.org/packages/lugasdev/mutasi)

Laravel package for mutation bank Indonesia. This package support 4 bank such as BCA, Mandiri, BNI, & BRI.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Installing

First, simply require the package through Composer.

```
$ composer require lugasdev/mutasi
```

Next, add the service provider in your config/app.php file.

```
Lugasdev\Mutasi\MutasiServiceProvider::class
```

If you'd like to use the Facade instead of the helper functions, add it to the aliases array.

```
'Mutasi' => Lugasdev\Mutasi\Facade\Mutasi::class,
```

Next, if you want to configure the credential. You can publish the config file.

```
php artisan vendor:publish --provider="Lugasdev\Mutasi\MutasiServiceProvider"
```

Copy this environment your .env file to help you change information easily.

```
DIMAS_LICENSE=
CHECK_CONTROL=
HTML_OUTPUT=
        
BCA_ACTIVE=
BCA_USERNAME=
BCA_PASSWORD=
BCA_DAY=
        
MANDIRI_ACTIVE=
MANDIRI_USERNAME=
MANDIRI_PASSWORD=
MANDIRI_REKENING=
MANDIRI_DAY=
        
BNI_ACTIVE=
BNI_PASSWORD=
BNI_DAY=
        
BRI_ACTIVE=
BRI_PASSWORD=
BRI_DAY=
```

Last, migrate table from this package to store data mutation. It will automatically create table. You don't need to copy it.

```
php artisan migrate
```

### Docs

To use this package just call the function.

```
example : yourdomain.com/BCA

Route::get('/{case}', function ($case) {
    return Mutasi::cekMutasi($case); //$case to store bank name such as 'BCA 'Mandiri', 'BNI', 'BRI'
});
```

To use for test on development by passing credential bank using route

```
use Illuminate\Http\Request;

Route::get('get/{case}', ['as' => '/get/{case}', 'uses' => function (Request $request, $case) {
    $auth = $request->all();

    return Mutasi::cekMutasiAuth($auth, $case);
}]);
```

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Lugas Septiawan** - *Initial work* - [Lugasdev](https://github.com/lugassawan)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details