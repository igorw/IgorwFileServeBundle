# FileServeBundle

## About

The FileServeBundle allows you to serve files that are not publicly available,
such as private attachments.

## Installation

Add the bundle to your `composer.json`:

```json
{
    "require": {
        "igorw/file-serve-bundle": "~1.0"
    }
}
```

Add the FileServeBundle to your application's kernel:

```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Igorw\FileServeBundle\IgorwFileServeBundle(),
        // ...
    );
    // ...
}
```

## Usage

Use the `igorw_file_serve.response_factory` service to create a FileServe
response. The path is relative to the `app` directory by default.

```php
$response = $this->get('igorw_file_serve.response_factory')->create('../VERSION', 'text/plain');
```

You can also pass a set of options as the third parameter of the `create`
method.

```php
$options = array(
    'serve_filename' => 'VERSION.txt',
    'absolute_path' => true,
    'inline' => false,
);

$response = $this->get('igorw_file_serve.response_factory')
    ->create('../VERSION', 'text/plain', $options);
```

* **serve_filename:** Filename the browser downloads the file as.
* **absolute_path:** If enabled, the bundle will ignore the `base_dir` option
  and use the provided filename as an absolute path.

You can configure the factory used, for example to use a nginx XSendfile
response factory:

```yml
igorw_file_serve:
    factory: sendfile     # The default value is "php"
```

You can also configure the base directory:

```yml
igorw_file_serve:
    base_dir: /files/dir     # The default value is "%kernel.root_dir%"
```

By default, this bundle does a `file_exists` check when creating a response
object. Recent nginx versions require relative paths, in which case the paths
inside of PHP are not actual physical paths. Use the `skip_file_exists`
setting to disable the check.

```yml
igorw_file_serve:
    skip_file_exists: true  # The default value is false
```

### Supported factories

 * `php`
 * `sendfile` (nginx)
 * `xsendfile` (apache)

## Features

* Incremental serving of large files
* Nginx [XSendfile](http://wiki.nginx.org/XSendfile)
* Apache [mod_xsendfile](https://tn123.org/mod_xsendfile/)

## Todo

* Tests
* HTTP caching
* HTTP range requests
* Lighttpd XSendfile
* Handle PhpResponse getContent(), setContent()
