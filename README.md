# FileServeBundle

## About

The FileServeBundle allows you to serve files that are not publicly available, such as private attachments.

## Installation

Put the FileServeBundle into the src/Igorw directory:

    $ git clone git://github.com/igorw/FileServeBundle.git src/Igorw

Register the `Igorw` namespace in your project's autoload script (app/autoload.php):

    $loader->registerNamespaces(array(
        'Igorw'                          => __DIR__.'/../src',
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

Use the `igorw.file_serve.response.factory` service to create a FileServe response.

    $response = $this->get('igorw.file_serve.response.factory')->create('../VERSION', 'text/plain');

You can adjust the `igorw.file_serve.response.factory.class` parameter, for example to use a nginx XSendfile response factory:

    parameters:
        igorw.file_serve.response.factory.class: Igorw\FileServeBundle\Response\SendfileResponseFactory

## Features

* Incremental serving of large files
* Nginx [XSendfile](http://wiki.nginx.org/XSendfile)

## Todo

* Base path (maybe %kernel.dir%, currently paths are relative to webroot)
* DependencyInjection Extension configuration
* Tests
* HTTP caching
* HTTP range requests
* Lighttpd/Apache XSendfile
* Handle PhpResponse getContent(), setContent()
