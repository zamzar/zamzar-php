# PHP file converter library for Zamzar

[![@zamzar on Twitter](https://img.shields.io/badge/twitter-zamzar-blue)](https://twitter.com/zamzar)
[![Total Downloads](https://img.shields.io/packagist/dt/zamzar/zamzar-php.svg?style=flat)](https://packagist.org/packages/zamzar/zamzar-php)
[![Apache 2 License](https://img.shields.io/packagist/l/zamzar/zamzar-php.svg?style=flat)](https://github.com/zamzar/zamzar-php/blob/main/LICENSE)

Easy to use PHP file conversion API with support for 1,100+ file conversions - convert documents, audio, images, video,
eBooks and more. Use `zamzar-php` to convert files between different formats as part of your PHP application with
the [Zamzar file conversion API](https://developers.zamzar.com). Common use cases include:

* Convert Microsoft Word (DOCX, DOC) to PDF
* Extract text from PDF files
* Convert Powerpoint (PPT, PPTX) to JPG
* Archive email (MSG files) to PDF

This is the official PHP SDK for the [Zamzar file conversion API](https://developers.zamzar.com).

Jump to:

- [Requirements](#requirements)
- [Installation](#installation)
- [Initialise the Zamzar Client](#initialise-the-zamzar-client)
- [Test the Connection](#test-the-connection)
- [Typical Usage](#typical-usage)
- [Configure a Logger](#configure-a-logger)
- [Resources](#resources)

## Requirements

- Before you begin, signup for a Zamzar API Account or retrieve your existing API Key from
  the [Zamzar Developers Homepage](https://developers.zamzar.com/user)
- PHP 7.2.34 and later.

## Installation

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require zamzar/zamzar-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Initialise the Zamzar Client

To initialise the client, declare a new ZamzarClient:

```php

// Connect to the Production API using an API Key
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => 'apiKey',
    'transport' => \Zamzar\ZamzarClient::recommendedTransport()
]);
```

To specify whether the client using the Production or Test API, use a Config array:

```php

// Use the Production API
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => 'apiKey',
    'environment' => 'production',
    'transport' => \Zamzar\ZamzarClient::recommendedTransport()
]);


// Use the Sandbox API
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => 'apiKey',
    'environment' => 'sandbox',
    'transport' => \Zamzar\ZamzarClient::recommendedTransport()
]);

```

## Test the Connection

To confirm your credentials are correct, test the connection to the API which will return a welcome message and confirm
which API you are using (Production or Test).

```php

echo $zamzar->testConnection();

```

## Typical Usage

The most common requirement is to submit a job to convert a file, wait for the job to complete, download the converted
files and delete the files on Zamzar servers.

```php
// Submit the file
$job = $zamzar->jobs->create([
    'source_file' => 'path/to/local/file',
    'target_format' => 'xxx'
]);

// Wait for the job to complete (the default timeout is 60 seconds)
$job->waitForCompletion(30);

// Download the converted files 
$job->downloadTargetFiles('path/to/folder/');

// Delete the source and target files on Zamzar's servers
$job->deleteAllFiles();
```

The above use case might be applied when other things are happening in between each step, but if not, and you want to
chain the whole thing together:

```php
// Do the whole thing together
$job = $zamzar->jobs->create([
        'source_file' => 'path/to/localfile', 
        'target_format' => 'pdf'
    ])
    ->waitForCompletion(120)
    ->downloadTargetFiles('path/to/folder')
    ->deleteAllFiles();
```

## Configure a Logger

The library does minimal logging, if the `debug` config option is used. Use either the supplied default logger or a
psr-3 compatible logger.

```php
$client = new Zamzar\ZamzarClient([
    'api_key' = '****',
    'debug' => true,
]);

// PSR-3 Compatible Logger
\Zamzar\Zamzar::setLogger($psr3Logger);

// Using Monolog
$logger = new Logger('Zamzar');
$logger->pushHandler(new StreamHandler(__DIR__.'/app.log', Logger::DEBUG));
\Zamzar\Zamzar::setLogger($logger);
```

## Resources

[Code Samples](samples.md) - Copy/Paste from examples which demonstrate all key areas of functionality.

[Exceptions Handling](exceptions.md) - Learn more about API Error Codes.

[Developer Docs](https://developers.zamzar.com/docs) - For more information about API operations, parameters, and
responses. Use this if you need additional context on all areas of functionality.
