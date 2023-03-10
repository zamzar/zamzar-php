# Zamzar PHP

[![@zamzar on Twitter](https://img.shields.io/badge/twitter-zamzar-blue)](https://twitter.com/zamzar)
[![Total Downloads](https://img.shields.io/packagist/dt/zamzar/zamzar-php.svg?style=flat)](https://packagist.org/packages/zamzar/zamzar-php)
[![Apache 2 License](https://img.shields.io/packagist/l/zamzar/zamzar-php.svg?style=flat)](https://github.com/zamzar/zamzar-php/blob/main/LICENSE)

The Zamzar PHP library provides convenient access to the Zamzar API from
applications written in the PHP language. It includes a pre-defined set of classes which make it easy to submit files for conversion, retrieve converted files and utilise all of the features that the API offers.

Jump to:

- [Requirements](#requirements)
- [Installation](#installation)
- [Dependencies](#dependencies)
- [Documentation](#documentation)
- [Initialise the Zamzar Client](#initialise-the-zamzar-client)
- [Test the Connection](#test-the-connection)
- [Typical Usage](#typical-usage)
- [Configure a Logger](#configure-a-logger)
- [Contributing](#contributing)
- [Resources](#resources)
- [SDK Sampler](#sdk-sampler)

## Requirements

- Before you begin, signup for a Zamzar API Account or retrieve your existing API Key from the [Zamzar Developers Homepage](https://developers.zamzar.com/user)
- PHP 7.2.5 and later.

## Installation

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require zamzar/zamzar-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

If you do not wish to use Composer, you can download the [latest release](https://github.com/zamzar/zamzar-php/releases). Then, to use the bindings, include the `init.php` file.

```php
require_once('/path/to/zamzar-php/init.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

-   [`Guzzle HTTP Client`](https://docs.guzzlephp.org/en/stable/overview.html)
-   [`JSON Extension`](https://secure.php.net/manual/en/book.json.php)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.


## Documentation

See the [SDK Guide](/docs/guide.md) for all of the features offered by the PHP SDK. The basics are covered below.


## Initialise the Zamzar Client

To initialise the client, declare a new ZamzarClient which accepts a string or config array:

```php

// Connect to the Production API using an API Key
$zamzar = new \Zamzar\ZamzarClient('apikey');
```

To specify whether the client using the Production or Test API, use a Config array:

```php

// Use the Production API
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => 'apiKey',
    'environment' => 'production'
]);


// Use the Sandbox API
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => 'apiKey',
    'environment' => 'sandbox'
]);

```

## Test the Connection

To confirm your credentials are correct, test the connection to the API which will return a welcome message and confirm which API you are using (Production or Test).

```php

echo $zamzar->testConnection();

```

## Typical Usage

The most common requirement is to submit a job to convert a file, wait for the job to complete, download the converted files and delete the files on  Zamzar servers.

```php
// Submit the file
$job = $zamzar->jobs->submit([
    'source_file' => 'path/to/local/file',
    'target_format' => 'xxx'
]);

// Wait for the job to complete (the default timeout is 60 seconds)
$job->waitForCompletion([
    'timeout' => 60
]);

// Download the converted files 
$job->downloadTargetFiles([
    'download_path' => 'path/to/folder/'
]);

// Delete the source and target files on Zamzar's servers
$job->deleteAllFiles();
```

The above use case might be applied when other things are happening in between each step, but if not, and you want to chain the whole thing together:

```php
// Do the whole thing together
$job = $zamzar
        ->jobs
        ->submit([
                'source_file' => 'path/to/localfile', 
                'target_format' => 'pdf'
                ])
        ->waitForCompletion([
                'timeout' => 120
        ])
        ->downloadTargetFiles([
                'download_path' => 'path/to/folder'
                ])
        ->deleteAllFiles();
```

## Configure a Logger

The library does minimal logging of errors and information. Use either the supplied default logger or a psr-3 compatible logger.

```php
// Default Zamzar Logger (output to the PHP error log)
$logger = new \Zamzar\Util\DefaultLogger;
$zamzar->setLogger($logger);

// PSR-3 Compatible Logger
$zamzar->setLogger($psr3logger);

// Using Monolog
$logger = new Logger('Zamzar');
$logger->pushHandler(new StreamHandler(__DIR__.'/app.log', Logger::DEBUG));
$zamzar->setLogger($logger);
```

## Contributing

We would greatly value feedback and contributions from our community. 

To run tests:

- Ensure dependencies are installed using <code>composer install</code>

- Rename the <code>testconfigtemplate.php</code> file in the <code>tests</code> folder to <code>testconfig.php</code>

- Specify your API Key within the <code>testconfig.php</code> file

- Run <code>./vendor/bin/phpunit tests</code>

## Resources

[Code Samples](samples.md) - Copy/Paste from examples which demonstrate all key areas of functionality.

[Exceptions Handling](exceptions.md) - Learn more about API Error Codes.

[Developer Docs](https://developers.zamzar.com/docs) - For more information about API operations, parameters, and responses. Use this if you need additional context on all areas of functionality.

## SDK Sampler

We've provided a simple app which demonstrates all features of the SDK, including code snippets to help you get started. [View the SDK Sampler](examples/zamzar-php-sampler/readme.md).

<img width="1379" alt="sampler-screenshot" src="https://user-images.githubusercontent.com/79094268/111761890-4f31c380-8898-11eb-99cc-55c381759020.png">
