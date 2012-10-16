# FileServeBundle

## About

The FileServeBundle allows you to serve files that are not publicly available,
such as private attachments.

## Installation

Add the bundle to your `composer.json`:

    {
        "require": {
            "igorw/file-serve-bundle": "1.0.*@dev"
        }
    }

Add the FileServeBundle to your application's kernel:

    public function registerBundles()
    {
        $bundles = array(
            ...
            new Igorw\FileServeBundle\IgorwFileServeBundle(),
            ...
        );
        ...
    }

## Usage

Use the `igorw_file_serve.response_factory` service to create a FileServe
response. The path is relative to the `app` directory by default.

    $response = $this->get('igorw_file_serve.response_factory')->create('../VERSION', 'text/plain');

You can also pass a set of options as the third parameter of the `create`
method.

    $options = array(
        'serve_filename' => 'VERSION.txt',
        'inline' => false,
    );

    $response = $this->get('igorw_file_serve.response_factory')
        ->create('../VERSION', 'text/plain', $options);

You can configure the factory used, for example to use a nginx XSendfile
response factory:

    igorw_file_serve:
        factory: sendfile     # The default value is "php"

You can also configure the base directory:

    igorw_file_serve:
        base_dir: /files/dir     # The default value is "%kernel.root_dir%"

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
