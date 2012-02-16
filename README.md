# FileServeBundle

## About

The FileServeBundle allows you to serve files that are not publicly available, such as private attachments.

## Installation

Put the FileServeBundle into the ``vendor/bundles/Igorw`` directory:

    $ git clone https://github.com/igorw/IgorwFileServeBundle vendor/bundles/Igorw/FileServeBundle

Register the `Igorw` namespace in your project's autoload script (app/autoload.php):

    $loader->registerNamespaces(array(
        'Igorw'                          => __DIR__.'/../vendor/bundles',
    ));

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

Use the `igorw_file_serve.response_factory` service to create a FileServe response. The path is relative to the `app` directory by default.

    $response = $this->get('igorw_file_serve.response_factory')->create('../VERSION', 'text/plain');

You can configure the factory used, for example to use a nginx XSendfile response factory:

    igorw_file_serve:
        factory: sendfile     # The default value is "php"

You can also configure the base directory:

    igorw_file_serve:
        base_dir: /files/dir     # The default value is "%kernel.root_dir%"

## Features

* Incremental serving of large files
* Nginx [XSendfile](http://wiki.nginx.org/XSendfile)

## Todo

* Tests
* HTTP caching
* HTTP range requests
* Lighttpd/Apache XSendfile
* Handle PhpResponse getContent(), setContent()
