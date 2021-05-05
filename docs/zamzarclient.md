# Zamzar Client

The Zamzar Client is the route through which all API requests are made to either the Production or Sandbox environments on Zamzar.

## Constructor

The constructor accepts an API Key string which it will then use when making requests to the Zamzar API.

```php
// Uses the production environment by default
$zamzar = new \Zamzar\ZamzarClient('abcd123456');
```

The constructor will also accept a config array to override the defaults:

```php
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => 'apikey',
    'environment' => 'production' | 'sandbox',
]);
```
Whether a string or config array is provided, the end result will be a validated config array which is used to submit all API requests to Zamzar.

## Methods

### -> <code>getConfig()</code>

Returns the config array which was initialised in the constructor.

```php
var_dump($zamzar->getConfig());
```

Please note that the config array is validated before all API requests are made, therefore any modification to any parameters which fall outside the permitted values will result in an exception being thrown.

### -> <code>testConnection()</code>

Tests the connection to the Zamzar API and returns a welcome message.
```php
echo $zamzar->testConnection();
```
If the connection was made, the following message will be displayed:
```
Welcome to the Zamzar API. Your API key is working correctly. You can now issue API requests. To get started, see the guide at https://developers.zamzar.com/docs#section-Getting_Started
```

### -> <code>getLastResponse()</code>

The last response from every call made to the API is accessible through this method. The ZamzarClient also uses the last response to expose information returned in the headers of each response (namely the number of credits remaining on a Production or Test account).

```php
var_dump($zamzar->getLastResponse());
```
Additional Information: [API Response Object](response.md)

### -> <code>getProductionCreditsRemaining()</code>

Returns the number of production credits remaining on your account.

```php
echo 'Production Credits Remaining = ' . $zamzar->getProductionCreditsRemaining();
```

### -> <code>getTestCreditsRemaining()</code>

Returns the number of production credits remaining on your account.

```php
echo 'Test Credits Remaining = ' . $testCredits = $zamzar->getTestCreditsRemaining();
```

### -> <code>setLogger($logger)</code>

Configures a logger using the provided psr-3 compatible object.

```php
$zamzar->setLogger($logger);
```

### -> <code>getLogger()</code>

Returns the currently configured logger.

```php
$zamzar->getLogger();
```

## Objects

The Zamzar Client exposes objects representing the high level endpoints of the API. Use these objects to make requests to the API to retrieve data, submit conversion jobs, send imports, upload files etc. Some simple examples are shown below with more examples given in each object's page.

### -> <code>[Account](account.md)</code>

View information relating to your Account and Plan.

```php
$account = $zamzar->account->get();
```

### -> <code>[Files](files.md)</code>

View information about files with options for uploading files from your local machine or remote servers.

```php
// list all files (using a paged collection)
$files = $zamzar->files->all();

// get a specific file
$file = $zamzar->files->get(123456);

// upload a file
$file = $zamzar->files->upload($params);

// delete a file
$file = $zamzar->files->get(123456)->delete();
```

### -> <code>[Formats](formats.md)</code>

View information about formats and target formats.

```php
// list all formats 
$formats = $zamzar->formats->all();

// get a specific format
$jpgFormat = $zamzar->formats->get('jpg');
```

### -> <code>[Imports](imports.md)</code>

View information about imports or initiate a new import.

```php
// list all imports
$imports = $zamzar->imports->all();

// get a specific import
$import = $zamzar->imports->get(123456);

/// start a new import
$import = $zamzar->imports->start($params);
```

### -> <code>[Jobs](jobs.md)</code>

View information about jobs, or submit a file for conversion with additional options for downloading and deleting the converted files.

```php
// list all jobs
$jobs = $zamzar->jobs->all();

// get a specific job
$job = $zamzar->job->get(123456);

// submit a file for conversion
$job = $zamzar->jobs->submit([
    'source_file' => '/path/to/source',
    'target_format' => 'valid-conversion-format'
]);
```

## Additional Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.