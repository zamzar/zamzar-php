# Zamzar PHP SDK Guide

[Introduction](#introduction)

[Getting Started](#getting-started)

[API Reference](#API-Reference)

[Authentication](#Authentication)

[Object Reference](#Object-Reference)

[Custom Headers](#Custom-Headers)

[Handling Errors](#Handling-Errors)

[Paged Collections](#Paged-Collections)

[Rate Limits](#Rate-Limits)

[Sandbox and Production Environments](#Sandbox-and-Production-Environments)

[Additional Information](#Additional-Information)


# Introduction

The Zamzar PHP library provides access to the Zamzar API from applications written in the PHP language. All API features and functionality are provided within the library, in addition to convenient helpers.

# Getting Started

Install the Zamzar Library to get started.

## Using Composer

Install:

```php
composer require zamzar/zamzar-php
```

Use Bindings:
```php
require_once('vendor/autoload.php');
```

## Install Manually

Alternatively, download the [latest release]() and use the bindings:

```php
require_once('/path/to/zamzar-php/init.php);
```


# API Reference

If you need more information relating to the API over and above what this guide offers, please visit the [Developer Docs](https://developers.zamzar.com/docs).

# Authentication

The Zamzar API uses API Keys to authenticate requests. You can view and manage your API keys in the [Developer Dashboard](https://developers.zamzar.com/user). 

Use your API key when declaring a new instance of the <code>\Zamzar\ZamzarClient</code> object. You can use the library in both production and test mode using the same API Key.

Remember to keep your API key secure!

# Connected Services

The Zamzar API supports the import and export of source files from/to external services. Currently support for Amazon's S3 if offered. This library does not provide any functionality for configuring Connected Services. [Read more about Connected Services](https://developers.zamzar.com/docs#section-Connected_services) and configure them in the [Developer Dashboard](https://developers.zamzar.com/user/services).

# Object Reference

The object model used within the library maps closely to the Zamzar API Endpoints, in terms of object names and language used. Having a good understanding of the API will help you understand the object structure.

## [ZamzarClient](zamzarclient.md)
The top level object through which all requests are made to the following objects.

## [Account](account.md) 

View Account information. 

Maps to Endpoint: https://api.zamzar.com/v1/account

### -> [Plan](account.md)

View Plan information. 

Maps to Endpoint: https://api.zamzar.com/v1/account

## [Formats](formats.md) 
View information for source to target conversion formats. 

Maps To Endpoint: https://api.zamzar.com/v1/formats

### -> [Format](format.md)

Represents an individual format and its valid conversion targets and credit cost.

Maps to Endpoint: https://api.zamzar.com/v1/formats/format/

## [Files](files.md)

Represents files which are stored on Zamzar's servers. Retrieve lists of files, individual file objects or upload files using the Files object.

Maps to Endpoint: https://api.zamzar.com/v1/files

### -> [File](file.md)

Represents an individual file object.

Maps to Endpoint: https://api.zamzar.com/v1/files/file

## [Imports](imports.md) 

Represents the process for copying files from remote servers to Zamzar servers or for retrieving data about imports.

Maps to Endpoint: https://api.zamzar.com/v1/imports

### -> [Import](import.md)

Represents an individual import object and its associated file object. 

Maps to Endpoint: https://api.zamzar.com/v1/imports/import

## [Jobs](jobs.md) 

The process of converting a file to a different format or retrieving data about jobs.

Maps to Endpoint: https://api.zamzar.com/v1/jobs

### -> [Job](job.md)

Represents an individual job object and its associated files, import and exports objects.

Maps to Endpoint: https://api.zamzar.com/v1/jobs/job

# Custom Headers

As part of every response from the API, two custom headers are provided which relate to the credits remaining on the account. These are accessible through the ZamzarClient object as convenience options (to avoid the need to make specific requests for this information).

```php
// register a new client
$zamzar = new \Zamzar\ZamzarClient('apikey');

// registering a new client does not issue an API call, so make a request to demonstrate the functionality
$zamzar->jobs->all(['limit' => 1]);

// get the headers which are exposed via the Zamzar client
$productionCreditsRemaining = $zamzar->getProductionCreditsRemaining();
$testCreditsRemaining = $zamzar->getTestCreditsRemaining();
```

The same information can be determined more explicitly by requesting Account information via the Account object, although the above method is preferred to reduce the volume of calls for this information.

```php
$account = $zamzar->account->get();
$productionCreditsRemaining = $account->getProductionCreditsRemaining();
$testCreditsRemaining = $account->getTestCreditsRemaining();
```

# Handling Errors

When a response from the API contains a <code>4xx</code> or <code>5xx</code> code, a custom exception will be thrown. Exceptions are also thrown before requests are made if parameters are invalid for example. [Read more about Exceptions](exceptions.md).

# Paged Collections

When a request is made for a list of objects (Files, Formats, Imports, Jobs), the response will be a paged collection. A paged collection allows you to view a subset of the overall collection with support for cursor based functionality between each page. [Read more about Pagination](pagination.md).

# Rate Limits

Rate limits are applied to different API endpoints. Rate Limit exceptions can be caught in the library using the <code>\Zamzar\Exception\RateLimitException</code>. [Read more about Exceptions](exceptions.md).

# Sandbox and Production Environments

Zamzar provides a sandbox and production environment. When instantiating a new ZamzarClient object, the production or sandbox environment can be specified.

```php

// Default to production when no environment is specified
$zamzar = new \Zamzar\ZamzarClient('apikey');

// Specify the environment
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => '',
    'environment' => 'production' | 'sandbox'
]);
```

[Read more about sandbox and production environments](https://developers.zamzar.com/docs#section-Sandbox_and_Production_Environments)

# Additional Information

[View a comprehensive list of code samples](samples.md) highlighting the features of the library which can be copy and pasted. All you need is a valid API key to use them.

We also recommend starting with the [ZamzarClient](zamzarclient.md) which will provide a good introduction and code snippets to help get you started with using the rest of the library.

View the [developer docs](https://developers.zamzar.com/docs) for a full description of the API for a complete understanding.

