# ApiErrorException

Zamzar uses conventional HTTP response codes to indicate success or failure of requests:

-  <code>2xx</code> indicates success

 - <code>4xx</code> indicate an error with the information provided or some other processing related issues

 - <code>5xx</code> indicate an error with Zamzar's servers

Codes in the <code>4xx</code> range have specific exceptions raised which can be caught using standard error handling. [View a full list of codes](https://developers.zamzar.com/docs#section-Response_codes) in the API documentation.

The ApiErrorException is the base API error class which extends the standard Exception class. This can be used as a catch all if required or the following errors can be caught. For any exception raised, the following properties and methods can be used to interrogate the error further.

## -> <code>getCode()</code>

Returns the HTTP Response Code.

```php
$error->getCode();
```

## -> <code>getMessage()</code>

Returns the message associated with the code.

```php
$error->getMessage();
```

## -> <code>getApiErrors()</code>

Returns an array of API [Error](error.md) objects representing Zamzar API Errors.

```php
 foreach($error->getApiErrors() as $apiError) {
    echo $apiError->getCode() . "\n";
    echo $apiError->getMessage() . "\n";
    if($apiError->hasContext()) {
        foreach($apiError->getContext() as $context) {
            echo $context . "\n";
        }
    }
}
```

## -> <code>getApiErrorsRaw()</code>

Returns the raw json array of errors.

```php
$error->getApiErrorsRaw();
```

## -> <code>getConfig()</code>

Returns the config array being used to submit the request, which may be useful for troubleshooting purposes.

```php
$error->getConfig();
```

## -> <code>getEndpoint()</code>

Returns the endpoint which was being accessed, which may be useful for troubleshooting purposes.

```php
$error->getEndpoint();
```

## AuthenticationException (<code>401</code>)

Thrown when there is an issue authenticating the request, for example the api key is invalid.

```php
try {
    // e.g. using an invalid api key for any request
} catch (\Zamzar\Exception\AuthenticationException $error) {
    echo $error->getCode();
}
```

## AccountException (<code>402</code>)

Thrown when there is an issue with account, for example the account does not have sufficient credit.

```php
try {
    // e.g. submitting a job (which uses conversion credits) when the account does not have sufficient conversion credits available
} catch (\Zamzar\Exception\AccountException $error) {
    echo $error->getCode();
}
```

## InvalidResourceException (<code>404,410</code>)

Thrown when the requested resource does not exist or is no longer available

```php
try {
    // e.g. request a file which no longer exists
} catch (\Zamzar\Exception\InvalidResourceException $error) {
    echo $error->getCode();
}
```

## PayloadException (<code>413</code>)

Thrown when the request is too large, for example the source file size exceeds the maximum for the account.

```php
try {
    // e.g. submit a file which is larger than the maximum permitted
} catch (\Zamzar\Exception\PayloadException $error) {
    echo $error->getCode();
}
```

## InvalidRequestException (<code>422</code>)

Thrown when there is an issue with the request, for example the request is malformed, e.g. missing a required parameter

```php
try {
    // e.g. convert a file to an invalid file format
} catch (\Zamzar\Exception\InvalidRequestException $error) {
    echo $error->getCode();
}
```

## RateLimitException (<code>429</code>)

Thrown when the request has exceeded a rate limit

```php
try {
    // e.g. submit a large volume of requests within a short period
} catch (\Zamzar\Exception\RateLimitException $error) {
    echo $error->getCode();
}
```

## UnknownApiErrorException (<code>500, 503</code>)

Thrown when an unexpected error has occurred, for example an internal server error, or when the API is temporarily unavailable.

```php
try {
    // this type of error is rare
} catch (\Zamzar\Exception\UnknownApiErrorException $error) {
    echo $error->getCode();
}
```

## InvalidArgumentException

Invalid argument exceptions are generally thrown before any request is made to the API and typically relates to invalid parameters being supplied.

```php
// try initialising the Zamzar client with an invalid api key
try {
    $zamzar = new \Zamzar\ZamzarClient('api key with whitespace');
} catch (\Zamzar\Exception\InvalidArgumentException $e) {
    echo $e->getMessage();
}
```

```php
Output: api_key cannot contain whitespace
```

```php
// try submitting a job without any parameters
try {
    $zamzar = new \Zamzar\ZamzarClient('apikey');
    $jobs = $zamzar->jobs->create([]);
} catch (\Zamzar\Exception\InvalidArgumentException $e) {
    echo $e->getMessage();
}
```

```php
Output: source_file must be specified and be either a local file (which exists) or a supported type of remote file.
```

## TimeOutException

TimeOut exceptions are thrown when a function experiences a timeout related issue.

```php
// try submitting a job and waiting for the job to complete for 0 seconds
try {
    $job = $zamzar->jobs->create([
  	    'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
  	    'target_format' => 'pdf'
    ])
  	    ->waitForCompletion(0);
} catch (\Zamzar\Exception\TimeOutException $e) {
    echo $e->getMessage();
}
```

## Additional Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.